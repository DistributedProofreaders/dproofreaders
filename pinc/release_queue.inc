<?php
// A place for shared functions dealing with release queues.

$today_MMDD = date('md', strtotime('today'));
$tomorrow_MMDD = date('md', strtotime('tomorrow'));

/**
 * Expand certain sequences in $project_selector.
 *
 * Expanding date-related sequences means we don't have to
 * update the queue's project_selector every day.
 */
function cook_project_selector($project_selector)
{
    global $today_MMDD, $tomorrow_MMDD;
    return
        preg_replace(
            ['/{today_MMDD}/', '/{tomorrow_MMDD}/'],
            [$today_MMDD,      $tomorrow_MMDD],
            $project_selector
        );
}
