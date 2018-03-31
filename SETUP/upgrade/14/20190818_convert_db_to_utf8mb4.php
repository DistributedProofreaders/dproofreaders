<?php
$relPath='../../../pinc/';
// To enable DB conversion from Latin-1, we need to bypass the encoding check.
include_once($relPath.'DPDatabase.inc');
DPDatabase::$skip_encoding_check = True;
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');
// We need to include udb_user to get the database name ($db_name)
// We require() it instead of include_once() since it may also be
// loaded elsewhere and we can't continue without it.
require($relPath.'udb_user.php');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "This script will migrate all non-project tables in $db_name from latin1 to utf8mb4.\n";

// First confirm the user has changed the default database character set
$default_charset = DPDatabase::get_default_db_charset();
if($default_charset != 'utf8mb4')
{
    echo <<<EOF
The default character set for $db_name is $default_charset, not utf8mb4.
Before proceeding, run the following command as mysql root user (or other
sufficiently privileged user) to update the default character set.

    ALTER DATABASE $db_name DEFAULT CHARACTER SET utf8mb4;

EOF;
    die();
}

// Now get all of the tables to iterate over that are still latin1

$sql = "
    SELECT table_name
    FROM information_schema.tables
    WHERE
        table_schema='$db_name' AND
        table_collation LIKE 'latin1_%' AND
        table_name NOT LIKE 'projectID%'
    ORDER BY table_name;
";

$result = mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

$latin1_tables = array();
while($row = mysqli_fetch_assoc($result))
{
    $latin1_tables[] = $row['table_name'];
}

$stop_file = '/tmp/utf8_conversion';

echo <<<EOF
This will now start converting all of the non-project latin1 tables in
$db_name to utf8mb4. This will likely take a while.
To stop the process gracefully create $stop_file
(eg: touch $stop_file) and it will stop after it
finishes the table it is working on.

EOF;

echo count($latin1_tables) . " tables to convert.\n";

foreach($latin1_tables as $table)
{
    if(file_exists($stop_file))
    {
        echo "\n\n";
        echo "Detected stop file ($stop_file), so stopping before starting $table.\n";
        echo "Delete this file and restart to continue.\n";
        exit();
    }

    $sql = "
        ALTER TABLE $table CONVERT TO CHARACTER SET utf8mb4;
    ";

    echo "Converting $table...\n";
    mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );
}

echo "\n";

// ------------------------------------------------------------

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
