<?php
include_once($relPath.'stages.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'release_queue.inc');

//===========================================================================
// Validators

function validate_round($roundid, $data)
{
    try {
        $round = Rounds::get_by_id($roundid);
        if (!$round) {
            throw new InvalidRoundException("Invalid round");
        }
        return $round;
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
    try {
        $pagerounds = array_merge(["OCR"], Rounds::get_ids());

        if (!in_array($pageround, $pagerounds)) {
            throw new InvalidPageRoundException("Invalid page round");
        }
        return $pageround;
    } catch (InvalidPageRoundException $exception) {
        throw new NotFoundError($exception->getMessage(), $exception->getCode());
    }
}

function validate_release_queue($queueid, $data)
{
    $queue_data = fetch_queue_data($queueid);
    if (is_null($queue_data)) {
        throw new NotFoundError("queue {$queueid} not found");
    }
    return $queue_data;
}
