<?php

// Take a snapshot of all current TallyBoards.
// (This assumes that you want to snapshot all of them on the same schedule.)

$relPath = './../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'TallyBoard.inc');
include_once($relPath.'job_log.inc');

require_localhost_request();

header('Content-type: text/plain');

$dry_run = array_get($_GET, 'dry_run', false);

function maybe_log_event($event, $comments)
{
    global $dry_run;

    $filename = "take_tally_snapshots.php";

    if ($dry_run) {
        echo "job_log entry: ($filename, $event, $comments)\n";
    } else {
        insert_job_log_entry($filename, $event, $comments);
    }
}

$today = getdate();
$midnight = mktime(0, 0, 0, $today['mon'], $today['mday'], $today['year']);

foreach (get_all_current_tallyboards() as $tallyboard) {
    echo "TallyBoard( tally_name='$tallyboard->tally_name', holder_type='$tallyboard->holder_type' ):\n";

    $id_for_logs = "TallyBoard($tallyboard->tally_name,$tallyboard->holder_type)";

    $t_start_tallyboard = time();
    maybe_log_event('BEGIN', "$id_for_logs: start (ascribed to $midnight)");

    $err = $tallyboard->take_snapshot($midnight, $dry_run);

    if ($err) {
        echo "    This tallyboard has already had a snapshot taken for $midnight!\n";

        maybe_log_event(
            "FAIL",
            "$id_for_logs: already has snapshot ascribed to $midnight!"
        );
    } else {
        $t_end_tallyboard = time();
        $elapsed_for_tallyboard = $t_end_tallyboard - $t_start_tallyboard;
        echo "    Snapshot completed in $elapsed_for_tallyboard seconds.\n";

        maybe_log_event(
            'END',
            "$id_for_logs: Started at $t_start_tallyboard, took $elapsed_for_tallyboard seconds total"
        );
    }
    echo "------------------------------------------------------------------\n";
}
