<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'User.inc');
include_once('sa_common.inc');

require_login();

if (!user_is_a_sitemanager()) {
    die(_("You are not authorized to invoke this script."));
}

// --------------------------------------

$username = $_POST['username'] ?? ($_GET['username'] ?? null);
$action = get_enumerated_param($_POST, 'action', null, ['logout'], true);

$title = _("Manage User Sessions");
output_header($title, NO_STATSBAR);

echo "<h1>$title</h1>\n";
echo "<p>" . _("This page shows how many separate sessions (logins) a user has and the option of logging them out.") . "</p>";

show_username_form($username);

if ($username) {
    try {
        $user = new User($username);
    } catch (NonexistentUserException $exception) {
        echo "<p class='error'>" . _("Invalid username") . "</p>";
        exit;
    }

    echo "<hr>";
    echo "<h2>$username ($user->real_name)</h2>";

    if ($action == "logout") {
        delete_user_sessions($username);
        echo "<p>" . _("User has been logged out.") . "</p>";
    } else {
        show_sessions_form($username);
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function show_sessions_form(?string $username): void
{
    echo "<p>" . _("User has the following sessions. Sessions that have expired are no longer valid and will be automatically cleaned up by the system.") . "</p>";
    echo "<table class='basic'>\n";
    echo "<tr>\n";
    echo "<th>" . _("SID Hash") . "</th>\n";
    echo "<th>" . _("Expires") . "</th>\n";
    echo "</tr>\n";
    foreach (load_user_sessions($username) as $result) {
        [$sid, $expiration] = $result;
        echo "<tr>";
        echo "<td>" . md5($sid) . "</td>";
        $notes = $expiration < time() ? _("(session has expired)") : "";
        echo "<td>" . icu_date_template("long+time", $expiration) . " $notes</td>";
        echo "</tr>";
    }

    echo "</table>\n";

    echo "<form method='POST'>\n";
    echo "<input type='hidden' name='username' value='" . attr_safe($username) . "'>\n";
    echo "<input type='hidden' name='action' value='logout'>\n";
    echo "<p><input type='submit' name='submit' value='" . attr_safe(_("Logout User")) . "'></p>\n";
    echo "</form>\n";
}
