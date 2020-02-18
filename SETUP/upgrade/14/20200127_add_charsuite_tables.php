<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'CharSuites.inc');
include_once($relPath.'Project.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Creating charsuites table\n";

$sql = "
    CREATE TABLE charsuites (
        name varchar(64) not null primary key,
        enabled tinyint default 1
    );
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

// ------------------------------------------------------------

echo "Creating project charsuites table\n";

$sql = "
    CREATE TABLE project_charsuites (
        projectid varchar(22) not null,
        charsuite_name varchar(64) not null,
        PRIMARY KEY (projectid, charsuite_name),
        FOREIGN KEY (charsuite_name) REFERENCES charsuites(name)
    );
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

// ------------------------------------------------------------

echo "Enabling Basic Latin charsuite\n";

CharSuites::enable("basic-latin");

echo "$sql\n";

// ------------------------------------------------------------

echo "Adding Basic Latin charsuites to all projects\n";

$sql = "
    SELECT projectid
    FROM projects
    ORDER BY projectid;
";

$result = mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

$projects = array();
while($row = mysqli_fetch_assoc($result))
{
    $projects[] = $row['projectid'];
}

foreach($projects as $projectid)
{
    echo "    Adding Basic Latin charsuite to $projectid\n";
    $project = new Project($projectid);
    $project->add_charsuite('basic-latin');
}

// ------------------------------------------------------------

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
