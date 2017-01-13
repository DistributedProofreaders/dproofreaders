<?php

$relPath='../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Increasing the column length of user_password...\n";
$sql = "
    ALTER TABLE non_activated_users
        CHANGE COLUMN user_password
            user_password varchar(128) not null default '';
";

echo "$sql\n";

mysql_query($sql) or die( mysql_error() );

// ------------------------------------------------------------

echo "\nDone!\n";


// vim: sw=4 ts=4 expandtab
?>
