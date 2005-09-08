<?php
$relPath='../../../pinc/';
include_once($relPath.'v_site.inc');
include($relPath.'connect.inc');
$db_Connection=new dbConnect();

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

echo "Done!\n";

// vim: sw=4 ts=4 expandtab
?>
