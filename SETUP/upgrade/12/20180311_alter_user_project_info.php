<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Adding default values to user_project_info columns..\n";
$sql = "
    ALTER TABLE user_project_info
        CHANGE COLUMN t_latest_home_visit t_latest_home_visit int(10) unsigned NOT NULL default '0',
        CHANGE COLUMN t_latest_page_event t_latest_page_event int(10) unsigned NOT NULL default '0',
        CHANGE COLUMN iste_round_available iste_round_available tinyint(1) NOT NULL default '0',
        CHANGE COLUMN iste_round_complete iste_round_complete tinyint(1) NOT NULL default '0',
        CHANGE COLUMN iste_pp_enter iste_pp_enter tinyint(1) NOT NULL default '0',
        CHANGE COLUMN iste_sr_available iste_sr_available tinyint(1) NOT NULL default '0',
        CHANGE COLUMN iste_ppv_enter iste_ppv_enter tinyint(1) NOT NULL default '0',
        CHANGE COLUMN iste_posted iste_posted tinyint(1) NOT NULL default '0';
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

// ------------------------------------------------------------

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
