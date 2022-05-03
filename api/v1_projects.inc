<?php
include_once($relPath.'Project.inc');
include_once($relPath.'wordcheck_engine.inc');
include_once($relPath.'ProjectSearchForm.inc');
include_once($relPath.'page_table.inc');
include_once($relPath.'special_colors.inc');
include_once("exceptions.inc");

// DP API v1 -- Projects

//===========================================================================
// projects/

function api_v1_projects($method, $data, $query_params)
{
    // set which fields are queryable and their column names
    $valid_fields = [
        "projectid" => "projectid",
        "state" => "state",
        "title" => "nameofwork",
        "author" => "authorname",
        "languages" => "language",
        "genre" => "genre",
        "difficulty" => "difficulty",
        "special_day" => "special_day",
        "project_manager" => "username",
        "pg_ebook_number" => "postednum",
        "pages_available" => "n_pages_available",
        "pages_total" => "n_pages",
    ];

    // pull out the query parameters
    $query = [];
    foreach (array_intersect(array_keys($valid_fields), array_keys($query_params)) as $field) {
        $param = $query_params[$field];
        $query[$field] = is_array($param) ? $param : [$param];
    }

    // now build project query
    $where = "1";
    foreach ($query as $field => $values) {
        $values = array_map("DPDatabase::escape", $values);
        $column_name = $valid_fields[$field];

        if (in_array($field, ["author", "title", "languages"])) {
            $likes_str = surround_and_join($values, "$column_name LIKE '%", "%'", ' OR ');
            $where .= " AND ($likes_str)";
        } else {
            $values_list = surround_and_join($values, "'", "'", ",");
            $where .= " AND $column_name IN ($values_list)";
        }
    }

    // build the order_by
    $order_by = "projectid";
    if (isset($query_params["sort"])) {
        $order_by = [];
        $sort_fields = explode(",", $query_params["sort"]);
        foreach ($sort_fields as $field) {
            $order = "ASC";
            if (startswith($field, "-")) {
                $order = "DESC";
                $field = substr($field, 1);
            }

            if (!isset($valid_fields[$field])) {
                continue;
            }
            $order_by[] = $valid_fields[$field] . " $order";
        }
        $order_by[] = "projectid";
        $order_by = implode(", ", $order_by);
    }

    $per_page = get_integer_param($query_params, "per_page", 20, 1, 100);
    $page = get_integer_param($query_params, "page", 1, 1, null);
    $offset = $per_page * ($page - 1);

    // get a page worth of projects
    $sql = "
        SELECT *
        FROM projects
        WHERE $where
        ORDER BY $order_by
        LIMIT $per_page OFFSET $offset
    ";
    $result = DPDatabase::query($sql, false);
    if (!$result) {
        throw new ServerError(DPDatabase::log_error());
    }

    // and a count of all projects
    $sql = "
        SELECT COUNT(*)
        FROM projects
        WHERE $where
    ";
    $result_count = DPDatabase::query($sql, false);
    if (!$result_count) {
        throw new ServerError(DPDatabase::log_error());
    }
    [$total_rows] = mysqli_fetch_row($result_count);
    $total_pages = round($total_rows / $per_page);

    api_send_pagination_header($query_params, $total_rows, $per_page, $page);

    $output = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $project = new Project($row);
        $output[] = render_project_json($project);
    }

    return $output;
}

//---------------------------------------------------------------------------
// projects/:projectid

// Return a list of updatable project fields mapped from the API names
// to the Project object names.
function get_updatable_project_fields()
{
    return [
        // API name => Project name
        "title" => "nameofwork",
        "author" => "authorsname",
        "languages" => "languages",
        "genre" => "genre",
        "difficulty" => "difficulty",
        "special_day" => "special_code",
        "project_manager" => "username",
        "image_source" => "image_source",
        "image_preparer" => "image_preparer",
        "text_preparer" => "text_preparer",
        "pg_ebook_number" => "postednum",
        "comments" => "comments",
        "comment_format" => "comment_format",
        "clearance" => "clearance",
        "custom_characters" => "custom_chars",
    ];
}

function create_or_update_project($project)
{
    // can the user use this update API at all?
    if (!user_is_PM()) {
        throw new UnauthorizedError();
    }

    // can the user manage the existing project?
    if ($project->projectid && !$project->can_be_managed_by_current_user) {
        throw new UnauthorizedError();
    }

    // can the user create new projects?
    if (!$project->projectid && user_has_project_loads_disabled()) {
        throw new UnauthorizedError();
    }

    // save a copy of the original project object for permission checks
    $orig_project = $project;

    // update all updatable fields
    $updates = (array)api_get_request_body();
    try {
        foreach (get_updatable_project_fields() as $api_key => $project_key) {
            // allow partial record updates by only updating specified values
            if (isset($updates[$api_key])) {
                $project->$project_key = $updates[$api_key];
            }
        }

        $project->validate(true);
    } catch (ValueError $error) {
        throw new InvalidValue($error->getMessage());
    }

    // validate charsuites
    if (!$project->projectid || isset($updates["character_suites"])) {
        if (!is_array($updates["character_suites"])) {
            throw new InvalidValue("character_suites must be an array.");
        }

        if (sizeof($updates["character_suites"]) == 0) {
            throw new InvalidValue("At least one Character Suite is required.");
        }

        $invalid_charsuites = array_diff($updates["character_suites"],
            array_extract_field(CharSuites::get_all(), "name"));
        if ($invalid_charsuites) {
            throw new InvalidValue(sprintf("%s is/are not valid Character Suites.", join(", ", $invalid_charsuites)));
        }
    }

    // enforce specific field authorization for non-SAs
    if (!user_is_a_sitemanager()) {
        // project creation
        if (!$orig_project->projectid) {
            if (User::current_username() != $project->username) {
                throw new UnauthorizedError("You do not have permission to create projects for another PM");
            }
            if (User::current_username() != 'BEGIN' && $project->difficulty == "beginner") {
                throw new UnauthorizedError("You do not have permission to set difficulty to 'beginner'");
            }
        }
        // project updates
        else {
            if ($project->username != $orig_project->username) {
                throw new UnauthorizedError("You do not have permission to change the PM");
            }
            if (User::current_username() != 'BEGIN' && $project->difficulty == "beginner" && $orig_project->difficulty != $project->difficulty) {
                throw new UnauthorizedError("You do not have permission to set difficulty to 'beginner'");
            }
        }
    }

    $project->save();

    if (isset($updates["character_suites"])) {
        $project->set_charsuites($updates["character_suites"]);
    }

    return $project;
}

function api_v1_project($method, $data, $query_params)
{
    if ($method == "GET") {
        return render_project_json($data[":projectid"]);
    } elseif ($method == "PUT") {
        $project = $data[":projectid"];
        return render_project_json(create_or_update_project($project));
    } elseif ($method == "POST") {
        $project = create_or_update_project(new Project());
        // reload the project to fetch defaults managed by the database
        $project = new Project($project->projectid);
        return render_project_json($project);
    }
}

function render_project_json($project)
{
    // We want to explicitly call out the parameters we want to return so
    // callers can know what to expect in this version of the API.
    $return_array = [
        "projectid" => $project->projectid,
    ];

    foreach (get_updatable_project_fields() as $api_key => $project_key) {
        $return_array[$api_key] = $project->$project_key;
    }

    $charsuites = [];
    foreach ($project->get_charsuites(false) as $project_charsuite) {
        array_push($charsuites, $project_charsuite->name);
    }
    $return_array["character_suites"] = $charsuites;

    $return_array = array_merge(
        $return_array,
        [
            "post_processor" => $project->PPer,
            "post_process_verifier" => $project->PPVer,
            "state" => $project->state,
            "pages_available" => $project->n_available_pages,
            "pages_total" => $project->n_pages,
            "last_state_change_time" => date(DATE_ATOM, $project->modifieddate),
            "last_page_done_time" => date(DATE_ATOM, $project->t_last_page_done),
            "last_edit_time" => date(DATE_ATOM, $project->t_last_edit),
        ]
    );

    return $return_array;
}

//---------------------------------------------------------------------------
// projects/:projectID/wordlists/:type

function api_v1_project_wordlists($method, $data, $query_params)
{
    // get the project this is for and the type of word list
    $project = $data[":projectid"];
    $wordlist_type = $data[":wordlist_type"];

    // handle GET and PUT requests
    if ($method == "GET") {
        // everyone can get word lists
        $list_loader = "load_project_{$wordlist_type}_words";
        return $list_loader($project->projectid);
    } elseif ($method == "PUT") {
        // can the user manage the word lists?
        if (!$project->can_be_managed_by_current_user) {
            throw new UnauthorizedError();
        }

        $words = api_get_request_body();
        if (!is_array($words)) {
            throw new BadRequest("No words provided");
        }
        $list_saver = "save_project_{$wordlist_type}_words";
        $list_saver($project->projectid, $words);

        // return updated list
        $list_loader = "load_project_{$wordlist_type}_words";
        return $list_loader($project->projectid);
    }
}

//---------------------------------------------------------------------------
// projects/:projectID/holdstates

function api_v1_project_holdstates($method, $data, $query_params)
{
    $project = $data[":projectid"];

    // handle GET and PUT requests
    if ($method == "GET") {
        // everyone can get hold states
        return $project->get_hold_states();
    } elseif ($method == "PUT") {
        // can the user manage the project?
        if (!$project->can_be_managed_by_current_user) {
            throw new UnauthorizedError();
        }

        $desired_states = api_get_request_body();

        // confirm that all of the desired states are valid
        if (array_diff($desired_states, Project::get_holdable_states()) != []) {
            throw new BadRequest("Invalid hold state specified.");
        }

        $current_holds = $project->get_hold_states();

        $remove_holds = array_diff($current_holds, $desired_states);
        if ($remove_holds) {
            $project->remove_holds($remove_holds);
        }

        $add_holds = array_diff($desired_states, $current_holds);
        if ($add_holds) {
            $project->add_holds($add_holds);
        }

        return $project->get_hold_states();
    }
}

//---------------------------------------------------------------------------
// projects/:projectid/pages

function api_v1_project_pages($method, $data, $query_params)
{
    $project = $data[":projectid"];

    $return_data = [];
    foreach ($project->get_page_names_from_db() as $image) {
        $return_data[] = [
            "image" => $image,
            "image_url" => "{$project->url}/$image",
            "image_size" => $project->get_image_file_size($image),
        ];
    }
    return $return_data;
}

//---------------------------------------------------------------------------
// projects/:projectid/pagedetails

function api_v1_project_pagedetails($method, $data, $query_params)
{
    $return_data = [];
    foreach (fetch_page_table_data($data[":projectid"]) as $image) {
        $page_rounds_data = [];
        // Remove proofer names and adjust timestamp format
        foreach ($image["pagerounds"] as $round_id => $round_data) {
            $round_data["pageround"] = $round_id;
            if (isset($round_data["modified_timestamp"])) {
                $round_data["last_modified"] = date(DATE_ATOM, $round_data["modified_timestamp"]);
                unset($round_data["modified_timestamp"]);
            }
            unset($round_data["username"]);
            $page_rounds_data[] = $round_data;
        }
        $image["pagerounds"] = $page_rounds_data;
        $return_data[] = $image;
    }
    return $return_data;
}

//---------------------------------------------------------------------------
// projects/:projectid/pages/:pagename/pagerounds/:pageroundid

function api_v1_project_page_round($method, $data, $query_params)
{
    if ($data[":pageroundid"] == "OCR") {
        $text_column = "master_text";
        $user_column = "NULL";
    } else {
        $round = get_Round_for_round_id($data[":pageroundid"]);
        $text_column = $round->text_column_name;
        $user_column = $round->user_column_name;
    }

    $sql = sprintf("
        SELECT
            image,
            %s AS text,
            %s AS user,
            state
        FROM %s
        WHERE image = '%s'
    ", $text_column, $user_column, $data[":projectid"]->projectid,
        DPDatabase::escape($data[":pagename"])
    );
    $result = DPDatabase::query($sql);
    $row = mysqli_fetch_assoc($result);
    $row["image_url"] = $data[":projectid"]->url . "/" . $row["image"];

    // We can't show the username here unless the user has proofed the page
    // in one round or they are an PF/SA. We need to abstract the conditional
    // logic out of tools/project_manager/page_detail.inc
    unset($row["user"]);

    return render_project_page_json($row);
}

function render_project_page_json($row)
{
    return [
        "pagename" => $row["image"],
        "image_url" => $row["image_url"],
        "text" => $row["text"],
        "state" => $row["state"],
    ];
}

//---------------------------------------------------------------------------
// projects/difficulties

function api_v1_projects_difficulties($method, $data, $query_params)
{
    $difficulties = get_project_difficulties();
    return array_keys($difficulties);
}

//---------------------------------------------------------------------------
// projects/genres

function api_v1_projects_genres($method, $data, $query_params)
{
    $genres = ProjectSearchForm::genre_options();
    unset($genres['']);
    return array_keys($genres);
}

//---------------------------------------------------------------------------
// projects/languages

function api_v1_projects_languages($method, $data, $query_params)
{
    $languages = ProjectSearchForm::language_options();
    unset($languages['']);
    return array_keys($languages);
}

//---------------------------------------------------------------------------
// projects/states

function api_v1_projects_states($method, $data, $query_params)
{
    $states = ProjectSearchForm::state_options();
    unset($states['']);
    return array_keys($states);
}

//---------------------------------------------------------------------------
// projects/pagerounds

function api_v1_projects_pagerounds($method, $data, $query_params)
{
    global $Round_for_round_id_;

    return array_merge(["OCR"], array_keys($Round_for_round_id_));
}

//---------------------------------------------------------------------------
// projects/charsuites

function api_v1_projects_charsuites($method, $data, $query_params)
{
    $enabled_filter = _get_enabled_filter($query_params);
    if ($enabled_filter === null) {
        $charsuites = CharSuites::get_all();
    } elseif ($enabled_filter) {
        $charsuites = CharSuites::get_enabled();
    } else {
        $charsuites = array_udiff(CharSuites::get_all(), CharSuites::get_enabled(), function ($a, $b) {
            return $a->name <=> $b->name;
        });
    }

    $return_data = [];
    foreach ($charsuites as $charsuite) {
        $return_data[] = [
            "id" => $charsuite->name,
            "name" => $charsuite->title,
            "characters" => convert_codepoint_ranges_to_characters($charsuite->codepoints),
            "enabled" => $charsuite->is_enabled(),
        ];
    }

    return $return_data;
}

//---------------------------------------------------------------------------
// projects/specialdays

function api_v1_projects_specialdays($method, $data, $query_params)
{
    $return_data = [];

    $special_days = load_special_days();
    $enabled_filter = _get_enabled_filter($query_params);
    foreach ($special_days as $spec_code => $special_day) {
        if ($enabled_filter !== null) {
            if ($enabled_filter && $special_day["enable"] != 1) {
                continue;
            } elseif (!$enabled_filter && $special_day["enable"] == 1) {
                continue;
            }
        }
        $return_data[] = [
            "id" => $special_day["spec_code"],
            "name" => $special_day["display_name"],
            "comment" => $special_day["comment"],
            "url" => $special_day["info_url"],
            "color" => $special_day["color"],
            "symbol" => $special_day["symbol"],
            "date_open_month" => (int)$special_day["open_month"],
            "date_open_day" => (int)$special_day["open_day"],
            "date_close_month" => (int)$special_day["close_month"],
            "date_close_day" => (int)$special_day["close_day"],
            "enabled" => $special_day["enable"] == 1,
        ];
    }

    return $return_data;
}

//---------------------------------------------------------------------------
// projects/imagesources

function api_v1_projects_imagesources($method, $data, $query_params)
{
    $return_data = [];

    $image_sources = load_image_sources();

    $enabled_filter = _get_enabled_filter($query_params);
    foreach ($image_sources as $id => $image_source) {
        if ($enabled_filter !== null) {
            if ($enabled_filter && $image_source["is_active"] != 1) {
                continue;
            } elseif (!$enabled_filter && $image_source["is_active"] == 1) {
                continue;
            }
        }

        if (!can_user_see_image_source($image_source)) {
            continue;
        }

        $return_data[] = [
            "id" => $image_source["code_name"],
            "name" => $image_source["display_name"],
            "name_full" => $image_source["full_name"],
            "url" => $image_source["url"],
            "credit" => $image_source["credit"],
            "enabled" => $image_source["is_active"] == 1,
        ];
    }

    return $return_data;
}

//---------------------------------------------------------------------------
// projects/holdstates

function api_v1_projects_holdstates($method, $data, $query_params)
{
    return Project::get_holdable_states();
}

//---------------------------------------------------------------------------
// Utility functions

// Check to see if the user requested an enabled filter. This returns one of
// three possible states:
//   null - no enabled flag was set
//   true - enabled flag was set to "true" or an empty string (for ?enabled)
//   false - enabled flag was set to "false"
function _get_enabled_filter($query_params)
{
    if (!isset($query_params["enabled"])) {
        return null;
    } elseif ($query_params["enabled"] == "") {
        // make '?enabled' work as if '?enabled=true'
        return true;
    } else {
        $bool = filter_var($query_params["enabled"], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        if ($bool === null) {
            throw new ValueError("Invalid value for 'enabled'");
        }
        return $bool;
    }
}
