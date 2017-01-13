<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

echo "\n";
echo "Changing 'u_intlang' column...\n";

mysqli_query(DPDatabase::get_connection(), "
    ALTER TABLE users
        CHANGE COLUMN u_intlang u_intlang VARCHAR(25) DEFAULT '' 
") or die(mysqli_error(DPDatabase::get_connection()));
mysqli_query(DPDatabase::get_connection(), "
    ALTER TABLE non_activated_users
        CHANGE COLUMN u_intlang u_intlang VARCHAR(25) DEFAULT ''
") or die(mysqli_error(DPDatabase::get_connection()));


echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
?>
