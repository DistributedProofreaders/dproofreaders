<?php

$relPath='../../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'SettingsClass.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Migrating manager and sitemanager columns from users to usersettings...\n";

$sql = "
    SELECT username, manager, sitemanager
    FROM users
    WHERE manager = 'yes' or sitemanager = 'yes';
";

echo "$sql\n";

$result = mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

while($row = mysqli_fetch_assoc($result))
{
    $userSetting =& Settings::get_settings($row['username']);
    if($row['manager'] == "yes")
        $userSetting->set_boolean("manager", true);
    if($row['sitemanager'] == "yes")
        $userSetting->set_boolean("sitemanager", true);
}

// ------------------------------------------------------------

echo "\nDone!\n";


// vim: sw=4 ts=4 expandtab
