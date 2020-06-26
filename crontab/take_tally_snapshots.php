<?php

// Take a snapshot of all current TallyBoards.
// (This assumes that you want to snapshot all of them on the same schedule.)

$relPath='./../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'TallyBoard.inc');

require_localhost_request();

header('Content-type: text/plain');

$dry_run = array_get($_GET, 'dry_run', FALSE);

function maybe_query( $query )
{
    global $dry_run;
    if ($dry_run)
    {
        // Normalize whitespace
        // (mainly to remove newlines and indentation)
        $query = preg_replace('/\s+/', ' ', trim($query));
        echo "$query\n";
        return TRUE;
    }
    else
    {
        return mysqli_query(DPDatabase::get_connection(), $query);
    }
}

$today = getdate();
$midnight = mktime(0,0,0,$today['mon'],$today['mday'],$today['year']);

foreach ( get_all_current_tallyboards() as $tallyboard )
{
    echo "TallyBoard( tally_name='$tallyboard->tally_name', holder_type='$tallyboard->holder_type' ):\n";

    $id_for_logs = "TallyBoard($tallyboard->tally_name,$tallyboard->holder_type)";

    $t_start_tallyboard = time();
    maybe_query("INSERT INTO job_logs (filename, tracetime, event, comments) VALUES ('take_tally_snapshots.php', $t_start_tallyboard, 'BEGIN', '$id_for_logs: start (ascribed to $midnight)' )");

    $err = $tallyboard->take_snapshot( $midnight, $dry_run );

    if ( $err )
    {
        echo "    This tallyboard has already had a snapshot taken for $midnight!\n";

        maybe_query("INSERT INTO job_logs (filename, tracetime, event, comments) VALUES ('take_tally_snapshots.php', ".time().", 'FAIL', '$id_for_logs: already has snapshot ascribed to $midnight!')");
    }
    else
    {
        $t_end_tallyboard = time();
        $elapsed_for_tallyboard = $t_end_tallyboard - $t_start_tallyboard;
        echo "    Snapshot completed in $elapsed_for_tallyboard seconds.\n";

        maybe_query("INSERT INTO job_logs (filename, tracetime, event, comments) VALUES ('take_tally_snapshots.php', $t_end_tallyboard, 'END', '$id_for_logs: Started at $t_start_tallyboard, took $elapsed_for_tallyboard seconds total')");
    }
    echo "------------------------------------------------------------------\n";
}

// vim: sw=4 ts=4 expandtab
