<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');
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

$stop_file = '/tmp/utf8_normalization';

echo <<<EOF
This will now start normalizing all of the project fields.
This will likely take a while.
To stop the process gracefully create $stop_file
(eg: touch $stop_file) and it will stop after it
finishes the table it is working on.

EOF;

echo count($projects) . " possible projects to normalize.\n";

foreach($projects as $project)
{
    if(file_exists($stop_file))
    {
        echo "\n\n";
        echo "Detected stop file ($stop_file), so stopping before starting " . $project["projectid"] . ".\n";
        echo "Delete this file and restart to continue.\n";
        exit();
    }

    
    $sql = "
        UPDATE projects SET
        nameofwork    = '".mysqli_real_escape_string(DPDatabase::get_connection(), utf8_normalize($project["nameofwork"]))."',
        authorsname   = '".mysqli_real_escape_string(DPDatabase::get_connection(), utf8_normalize($project["authorsname"]))."',
        comments      = '".mysqli_real_escape_string(DPDatabase::get_connection(), utf8_normalize($project["comments"]))."',
        extra_credits = '".mysqli_real_escape_string(DPDatabase::get_connection(), utf8_normalize($project["extra_credits"]))."'
        WHERE projectid = '".$project["projectid"]."';
    ";

    mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );
}

echo "\n";

// ------------------------------------------------------------

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
