<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'wordcheck_engine.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Convert site word lists to UTF-8\n";

$files = get_site_word_files("/txt/", FALSE);

foreach($files as $file)
{
    convert_file_to_utf8($file);
}

// ------------------------------------------------------------

echo "Convert project word lists to UTF-8\n";

$files = get_project_word_files("good_words.txt");
foreach($files as $file)
{
    convert_file_to_utf8($file);
}

$files = get_project_word_files("bad_words.txt");
foreach($files as $file)
{
    convert_file_to_utf8($file);
}


// ------------------------------------------------------------

function get_project_word_files($filename)
{
    global $projects_dir;

    $output = [];
    exec("find $projects_dir -name '$filename'", $output);
    return $output;
}

function convert_file_to_utf8($filename)
{
    $text = file_get_contents($filename);
    $encoding = guess_string_encoding($text);

    if($encoding === FALSE)
    {
        echo "ERROR: Unable to detect coding for $filename, skipping\n";   
        return;
    }

    if($encoding == 'UTF-8')
    {
        echo "$filename already in UTF-8, skipping\n";
        return;
    }

    echo "$filename will be converted from $encoding to UTF-8\n";
    $text = mb_convert_encoding($text, "UTF-8", $encoding);
    file_put_contents($filename, $text);
}


// ------------------------------------------------------------

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
