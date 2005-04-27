<?PHP
// Variables (constants?) whose values are specific
// to the local installation of the DP code.

$code_dir = '<<CODE_DIR>>';
// This is the location in the local file system where the code was
// installed (i.e., the directory that corresponds to 'dp-devel' in the CVS
// repository -- it should contain directories such as 'pinc' and 'tools').

$code_url='<<CODE_URL>>';
// This is the HTTP URL that resolves to the directory described above.

$projects_dir = '<<PROJECTS_DIR>>';
$projects_url = '<<PROJECTS_URL>>';

$dyn_dir = '<<DYN_DIR>>';
$dyn_url = '<<DYN_URL>>';

$dynstats_dir = "$dyn_dir/stats";
$dynstats_url = "$dyn_url/stats";

$dyn_locales_dir = "$dyn_dir/locale";

$xmlfeeds_dir = "$dyn_dir/xmlfeeds";

$jpgraph_dir = '<<JPGRAPH_DIR>>';

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


$uploads_dir = '<<UPLOADS_DIR>>';
$uploads_host = '<<UPLOADS_HOST>>';
$uploads_account = '<<UPLOADS_ACCOUNT>>';
$uploads_password = '<<UPLOADS_PASSWORD>>';

// -----------------------------------------------------------------------------

// location of aspell executable
$aspell_executable = '<<ASPELL_EXECUTABLE>>';

// root of all aspell dir ./bin/ etc.
// (passed to aspell as --prefix=$aspell_prefix)
$aspell_prefix = "<<ASPELL_PREFIX>>";

// document root for temp files
// $aspell_temp_dir = "{$_SERVER['DOCUMENT_ROOT']}/~userdirectory~/spell/tmp";
// So far we have always located this under the system tmp
// in its own dir for easy purging.
$aspell_temp_dir = '<<ASPELL_TEMP_DIR>>';

// -----------------------------------------------------------------------------

// location of xgettext executable
$xgettext_executable = '<<XGETTEXT_EXECUTABLE>>';

// system's locale directory; this is NOT the same as $dyn_locales_dir above;
// for example, it usually is "/usr/share/locale/"
$system_locales_dir = '<<GETTEXT_LOCALES_DIR>>';

// -----------------------------------------------------------------------------

$no_reply_email_addr = '<<NO_REPLY_EMAIL_ADDR>>';

$general_help_email_addr = '<<GENERAL_HELP_EMAIL_ADDR>>';
$site_manager_email_addr = $general_help_email_addr;
$auto_email_addr = $general_help_email_addr;

// -----------------------------------------------------------------------------

// So far, the effects of setting $testing to TRUE are:
// (1) It prevents email messages from being sent. Instead, the site shows a
//     copy of the message that would have been sent. See pinc/maybe_mail.inc.
// (2) metarefresh delays by 15 seconds.

$testing = <<TESTING>>;

// -----------------------------------------------------------------------------

// If $use_php_sessions is true, PHP sessions are used to track user
// preferences, etc; if false, the original DP cookie system is used.

$use_php_sessions = <<USE_PHP_SESSIONS>>;

// You only need to define this if $use_php_sessions is FALSE.
$cookie_encryption_key = '<<COOKIE_ENCRYPTION_KEY>>';

// -----------------------------------------------------------------------------

// so far maintenance = TRUE prevents the front page from loading
// (displaying a 'back soon' message) for anyone but admins;
// but bookmarks to interior pages are still live for everyone

$maintenance = <<MAINTENANCE>>;

// -----------------------------------------------------------------------------

// $site_supports_metadata is a flag to allow the still developing metadata functionality, links, etc
// to be active or not

$site_supports_metadata = <<METADATA>>;

// Similarly for the corrections-after-posting facility.

$site_supports_corrections_after_posting = <<CORRECTIONS>>;

// -----------------------------------------------------------------------------

// whether the site should use Library of Congress's MARC catalogue when
// creating a project
$use_MARC = <<USE_MARC>>;

// -----------------------------------------------------------------------------

// $charset selects the charset used by the site, which is applied to all
// relevant pages on the site

$charset = '<<CHARSET>>';

// -----------------------------------------------------------------------------

// Font face and style values for JpGraph graphs. For possible values, see
// $jpgraph_dir/jpgraph.php , lines 233-269. Previous default values: 2 and 9002

$jpgraph_FF='<<JPGRAPH_FONT_FACE>>';
$jpgraph_FS='<<JPGRAPH_FONT_STYLE>>';

// -----------------------------------------------------------------------------

// for staged transition to all in one project_pages table

$writeBIGtable = <<WRITEBIGTABLE>>;
$readBIGtable = <<READBIGTABLE>>;

// -----------------------------------------------------------------------------


// If the gettext extension is compiled into PHP, then the function named '_'
// (an alias for 'gettext') will be defined.
// If it's not defined (e.g., on dproofreaders.sourceforge.net),
// define it to simply return its argument.
if (! function_exists('_') )
{
    function _($str) { return $str; }
}
?>
