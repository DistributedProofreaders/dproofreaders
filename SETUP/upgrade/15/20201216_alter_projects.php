<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Adding comment_format column to projects table..\n";
$sql = "
    ALTER TABLE projects
        ADD COLUMN comment_format varchar(8) NOT NULL DEFAULT 'markdown' AFTER comments
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

// ------------------------------------------------------------4

echo "Setting comment_format for existing projects to html..\n";
$sql = "
    UPDATE projects SET comment_format='html'
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

// ------------------------------------------------------------

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
