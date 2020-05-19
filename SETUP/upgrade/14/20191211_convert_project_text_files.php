<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'unicode.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Convert all text files in project directory to UTF-8\n";

$dirs = get_project_dirs();
$total = count($dirs);
$index = 1;
foreach($dirs as $dir)
{
    echo sprintf("%d/%d (%0.1f%%) $dir\n", $index, $total, ($index / $total) * 100);
    $files = get_project_text_files($dir);
    foreach($files as $file)
    {
        list($success, $message) = convert_text_file_to_utf8($file);
        if(!$success)
            echo "ERROR: $message\n";
    }
    $index++;
}

// ------------------------------------------------------------

function get_project_dirs()
{
    global $projects_dir;

    $output = [];
    exec("find $projects_dir -maxdepth 1 -type d", $output);
    return array_diff($output, [$projects_dir]);
}

function get_project_text_files($dir)
{
    $output = [];
    exec("find $dir -name '*.txt'", $output);
    return $output;
}

// ------------------------------------------------------------

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
