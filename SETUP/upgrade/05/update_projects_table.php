<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');

echo "Altering 'projects' table...\n";

mysqli_query(DPDatabase::get_connection(), "
    ALTER TABLE projects
        MODIFY COLUMN projectid VARCHAR(22) NOT NULL DEFAULT '',
        MODIFY COLUMN clearance TEXT NOT NULL,
        ADD PRIMARY KEY (projectid),
        ADD INDEX (state)
") or die(mysqli_error(DPDatabase::get_connection()));

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
?>
