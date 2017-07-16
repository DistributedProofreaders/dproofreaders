<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');

echo "Altering 'user_profiles' table...\n";

mysqli_query(DPDatabase::get_connection(), "
    ALTER TABLE user_profiles
        ALTER COLUMN v_twrap SET DEFAULT '0',
        ALTER COLUMN h_twrap SET DEFAULT '0'
") or die(mysqli_error(DPDatabase::get_connection()));

echo "\nDone!\n";
?>
