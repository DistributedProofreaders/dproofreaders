<?php
include_once($relPath.'Project.inc');
include_once($relPath.'wordcheck_engine.inc');
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
    // searching is not yet implemented
    throw new NotImplementedError();
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
