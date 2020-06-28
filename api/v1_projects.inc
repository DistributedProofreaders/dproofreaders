<?php
include_once($relPath.'Project.inc');
include_once($relPath.'wordcheck_engine.inc');
include_once($relPath.'ProjectSearchForm.inc');
include_once("exceptions.inc");

// DP API v1 -- Projects

//===========================================================================
// Validators

function validate_project($projectid)
{
    // validate and load the specified projectid
    try
    {
        return new Project($projectid);
    }
    catch(NonexistentProjectException $exception)
    {
        throw new NotFoundError("No such project ID");
    }
}

function validate_wordlist($wordlist)
{
    if(!in_array($wordlist, ["good", "bad"]))
    {
        throw new NotFoundError();
    }
    return $wordlist;
}

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
    foreach(array_intersect(array_keys($valid_fields), array_keys($query_params)) as $field)
    {
        $param = $query_params[$field];
        $query[$field] = is_array($param) ? $param : [ $param ];;
    }

    // now build project query
    $where = "1";
    foreach($query as $field => $values)
    {
        $values = array_map("DPDatabase::escape", $values);
        $column_name = $valid_fields[$field];

        if(in_array($field, [ "author", "title", "languages" ]))
        {
            $likes_str = surround_and_join( $values, "$column_name LIKE '%", "%'", ' OR ' );
            $where .= " AND ($likes_str)";
        }
        else
        {
            $values_list = surround_and_join( $values, "'", "'", "," );
            $where .= " AND $column_name IN ($values_list)";
        }
    }

    // build the order_by
    $order_by = "projectid";
    if(isset($query_params["sort"]))
    {
        $order_by = [];
        $sort_fields = explode(",", $query_params["sort"]);
        foreach($sort_fields as $field)
        {
            $order = "ASC";
            if(startswith($field, "-"))
            {
                $order = "DESC";
                $field = substr($field, 1);
            }

            if(!isset($valid_fields[$field]))
            {
                continue;
            }
            $order_by[] = $valid_fields[$field] . " $order";
        }
        $order_by[] = "projectid";
        $order_by = implode(", ", $order_by);
    }

    $per_page = get_integer_param($query_params, "per_page", 20, 1, 100);
    $page = get_integer_param($query_params, "page", 1, 1, NULL);
    $offset = $per_page * ($page - 1);

    $sql = "
        SELECT SQL_CALC_FOUND_ROWS *
        FROM projects
        WHERE $where
        ORDER BY $order_by
        LIMIT $per_page OFFSET $offset
    ";
    $result = DPDatabase::query($sql, FALSE);
    if(!$result)
    {
        throw new ServerError(DPDatabase::log_error());
    }

    $res_found = DPDatabase::query("SELECT FOUND_ROWS()");
    list($total_rows) = mysqli_fetch_row($res_found);
    $total_pages = round($total_rows / $per_page);

    api_send_pagination_header($query_params, $total_rows, $per_page, $page);

    $output = [];
    while($row = mysqli_fetch_assoc($result))
    {
        $project = new Project($row);
        $output[] = render_project_json($project);
    }

    return $output;
}

//---------------------------------------------------------------------------
// projects/:projectid

function api_v1_project($method, $data, $query_params)
{
    return render_project_json($data[":projectid"]);
}

function render_project_json($project)
{
    // We want to explicitly call out the parameters we want to return so
    // callers can know what to expect in this version of the API.
    return [
        "projectid" => $project->projectid,
        "state" => $project->state,
        "title" => $project->nameofwork,
        "author" => $project->authorsname,
        "languages" => explode(" with ", $project->language),
        "genre" => $project->genre,
        "difficulty" => $project->difficulty,
        "special_day" => $project->special_code,
        "project_manager" => $project->username,
        "post_processor" => $project->PPer,
        "post_process_verifier" => $project->PPVer,
        "image_source" => $project->image_source,
        "image_preparer" => $project->image_preparer,
        "text_preparer" => $project->text_preparer,
        "pages_available" => $project->n_available_pages,
        "pages_total" => $project->n_pages,
        "pg_ebook_number" => $project->postednum,
        "last_modified" => date(DATE_ATOM, $project->modifieddate),
        "last_page_done" => date(DATE_ATOM, $project->t_last_page_done),
        "last_edit" => date(DATE_ATOM, $project->t_last_edit),
    ];
}

//---------------------------------------------------------------------------
// projects/:projectID/wordlists/:type

function api_v1_project_wordlists($method, $data, $query_params)
{
    // get the project this is for and the type of word list
    $project = $data[":projectid"];
    $wordlist_type = $data[":wordlist_type"];

    // handle GET and PUT requests
    if($method == "GET")
    {
        // everyone can get word lists
        $list_loader = "load_project_{$wordlist_type}_words";
        return $list_loader($project->projectid);
    }
    elseif($method == "PUT")
    {
        // can the user manage the word lists?
        if(!$project->can_be_managed_by_current_user)
        {
            throw new UnauthorizedError();
        }

        $words = api_get_request_body();
        if(!is_array($words))
        {
            throw new BadRequest("No words provided");
        }
        $list_saver = "save_project_{$wordlist_type}_words";
        $list_saver($project->projectid, $words);
        return [];
    }
}

//---------------------------------------------------------------------------
// projects/difficulties

function api_v1_projects_difficulties($method, $data, $query_params)
{
    $difficulties = ProjectSearchForm::difficulty_options();
    unset($difficulties['']);
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

