<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'unicode.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Convert all text files in project directory to UTF-8\n";

$files = get_project_text_files();
foreach($files as $file)
{
    list($success, $message) = convert_text_file_to_utf8($file);
    if(!$success)
        echo "ERROR: $message\n";
    else
        echo "$message\n";
}

// ------------------------------------------------------------

function get_project_text_files()
{
    global $projects_dir;

    $output = [];
    exec("find $projects_dir -name '*.txt'", $output);
    return $output;
}

// ------------------------------------------------------------

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
