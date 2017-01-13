<?php

$relPath='../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

echo "\n";
echo "----------------------------------------------------------------------\n";
echo "Renaming/retyping modifieddate (VARCHAR) to t_last_change (INT)...\n";
// No idea why it was introduced as VARCHAR.
$sql = "
    ALTER TABLE news_pages
        CHANGE COLUMN modifieddate t_last_change INT NOT NULL
";
echo "$sql\n";
mysql_query($sql) or die( mysql_error() );

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
