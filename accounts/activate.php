<?php
$relPath="./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'new_user_mails.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'forum_interface.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'User.inc');

$ID = array_get($_GET, "id", "");

try
{
    $user = NonactivatedUser::load_from_id($ID);
}
catch(NonexistentNonactivatedUserException $exception)
{
    // If the user is already activated, and the activated user is the
    // one that is logged in, redirect them to the Activity Hub. This can
    // happen if they use the login form in the navbar and are redirected
    // back here.
    try
    {
        $user_test = new User();
        $user_test->load("id", $ID);
        $existing_user = $user_test->username;
        if($pguser == $existing_user)
        {
            metarefresh(0, "$code_url/activity_hub.php");
        }
    }
    catch(Exception $exception)
    {
        $existing_user = NULL;
    }
}

// A newly registered user has clicked the link in the welcoming e-mail and has thus
// proved that the e-mail is working. It is time to 'activate' the account, i.e.
// create a record in the users table, create a profile, stats data, etc.
// and send a welcome mail.
output_header(_('Activate account'));
if(!isset($user->id)) {
    echo "<p>\n";
    echo sprintf(
        _("There is no account with the id '%s' waiting to be activated."), $ID
    );

    if($existing_user) {
        echo "\n";
        echo _("It appears that the account has already been activated.");
        echo "\n";
        echo _("(Probably you just clicked the activation link more than once.)");
        echo "\n";
        echo _("There should be an introductory email message on its way to you.");
        echo "\n";
        if(!$pguser) {
            echo _("Please enter your username and password in the fields above to login to your account.");
            echo "\n";
        }
    }
    else
    {
        echo "\n";
        $mailto_url = "mailto:$general_help_email_addr";
        echo sprintf(
            _('For assistance, please contact <a href="%1$s">%2$s</a>.'),
            $mailto_url, $general_help_email_addr );
        echo "\n";
    }
    echo "</p>\n";

    exit;
}

// Verify we can create the user's forum account, and bail if we can't.
$create_user_status = create_forum_user($user->username, $user->user_password, $user->email, TRUE);

if($create_user_status !== TRUE) {
    // Failure here should be rare (which is good given that this is not a
    // great user experience). The most common instance where this could
    // come up is for the 'Anonymous' user. Better validation is needed in
    // addproofer.php to detect duplicate account names during registration.
    echo "<p>\n";
    echo _("Account creation failed due to inability to register with forum.");
    echo "\n";
    echo "<!-- Forum error: $create_user_status -->";
    echo "\n";
    error_log("Error activating $ID: $create_user_status");
    $mailto_url = "mailto:$general_help_email_addr";
    echo sprintf(
        _('For assistance, please contact <a href="%1$s">%2$s</a>.'),
        $mailto_url, $general_help_email_addr );
    echo "\n";
    echo sprintf(
        _("Please include the account activation code %s in your email for assistance."),
        $ID);
    echo "</p>\n";
    exit;
}

// Insert into 'real' table -- users
$query = sprintf("
    INSERT INTO users (id, real_name, username, email, date_created, active,
                       email_updates, referrer, http_referrer, u_neigh, u_intlang)
    VALUES ('%s', '%s', '%s', '%s', $user->date_created, '',
            $user->email_updates, '%s', '%s', 10, '%s')
    ",  mysqli_real_escape_string(DPDatabase::get_connection(), $ID),
        mysqli_real_escape_string(DPDatabase::get_connection(), $user->real_name),
        mysqli_real_escape_string(DPDatabase::get_connection(), $user->username),
        mysqli_real_escape_string(DPDatabase::get_connection(), $user->email),
        mysqli_real_escape_string(DPDatabase::get_connection(), $user->referrer),
        mysqli_real_escape_string(DPDatabase::get_connection(), $user->http_referrer),
        mysqli_real_escape_string(DPDatabase::get_connection(), $user->u_intlang)
);

$result = mysqli_query(DPDatabase::get_connection(), $query) or die(mysqli_error(DPDatabase::get_connection()));
$u_id = mysqli_insert_id(DPDatabase::get_connection()); // auto-incremented users.u_id

// Delete record in non_activated_users.
$user->delete();

// create profile
$profileString="INSERT INTO user_profiles SET u_ref=$u_id";
$makeProfile = mysqli_query(DPDatabase::get_connection(), $profileString);
$profile_id = mysqli_insert_id(DPDatabase::get_connection()); // auto-incremented user_profiles.id

// add ref to profile
$refString=sprintf("
    UPDATE users SET u_profile=$profile_id WHERE id='%s' AND username='%s'
    ",  mysqli_real_escape_string(DPDatabase::get_connection(), $ID),
        mysqli_real_escape_string(DPDatabase::get_connection(), $user->username)
);
$makeRef = mysqli_query(DPDatabase::get_connection(), $refString);

// Send them an introduction e-mail
maybe_welcome_mail($user->email, $user->real_name, $user->username);

printf(_("User %s activated successfully."), $user->username);
echo " ";        
// TRANSLATORS: %s is the site name
printf(_("Please check the e-mail being sent to you for further information about %s."),
        $site_name);

echo "<p class='large'>"._("Enter your password below to sign in and start proofreading!!") . "</p>";
echo "<form action='login.php' method='post'>
<input type='hidden' name='userNM' value='".attr_safe($user->username)."'>
<input type='password' name='userPW'>
<input type='submit' value='".attr_safe(_("Sign In"))."'></form>";

// vim: sw=4 ts=4 expandtab
