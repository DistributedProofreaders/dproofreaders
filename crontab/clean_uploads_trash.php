<?php
$relPath = "./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc'); // requester_is_localhost()

require_localhost_request();

$trash_dir = realpath("$uploads_dir/$uploads_subdir_trash");
if (!$trash_dir) {
    die("Invalid or non-existent uploads trash subdirectory, bailing.");
}

// remove files from TRASH subdirectory older than 30 days
unset($output);
exec("/usr/bin/find '$trash_dir' -type f -mtime +30 -delete", $output, $return);
if ($return != 0) {
    echo "An error occurred while cleaning up files.\n";
    echo "Return value: $return\n";
    echo "Command output:\n";
    foreach ($output as $line) {
        echo "    $line\n";
    }
}

// remove empty directories
unset($output);
exec("/usr/bin/find '$trash_dir' -type d -empty -delete", $output, $return);
if ($return != 0) {
    echo "An error occurred while cleaning up files.\n";
    echo "Return value: $return\n";
    echo "Command output:\n";
    foreach ($output as $line) {
        echo "    $line\n";
    }
}
