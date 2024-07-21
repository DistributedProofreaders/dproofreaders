#!/usr/bin/env php
<?php
// Check all .php and .inc files for security best-practices.

$relPath = "../../pinc/";
include_once($relPath."misc.inc");

$basedir = $argv[1] ?? "../../";

// List of files that can contain system/exec calls
// TODO: Likely few or none of these should contain the calls either --
//       opting for Symfony Process() instead -- but this ensures that no
//       others are added from the current set until they get updated.
$ok_system_calls = [
    "crontab/CleanUploadsTrash.inc",
    "locale/translators/index.php",
    "pinc/archiving.inc",
    "pinc/forum_interface_phpbb3.inc",
    "pinc/languages.inc",
    "pinc/phpbb3.inc",
    "pinc/POFile.inc",
    "pinc/Project.inc",
    "pinc/project_quick_check.inc",
    "pinc/upload_file.inc",
    "tools/project_manager/add_files.php",
    "tools/project_manager/show_project_stealth_scannos.php",
    "tools/proofers/images_index.php",
];

// List of files that can contain mysqli_error() calls
$ok_mysqli_error_calls = [
    "pinc/DPDatabase.inc",
];

// Skip files that start with a specific prefix
$skip_file_prefixes = [
    "SETUP/",
    "vendor/",
    ".phpstan.cache/",
    "node_modules/",
];

$basedir .= endswith($basedir, "/") ? "" : "/";
$files = get_all_php_files($basedir);
foreach ($files as $file) {
    foreach ($skip_file_prefixes as $prefix) {
        if (startswith($file, $prefix)) {
            continue 2;
        }
    }

    echo "$file\n";

    // No file should include mysqli_error()
    if (file_includes_mysqli_error("$basedir/$file") && !in_array($file, $ok_mysqli_error_calls)) {
        abort("file includes mysqli_error()");
    }

    // No file should include a system call (use Symfony Process instead)
    if (file_includes_system_call("$basedir/$file") && !in_array($file, $ok_system_calls)) {
        abort("file includes system(), exec(), passthru(), shell_exec(), or escapeshellcmd()");
    }

}

function get_all_php_files(string $basedir): array
{
    $php_files = [];

    $dir_iter = new RecursiveDirectoryIterator($basedir);
    $files = new RecursiveIteratorIterator($dir_iter);
    foreach ($files as $file_info) {
        $file = $file_info->getPathname();
        if (!endswith($file, ".php") && !endswith($file, ".inc")) {
            continue;
        }
        $php_files[] = str_replace($basedir, "", $file);
    }
    sort($php_files);
    return $php_files;
}

function file_includes_mysqli_error(string $filename): bool
{
    // mysqli_error() can leak database information in the error message
    // so we don't want to use it except under specifically vetted uses.

    $contents = file_get_contents($filename);
    return preg_match("/mysqli_error\(/", $contents);
}

function file_includes_system_call(string $filename): bool
{
    // It's easy to get system calls wrong so prevent adding more, preferring
    // to use Symfony Process() instead. We don't really care about
    // escapeshellcmd() but it's a sign that there's probably some system/shell
    // magic happening that we want to catch.

    $contents = file_get_contents($filename);
    return preg_match("/\b(?:exec|system|passthru|shell_exec|escapeshellcmd)\(/", $contents, $matches);
}

/** @return never */
// TODO: Add never as a return value once we switch to PHP 8.1.
function abort(string $message)
{
    echo "    ERROR: $message\n";
    exit(1);
}
