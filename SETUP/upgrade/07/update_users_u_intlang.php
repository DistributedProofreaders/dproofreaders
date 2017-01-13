<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

echo "\n";
echo "Changing 'u_intlang' column...\n";

mysql_query("
    ALTER TABLE users
        CHANGE COLUMN u_intlang u_intlang VARCHAR(25) DEFAULT '' 
") or die(mysql_error());
mysql_query("
    ALTER TABLE non_activated_users
        CHANGE COLUMN u_intlang u_intlang VARCHAR(25) DEFAULT ''
") or die(mysql_error());


echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
?>
