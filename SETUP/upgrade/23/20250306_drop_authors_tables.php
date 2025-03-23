<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

echo <<<EOF
    WARNING: Do not run this script until 20250305_replace_project_bios.php
    has completed successfully and all project comments with existing bios
    has been updated. Edit this file and comment out the exit() to continue.
    
    EOF;
exit(1);

// ------------------------------------------------------------

echo "Dropping table biographies...\n";
$sql = "
    DROP TABLE biographies
";

mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));

// ------------------------------------------------------------

echo "Dropping table authors...\n";
$sql = "
    DROP TABLE authors
";

mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));

// ------------------------------------------------------------

echo "\nDone!\n";
