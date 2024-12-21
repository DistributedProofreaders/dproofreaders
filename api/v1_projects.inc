<?php
include_once($relPath.'Project.inc');
include_once($relPath.'wordcheck_engine.inc');
include_once($relPath.'ProjectSearchForm.inc');
include_once($relPath.'page_table.inc');
include_once($relPath.'special_colors.inc');
include_once($relPath.'ProofProject.inc');
include_once("exceptions.inc");
include_once("api_common.inc");

// DP API v1 -- Projects

//===========================================================================
// projects/

/**
 * Get an array of the fields to be returned to the client.
 * @param ?array<string,string> $query_params the HTTP query
 * @param ?Project $project
 *   If we're querying a single project, what is it? (Certain fields
 *   aren't queryable or viewable by most users.)
 * @return ?array<string>
 * @throws InvalidValue if any of the filter arguments aren't recognized
 */
function get_return_fields(?array $query_params, ?Project $project)
{
    $return_fields = array_get_as_array($query_params, "field", null);
    if (is_null($return_fields)) {
        return null;
    }
    $valid_render_fields = array_keys(get_project_fields_with_attr(null, $project));
    $invalid_fields = array_diff($return_fields, $valid_render_fields);
    if (!empty($invalid_fields)) {
        throw new InvalidValue("Invalid filter args: " . implode(", ", $invalid_fields));
    }
    return $return_fields;
}

function api_v1_projects($method, $data, $query_params)
{
    // set which fields are queryable and their column names
    $valid_fields = get_project_fields_with_attr("queryable", null);

    // parameters in the query where the value can be "false", "true" or absent (==true)
    // When true, a WHERE clause is added to the SQL query.
    $valid_flags = [
        "in_smoothreading" => "smoothread_deadline > UNIX_TIMESTAMP()",
    ];

    $valid_query_keys = array_merge(
        array_keys($valid_fields),
        array_keys($valid_flags),
        ['field', 'sort', 'page', 'per_page'],
    );
    $invalid_query_params = array_diff(array_keys($query_params), $valid_query_keys);
    if (!empty($invalid_query_params)) {
        throw new InvalidValue("Invalid query args: " . implode(", ", $invalid_query_params));
    }

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

        if (in_array($field, ["author", "title", "languages", "clearance"])) {
            $likes_str = surround_and_join($values, "$column_name LIKE '%", "%'", ' OR ');
            $where .= " AND ($likes_str)";
        } else {
            $values_list = surround_and_join($values, "'", "'", ",");
            $where .= " AND $column_name IN ($values_list)";
        }
    }

    foreach (array_intersect(array_keys($valid_flags), array_keys($query_params)) as $flag) {
        if (get_flag_value($query_params, $flag)) {
            $where .= " AND " . $valid_flags[$flag];
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

    // restrict to list of desired fields, if set
    $return_fields = get_return_fields($query_params, null);

    $output = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $project = new Project($row);
        $output[] = render_project_json($project, $return_fields);
    }

    return $output;
}

//---------------------------------------------------------------------------
// projects/:projectid

function get_project_fields_with_attr(?string $attr, ?Project $project): array
{
    // A list of all the fields that can be rendered by render_project_json
    // and the corresponding name of the SQL column.
    // In addition, fields with the "queryable" attribute can be used
    // as query term for api/v1/projects, and fields with the "updatable"
    // attribute can be set by a POST request.
    $fields = [
        "projectid" => ["sql_name" => "projectid", "queryable" => true],
        "title" => ["sql_name" => "nameofwork", "updatable" => true, "queryable" => true],
        "author" => ["sql_name" => "authorsname", "updatable" => true, "queryable" => true],
        "languages" => ["sql_name" => "languages",  "updatable" => true, "queryable" => true],
        "genre" => ["sql_name" => "genre", "updatable" => true, "queryable" => true],
        "difficulty" => ["sql_name" => "difficulty", "updatable" => true, "queryable" => true],
        "special_day" => ["sql_name" => "special_code", "updatable" => true, "queryable" => true],
        "project_manager" => ["sql_name" => "username", "updatable" => true, "queryable" => true],
        "image_source" => ["sql_name" => "image_source", "updatable" => true],
        "image_preparer" => ["sql_name" => "image_preparer", "updatable" => true],
        "text_preparer" => ["sql_name" => "text_preparer", "updatable" => true],
        "pg_ebook_number" => ["sql_name" => "postednum", "updatable" => true, "queryable" => true],
        "comments" => ["sql_name" => "comments", "updatable" => true],
        "comment_format" => ["sql_name" => "comment_format", "updatable" => true],
        "custom_characters" => ["sql_name" => "custom_chars", "updatable" => true],
        "pages_available" => ["sql_name" => "n_available_pages", "queryable" => true],
        "pages_total" => ["sql_name" => "n_pages", "queryable" => true],
        "post_processor" => ["sql_name" => "postproofer"],
        "post_process_verifier" => ["sql_name" => "ppverifier"],
        "state" => ["sql_name" => "state", "queryable" => true],
        "last_state_change_time" => ["sql_name" => "modifieddate"],
        "last_page_done_time" => ["sql_name" => "t_last_page_done"],
        "last_edit_time" => ["sql_name" => "t_last_edit"],
        "smoothread_deadline" => ["sql_name" => "smoothread_deadline"],
        "forum_url" => ["sql_name" => "forum_url"],
        "round_type" => ["sql_name" => null],
        "round_document" => ["sql_name" => null],
    ];

    // If accessing a single project using the api/v1/projects/:id endpoint,
    // only allow clearance to be rendered/updated if the user has permission
    // for that project.
    $can_render = !is_null($project) && $project->clearance_line_can_be_seen_by_current_user();
    // If using the api/v1/projects/ endpoint to search for (multiple) projects
    // allow SAs and PFs to search on clearance. This does have the odd corner
    // case of forbidding PMs to search for clearances that they can in fact
    // see in their individual projects, but we don't want to open up the
    // ability for them to value-fishing on clearances.
    $can_search = is_null($project) && (user_is_a_sitemanager() || user_is_proj_facilitator());
    if ($can_search || $can_render) {
        $fields["clearance"] = ["sql_name" => "clearance", "updatable" => true, "queryable" => true];
    }

    $r = [];
    foreach ($fields as $api_name => $attrs) {
        if (is_null($attr) || @$attrs[$attr] == true) {
            $r[$api_name] = $attrs["sql_name"];
        }
    }
    return $r;
}

/**
 * Return a list of updatable project fields mapped from the API names
 * to the Project object names.
 */
function get_updatable_project_fields(Project $project): array
{
    return get_project_fields_with_attr("updatable", $project);
}

function create_or_update_project(Project $project)
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
    $updates = api_get_request_body();
    try {
        foreach (get_updatable_project_fields($project) as $api_key => $project_key) {
            // allow partial record updates by only updating specified values
            if (isset($updates[$api_key])) {
                $project->$project_key = $updates[$api_key];
            }
        }

        $project->validate(true);
    } catch (ProjectException $exception) {
        throw new InvalidValue($exception->getMessage(), $exception->getCode());
    }

    // validate charsuites
    if (!$project->projectid || isset($updates["character_suites"])) {
        if (!is_array($updates["character_suites"])) {
            throw new InvalidValue("character_suites must be an array.");
        }

        if (sizeof($updates["character_suites"]) == 0) {
            throw new InvalidValue("At least one Character Suite is required.");
        }

        $invalid_charsuites = array_diff(
            $updates["character_suites"],
            array_extract_field(CharSuites::get_all(), "name")
        );
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
        // restrict to list of desired fields, if set
        $project = $data[":projectid"];
        $return_fields = get_return_fields($query_params, $project);
        return render_project_json($project, $return_fields);
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

function render_project_json(Project $project, ?array $return_fields = null)
{
    // We want to explicitly call out the parameters we want to return so
    // callers can know what to expect in this version of the API.
    $return_array = [
        "projectid" => $project->projectid,
    ];

    foreach (get_updatable_project_fields($project) as $api_key => $project_key) {
        $return_array[$api_key] = $project->$project_key;

        // force pg_ebook_number to an int
        if ($api_key == "pg_ebook_number" && $return_array[$api_key] != null) {
            $return_array[$api_key] = (int)$return_array[$api_key];
        }
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
            "pages_available" => (int)$project->n_available_pages,
            "pages_total" => (int)$project->n_pages,
            "last_state_change_time" => date(DATE_ATOM, $project->modifieddate),
            "last_page_done_time" => date(DATE_ATOM, $project->t_last_page_done),
            "last_edit_time" => date(DATE_ATOM, $project->t_last_edit),
            "smoothread_deadline" => date(DATE_ATOM, $project->smoothread_deadline),
            "forum_url" => $project->forum_url,
            "round_type" => $project->round_type,
            "round_document" => $project->round_document,
        ]
    );

    // remove any fields that weren't requested
    array_remove_invalid_fields($return_array, $return_fields);

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
    try {
        foreach ($project->get_page_names_from_db() as $image) {
            $return_data[] = [
                "image" => $image,
                "image_url" => "{$project->url}/$image",
                "image_size" => (int)$project->get_image_file_size($image),
            ];
        }
    } catch (NoProjectPageTable $exception) {
        throw new NotFoundError($exception->getMessage(), $exception->getCode());
    }
    return $return_data;
}

//---------------------------------------------------------------------------
// projects/:projectid/pagedetails

function api_v1_project_pagedetails($method, $data, $query_params)
{
    // optional page round IDs (one or more) to filter down to
    $only_rounds = null;
    $pageroundids = $query_params["pageroundid"] ?? null;
    if ($pageroundids) {
        $only_rounds = [];
        if (!is_array($pageroundids)) {
            $pageroundids = [$pageroundids];
        }
        foreach ($pageroundids as $pageroundid) {
            validate_page_round($pageroundid, null);
            if ($pageroundid === "OCR") {
                $only_rounds[] = "OCR";
            } else {
                $only_rounds[] = get_Round_for_round_id($pageroundid);
            }
        }
    }

    $project = $data[":projectid"];
    try {
        $rounds_to_display = get_rounds_to_display($project);
    } catch (NoProjectPageTable $exception) {
        throw new NotFoundError($exception->getMessage(), $exception->getCode());
    }
    if (!is_null($only_rounds)) {
        $rounds_to_display = array_intersect($rounds_to_display, $only_rounds);
    }

    $return_data = [];
    foreach (fetch_page_table_data($project, null, null, $only_rounds) as $image) {
        $page_rounds_data = [];
        // Remove proofer names and adjust timestamp format
        foreach ($image["pagerounds"] as $round_id => $round_data) {
            $round_data["pageround"] = $round_id;
            if (isset($round_data["modified_timestamp"])) {
                $round_data["last_modified"] = date(DATE_ATOM, $round_data["modified_timestamp"]);
                unset($round_data["modified_timestamp"]);
            }

            $proofer_usernames = array_map(fn ($r) => $image["pagerounds"][$r->id]["username"], $rounds_to_display);
            if (!can_user_see_usernames_for_page($project, $proofer_usernames)) {
                unset($round_data["username"]);
            }
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

    $project = $data[":projectid"];
    $user_cols = array_map(fn ($r) => $r->user_column_name, Rounds::get_all());

    $sql = sprintf(
        "
        SELECT
            image,
            %s AS text,
            %s AS user,
            %s,
            state
        FROM %s
        WHERE image = '%s'
        ",
        $text_column,
        $user_column,
        implode(",", $user_cols),
        $project->projectid,
        DPDatabase::escape($data[":pagename"]->page_name)
    );
    $result = DPDatabase::query($sql);
    $row = mysqli_fetch_assoc($result);
    $row["image_url"] = $project->url . "/" . $row["image"];

    $json = render_project_page_json($row);

    $proofer_usernames = array_map(fn ($u) => $row[$u], $user_cols);
    if (!can_user_see_usernames_for_page($project, $proofer_usernames)) {
        unset($json["username"]);
    }

    return $json;
}

function render_project_page_json($row)
{
    return [
        "username" => $row["user"],
        "pagename" => $row["image"],
        "image_url" => $row["image_url"],
        "text" => $row["text"],
        "state" => $row["state"],
    ];
}

//---------------------------------------------------------------------------
// projects/:projectid/transitions

function api_v1_project_transitions($method, $data, $query_params)
{
    $sql = sprintf(
        "
        SELECT *
        FROM project_events
        WHERE projectid = '%s'
        AND event_type in ('creation', 'transition', 'archive')
        ORDER BY timestamp
        ",
        $data[":projectid"]->projectid
    );
    $result = DPDatabase::query($sql);
    $return_data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $return_data[] = [
            "timestamp" => date(DATE_ATOM, (int)$row["timestamp"]),
            "event_type" => $row["event_type"],
            "details" => [$row["details1"], $row["details2"], $row["details3"]],
        ];
    }
    return $return_data;
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
    return array_merge(["OCR"], Rounds::get_ids());
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
        $enabled = $special_day["enable"] == 1;
        if ($enabled_filter !== null) {
            if ($enabled_filter != $enabled) {
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
            "enabled" => $enabled,
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
        $is_active = $image_source["is_active"] == 1;
        if ($enabled_filter !== null) {
            if ($enabled_filter != $is_active) {
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
            "enabled" => $is_active,
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

/**
 * Check to see if the user requested an enabled filter.
 *
 * This returns one of three possible states:
 * - null - no enabled flag was set
 * - true - enabled flag was set to "true" or an empty string (for ?enabled)
 * - false - enabled flag was set to "false"
 */
function _get_enabled_filter($query_params)
{
    return get_flag_value($query_params, "enabled");
}

//---------------------------------------------------------------------------
// Proofreading functions

// checkout a page
function api_v1_project_checkout($method, array $data, array $query_params)
{
    try {
        $project = $data[":projectid"];
        $state = $query_params["state"] ?? null;
        validate_project_state($project, $state);
        $proof_project = new ProofProject($project);
        return $proof_project->checkout();
    } catch (ProjectException $exception) {
        throw new NotFoundError($exception->getMessage(), $exception->getCode());
    } catch (UserAccessException $exception) {
        throw new ForbiddenError($exception->getMessage(), $exception->getCode());
    }
}

function api_v1_project_validatetext(string $method, array $data, array $query_params)
{
    $project = $data[":projectid"];
    $invalid_characters = $project->find_invalid_characters(receive_project_text_from_request_body('text'));
    return ["invalid_chars" => $invalid_characters];
}

function api_v1_project_wordcheck($method, $data, array $query_params)
{
    $project = $data[":projectid"];
    $accepted_words = receive_data_from_request_body("accepted_words") ?? [];
    $languages = receive_data_from_request_body("languages") ?? [];
    if (!$languages) {
        $languages = $project->languages;
    }

    $text = receive_project_text_from_request_body();
    [$bad_words, $languages, $messages] = get_bad_word_levels_for_project_text($text, $project->projectid, $languages, $accepted_words);
    return [
        "bad_words" => $bad_words,
        "messages" => $messages,
    ];
}

function api_v1_project_pickersets(string $method, array $data, array $query_params): array
{
    $project = $data[":projectid"];
    $verbose_pickersets = [];
    foreach ($project->get_pickersets() as $pickerset) {
        $verbose_pickersets[] = [
            "name" => $pickerset->name,
            "subsets" => $pickerset->get_verbose_subsets(),
        ];
    }
    return $verbose_pickersets;
}

function api_v1_project_page(string $method, array $data, array $query_params)
{
    global $pguser;

    try {
        $project = $data[":projectid"];
        $project_page = $data[":pagename"];
        if ($method == "GET") {
            $proof_project = new ProofProject($project);
            $proof_project_page = new ProofProjectPage($proof_project, $project_page);
            return $proof_project_page->get_page_text_data();
        }
        // $method is "PUT"
        $state = $query_params["state"] ?? null;
        validate_project_state($project, $state);
        $proof_project = new ProofProject($project);

        $page_state = $query_params['pagestate'] ?? null;
        validate_page_state($project_page, $page_state);
        $proof_project_page = new ProofProjectPage($proof_project, $project_page);

        $page_action = $query_params['pageaction'] ?? null;
        switch ($page_action) {
            case null:
                throw new BadRequest("No page action found in request.");
            case 'resume':
                return $proof_project_page->pp_resume_page();
            case 'checkin':
                return $proof_project_page->attempt_checkin(receive_project_text_from_request_body('text'));
            case 'save':
                return $proof_project_page->save(receive_project_text_from_request_body('text'));
            case 'revert':
                return $proof_project_page->save_and_revert(receive_project_text_from_request_body('text'));
            case 'abandon':
                return $proof_project_page->returnToRound($pguser);
            default:
                throw new BadRequest("$page_action is not a valid page action.");
        }
    } catch (ProjectException $exception) {
        throw new NotFoundError($exception->getMessage(), $exception->getCode());
    } catch (UserAccessException $exception) {
        throw new ForbiddenError($exception->getMessage(), $exception->getCode());
    }
}

function api_v1_project_page_wordcheck(string $method, array $data, array $query_params): void
{
    try {
        $project = $data[":projectid"];
        $proof_project = new ProofProject($project);

        $project_page = $data[":pagename"];
        $proof_project_page = new ProofProjectPage($proof_project, $project_page);

        $proof_project_page->wc_report(receive_data_from_request_body("accepted_words") ?? []);
    } catch (ProjectException $exception) {
        throw new NotFoundError($exception->getMessage(), $exception->getCode());
    } catch (UserAccessException $exception) {
        throw new ForbiddenError($exception->getMessage(), $exception->getCode());
    }
}

function validate_project_state($project, $state)
{
    if (null === $state) {
        throw new InvalidValue("No project state found in request.");
    }
    if (!in_array($state, ProjectStates::get_states())) {
        throw new InvalidValue("Invalid project state");
    }
    if ($state != $project->state) {
        $err = sprintf(
            _('Project "%1$s" is not in state "%2$s"; it is now in state "%3$s".'),
            $project->nameofwork,
            project_states_text($state),
            project_states_text($project->state)
        );
        throw new ProjectNotInRequiredStateException($err);
    }
}

function validate_page_state($project_page, $page_state)
{
    if (null === $page_state) {
        throw new InvalidValue("No page state found in request.");
    }
    if (!in_array($page_state, Rounds::get_page_states())) {
        throw new InvalidValue(sprintf("%s is not a valid page state", $page_state));
    }
    if ($page_state != $project_page->page_state) {
        $err = sprintf(
            _('Page "%1$s" is not in state "%2$s"; it is now in state "%3$s".'),
            $project_page->page_name,
            $page_state,
            $project_page->page_state
        );
        throw new ProjectPageInconsistentStateException($err);
    }
}

function receive_project_text_from_request_body(): string
{
    $page_text = receive_data_from_request_body("text");
    if (null === $page_text) {
        throw new InvalidValue("There is no text");
    }
    return $page_text;
}

function receive_data_from_request_body($field)
{
    $request_data = api_get_request_body();
    return $request_data[$field] ?? null;
}
