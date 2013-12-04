<?php
// Variables (constants?) whose values are specific
// to the local installation of the DP code.

// During site configuration, identifiers delimited by double angle-brackets
// are replaced by the corresponding values in SETUP/configuration.sh.
// See that file for specific information on these and other settings.


$code_dir = '<<CODE_DIR>>';
$code_url='<<CODE_URL>>';

$projects_dir = '<<PROJECTS_DIR>>';
$projects_url = '<<PROJECTS_URL>>';

$dyn_dir = '<<DYN_DIR>>';
$dyn_url = '<<DYN_URL>>';

$dyn_locales_dir = "$dyn_dir/locale";

$xmlfeeds_dir = "$dyn_dir/xmlfeeds";

$jpgraph_dir = '<<JPGRAPH_DIR>>';

$blog_url = '<<BLOG_URL>>';

$wiki_url = '<<WIKI_URL>>';

$wikihiero_dir = '<<WIKIHIERO_DIR>>';
$wikihiero_url = '<<WIKIHIERO_URL>>';

$archive_projects_dir = '<<ARCHIVE_PROJECTS_DIR>>';

$forums_dir = '<<FORUMS_DIR>>';
$forums_url = '<<FORUMS_URL>>';
$reset_password_url        = "$forums_url/profile.php?mode=sendpassword";


$general_forum_idx                = '<<FORUMS_GENERAL_IDX>>';
$beginners_site_forum_idx         = '<<FORUMS_BEGIN_SITE_IDX>>';
$beginners_proofing_forum_idx     = '<<FORUMS_BEGIN_PROOF_IDX>>';
$waiting_projects_forum_idx       = '<<FORUMS_PROJECT_WAITING_IDX>>';
$projects_forum_idx               = '<<FORUMS_PROJECT_AVAIL_IDX>>';
$pp_projects_forum_idx            = '<<FORUMS_PROJECT_PP_IDX>>';
$posted_projects_forum_idx        = '<<FORUMS_PROJECT_POSTED_IDX>>';
$content_providing_forum_idx      = '<<FORUMS_CONTENT_PROVIDERS_IDX>>';
$post_processing_forum_idx        = '<<FORUMS_POST_PROCESSORS_IDX>>';
$teams_forum_idx                  = '<<FORUMS_TEAMS_IDX>>';


$general_forum_url                = "$forums_url/viewforum.php?f=$general_forum_idx";
$waiting_projects_forum_url       = "$forums_url/viewforum.php?f=$waiting_projects_forum_idx";
$projects_forum_url               = "$forums_url/viewforum.php?f=$projects_forum_idx";
$pp_projects_forum_url            = "$forums_url/viewforum.php?f=$pp_projects_forum_idx";
$posted_projects_forum_url        = "$forums_url/viewforum.php?f=$posted_projects_forum_idx";
$post_processing_forum_url        = "$forums_url/viewforum.php?f=$post_processing_forum_idx";
$content_providing_forum_url   	  = "$forums_url/viewforum.php?f=$content_providing_forum_idx";
$beginners_site_forum_url 	  = "$forums_url/viewforum.php?f=$beginners_site_forum_idx";
$beginners_proofing_forum_url     = "$forums_url/viewforum.php?f=$beginners_proofing_forum_idx";
$teams_forum_url                  = "$forums_url/viewforum.php?f=$teams_forum_idx";

// -----------------------------------------------------------------------------

$site_name = '<<SITE_NAME>>';
$site_abbreviation = '<<SITE_ABBREVIATION>>';
$site_signoff = "<<SITE_SIGNOFF>>";
$site_url = '<<SITE_URL>>';

$preceding_proofer_restriction = '<<PRECEDING_PROOFER_RESTRICTION>>';
$public_page_details = <<PUBLIC_PAGE_DETAILS>>;
// -----------------------------------------------------------------------------

$uploads_dir = '<<UPLOADS_DIR>>';
$uploads_host = '<<UPLOADS_HOST>>';
$uploads_account = '<<UPLOADS_ACCOUNT>>';
$uploads_password = '<<UPLOADS_PASSWORD>>';

// -----------------------------------------------------------------------------

$aspell_executable = '<<ASPELL_EXECUTABLE>>';
$aspell_prefix = "<<ASPELL_PREFIX>>";
$aspell_temp_dir = '<<ASPELL_TEMP_DIR>>';

$xgettext_executable = '<<XGETTEXT_EXECUTABLE>>';
$system_locales_dir = '<<GETTEXT_LOCALES_DIR>>';

$php_cli_executable = '<<PHP_CLI_EXECUTABLE>>';

// -----------------------------------------------------------------------------

$no_reply_email_addr = '<<NO_REPLY_EMAIL_ADDR>>';
$general_help_email_addr = '<<GENERAL_HELP_EMAIL_ADDR>>';
$site_manager_email_addr = $general_help_email_addr;
$auto_email_addr = $general_help_email_addr;
$db_requests_email_addr = '<<DB_REQUESTS_EMAIL_ADDR>>';
$promotion_requests_email_addr = '<<PROMOTION_REQUESTS_EMAIL_ADDR>>';
$ppv_reporting_email_addr = '<<PPV_REPORTING_EMAIL_ADDR>>';
$image_sources_manager_addr = '<<IMAGE_SOURCES_EMAIL_ADDR>>';

// -----------------------------------------------------------------------------

$testing = <<TESTING>>;
$use_php_sessions = <<USE_PHP_SESSIONS>>;
$cookie_encryption_key = '<<COOKIE_ENCRYPTION_KEY>>';
$maintenance = <<MAINTENANCE>>;
$site_supports_metadata = <<METADATA>>;
$site_supports_corrections_after_posting = <<CORRECTIONS>>;
$auto_post_to_project_topic = <<AUTO_POST_TO_PROJECT_TOPIC>>;
$ordinary_users_can_see_queue_settings = <<ORDINARY_USERS_CAN_SEE_QUEUE_SETTINGS>>;
$external_catalog_locator = '<<EXTERNAL_CATALOG_LOCATOR>>';
$charset = '<<CHARSET>>';
$utf8_site = (strcasecmp($charset,"UTF-8") == 0);

$jpgraph_FF='<<JPGRAPH_FONT_FACE>>';
$jpgraph_FS='<<JPGRAPH_FONT_STYLE>>';

$writeBIGtable = <<WRITEBIGTABLE>>;
$readBIGtable = <<READBIGTABLE>>;

// -----------------------------------------------------------------------------

// Placeholders for gettext functions.

// These functions should be moved into gettext_setup.inc as soon 
// as issues about the order of include files are solved, so as to
// guarantee that gettext_setup.inc is included before any call
// to _() or other gettext functions.

// If the gettext extension is compiled into PHP, then the function named '_'
// (an alias for 'gettext') will be defined.
// If it's not defined (e.g., on dproofreaders.sourceforge.net),
// define it to simply return its argument.
if (! function_exists('_') )
{
    function _($str) { return $str; }
}

if (! function_exists('ngettext') )
{
    function ngettext($singular, $plural, $number)
    {
        return ($number == 1) ? $singular : $plural;
    }
}

// -----------------------------------------------------------------------------

// Make sure magic_quotes_gpc is turned on, we rely heavily on it.
if (get_magic_quotes_gpc() == 0) {
    die("Error: magic_quotes_gpc is not turned on in PHP configuration.");
}
?>
