<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Adding a primary key on tasks_comments...\n";
$sql = "
    ALTER TABLE tasks_comments
        ADD PRIMARY KEY
            ( task_id, u_id, comment_date );
";

echo "$sql\n";

mysql_query($sql) or die( mysql_error() );

// ------------------------------------------------------------

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
