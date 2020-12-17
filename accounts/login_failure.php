<?php
/*
A user is redirected to this page upon login failure from login.php.

By design, this file does not access any phpBB functions, delegating that to
login.php.

The authentication and user-presentation functions were split out to further
minimize possible code collisions between the DP code and the phpBB code.
*/

$relPath="./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'metarefresh.inc');

// If the user is already logged in, send them to the Activity Hub
if($user_is_logged_in)
{
    metarefresh(0, "$code_url/activity_hub.php");
}

// login.php loads this page with an error_code parameter to indicate what
// went wrong. Here we change this into a user-visible string.
$login_failures = array(
    'no_username'      => _("You did not supply a username."),
    'no_password'      => _("You did not supply a password."),
    'auth_failure'     => _("Unable to authenticate. The username/password may be incorrect or your account may be locked."),
    'unknown_failure'  => sprintf(_("An unexpected failure occurred, please contact a <a href='%s'>site manager</a>."), "mailto:$site_manager_email_addr"),
    'too_many_attempts'=> sprintf(_("You exceeded the maxiumum number of failed logins. Go <a href='%s'>log into the forums</a> and answer the CAPTCHA. Once you have successfully logged in there, return here and try again."), "$forums_url/ucp.php?mode=login"),
    'reg_mismatch'     => sprintf(_("You are registered with the forum software, but not with %s."), $site_abbreviation),
    'form_timeout'     => _("Form submission timeout, go back and try again."),
);

$error = @$login_failures[@$_GET['error_code']];
if(!$error)
{
    $error = _("An undefined error occurred while attempting to log you in.");
}

$title = _("Login Failed");
output_header($title);

echo "<br>\n";
echo "<b>$error</b>\n";

echo "<p>" . _("Please attempt again to log in above. If problems persist, review the following possible fixes:") . "</p>";
echo "<ol>";
if ($testing)
{
    echo "<li class='test_warning'>" . _("Register! (Note that this is a test site, and has a separate database from the production site, so you need to register separately.)") . "</li>\n";
}
echo "<li>" . _("Type your username in the exact same way as when you registered.") . "</li>\n";
echo "<li>" . sprintf( _("<a href='%s'>Reset your password</a>."), get_reset_password_url()) . "</li>\n";
echo "<li>" . _("Enable Javascript.") . "</li>\n";
echo "<li>" . sprintf(_("Accept cookies (at least from us at %s)."), $_SERVER["HTTP_HOST"]) . "</li>\n";
echo "<li>" . sprintf(_("Allow popup windows (at least from us at %s)."), $_SERVER["HTTP_HOST"]) . "</li>\n";
echo "<li>" . _("Ensure your computer's clock is set to the correct date &amp; time.") . "</li>\n";
echo "</ol>";
echo "<p>" . sprintf( _("If all of this fails, contact a <a href='%s'>site manager</a>."), "mailto:$site_manager_email_addr") . "</p>";
if($testing)
{
    echo "<p class='test_warning'>" . sprintf( _("Note: This is a testing site, and is not set up to send emails. If you have just registered, the page generated should have had an activation link on the page for you to copy and paste into a new browser tab. If you closed that page and can no longer access the information on it, please contact a <a href='%s'>site manager</a> to solve the problem."), "mailto:$site_manager_email_addr") . "</p>";
}
else
{
    echo "<p>" . sprintf( _("Note: If you have just registered, you will need to wait for the welcome mail to arrive to your mailbox. Once it does, please click the activation link to complete the registration (this is to prevent others from signing you up to the site without your knowledge). If you have waited for an hour or so and have still not received any mail from us (please check any spam filters!), it is likely that you misentered your email-address. Please contact a <a href='%s'>site manager</a> to solve the problem."), "mailto:$site_manager_email_addr") . "</p>";
}

// vim: sw=4 ts=4 expandtab
