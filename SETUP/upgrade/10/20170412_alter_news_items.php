<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Adding locale column to news_items...\n";
$sql = "
    ALTER TABLE news_items
        ADD COLUMN locale VARCHAR(8) NOT NULL DEFAULT ''
            AFTER content;
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

// ------------------------------------------------------------

echo "Adding index to news_items...\n";
$sql = "
    ALTER TABLE news_items
        ADD KEY pageid_locale (news_page_id, locale);
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

// ------------------------------------------------------------

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
