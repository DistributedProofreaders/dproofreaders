<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Updating image_sources...\n";
$update_statements = [
    "UPDATE image_sources SET url = '' where url is NULL",
    "UPDATE image_sources SET credit = '' where credit is NULL",
    "UPDATE image_sources SET public_comment = '' where public_comment is NULL",
];

foreach ($update_statements as $sql) {
    echo "\n    $sql\n";
    mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));
}

$sql = "
    ALTER TABLE image_sources
        MODIFY COLUMN `url` varchar(255) NOT NULL default '',
        MODIFY COLUMN `credit` varchar(255) NOT NULL default '',
        MODIFY COLUMN `public_comment` varchar(255) NOT NULL default '',
        DROP INDEX `code_name`,
        ADD PRIMARY KEY (code_name);
";

mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));

// ------------------------------------------------------------

echo "\nDone!\n";
