<?php
include_once($relPath.'stages.inc');
include_once($relPath.'Project.inc');

//===========================================================================
// Validators

function validate_round($roundid, $data)
{
    global $Round_for_round_id_;

    try {
        if (!in_array($roundid, array_keys($Round_for_round_id_))) {
            throw new InvalidRoundException("Invalid round");
        }
        return $Round_for_round_id_[$roundid];
    } catch (InvalidRoundException $exception) {
        throw new NotFoundError($exception->getMessage(), $exception->getCode());
    }
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
    try {
        $pages = $data[":projectid"]->get_page_names_from_db();
        if (!in_array($pagename, $pages)) {
            throw new NonexistentPageException("No such page in project");
        }
        return $pagename;
    } catch (NonexistentPageException $exception) {
        throw new NotFoundError($exception->getMessage(), $exception->getCode());
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
