<?php
include_once($relPath.'stages.inc');
include_once($relPath.'Project.inc');

//===========================================================================
// Validators

function validate_round($roundid, $data)
{
    global $Round_for_round_id_;

    if (!in_array($roundid, array_keys($Round_for_round_id_))) {
        throw new NotFoundError(_("Invalid round"), DP::INVALID_ROUND);
    }
    return $Round_for_round_id_[$roundid];
}


function validate_project($projectid, $data)
{
    // validate and load the specified projectid
    try {
        return new Project($projectid);
    } catch (NonexistentProjectException $exception) {
        throw new NotFoundError($exception->getMessage(), $exception->getCode());
    }
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
    $pages = $data[":projectid"]->get_page_names_from_db();
    if (!in_array($pagename, $pages)) {
        throw new NotFoundError(_("There is no page $pagename in the project."), DP::NO_SUCH_PAGE);
    }
    return $pagename;
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
