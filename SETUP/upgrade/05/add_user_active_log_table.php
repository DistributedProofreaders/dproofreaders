<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');

echo "Creating 'user_active_log' table...\n";
$sql = "
    CREATE TABLE user_active_log (
        year       SMALLINT(4)  UNSIGNED NOT NULL default '2003',
        month      TINYINT(2)   UNSIGNED NOT NULL default '0',
        day        TINYINT(2)   UNSIGNED NOT NULL default '0',
        hour       SMALLINT(2)  UNSIGNED NOT NULL default '0',
        time_stamp INT(10)      UNSIGNED NOT NULL default '0',
        U_lasthour MEDIUMINT(6) UNSIGNED NOT NULL default '0',
        U_day      MEDIUMINT(6) UNSIGNED NOT NULL default '0',
        U_week     MEDIUMINT(7) UNSIGNED NOT NULL default '0',
        U_4wks     MEDIUMINT(7) UNSIGNED NOT NULL default '0',
        comments   VARCHAR(255)                   default NULL,
        KEY timestamp_ndx (time_stamp)
    ) TYPE=MyISAM
";
mysql_query($sql) or die( mysql_error() );

echo "\nDone!\n";
?>
