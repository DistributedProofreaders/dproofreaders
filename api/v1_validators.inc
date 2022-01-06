<?php
include_once($relPath.'stages.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'project_states.inc'); // project_states_text()
include_once($relPath.'ProjectPage.inc'); // ProjectPage(),

//===========================================================================
// Validators

function validate_round($roundid, $data)
{
    global $Round_for_round_id_;

    if (!in_array($roundid, array_keys($Round_for_round_id_))) {
        throw new NotFoundError("Invalid round");
    }
    return $Round_for_round_id_[$roundid];
}

function validate_project($projectid, $data)
{
    // validate and load the specified projectid
    try {
        return new Project($projectid);
    } catch (NonexistentProjectException $exception) {
        throw new NotFoundError("No such project ID");
    }
}

function validate_project_state($proj_state, $data)
{
    global $PROJECT_STATES_IN_ORDER;
    if (!in_array($proj_state, $PROJECT_STATES_IN_ORDER)) {
        throw new BadRequest("Invalid project state: $proj_state");
    }

    $project = $data[":projectid"];
    $curr_state = $project->state;
    if ($curr_state != $proj_state) {
        $orig_state_text = project_states_text($proj_state);
        $curr_state_text = project_states_text($curr_state);
        $err = sprintf(
            _("This project is no longer '%1\$s', it is now '%2\$s', so your request cannot be fulfilled."),
            $orig_state_text,
            $curr_state_text
        );
        throw new BadRequest($err);
    }
    return $proj_state;
}

function validate_page_state($page_state, $data)
{
    $project_page = $data[":pagename"];
    if ($project_page->state != $page_state) {
        $err = sprintf(
            _('Page %1$s has changed state from %2$s to %3$s, so your request cannot be fulfilled.'),
            $project_page->name,
            $page_state,
            $project_page->state
        );
        throw new BadRequest($err);
    }

    return $page_state;
}

function validate_page_version($version_name)
{
    if (!in_array($version_name, ["current", "previous"])) {
        throw new BadRequest("Bad page version");
    }
    return $version_name;
}

function validate_wordlist($wordlist, $data)
{
    if (!in_array($wordlist, ["good", "bad"])) {
        throw new NotFoundError();
    }
    return $wordlist;
}

function validate_page_name($pagename, $data)
{
    $project = $data[":projectid"];
    try {
        return new ProjectPage($project->projectid, $pagename);
    } catch (NonexistentPageException $exception) {
        throw new NotFoundError("No such page in project");
    }
}

function validate_page_round($pageround, $data)
{
    global $Round_for_round_id_;

    $pagerounds = array_merge(["OCR"], array_keys($Round_for_round_id_));

    if (!in_array($pageround, $pagerounds)) {
        throw new NotFoundError("Invalid page round");
    }
    return $pageround;
}
