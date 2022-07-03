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
$cmd = join(" ", [
    "/usr/bin/find",
    escapeshellarg($trash_dir),
    "-type f",
    "-mtime +30",
    "-delete",
]);
exec($cmd, $output, $return);
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
$cmd = join(" ", [
    "/usr/bin/find",
    escapeshellarg($trash_dir),
    "-type d",
    "-empty",
    "-delete",
]);
exec($cmd, $output, $return);
if ($return != 0) {
    echo "An error occurred while cleaning up files.\n";
    echo "Return value: $return\n";
    echo "Command output:\n";
    foreach ($output as $line) {
        echo "    $line\n";
    }
}
