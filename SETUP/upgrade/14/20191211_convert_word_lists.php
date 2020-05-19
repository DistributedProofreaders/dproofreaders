<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'unicode.inc');
include_once($relPath.'wordcheck_engine.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

echo "Convert site word lists to UTF-8\n";

$files = get_site_word_files("/txt/", FALSE);

foreach($files as $file)
{
    list($success, $message) = convert_text_file_to_utf8($file);
    if(!$success)
        echo "ERROR: $message\n";
    else
        echo "$message\n";
}

// ------------------------------------------------------------

// project word lists are taken care of in
// 20191211_convert_project_text_files.php

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
