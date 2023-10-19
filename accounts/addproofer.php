<?php
$relPath = "./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'pg.inc');
include_once($relPath.'User.inc');
include_once($relPath.'email_address.inc');
include_once($relPath.'new_user_mails.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'User.inc');
include_once($relPath.'misc.inc'); // attr_safe()

$real_name = array_get($_POST, 'real_name', '');
$username = array_get($_POST, 'userNM', '');
$userpass = array_get($_POST, 'userPW', '');
$userpass2 = array_get($_POST, 'userPW2', '');
$email = array_get($_POST, 'email', '');
$email2 = array_get($_POST, 'email2', '');
$email_updates = array_get($_POST, 'email_updates', 1);
$referrer = array_get($_POST, 'referrer', '');
$referrer_details = array_get($_POST, 'referrer_details', '');

$form_data_inserters = [];
$form_validators = [
    "_validate_fields",
    "_validate_csrf",
];

// If configured, load site-specific bot-prevention and validation funcs
if ($site_registration_protection_code) {
    include_once($site_registration_protection_code);
    $form_data_inserters = get_registration_form_inserters();
    $form_validators = array_merge($form_validators, get_registration_form_validators());
}

// assume there is no error
$error = "";

if (count($_POST)) {
    // When in testing mode, to avoid leaking private email addresses,
    // create a fake but distinct email address based on the username.
    // DP usernames allow [0-9A-Za-z@._ -]. '@' and ' ' are not valid
    // (unless quoted) in the local part of an email address, so
    // convert those to '%' and '+' respectively.
    if ($testing) {
        $local_part = str_replace(['@', ' '], ['%', '+'], $username);
        $email = $local_part . "@localhost";
        $email2 = $email;
    }

    // Run all form validators against the data
    foreach ($form_validators as $func) {
        $error = $func($real_name, $username, $userpass, $userpass2, $email, $email2, $email_updates, $referrer, $referrer_details);
        if (!empty($error)) {
            break;
        }
    }

    // if all fields validated, create the registration
    if (empty($error)) {
        $intlang = get_desired_language();
        $register = new NonactivatedUser();
        $register->real_name = $real_name;
        $register->username = $username;
        $register->email = $email;
        $register->email_updates = $email_updates;
        $register->referrer = $referrer;
        $register->referrer_details = $referrer_details;
        $register->http_referrer = array_get($_COOKIE, "http_referer", "");
        $register->u_intlang = $intlang;
        $register->user_password = forum_password_hash($userpass);

        try {
            $register->save();

            // delete the cookie tracking the HTTP_REFERER
            dp_setcookie("http_referer", '', time() - 1);

            // Page shown when account is successfully created
            $title = _("Registration complete");
            output_header($title);
            echo "<h1>$title</h1>";

            // Send them an activation e-mail
            send_activate_mail($email, $real_name, $register->id, $username, $intlang);

            echo "<p>";
            echo sprintf(
                _("User %s registered successfully. Please check the e-mail being sent to you for further information about activating your account. This extra step is taken so that no-one can register you to the site without your knowledge."),
                html_safe($username)
            );
            echo "</p>";
            exit();
        } catch (Exception $exception) {
            $error = _("Can not initiate user registration.");
        }
    }
}


// This is the portion that shows up when no parameters are given to the file
// or an error occurs during registration.
//
// When users fill the form out below, it will submit the information back
// to this file & run the above commands.

$header = _("Create An Account");
output_header($header, SHOW_STATSBAR, ["js_files" => ["$code_url/accounts/addproofer.js"]]);

echo "<h1>" . _("Account Registration") . "</h1>";

// See if the user is already logged in
if (User::load_current()) {
    echo "<p class='error'>" . _("You already have an account and cannot create another one while logged in.") . "</p>";
    exit;
}

echo sprintf(_("Thank you for your interest in %s. To create an account, please complete the form below."), $site_name);

echo "<h2>" . _("Registration Hints") . "</h2>";
echo "<ul>";
echo "<li>" . _("Your User Name will be visible to other volunteers and <strong>cannot be changed</strong>. Please choose it carefully.") . "</li>";
echo "<li>" . sprintf(_("Please ensure that the e-mail address you provide is correct. %s will e-mail a confirmation link for you to follow in order to activate your account."), $site_name) . "</li>";
echo "<li>" . sprintf(_("<strong>Before</strong> you submit this form, please add <i>%s</i> to your e-mail contacts list to avoid the activation e-mail being treated as spam."), $general_help_email_addr) . "</li>";
echo "</ul>";

if ($testing) {
    echo "<p class='test_warning'>";
    echo _("Because this is a test site, you <strong>don't</strong> need to provide an email address and an email <strong>won't</strong> be sent to you. Instead, when you hit the 'Send E-mail ...' button below, the text of the would-be email will be displayed on the next screen. After the greeting, there's a line that ends 'please visit this URL:', followed by a confirmation URL. Copy and paste that URL into your browser's location field and hit return. <strong>Your account won't be created until you access the confirmation link.</strong>");
    echo "</p>\n";
}

// If the user filled out the form but there was an error during the
// data validation, print out the error here and let them resubmit.
if (!empty($error)) {
    echo "<p class='error'>$error</p>";
}

echo "<form method='post' action='addproofer.php'>\n";
foreach ($form_data_inserters as $func) {
    $func();
}
echo_csrf_token_form_input();
echo "<table class='register'>";
echo "<tr>";
echo "  <th>" . _("Real Name") . ":</th>";
echo "  <td><input type='text' name='real_name' value='". attr_safe($real_name) ."' maxlength='100' required></td>";
echo "</tr>\n<tr>";
echo "  <th>" . _("User Name") . ":</th>";
echo "  <td><input type='text' name='userNM' value='" . attr_safe($username) . "' required><br><small>$valid_username_chars_statement_for_reg_form</small></td>";
echo "</tr>\n<tr>";
echo "  <th>" . _("Password") . ":</th>";
echo "  <td><input type='password' name='userPW' required></td>";
echo "</tr>\n<tr>";
echo "  <th>" . _("Confirm Password") . ":</th>";
echo "  <td><input type='password' name='userPW2' required></td>";
echo "</tr>\n";
if (!$testing) {
    echo "<tr>";
    echo "  <th>" . _("E-mail Address") . ":</th>";
    echo "  <td><input type='email' name='email' value='". attr_safe($email) . "' required></td>";
    echo "</tr>\n<tr>";
    echo "  <th>" . _("Confirm E-mail Address") . ":</th>";
    echo "  <td><input type='email' name='email2' value='" . attr_safe($email2) . "' required></td>";
    echo "</tr>\n";
}
echo "<tr>";
echo "  <th>" . _("E-mail Updates") . ":</th>";
echo "  <td>";
echo "    <input type='radio' name='email_updates' value='1' ";
if ($email_updates) {
    echo "checked";
}
echo "    >" . _("Yes") . "&nbsp;&nbsp;";
echo "    <input type='radio' name='email_updates' value='0' ";
if (!$email_updates) {
    echo "checked";
}
echo "    >" . _("No");
echo "  </td>";
echo "</tr>\n";

echo "<tr>";
echo "  <th>" . _("How did you hear about us?") . "</th>";
echo "  <td>";
echo "    <select name='referrer'>";
if (!$referrer) {
    echo "  <option selected disabled>" . _("Please select one") . "</option>";
}
foreach (User::get_referrer_options() as $key => $value) {
    $selected = "";
    if ($key == $referrer) {
        $selected = "selected";
    }
    echo "<option value='$key' $selected>$value</option>";
}
echo "    </select>";
echo "  </td>";
echo "</tr>\n";

echo "<tr id='referrer_details'>";
echo "  <th>" . _("Other, Details") . ":</th>";
echo "  <td><input type='text' name='referrer_details' value='". attr_safe($referrer_details) . "' maxlength='255'></td>";
echo "</tr>\n";

echo "<tr>";
echo "<td class='bar center-align' colspan='2'><input type='submit' value='" . attr_safe(_("Send E-Mail required to activate account")) . "'>&nbsp;&nbsp;<input type='reset'></td>";
echo "</tr></table></form>";

include($relPath.'/../faq/privacy.php');


//---------------------------------------------------------------------------


/**
 * Validate the user input fields for the page.
 *
 * Returns an empty string upon success and an error message upon failure.
 */
function _validate_fields($real_name, $username, $userpass, $userpass2, $email, $email2, $email_updates, $referrer, $referrer_details)
{
    global $testing, $general_help_email_addr;

    // Make sure that password and confirmed password are equal.
    if ($userpass != $userpass2) {
        return _("The passwords you entered were not equal.");
    }

    // Make sure that email and confirmed email are equal.
    if ($email != $email2) {
        return _("The e-mail addresses you entered were not equal.");
    }

    // See if there is already an account creation request for this email
    try {
        NonactivatedUser::load_from_email($email);
        $email_exists = true;
    } catch (NonuniqueNonactivatedUserException $e) {
        $email_exists = true;
    } catch (NonexistentNonactivatedUserException $e) {
        // this is the expected case
        $email_exists = false;
    }

    if ($email_exists) {
        return sprintf(_("There is already an account creation request for this email address. Please allow time for the account activation email to arrive in your inbox. It is also a good idea to check your spam folder. If you have not received it within 12 hours, please contact %s to have it re-sent."), $general_help_email_addr);
    }

    // Do some validity-checks on inputted username, password, e-mail and real name

    $err = check_username($username, true);
    if ($err != '') {
        return $err;
    }

    // In testing mode, a fake email address is constructed using
    // 'localhost' as the domain. check_email_address() incorrectly
    // thinks the domain should end in a 2-63 character top level
    // domain, so disable the address check for testing.
    if (!$testing) {
        $err = check_email_address($email);
        if ($err != '') {
            return $err;
        }
    }

    if (empty($userpass) || empty($real_name)) {
        return _("You did not completely fill out the form.");
    }

    // Make sure that the requested username is not already taken.
    // Use non-strict validation, which will return TRUE if the username
    // is the same as an existing one, or differs only by case or trailing
    // whitespace.
    if (User::is_valid_user($username, false)) {
        return _("That user name already exists, please try another.");
    }

    // Ensure we don't already have a registration with this name
    try {
        $na_user = new NonactivatedUser($username);
        return _("That username has already been requested. Please try another.");
    } catch (NonexistentNonactivatedUserException$exception) {
        // pass
    }

    // TODO: The above check only validates against users in the DP database.
    // It's possible that there are usernames already registered with the
    // underlying forum software (like 'Anonymous') or are disallowed in the
    // forum software which, if used, will cause account creation to fail in
    // activate.php.

    if (!$referrer || ($referrer == 'other' && !$referrer_details)) {
        return _("Please tell us how you heard about us.");
    }

    return '';
}

function _validate_csrf()
{
    try {
        validate_csrf_token();
    } catch (InvalidCSRFTokenException $e) {
        return $e->getMessage();
    }

    return '';
}
