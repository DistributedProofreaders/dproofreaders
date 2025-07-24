#!/usr/bin/env php
<?php
if (php_sapi_name() == "cli") {
    $basedir = $argv[1] ?? "";
    $relPath = "$basedir/pinc/";
} else {
    throw new RuntimeException("Script is meant to be run via CLI");
}

if (! is_file("$relPath/base.inc")) {
    throw new RuntimeException("First argument '$relPath' does not point to `pinc/` directory");
}

include_once($relPath."base.inc");
include_once($relPath."html_page_common.inc");

$manifest = get_js_manifest($basedir);
if (!$manifest) {
    echo "ERROR: No manifest file found, does dist/manifest.json exist?\n";
    exit(1);
}

echo "Generating $basedir/dist/manifest.php...\n";
generate_cached_js_manifest($basedir, $manifest);
