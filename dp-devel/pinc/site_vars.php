<?PHP
// Variables (constants?) whose values are specific
// to the local installation of the DP code.

$site_dir = '<<SITE_DIR>>';
// This is the location in the local file system where the code was
// installed (i.e., the directory that corresponds to 'dp-devel' in the CVS
// repository -- it should contain directories such as 'pinc' and 'tools').

$siteurl=''; // <<SITE_URL>>
// This is the HTTP URL that resolves to the directory described above.

$projects_dir = '<<PROJECTS_DIR>>';
$projects_url = '<<PROJECTS_URL>>';

$forums_url = '<<FORUMS_URL>>';
$reset_password_url = "$forums_url/profile.php?mode=sendpassword";

$uploads_dir = '<<UPLOADS_DIR>>';

// -----------------------------------------------------------------------------

$no_reply_email_addr = '<<NO_REPLY_EMAIL_ADDR>>';

$general_help_email_addr = '<<GENERAL_HELP_EMAIL_ADDR>>';
$site_manager_email_addr = $general_help_email_addr;
$auto_email_addr = $general_help_email_addr;

// -----------------------------------------------------------------------------

$testing = TRUE;
// So far, the only effect of setting $testing to TRUE is to prevent email
// messages from being sent. Instead, the site shows a copy of the message
// that would have been sent. See pinc/maybe_mail.inc.

?>
