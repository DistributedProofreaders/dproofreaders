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

$stop_file = '/tmp/stop_utf8_conversion';

echo <<<EOF
This will now start converting all of the non-project latin1 tables in
$db_name to utf8mb4. This will likely take a while.

To stop the process gracefully create $stop_file
(eg: touch $stop_file) and it will stop after it
finishes the table it is working on.

EOF;

echo count($latin1_tables) . " tables to convert.\n";

$schema_tables = get_db_schema_tables();
$non_schema_tables = [];

foreach($latin1_tables as $table)
{
    if(file_exists($stop_file))
    {
        echo "\n\n";
        echo "Detected stop file ($stop_file), so stopping before starting $table.\n";
        echo "Delete this file and restart to continue.\n";
        exit();
    }

    if(!in_array($table, $schema_tables))
    {
        $non_schema_tables[] = $table;
        continue;
    }

    $sql = "
        ALTER TABLE $table CONVERT TO CHARACTER SET utf8mb4;
    ";

    echo "Converting $table...\n";
    mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );
}

if($non_schema_tables)
{
    echo "\n\n";
    echo "The following tables were found in $db_name but are not part\n";
    echo "of the standard DP schema and were not converted:\n";
    foreach($non_schema_tables as $table)
    {
        echo "    $table\n";
    }
}

echo "\n";

// ------------------------------------------------------------

function get_db_schema_tables()
{
    $tables = [];
    foreach(explode("\n", file_get_contents("../../db_schema.sql")) as $line)
    {
        if(preg_match('/create table `(\w+)`/i', $line, $matches))
        {
            $tables[] = $matches[1];
        }
    }
    return $tables;
}

// ------------------------------------------------------------

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
