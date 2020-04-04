<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'unicode.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Normalizing project details...";

$sql = "
    SELECT projectid, nameofwork, authorsname, comments, extra_credits
    FROM projects
    ORDER BY projectid;
";

$result = mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

$projects = array();
while($row = mysqli_fetch_assoc($result))
{
    $projects[] = $row;
}

echo count($projects) . " possible projects to normalize.\n";

foreach($projects as $project)
{
    $sql = sprintf("
            UPDATE projects SET
            nameofwork    = '%s',
            authorsname   = '%s',
            comments      = '%s',
            extra_credits = '%s'
            WHERE projectid = '%s';
        ",
        mysqli_real_escape_string(DPDatabase::get_connection(), utf8_normalize(html_entity_decode($project["nameofwork"]))),
        mysqli_real_escape_string(DPDatabase::get_connection(), utf8_normalize(html_entity_decode($project["authorsname"]))),
        mysqli_real_escape_string(DPDatabase::get_connection(), utf8_normalize($project["comments"])),
        mysqli_real_escape_string(DPDatabase::get_connection(), utf8_normalize(html_entity_decode($project["extra_credits"]))),
        $project["projectid"]
    );

    echo "Processing " . $project["projectid"] . "\n";
    mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );
}

// ------------------------------------------------------------

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
