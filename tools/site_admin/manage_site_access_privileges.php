<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'User.inc');
include_once($relPath.'misc.inc'); // attr_safe()
include_once($relPath.'access_log.inc');

require_login();

if (!user_is_a_sitemanager()) {
    die(_("You are not authorized to invoke this script."));
}

// --------------------------------------

$boolean_user_settings = [
    // Creating site managers can be done here too, although given how
    // infrequently that will happen, it's probably best to leave it commented
    // out to prevent accidental usage when they're really just trying to
    // create a PM.
    //    'sitemanager'             => _("Grants site administrator privileges"),
    'manager' => _("Grants project management (PM) privileges"),
    'proj_facilitator' => _("Grants project facilitator (PF) privileges"),
    'access_request_reviewer' => _("Creates level evaluators; gives access to special reviewer-only scripts; <b>must</b> be combined with PF access"),
    'image_sources_manager' => _("Grants ability to create new image source listings and manage existing records"),
    'site_news_editor' => _("Grants ability to create and edit news on any of the site pages"),
    'site_translator' => _("Grants ability to create and update site translations "),
    'task_center_mgr' => _("Grants administrative access to the Task Center; typically granted to developers so they can manage their own tasks"),
    'authors_db_manager' => _("Grants ability to manage author records"),
    'send_to_post' => _("Send user's projects to the PP pool"),
    'disable_project_loads' => _("Revoke user's ability to load projects"),
];

$value_user_settings = [
    'remote_file_manager' => [
        'disabled' => _("Revokes user access to remote file manager"),
        'common' => _("Grants user access to Commons upload directory"),
        'self' => _("Grants user access their own private upload directory"),
        '' => _("Value is not set"),
    ],
];

$freeform_user_settings = [
    'pp_limit_value' => _("Post-processing projects limit, -1 means unlimited"),
];

$username = array_get($_POST, 'username', array_get($_GET, 'username', null));
$action = get_enumerated_param($_POST, 'action', null, ['update'], true);

$title = _("Manage Site Access Privileges");
output_header($title, NO_STATSBAR);

echo "<h1>$title</h1>\n";
echo "<p>" . _("This page allows you to grant or revoke various site access permissions for a user and adjust some limits. Round accesses are managed from the user's statistics page.") . "</p>";

show_username_form($username);

if ($username) {
    try {
        $user = new User($username);
    } catch (NonexistentUserException $exception) {
        echo "<p class='error'>" . _("Invalid username") . "</p>";
        exit;
    }

    $user_settings = & Settings::get_settings($username);

    echo "<hr>";
    echo "<h2>$username ($user->real_name)</h2>";

    if ($action == "update") {
        update_settings($user, $user_settings);
    } else {
        show_toggles_form($username, $user_settings);
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function show_username_form($username)
{
    echo "<form method='GET'>\n";
    echo "<p>" . _("Username") . ": ";
    echo "<input name='username' type='text' value='$username' size='20' required>\n";
    echo "</p>";
    echo "<input type='submit' value='" . attr_safe(_("Look up this user")) . "'>\n";
    echo "</form>\n";
}

// ------------------------------------------------

function show_toggles_form($username, $user_settings)
{
    global $boolean_user_settings, $value_user_settings, $freeform_user_settings;

    echo "<form method='POST'>\n";
    echo "<input type='hidden' name='username' value='$username'>\n";
    echo "<input type='hidden' name='action' value='update'>\n";
    echo "<table class='basic'>\n";
    {
        echo "<tr>\n";
        echo "<th>" . _("Enable") . "</th>\n";
        echo "<th>" . _("Changed") . "</th>\n";
        echo "<th>" . _("Setting") . "</th>\n";
        echo "<th>" . _("Description") . "</th>\n";
        echo "</tr>\n";
    }
    foreach ($boolean_user_settings as $setting_name => $setting_description) {
        $user_current_value = $user_settings->get_boolean($setting_name);
        $checked_attr = ($user_current_value ? 'checked' : '');

        $access_log_entry = get_latest_access_change_entry($username, $setting_name);
        if ($access_log_entry) {
            $changed = date('Y-m-d H:i', $access_log_entry["timestamp"]);
        } else {
            $changed = "<i>" . _("unknown") . "</i>";
        }

        echo "<tr>\n";
        echo "<td><input name='$setting_name' type='checkbox' $checked_attr></td>\n";
        echo "<td>$changed</td>";
        echo "<td>$setting_name</td>\n";
        echo "<td>$setting_description</td>\n";
        echo "</tr>\n";
    }

    foreach ($value_user_settings as $setting_name => $options) {
        echo "<tr>\n";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td>$setting_name</td>\n";
        echo "<td>";
        foreach ($options as $value => $label) {
            echo "<input type='radio' name='$setting_name' value='$value'";
            if ($user_settings->get_value($setting_name, '') == $value) {
                echo 'checked';
            }
            echo ">";
            if ($value) {
                echo " $value: $label<br>";
            } else {
                echo " $label<br>";
            }
        }
        echo "</td>";
        echo "</tr>\n";
    }

    foreach ($freeform_user_settings as $setting_name => $setting_description) {
        $user_current_value = $user_settings->get_value($setting_name);
        echo "<tr>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td>$setting_name</td>\n";
        echo "<td>$setting_description<br>\n";
        echo "<input type='text' name='$setting_name' value='" . attr_safe($user_current_value) . "'>";
        echo "</td>";
        echo "</tr>";
    }

    echo "</table>\n";
    echo "<p><input type='submit' name='submit' value='" . attr_safe(_("Update")) . "'></p>\n";
    echo "</form>\n";
}

// -----------------------------------------------------------------------------

function update_settings($user, $user_settings)
{
    global $boolean_user_settings, $value_user_settings, $freeform_user_settings, $pguser;

    $username = $user->username;

    $disposition = [
        'turn on' => [],
        'turn off' => [],
        'leave on' => [],
        'leave off' => [],
        'set' => [],
    ];

    foreach ($boolean_user_settings as $setting_name => $setting_description) {
        $user_current_value = $user_settings->get_boolean($setting_name);
        if (isset($_POST[$setting_name])) {
            assert($_POST[$setting_name] == 'on');
            // The admin is requesting that this setting be on/yes.

            if ($user_current_value) {
                $which = 'leave on';
            } else {
                $which = 'turn on';
            }
        } else {
            // The admin is requesting that this setting be off/no/absent.

            if (!$user_current_value) {
                $which = 'leave off';
            } else {
                $which = 'turn off';
            }
        }

        $disposition[$which][] = $setting_name;
    }

    foreach (array_merge($value_user_settings, $freeform_user_settings) as
        $setting_name => $options) {
        if (isset($_POST[$setting_name])) {
            if ($user_settings->get_value($setting_name) == $_POST[$setting_name]) {
                continue;
            }

            $disposition['set'][$setting_name] = $_POST[$setting_name];
        }
    }

    echo "<p>" . _("You have made the following request for the above user") . ":</p>\n";
    foreach ($disposition as $which => $setting_names) {
        echo "<p><b>$which:</b></p>\n";
        echo "<ul>\n";
        if ($which == 'set') {
            foreach ($setting_names as $setting => $value) {
                echo "<li>$setting = $value</li>";
            }
        } else {
            foreach ($setting_names as $setting_name) {
                echo "<li>$setting_name</li>\n";
            }
        }

        if (!count($setting_names)) {
            echo "<li><i>" . pgettext("no changes", "none") . "</i></li>\n";
        }
        echo "</ul>\n";
    }

    if (count($disposition['turn on']) == 0 and
        count($disposition['turn off']) == 0 and
        count($disposition['set']) == 0) {
        echo "<p>" . _("No changes requested!") . "</p>\n";
        return;
    }

    // --------------------------------------------------------

    echo "<p>" . _("Now performing the requested changes...") . "</p>";

    foreach ($disposition['turn on'] as $setting_name) {
        $user->grant_access($setting_name, $pguser, false);
    }

    foreach ($disposition['turn off'] as $setting_name) {
        $user->revoke_access($setting_name, $pguser, false);
    }

    foreach ($disposition['set'] as $setting_name => $value) {
        if ($value == '') {
            $value = null;
        }

        $user_settings->set_value($setting_name, $value);
    }

    echo "<p>" . _("Done.") . "</p>";
}
