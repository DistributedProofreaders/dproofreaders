<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');
// We need to include udb_user to get the archive name ($archive_db_name)
// We require() it instead of include_once() since it may also be
// loaded elsewhere and we can't continue without it.
require($relPath.'udb_user.php');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "This script will migrate all non-project tables in $archive_db_name from latin1 to utf8mb4.\n";

// First confirm the user has changed the default database character set
// Lift this code from pinc/DPDatabase.inc since it's for the archive DB
$sql = sprintf("
    SELECT *
    FROM information_schema.schemata
    WHERE schema_name='%s';
", $archive_db_name);

$result = mysqli_query(DPDatabase::get_connection(), $sql);
if(!$result)
{
    echo "Unable to determine archive database character set.\n";
    exit(1);
}

$row = mysqli_fetch_assoc($result);
if(!$row)
{
    echo "No archive database found. Nothing to do.\n";
    echo "If you think this is incorrect, ensure user $db_user has access to $archive_db_name.\n";
    exit(0);
}

$default_charset = $row['DEFAULT_CHARACTER_SET_NAME'];
if($default_charset != 'utf8mb4')
{
    echo <<<EOF
The default character set for $archive_db_name is $default_charset, not utf8mb4.
Before proceeding, run the following command as mysql root user (or other
sufficiently privileged user) to update the default character set.

    ALTER DATABASE $archive_db_name DEFAULT CHARACTER SET utf8mb4;

EOF;
    exit(1);
}

// Now get all of the tables to iterate over that are still latin1

$sql = "
    SELECT TABLE_NAME
    FROM information_schema.tables
    WHERE
        table_schema='$archive_db_name' AND
        table_collation LIKE 'latin1_%' AND
        table_name NOT LIKE 'projectID%'
    ORDER BY table_name;
";

$result = mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

$latin1_tables = array();
while($row = mysqli_fetch_assoc($result))
{
    $latin1_tables[] = $row['TABLE_NAME'];
}

$stop_file = '/tmp/stop_utf8_conversion';

echo <<<EOF
This will now start converting all of the non-project latin1 tables in
$archive_db_name to utf8mb4. This will likely take a while.

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
        ALTER TABLE $archive_db_name.$table CONVERT TO CHARACTER SET utf8mb4;
    ";

    echo "Converting $table...\n";
    mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );
}

// ------------------------------------------------------------

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
