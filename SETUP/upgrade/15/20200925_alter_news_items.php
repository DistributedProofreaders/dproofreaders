<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Adding columns to news_items..\n";
$sql = "
    ALTER TABLE news_items
        ADD COLUMN header varchar(256) NOT NULL DEFAULT '',
        ADD COLUMN item_type varchar(16) NOT NULL DEFAULT ''
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));

// ------------------------------------------------------------

echo "\nDone!\n";
