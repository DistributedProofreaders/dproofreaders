<?php

$relPath='../../../pinc/';
include($relPath.'connect.inc');
new dbConnect();

header('Content-type: text/plain');

echo "\n";
echo "----------------------------------------------------------------------\n";
echo "Adding a primary key...\n";
// This should have been done when the table was introduced.
$sql = "
    ALTER TABLE news_pages
        ADD PRIMARY KEY (news_page_id)
";
echo "$sql\n";
mysql_query($sql) or die( mysql_error() );

echo "\n";
echo "----------------------------------------------------------------------\n";
echo "Dropping 'news_type' column...\n";
$sql = "
    ALTER TABLE news_pages
        DROP COLUMN news_type
";
echo "$sql\n";
mysql_query($sql) or die( mysql_error() );

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
?>
