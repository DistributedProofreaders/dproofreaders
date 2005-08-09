<?PHP
// A place for shared functions dealing with release queues.

$today_MMDD    = strftime( '%m%d', strtotime('today') );
$tomorrow_MMDD = strftime( '%m%d', strtotime('tomorrow') );

function cook_project_selector( $project_selector )
// Expand certain sequences in $project_selector.
// (Expanding date-related sequences means we don't have to
// update the queue's project_selector every day.)
{
    global $today_MMDD, $tomorrow_MMDD;
    return
        preg_replace(
            array( '/{today_MMDD}/', '/{tomorrow_MMDD}/' ),
            array(   $today_MMDD,      $tomorrow_MMDD ),
            $project_selector
        );
}

// vim: sw=4 ts=4 expandtab
?>
