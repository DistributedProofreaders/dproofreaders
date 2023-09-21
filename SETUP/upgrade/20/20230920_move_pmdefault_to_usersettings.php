<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

$pm_view_options = [
    0 => "user_all",
    1 => "user_active",
    2 => "blank",
];

$pms = Settings::get_users_with_setting("manager", "yes");

foreach ($pms as $pm) {
    $user = new User($pm);
    $user_settings = new Settings($pm);
    $user_settings->set_value("pm_view", $pm_view_options[$user->i_pmdefault]);
}

echo "Dropping users.i_pmdefault column\n";
$sql = "
    ALTER TABLE users
    DROP COLUMN i_pmdefault
";

$result = mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));

// ------------------------------------------------------------

echo "\nDone!\n";
