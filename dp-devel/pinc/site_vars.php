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

$dynstats_dir = '<<DYNSTATS_DIR>>';
$dynstats_url = '<<DYNSTATS_URL>>';

$xmlfeeds_dir = '<<XMLFEEDS_DIR>>';
$xmlfeeds_url = '<<XMLFEEDS_URL>>';

$forums_url = '<<FORUMS_URL>>';
$reset_password_url        = "$forums_url/profile.php?mode=sendpassword";
$general_forum_url         = "$forums_url/viewforum.php?f=1";
$projects_forum_url        = "$forums_url/viewforum.php?f=2";
$post_processing_forum_url = "$forums_url/viewforum.php?f=3";

$uploads_dir = '<<UPLOADS_DIR>>';

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

$pagesneeded = 20000; //Total pages available for proofing at one time
$noneng_pagesneeded = 8000; //Total pages of Non English pages out of $pagesneeded
$eng_pagesneeded = 10000; //Total pages of English pages out of $pagesneeded
$beginners_projects = 1; //Total number of beginners projects available in first round at one time
$easy_projects = 5; //Total number of easy projects available in first round at one time

// -----------------------------------------------------------------------------

$testing = <<TESTING>>;
// So far, the effects of setting $testing to TRUE are:
// (1) It prevents email messages from being sent. Instead, the site shows a
//     copy of the message that would have been sent. See pinc/maybe_mail.inc.
// (2) metarefresh delays by 15 seconds.
?>
