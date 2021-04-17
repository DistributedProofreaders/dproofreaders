<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Adding bio_format column to biographies table..\n";
$sql = "
    ALTER TABLE biographies
        ADD COLUMN bio_format varchar(8) NOT NULL DEFAULT 'markdown' AFTER last_modified
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));

// ------------------------------------------------------------4

echo "Setting bio_format for existing biographies to html..\n";
$sql = "
    UPDATE biographies SET bio_format='html'
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));

// ------------------------------------------------------------

echo "\nDone!\n";
