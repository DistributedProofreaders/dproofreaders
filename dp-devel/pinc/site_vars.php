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

$forums_dir = '<<FORUMS_DIR>>';
$forums_url = '<<FORUMS_URL>>';
$reset_password_url        = "$forums_url/profile.php?mode=sendpassword";
$general_forum_url         = "$forums_url/viewforum.php?f=1";
$projects_forum_url        = "$forums_url/viewforum.php?f=2";
$post_processing_forum_url = "$forums_url/viewforum.php?f=3";
$content_providing_forum_url 	= "$forums_url/viewforum.php?f=9";
$beginners_site_forum_url 	= "$forums_url/viewforum.php?f=18";
$beginners_proofing_forum_url 	= "$forums_url/viewforum.php?f=19";


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

// $metadata is a flag to allow the still developing metadata functionality, links, etc
// to be active or not

$metadata = <<METADATA>>;

// -----------------------------------------------------------------------------

// $charset will hopefully one day apply to all relevant pages on site and can be
// changed at this one central place

$charset = '<<CHARSET>>';

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
