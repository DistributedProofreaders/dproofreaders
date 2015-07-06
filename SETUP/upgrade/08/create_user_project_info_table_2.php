<?php

$relPath='../../../pinc/';
include($relPath.'connect.inc');
new dbConnect();

echo "Enlarging user_project_info table...\n";
$sql = "
    ALTER TABLE user_project_info
        ADD COLUMN t_latest_home_visit INT UNSIGNED NOT NULL AFTER projectid,
        ADD COLUMN t_latest_page_event INT UNSIGNED NOT NULL AFTER t_latest_home_visit
";
echo "$sql\n";
mysql_query($sql) or die( mysql_error() );

// echo "Populating the new columns...\n";
// Will commit that code later.

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
?>
