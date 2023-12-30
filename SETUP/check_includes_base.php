#!/usr/bin/env php
<?php
/*
 * All .php files should include pinc/base.inc to ensure common files
 * are included and variables are defined.
 */

$relPath = "../pinc/";
include_once($relPath."misc.inc");

// List of files that don't need to contain base.inc
$ok_files = [
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

$files = get_all_php_files("../");
foreach ($files as $file) {
    // If it's in the SETUP directory, skip it
    if (startswith($file, "SETUP/")) {
        continue;
    }

    // If it's in the vendor directory, skip it
    if (startswith($file, "vendor/")) {
        continue;
    }

    // If it's in the node_modules directory, skip it
    if (startswith($file, "node_modules/")) {
        continue;
    }

    // If it's a 3rdparty file, skip it
    if (startswith($file, "pinc/3rdparty/mediawiki/")) {
        continue;
    }

    echo "$file\n";

    // If it requires authentication, skip it
    if (file_includes_base("../$file")) {
        continue;
    }

    // If it's on the OK list, skip it
    if (in_array($file, $ok_files)) {
        continue;
    }

    echo "ERROR: file does not include base.inc or api.inc\n";
    exit(1);
}

function get_all_php_files($basedir)
{
    $php_files = [];

    $dir_iter = new RecursiveDirectoryIterator($basedir);
    $files = new RecursiveIteratorIterator($dir_iter);
    foreach ($files as $file_info) {
        $file = $file_info->getPathname();
        if (!endswith($file, ".php")) {
            continue;
        }
        $php_files[] = str_replace($basedir, "", $file);
    }
    return $php_files;
}

function file_includes_base($filename)
{
    $contents = file_get_contents($filename);

    return preg_match("/^\w+_once\(.*['\"]base.inc['\"]\);$/m", $contents);
}
