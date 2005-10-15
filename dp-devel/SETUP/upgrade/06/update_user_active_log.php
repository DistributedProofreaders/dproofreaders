<?php
$relPath='../../../pinc/';
include_once($relPath.'connect.inc');
new dbConnect();

header('Content-type: text/plain');

echo "\n";
echo "Renaming U_* columns to L_*...\n";

mysql_query("
    ALTER TABLE user_active_log
        CHANGE COLUMN U_lasthour L_hour MEDIUMINT UNSIGNED,
        CHANGE COLUMN U_day      L_day  MEDIUMINT UNSIGNED,
        CHANGE COLUMN U_week     L_week MEDIUMINT UNSIGNED,
        CHANGE COLUMN U_4wks     L_4wks MEDIUMINT UNSIGNED
") or die(mysql_error());

echo "\n";
echo "Adding A_* columns...\n";

mysql_query("
    ALTER TABLE user_active_log
        ADD COLUMN A_hour MEDIUMINT UNSIGNED AFTER L_4wks,
        ADD COLUMN A_day  MEDIUMINT UNSIGNED AFTER A_hour,
        ADD COLUMN A_week MEDIUMINT UNSIGNED AFTER A_day,
        ADD COLUMN A_4wks MEDIUMINT UNSIGNED AFTER A_week
") or die(mysql_error());

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
?>
