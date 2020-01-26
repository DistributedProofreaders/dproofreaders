<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'Project.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Converting project tables to UTF8...";

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

$stop_file = '/tmp/utf8_conversion';

echo <<<EOF
This will now start converting all of the project latin1 tables in
$db_name to utf8mb4. This will likely take a while.
To stop the process gracefully create $stop_file
(eg: touch $stop_file) and it will stop after it
finishes the table it is working on.

EOF;

echo count($projects) . " possible tables to convert.\n";

foreach($projects as $projectid)
{
    if(file_exists($stop_file))
    {
        echo "\n\n";
        echo "Detected stop file ($stop_file), so stopping before starting $projectid.\n";
        echo "Delete this file and restart to continue.\n";
        exit();
    }

    $project = new Project($projectid);
    $was_converted = $project->convert_to_utf8();
    if($was_converted)
    {
        echo "$projectid was converted\n";
    }
    else
    {
        echo "$projectid was not converted because ";
        if($project->is_utf8)
            echo "project is already UTF-8";
        elseif($project->archived)
            echo "project was archived";
        elseif($project->state == PROJ_SUBMIT_PG_POSTED)
            echo "project has been posted to PG";
        elseif(!$project->check_pages_table_exists($message))
            echo "project page table does not exist";
        else
            echo "unknown";
        echo "\n";
    }
}

echo "\n";

// ------------------------------------------------------------

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
