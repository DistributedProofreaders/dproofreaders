<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Add language to rules table...\n";

$sql = "
    ALTER TABLE rules ADD COLUMN langcode VARCHAR(5) after document
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()));

// ------------------------------------------------------------

echo "Setting existing rule langcode to 'en'...\n";

$sql = "
    UPDATE rules SET langcode='en'
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()));

// ------------------------------------------------------------

echo "Updating document name for formatting guidelines...\n";

$sql = "
    UPDATE rules
        SET document='formatting_guidelines.php'
        WHERE document='document.php'
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()));

// ------------------------------------------------------------

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
