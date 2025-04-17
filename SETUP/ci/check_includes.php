#!/usr/bin/env php
<?php
// Check all .php and .inc files for include best-practices.

$relPath = "../../pinc/";
include_once($relPath."misc.inc");

$basedir = $argv[1] ?? "../../";

// List of files that don't need to contain base.inc
$ok_not_includes_base = [
    // Settings files
    "pinc/site_vars.php",
    "pinc/udb_user.php",
    // Simple redirects
    "stats/default.php",
    "faq/default.php",
    // API loads bootstrap directly
    "api/index.php",
    // Privacy is a dual-mode include page and the UI version has base.inc
    "faq/privacy.php",
    // Dev tools
    ".php-cs-fixer.dist.php",
];

// List of files that are ok to contain site_vars.php
$ok_includes_site_vars = [
    "pinc/bootstrap.inc",
];

// List of files that are ok to contain misc.inc
$ok_includes_misc = [
    "pinc/bootstrap.inc",
];

// List of files considered "site structure" includes.
$site_structure_includes = [
    "Activity.inc",
    "Stage.inc",
    "Round.inc",
    "Pool.inc",
    "ProjectState.inc",
    "stages.inc",
    "quizzes.inc",
    "site_structure.inc",
];

// List of files that are ok to contain site structure includes
$ok_includes_site_structure = [
    "pinc/base.inc",
    "pinc/Quiz.inc",
    "pinc/Round.inc",
    "pinc/Stage.inc",
    "pinc/Pool.inc",
    "pinc/ProjectState.inc",
    "pinc/quizzes.inc",
    "pinc/stages.inc",
    "pinc/site_structure.inc",
    "api/index.php",
];

echo "Checking files for include() best practices...\n";

$basedir .= str_ends_with($basedir, "/") ? "" : "/";
$files = get_all_php_files($basedir);
foreach ($files as $file) {
    // If it's in the SETUP directory, skip it
    if (str_starts_with($file, "SETUP/")) {
        continue;
    }

    // If it's in the vendor directory, skip it
    if (str_starts_with($file, "vendor/")) {
        continue;
    }

    // If it's in the phpstan cache directory, skip it
    if (str_starts_with($file, ".phpstan.cache/")) {
        continue;
    }

    // If it's in the node_modules directory, skip it
    if (str_starts_with($file, "node_modules/")) {
        continue;
    }

    // If it's a 3rdparty file, skip it
    if (str_starts_with($file, "pinc/3rdparty/mediawiki/")) {
        continue;
    }

    echo ".";
    flush();

    // All .php files should include base.inc, but no .inc file should
    if (file_includes_base("$basedir/$file")) {
        if (str_ends_with($file, ".inc")) {
            abort($file, ".inc files should not include base.inc");
        }
    } elseif (in_array($file, $ok_not_includes_base)) {
        // it's in our exception list
    } elseif (str_ends_with($file, ".php")) {
        abort($file, "file does not include base.inc");
    }

    // No file should include site_vars.php
    if (file_includes_site_vars("$basedir/$file") && !in_array($file, $ok_includes_site_vars)) {
        abort($file, "no file should include site_vars.php");
    }

    // No file should include misc.inc
    if (file_includes_misc("$basedir/$file") && !in_array($file, $ok_includes_misc)) {
        abort($file, "no file should include misc.inc");
    }

    // No file should include site structure code
    if (file_includes_site_structure("$basedir/$file") && !in_array($file, $ok_includes_site_structure)) {
        abort($file, "no file should include site structure code (" . join(", ", $site_structure_includes) . ")");
    }
}

echo "\nAll files follow include() best practices.\n";

function get_all_php_files($basedir)
{
    $php_files = [];

    $dir_iter = new RecursiveDirectoryIterator($basedir);
    $files = new RecursiveIteratorIterator($dir_iter);
    foreach ($files as $file_info) {
        $file = $file_info->getPathname();
        if (!str_ends_with($file, ".php") && !str_ends_with($file, ".inc")) {
            continue;
        }
        $php_files[] = str_replace($basedir, "", $file);
    }
    return $php_files;
}

function file_includes_base($filename)
{
    $contents = file_get_contents($filename);

    return preg_match("/[?include|require].*\(.*['\"]base.inc['\"]\);$/m", $contents);
}

function file_includes_site_vars($filename)
{
    $contents = file_get_contents($filename);

    return preg_match("/[?include|require].*\(.*['\"]site_vars.php['\"]\);$/m", $contents);
}

function file_includes_misc($filename)
{
    $contents = file_get_contents($filename);

    return preg_match("/^\w+_once\(.*['\"]misc.inc['\"]\);$/m", $contents);
}

function file_includes_site_structure($filename)
{
    global $site_structure_includes;

    $contents = file_get_contents($filename);

    foreach ($site_structure_includes as $include) {
        if (preg_match("/^\w+_once\(.*['\"]{$include}['\"]\);$/m", $contents)) {
            return true;
        }
    }
    return false;
}

function abort(string $file, string $message)
{
    echo "\n$file\n";
    echo "    ERROR: $message\n";
    exit(1);
}
