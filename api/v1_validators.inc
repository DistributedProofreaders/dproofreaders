<?php
include_once($relPath.'Project.inc');
include_once($relPath.'release_queue.inc');

//===========================================================================
// Validators

function validate_round(string $roundid, array $_data): Round
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

function validate_project(string $projectid, array $_data): Project
{
    // validate and load the specified projectid
    try {
        return new Project($projectid);
    } catch (NonexistentProjectException $exception) {
        throw new NotFoundError($exception->getMessage(), $exception->getCode());
    }
}

function validate_wordlist(string $wordlist, array $_data): string
{
    if (!in_array($wordlist, ["good", "bad"])) {
        throw new NotFoundError();
    }
    return $wordlist;
}

function validate_page_name(string $pagename, array $data): ProjectPage
{
    try {
        return $data[":projectid"]->get_project_page($pagename);
    } catch (NonexistentPageException | NoProjectPageTable $exception) {
        throw new NotFoundError($exception->getMessage(), $exception->getCode());
    }
}

function validate_page_round(string $pageround, array $_data): string
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

function validate_release_queue(string $queueid, array $_data): array
{
    $queue_data = fetch_queue_data((int)$queueid);
    if (is_null($queue_data)) {
        throw new NotFoundError("queue {$queueid} not found");
    }
    return $queue_data;
}

function validate_document(string $document): string
{
    global $external_faq_overrides;

    if (!array_key_exists($document, $external_faq_overrides)) {
        throw new NotFoundError("document $document not found");
    }
    return $document;
}

function validate_storage_key(string $storage_key, array $data): string
{
    global $api_storage_keys;

    if (!in_array($storage_key, $api_storage_keys)) {
        throw new NotFoundError("$storage_key is not a valid storage key");
    }
    return $storage_key;
}
