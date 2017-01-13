<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

echo "\n";
echo "Adding tallyboard_time index...\n";
// This is to speed up TallyBoard::get_time_of_latest_snapshot()

mysql_query("
    ALTER TABLE past_tallies
        ADD INDEX tallyboard_time (tally_name,holder_type,timestamp);
") or die(mysql_error());

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
?>
