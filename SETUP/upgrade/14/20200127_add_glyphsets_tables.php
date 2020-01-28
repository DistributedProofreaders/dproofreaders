<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'Glyphsets.inc');
include_once($relPath.'Project.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Creating glyphsets table\n";

$sql = "
    CREATE TABLE glyphsets (
        name varchar(64) not null primary key,
        enabled tinyint default 1
    );
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

// ------------------------------------------------------------

echo "Creating project glyphsets table\n";

$sql = "
    CREATE TABLE project_glyphsets (
        projectid varchar(22) not null,
        glyphset_name varchar(64) not null,
        PRIMARY KEY (projectid, glyphset_name),
        FOREIGN KEY (glyphset_name) REFERENCES glyphsets(name)
    );
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

// ------------------------------------------------------------

echo "Enabling Basic Latin glyphset\n";

Glyphsets::enable("basic-latin");

echo "$sql\n";

// ------------------------------------------------------------

echo "Adding Basic Latin glyphsets to all projects\n";

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
    echo "    Adding Basic Latin glyphset to $projectid\n";
    $project = new Project($projectid);
    $project->add_glyphset('basic-latin');
}

// ------------------------------------------------------------

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
