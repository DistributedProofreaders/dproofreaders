#!/usr/bin/env php
<?php
$relPath = "../../pinc/";
include_once($relPath."misc.inc");

$basedir = $argv[1] ?? "../../";

// List of accepted unauthenticated files
$ok_files = [
    // Base website
    "default.php",
    "credits.php",
    "sitemap.php",
    "project.php",
    "list_etexts.php",
    "locale/debug_ui_language.php",
    // RSS feeds
    "feeds/backend.php",
    // Account login / registration pages
    "accounts/addproofer.php",
    "accounts/login_failure.php",
    "accounts/activate.php",
    "accounts/logout.php",
    "accounts/require_login.php",
    "accounts/login.php",
    // API
    "api/index.php",
    // FAQs
    "faq/doc-copy.php",
    "faq/font_sample.php",
    "faq/privacy.php",
    "faq/prooffacehelp.php",
    "faq/site_progress_snapshot_legend.php",
    "faq/translate.php",
    // .inc pages hiding as .php pages
    "pinc/site_vars.php",
    "pinc/udb_user.php",
    "pinc/site_registration_protection.php",
    // 3rd party libraries
    "pinc/3rdparty/mediawiki/DiffEngine.php",
    "pinc/3rdparty/mediawiki/TableDiffFormatter.php",
    "pinc/3rdparty/mediawiki/DairikiDiff.php",
    "pinc/3rdparty/mediawiki/ComplexityException.php",
    "pinc/3rdparty/mediawiki/WordAccumulator.php",
    "pinc/3rdparty/mediawiki/WordLevelDiff.php",
    "pinc/3rdparty/mediawiki/DiffFormatter.php",
    // Simple redirect
    "quiz/index.php",
    // Stats graphs that we make globally available
    "stats/default.php",
    "stats/percent_users_who_proof.php",
    "stats/pp_stage_goal.php",
    "stats/round_backlog_days.php",
    "stats/round_backlog.php",
    "stats/equilibria.php",
    // Design documentation
    "styles/style_demo.php",
    "styles/design_philosophy.php",
    // Tools
    "tools/setlangcookie.php",  // allows unauth users to set their language
    "tools/proofers/ctrl_frame.php",  // required for unauth quiz access
    // Dev tools
    ".php-cs-fixer.dist.php",
];

echo "Checking PHP files for proper login requirements...\n";

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

    echo ".";
    flush();

    // If it requires authentication, skip it
    if (file_requires_auth("../../$file")) {
        continue;
    }

    // If it's on the OK list, skip it
    if (in_array($file, $ok_files)) {
        continue;
    }

    echo "\n$file\n";
    echo "    ERROR: file does not require authentication and is not on the known-good list\n";
    exit(1);
}

echo "\nNo login requirement issues found.\n";

function get_all_php_files($basedir)
{
    $php_files = [];

    $dir_iter = new RecursiveDirectoryIterator($basedir);
    $files = new RecursiveIteratorIterator($dir_iter);
    foreach ($files as $file_info) {
        $file = $file_info->getPathname();
        if (!str_ends_with($file, ".php")) {
            continue;
        }
        $php_files[] = str_replace($basedir, "", $file);
    }
    return $php_files;
}

function file_requires_auth($filename)
{
    $contents = file_get_contents($filename);

    // "regular" pages use require_login();
    $login = preg_match("/^require_login\(\);$/m", $contents);

    // crontab pages use require_localhost_request();
    $localhost = preg_match("/^require_localhost_request\(/m", $contents);

    return $login || $localhost;
}
