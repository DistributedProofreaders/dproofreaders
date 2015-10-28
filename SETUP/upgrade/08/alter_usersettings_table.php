<?php

$relPath='../../../pinc/';
include($relPath.'connect.inc');
new dbConnect();

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Adding an index for username, setting, value...\n";
$sql = "
    ALTER TABLE usersettings
        ADD INDEX username_setting_val
            ( username, setting, value );
";

echo "$sql\n";

mysql_query($sql) or die( mysql_error() );

// ------------------------------------------------------------

echo "Updating the setting index...\n";
echo "  dropping the old one...\n";
$sql = "
    ALTER TABLE usersettings
        DROP INDEX setting;
";

echo "$sql\n";

mysql_query($sql) or die( mysql_error() );

echo "  creating the new one...\n";
$sql = "
    ALTER TABLE usersettings
        ADD INDEX setting
            ( setting, value desc );
";

echo "$sql\n";

mysql_query($sql) or die( mysql_error() );

echo "Creating the value index...\n";
$sql = "
    ALTER TABLE usersettings
        ADD INDEX value 
            ( value, setting );
";

echo "$sql\n";

mysql_query($sql) or die( mysql_error() );

// ------------------------------------------------------------

echo "\nDone!\n";


// vim: sw=4 ts=4 expandtab
?>
