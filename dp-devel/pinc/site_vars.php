<?PHP
// Variables (constants?) whose values are specific
// to the local installation of the DP code.

$site_dir = '<<SITE_DIR>>';
// This is the location in the local file system where the code was
// installed (i.e., the directory that corresponds to 'dp-devel' in the CVS
// repository -- it should contain directories such as 'pinc' and 'tools').

$siteurl='';
// This is the HTTP URL that resolves to the directory described above.

$projects_dir = "$site_dir/projects";  // '<<PROJECTS_DIR>>';
$projects_url = "$siteurl/projects";   // '<<PROJECTS_URL>>';

$forums_url = "$siteurl/phpBB2"; // <<FORUMS_URL>>
$reset_password_url = "$forums_url/profile.php?mode=sendpassword";

// -----------------------------------------------------------------------------

$no_reply_email_addr = 'no-reply@texts01.archive.org'; // <<NO_REPLY_EMAIL_ADDR>>

$general_help_email_addr = 'dphelp@texts01.archive.org'; // <<GENERAL_HELP_EMAIL_ADDR>>
$site_manager_email_addr = $general_help_email_addr;
$auto_email_addr = $general_help_email_addr;

// -----------------------------------------------------------------------------

?>
