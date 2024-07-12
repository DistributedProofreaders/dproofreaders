<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Updating special_days...\n";
$update_statements = [
    "UPDATE special_days SET comment = '' where comment is NULL",
    "UPDATE special_days SET open_day = 0 where open_day is NULL",
    "UPDATE special_days SET open_month = 0 where open_day is NULL",
    "UPDATE special_days SET close_day = 0 where open_day is NULL",
    "UPDATE special_days SET close_month = 0 where open_day is NULL",
    "UPDATE special_days SET date_changes = '' where date_changes is NULL",
    "UPDATE special_days SET info_url = '' where info_url is NULL",
    "UPDATE special_days SET image_url = '' where image_url is NULL",
    "UPDATE special_days SET symbol = '' where symbol is NULL",
];

foreach ($update_statements as $sql) {
    echo "\n    $sql\n";
    mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));
}

$sql = "
    ALTER TABLE special_days
        MODIFY COLUMN `comment` varchar(255) NOT NULL default '',
        MODIFY COLUMN `open_day` tinyint NOT NULL default 0,
        MODIFY COLUMN `open_month` tinyint NOT NULL default 0,
        MODIFY COLUMN `close_day` tinyint NOT NULL default 0,
        MODIFY COLUMN `close_month` tinyint NOT NULL default 0,
        MODIFY COLUMN `date_changes` varchar(100) NOT NULL default '',
        MODIFY COLUMN `info_url` varchar(255) NOT NULL default '',
        MODIFY COLUMN `image_url` varchar(255) NOT NULL default '',
        MODIFY COLUMN `symbol` varchar(2) NOT NULL default '',
        DROP INDEX `spec_code`,
        ADD PRIMARY KEY (spec_code);
";

mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));

// ------------------------------------------------------------

echo "\nDone!\n";
