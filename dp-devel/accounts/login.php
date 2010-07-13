<?php
$relPath="./../pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'username.inc');
include_once($relPath.'connect.inc');
$db_Connection=new dbConnect();
include_once($relPath.'dpsession.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'forum_interface.inc');

function abort_login( $error )
{
    global $reset_password_url;
    global $site_manager_email_addr;
    global $testing;

    $title = _("Login Failed");
    theme($title, "header");

    echo "<br>\n";
    echo "<b>$error</b>\n";

    echo "<p>" . _("Please attempt again to log in above. If problems persist, review the following possible fixes:") . "</p>";
    echo "<ol>";
    if ($testing)
    {
    	echo "<li>" . _("Register! (Note that this is a test site, and has a separate database from the production site, so you need to register separately.)") . "</li>\n";
    }
    echo "<li>" . _("Type your username in the exact same way as when you registered.") . "</li>\n";
    echo "<li>" . sprintf( _("<a href='%s'>Reset</a> your password."), $reset_password_url) . "</li>\n";
    echo "<li>" . _("Enable Javascript.") . "</li>\n";
    echo "<li>" . sprintf(_("Accept cookies (at least from us at %s)."), $_SERVER["HTTP_HOST"]) . "</li>\n";
    echo "<li>" . sprintf(_("Allow popup windows (at least from us at %s)."), $_SERVER["HTTP_HOST"]) . "</li>\n";
    echo "<li>" . _("Caching set to off (or: refresh page every visit).") . "</li>\n";
    echo "<li>" . _("Ensure your PC clock is set to the correct date &amp; time.") . "</li>\n";
    echo "</ol>";
    echo "<p>" . sprintf( _("If all of this fails, contact the <a href='%s'>site manager</a>."), "mailto:$site_manager_email_addr") . "</p>";
    echo "<p>" . sprintf( _("Note: If you have just registered, you will need to wait for the welcome mail to arrive to your mailbox. Once it does, please click the activation link to complete the registration (this is to prevent others from signing you up to the site without your knowledge). If you have waited for an hour or so and have still not received any mail from us (please check any spam filters!), it is likely that you misentered your email-address. Please contact the <a href='%s'>site manager</a> to solve the problem."), "mailto:$site_manager_email_addr") . "</p>";

    theme("", "footer");
    exit();
}

// -----------------------------------------------------------------------------

$userNM = $_POST['userNM'];
$userPW = $_POST['userPW'];
$destination = ( isset($_REQUEST['destination']) ? $_REQUEST['destination'] : '' );

$err = check_username($userNM);
if ($err != '')
{
     abort_login($err);
}


if ($userPW == '')
{
    $error = _("You did not supply a password.");
    abort_login($error);
}

// Confirm a valid username and password
if (!is_username_password_valid($userNM, $userPW))
{
   abort_login(_("Username or password is incorrect."));
}

// Look for user in 'users' table.
$q = "SELECT * FROM users WHERE username='$userNM'";
$u_res = mysql_query($q) or die(mysql_error());
if (mysql_num_rows($u_res)==0)
{
    $error = sprintf(_("You are registered with the forum software, but not with %s."), $site_abbreviation);
    abort_login($error);
}

// -------------------------------------
// The login is successful!

$u_row = mysql_fetch_assoc($u_res);

// Note that phpbb_users.username and users.username are non-BINARY varchar,
// so the SQL comparison "username='$userNM'" is evaluated case-insensitively.
// This means that the user may have just logged in by typing a username
// that's case-different from the username that they registered with.
// That is, $userNM may be case-different from $u_row['username'].
// (E.g., I registered as 'jmdyck', but I can login as 'JMDyck'.)
//
// However, some places in the PHP code do case-sensitive comparisons of
// usernames (e.g., the code that determines whether the user is entitled
// to pull a particular page out of DONE or IN-PROGRESS). For those places,
// it's important that we always use the same form of the username. Following
// the principle of least surprise, we use the form used at registration time,
// i.e. the form stored in the users table.
//
$userNM = $u_row['username'];

// Start the DP session.
dpsession_begin( $userNM );

// Log into phpBB2
if (is_dir($forums_dir))
{
    $user_id = get_forum_user_id($userNM);
    define('IN_PHPBB', true);
    $phpbb_root_path = $forums_dir."/";
    include($phpbb_root_path.'extension.inc');
    include($phpbb_root_path.'common.php');
    include($phpbb_root_path.'config.php');
    session_begin($user_id, $user_ip, PAGE_INDEX, false, 1);
}

// It's possible that a user might be redirected back to this page after
// a successful login in this scenario:
//   1. User logs in with an incorrect password and ends up on login.php.
//   2. While on login.php user logs in with a correct password and is
//      redirected back to login.php as that was the page they were on
//      prior to login.
//   3. User is presented with an error message since $_POST has no login
//      information.
// To avoid this case we ignore $destination if it points to login.php.
// send them to the correct page
if (!empty($destination) && $destination != $_SERVER["REQUEST_URI"])
{
    // They were heading to $destination (via a bookmark, say)
    // when we sidetracked them into the login pages.
    // Make sure they get to where they were going.
    $url = $destination;
}
else
{
    $url = "$code_url/activity_hub.php";
}
$title = _("Sign In");
metarefresh(0,$url,$title,"");

// vim: sw=4 ts=4 expandtab
?>
