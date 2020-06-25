#!/usr/bin/env php
<?php
$relPath="../pinc/";
include_once($relPath."misc.inc");

# List of accepted unauthenticated files
$ok_files = [
    # Base website
    "default.php",
    "credits.php",
    "sitemap.php",
    "project.php",
    "list_etexts.php",
    "locale/debug_ui_language.php",
    # RSS and XML feeds
    "feeds/backend.php",
    # Account login / registration pages
    "accounts/addproofer.php",
    "accounts/login_failure.php",
    "accounts/activate.php",
    "accounts/logout.php",
    "accounts/require_login.php",
    "accounts/login.php",
    # FAQs
    "faq/cp.php",
    "faq/default.php",
    "faq/dev_process.php",
    "faq/doc-copy.php",
    "faq/dochist.php",
    "faq/dpf.php",
    "faq/DPflow.php",
    "faq/faq_central.php",
    "faq/feeds_sdk/index.php",
    "faq/font_sample.php",
    "faq/formatting_guidelines.php",
    "faq/longabsent.php",
    "faq/pm-faq.php",
    "faq/post_proof.php",
    "faq/ppv.php",
    "faq/privacy.php",
    "faq/ProoferFAQ.php",
    "faq/prooffacehelp.php",
    "faq/proofreading_guidelines.php",
    "faq/scanning.php",
    "faq/simple_proof_rules.php",
    "faq/site_progress_snapshot_legend.php",
    "faq/templates_guide.php",
    "faq/translate.php",
    "faq/wordcheck-faq.php",
    "faq/de/formatting_guidelines.php",
    "faq/de/proofreading_guidelines.php",
    "faq/es/proofreading_guidelines.php",
    "faq/fr/formatting_guidelines.php",
    "faq/fr/proofreading_guidelines.php",
    "faq/it/formatting_guidelines.php",
    "faq/it/proofreading_guidelines.php",
    "faq/nl/formatting_guidelines.php",
    "faq/nl/proofreading_guidelines.php",
    "faq/pt/formatting_guidelines.php",
    "faq/pt/proofreading_guidelines.php",
    # .inc pages hiding as .php pages
    "pinc/site_vars.php",
    "pinc/udb_user.php",
    # 3rd party libraries
    "pinc/functions_insert_post.php",
    "pinc/3rdparty/mediawiki/DiffEngine.php",
    "pinc/3rdparty/mediawiki/TableDiffFormatter.php",
    "pinc/3rdparty/mediawiki/DairikiDiff.php",
    "pinc/3rdparty/mediawiki/ComplexityException.php",
    "pinc/3rdparty/mediawiki/WordAccumulator.php",
    "pinc/3rdparty/mediawiki/WordLevelDiff.php",
    "pinc/3rdparty/mediawiki/DiffFormatter.php",
    "pinc/3rdparty/portable-utf8/portable-utf8.php",
    # Quizzes we want externally available
    "quiz/index.php",
    "quiz/start.php",
    "quiz/generic/proof.php",
    "quiz/generic/orig.php",
    "quiz/generic/hints.php",
    "quiz/generic/returnfeed.php",
    "quiz/generic/main.php",
    "quiz/generic/right.php",
    "quiz/tuts/tut_p_mod2_3.php",
    "quiz/tuts/tut_p_mod2_1.php",
    "quiz/tuts/tut_p_mod1_4.php",
    "quiz/tuts/tut_p_greek_2.php",
    "quiz/tuts/tut_p_mod1_5.php",
    "quiz/tuts/tut_p_mod1_3.php",
    "quiz/tuts/tut_p_basic_1.php",
    "quiz/tuts/tut_p_basic_3.php",
    "quiz/tuts/tut_p_basic_5.php",
    "quiz/tuts/tut_p_old_3.php",
    "quiz/tuts/tut_p_basic_4.php",
    "quiz/tuts/tut_p_old_2.php",
    "quiz/tuts/tut_p_aeoe_2.php",
    "quiz/tuts/tut_p_mod2_2.php",
    "quiz/tuts/tut_p_mod1_2.php",
    "quiz/tuts/tut_p_thorn.php",
    "quiz/tuts/tut_p_aeoe_1.php",
    "quiz/tuts/tut_p_greek_5.php",
    "quiz/tuts/tut_p_greek_1.php",
    "quiz/tuts/tut_p_mod2_4.php",
    "quiz/tuts/tut_p_mod1_1.php",
    "quiz/tuts/tut_p_basic_2.php",
    "quiz/tuts/tut_p_old_1.php",
    "quiz/tuts/tut_p_greek_4.php",
    "quiz/tuts/tut_p_mod2_5.php",
    "quiz/tuts/tut_p_fraktur.php",
    "quiz/tuts/tut_p_greek_3.php",
    # Stats graphs that we make globally available
    "stats/default.php",
    "stats/jpgraph_files/percent_users_who_proof.php",
    "stats/jpgraph_files/pages_daily.php",
    "stats/jpgraph_files/cumulative_total_proj_summary_graph.php",
    "stats/jpgraph_files/users_by_pages_proofed_graph.php",
    "stats/jpgraph_files/users_by_country.php",
    "stats/jpgraph_files/round_backlog_trend.php",
    "stats/jpgraph_files/round_backlog_days.php",
    "stats/jpgraph_files/round_backlog.php",
    "stats/jpgraph_files/cumulative_total_proj_graph.php",
    "stats/jpgraph_files/users_logging_on.php",
    "stats/jpgraph_files/users_by_language.php",
    "stats/jpgraph_files/total_proj_graph.php",
    "stats/jpgraph_files/average_hour_users_logging_on.php",
    "stats/jpgraph_files/total_pages_by_month_graph.php",
    "stats/jpgraph_files/new_users.php",
    "stats/jpgraph_files/cumulative_month_proj.php",
    "stats/jpgraph_files/tallyboard_deltas.php",
    "stats/jpgraph_files/curr_month_proj.php",
    "stats/jpgraph_files/equilibria.php",
    # Design documentation
    "styles/style_demo.php",
    "styles/design_philosophy.php",
    # Tools
    "tools/setlangcookie.php",  # allows unauth users to set their language
    "tools/post_proofers/smooth_reading.php",
    "tools/project_manager/automodify.php",  # requires localhost or login
    "tools/proofers/ctrl_frame.php",  # required for unauth quiz access
];

$files = get_all_php_files("../");
foreach($files as $file)
{
    echo "$file\n";

    # If it requires authentication, skip it
    if(file_requires_auth("../$file"))
        continue;

    # If it's in the SETUP directory, skip it
    if(startswith($file, "SETUP/"))
        continue;

    # If it's on the OK list, skip it
    if(in_array($file, $ok_files))
        continue;

    echo "ERROR: file does not require authentication and is not on the known-good list\n";
    exit(1);
}

function get_all_php_files($basedir)
{
    $php_files = [];

    $dir_iter = new RecursiveDirectoryIterator($basedir);
    $files = new RecursiveIteratorIterator($dir_iter);
    foreach($files as $file_info)
    {
        $file = $file_info->getPathname();
        if(!endswith($file, ".php"))
            continue;
        $php_files[] = str_replace($basedir, "", $file);
    }
    return $php_files;
}

function file_requires_auth($filename)
{
    $contents = file_get_contents($filename);

    # "regular" pages use require_login();
    $login = preg_match("/^require_login\(\);$/m", $contents);

    # crontab pages use require_localhost_request();
    $localhost = preg_match("/^require_localhost_request\(/m", $contents);

    return $login || $localhost;
}
