<?php

$relPath='../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

echo "Removing empty user filters from user_filters...\n";

$sql = "
    DELETE FROM user_filters
        WHERE filtertype like '%_internal' and value = ' AND 1'
";

echo "$sql\n";

mysql_query($sql) or die( mysql_error() );

$sql = "
    DELETE FROM user_filters
        WHERE filtertype like '%_display' and value = ''
";

echo "$sql\n";

mysql_query($sql) or die( mysql_error() );

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
?>
