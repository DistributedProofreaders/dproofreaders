<?php
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
        return $data[":projectid"]->get_project_page($pagename);
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

function validate_project_state($state, $project)
{
    global $PROJECT_STATES_IN_ORDER;

    try {
        if (null === $state) {
            throw new ProjectNoStateGivenException("No project state found in request.");
        }
        if (!in_array($state, $PROJECT_STATES_IN_ORDER)) {
            throw new ProjectInvalidStateException("Invalid project state");
        }
        if ($state != $project->state) {
            $err = sprintf(
                _('Warning: Project "%1$s" is not in state "%2$s"; it is now in state "%3$s".'),
                $project->nameofwork,
                project_states_text($state),
                project_states_text($project->state)
            );
            throw new ProjectNotInRequiredStateException($err);
        }
    } catch (ProjectException $exception) {
        throw new NotFoundError($exception->getMessage(), $exception->getCode());
    }
}

function validate_page_state($page_state, $project_page)
{
    global $PAGE_STATES_IN_ORDER;

    try {
        if (null === $page_state) {
            throw new ProjectNoPageStateGivenException("No page state found in request.");
        }
        if (!in_array($page_state, $PAGE_STATES_IN_ORDER)) {
            throw new ProjectInvalidPageStateException(sprintf("%s is not a valid page state", $page_state));
        }
        if ($page_state != $project_page->page_state) {
            $err = sprintf(
                _('Warning: Page  "%1$s" is no longer in state "%2$s"; it is now in state "%3$s".'),
                $project_page->page_name,
                $page_state,
                $project_page->page_state
            );
            throw new ProjectPageInconsistentStateException($err);
        }

    } catch (ProjectException $exception) {
        throw new NotFoundError($exception->getMessage(), $exception->getCode());
    }
}
