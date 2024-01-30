<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Adding autoincrement id to queue_defns\n";
$sql = "
    ALTER TABLE queue_defns
    ADD COLUMN `id` INT AUTO_INCREMENT PRIMARY KEY FIRST
";

$result = mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));

// ------------------------------------------------------------

echo "\nDone!\n";
