<?php
$relPath = "./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');

require_localhost_request();

// remove temporary spellcheck files older than 1 day
unset($output);
$cmd = join(" ", [
    "/usr/bin/find",
    escapeshellarg($aspell_temp_dir),
    "-type f",
    "-mtime +1",
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
