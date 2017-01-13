<?php

// Where appropriate, prepend 'via_q:' to project_events.details3

$relPath='../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

echo "Tweaking project_events.details3...\n";

$sql = "
    UPDATE project_events
    SET details3 = CONCAT('via_q: ', details3)
    WHERE who = '[AUTO]'
        AND event_type='transition'
        AND details1 LIKE '%.proj_waiting'
        -- details2 could be <round_id>.proj_avail or <round_id>.proj_bad
";

echo "$sql\n";

mysql_query($sql) or die( mysql_error() );
echo "\n", mysql_affected_rows(), " rows affected.\n";

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
?>
