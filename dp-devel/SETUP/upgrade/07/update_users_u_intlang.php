<?php
$relPath='../../../pinc/';
include_once($relPath.'v_site.inc');
include($relPath.'connect.inc');
$db_Connection=new dbConnect();

header('Content-type: text/plain');

echo "\n";
echo "Changing 'u_intlang' column...\n";

mysql_query("
    ALTER TABLE users
        CHANGE COLUMN u_intlang u_intlang VARCHAR(25) DEFAULT '' 
") or die(mysql_error());

echo "Done!\n";

// vim: sw=4 ts=4 expandtab
?>
