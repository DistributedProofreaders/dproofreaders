<?php
$relPath = "./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'new_user_mails.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'forum_interface.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'User.inc');

// The current param value is "reg_token" but the prior value was "id"
// so we support both here.
$reg_token = array_get($_GET, "reg_token", array_get($_GET, "id", ""));

// If the user is already logged in, redirect them to the Activity Hub.
if (User::current_username()) {
    metarefresh(0, "$code_url/activity_hub.php");
}

try {
    $user = NonactivatedUser::load_from_id($reg_token);
} catch (NonexistentNonactivatedUserException $exception) {
    // See if the user is already activated.
    try {
        $user_test = User::load_from_registration_token($reg_token);
        $existing_user = $user_test->username;
    } catch (Exception $exception) {
        $existing_user = null;
    }
}

// A newly registered user has clicked the link in the welcoming e-mail and has thus
// proved that the e-mail is working. It is time to 'activate' the account, i.e.
// create a record in the users table, create a profile, stats data, etc.
// and send a welcome mail.
$title = _('Activate account');
output_header($title);
echo "<h1>$title</h1>";

if (!isset($user->id)) {
    if ($existing_user) {
        echo "<p>";
        echo _("It appears that the account has already been activated.");
        echo "\n";
        echo _("(Probably you just clicked the activation link more than once.)");
        echo "\n";
        echo _("There should be an introductory email message on its way to you.");
        echo "</p>";

        echo "<p>";
        echo _("Please enter your username and password in the fields above to login to your account.");
        echo "</p>";
    } else {
        echo "<p>";
        echo sprintf(
            _("There is no account with activation code '%s' waiting to be activated."),
            html_safe($reg_token)
        );
        echo "</p>";

        echo "<p>";
        $mailto_url = "mailto:$general_help_email_addr";
        echo sprintf(
            _('For assistance, please contact <a href="%1$s">%2$s</a>.'),
            $mailto_url,
            $general_help_email_addr
        );
        echo "</p>";
    }

    exit;
}

// Verify we can create the user's forum account, and bail if we can't.
$create_user_status = create_forum_user($user->username, $user->user_password, $user->email, true);

if ($create_user_status !== true) {
    // Failure here should be rare (which is good given that this is not a
    // great user experience). The most common instance where this could
    // come up is for the 'Anonymous' user. Better validation is needed in
    // addproofer.php to detect duplicate account names during registration.
    echo "<p>\n";
    echo _("Account creation failed due to inability to register with forum.");
    echo "\n";
    echo "<!-- Forum error: $create_user_status -->";
    echo "\n";
    error_log("activate.php - Error activating $reg_token: $create_user_status");
    $mailto_url = "mailto:$general_help_email_addr";
    echo sprintf(
        _('For assistance, please contact <a href="%1$s">%2$s</a>.'),
        $mailto_url,
        $general_help_email_addr
    );
    echo "\n";
    echo sprintf(
        _("Please include the account activation code %s in your email for assistance."),
        $reg_token
    );
    echo "</p>\n";
    exit;
}

// Insert into 'real' table -- users
$query = sprintf(
    "
    INSERT INTO users (reg_token, real_name, username, email, date_created,
                       email_updates, referrer, referrer_details, http_referrer, u_neigh, u_intlang)
    VALUES ('%s', '%s', '%s', '%s', $user->date_created,
            $user->email_updates, '%s', '%s', '%s', 0, '%s')
    ",
    DPDatabase::escape($reg_token),
    DPDatabase::escape($user->real_name),
    DPDatabase::escape($user->username),
    DPDatabase::escape($user->email),
    DPDatabase::escape($user->referrer),
    DPDatabase::escape($user->referrer_details),
    DPDatabase::escape($user->http_referrer),
    DPDatabase::escape($user->u_intlang)
);

$result = DPDatabase::query($query);
$u_id = mysqli_insert_id(DPDatabase::get_connection()); // auto-incremented users.u_id

// Delete record in non_activated_users.
$user->delete();

// create profile
$profile = new UserProfile();
$profile->u_ref = $u_id;
$profile->save();

// add ref to profile
$refString = sprintf(
    "UPDATE users SET u_profile=%d WHERE u_id=%d",
    DPDatabase::escape($profile->id),
    $u_id
);
DPDatabase::query($refString);

// Send them an introduction e-mail
send_welcome_mail($user->email, $user->real_name, $user->username);

echo "<p>";
printf(_("User %s activated successfully."), $user->username);
echo " ";

// TRANSLATORS: %s is the site name
printf(
    _("Please check the e-mail being sent to you for further information about %s."),
    $site_name
);
echo "</p>";

echo "<p>"._("Enter your password below to sign in and start proofreading!!") . "</p>";

// We use the same field labels here that are in the navbar
$csrf_token = get_csrf_token_form_input();
echo "<form action='login.php' method='post'>
$csrf_token
<table class='themed theme_striped' style='width: auto'>
<tr>
  <th><label for='loginform-userNM'>" . _("ID") . "</label></th>
  <td><input type='text' id='loginform-userNM' name='userNM' value='".attr_safe($user->username)."' autocapitalize='none' required></td>
</tr>
<tr>
  <th><label for='loginform-userPW'>" . _("Password") . "</label></th>
  <td><input type='password' id='loginform-userPW' name='userPW' required></td>
</tr>
</table>
<p><input type='submit' value='".attr_safe(_("Sign In"))."'></p>
</form>";
