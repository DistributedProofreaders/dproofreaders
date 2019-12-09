<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');
// We need to include udb_user to get the database name ($db_name)
// We require() it instead of include_once() since it may also be
// loaded elsewhere and we can't continue without it.
require($relPath.'udb_user.php');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo <<<EOF
This script will convert all non-project DP tables to InnoDB.
This is only recommended on MySQL 5.6.6 and later with innodb_file_per_table
enabled.

If you wish to continue with this, edit the script and remove the die()
statement.

EOF;

die();

// Get all of the non-project tables to iterate over that are still MyISAM

$sql = "
    SELECT table_name
    FROM information_schema.tables
    WHERE
        table_schema='$db_name' AND
        engine = 'MyISAM' AND
        table_name NOT LIKE 'projectID%'
    ORDER BY table_name;
";

$result = mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

$myisam_tables = array();
while($row = mysqli_fetch_assoc($result))
{
    $myisam_tables[] = $row['table_name'];
}

$stop_file = '/tmp/innodb_conversion';

echo <<<EOF
This will now start converting all of the non-project MyISAM tables in
$db_name to InnoDB. This will likely take a while.
To stop the process gracefully create $stop_file
(eg: touch $stop_file) and it will stop after it
finishes the table it is working on.

EOF;

echo count($myisam_tables) . " tables to evaluate.\n";

$schema_tables = get_db_schema_tables();
$non_schema_tables = [];

foreach($myisam_tables as $table)
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
        ALTER TABLE $table ENGINE = InnoDB;
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
