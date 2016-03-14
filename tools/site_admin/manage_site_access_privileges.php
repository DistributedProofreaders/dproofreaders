<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'User.inc');
include_once($relPath.'misc.inc'); // attr_safe()

require_login();

if ( !user_is_a_sitemanager() )
{
    die("permission denied");
}

// --------------------------------------

$boolean_user_settings = array(
    'proj_facilitator'        => _("Grants project facilitator privileges"),
    'access_request_reviewer' => _("Creates level evaluators; gives access to special reviewer-only scripts; <b>must</b> be combined with PF access"),
    'image_sources_manager'   => _("Grants ability to create new image source listings and manage existing records"),
    'site_news_editor'        => _("Grants ability to create and edit news on any of the site pages"),
    'task_center_mgr'         => _("Grants administrative access to the Task Center; typically granted to developers so they can manage their own tasks"),
    'authors_db_manager'      => _("Grants ability to manage author records"),
);

$value_user_settings = array(
    'remote_file_manager' => array(
        'disabled' => _("Revokes user access to remote file manager"),
        'common' => _("Grants user access to Commons upload directory"),
        'self' => _("Grants user access their own private upload directory"),
        '' => _("Value is not set"),
    ),
);

$username = array_get($_POST, 'username', array_get($_GET, 'username', NULL));
$action = get_enumerated_param($_POST, 'action', NULL, array('update'), TRUE);

$title = _("Manage Site Access Privileges");
output_header($title);

echo "<h1>$title</h1>\n";
echo "<p>" . _("This page allows you to toggle boolean settings in user_settings to grant or revoke various site access permissions. Round accesses are managed from the user's statistics page.") . "</p>";

show_username_form($username);

if($username)
{
    try
    {
        $user = new User($username);
    }
    catch(NonexistentUserException $exception)
    {
        echo "<p class='error'>" . _("Invalid username") . "</p>";
        exit;
    }

    $user_settings =& Settings::get_settings($username);

    echo "<hr>";
    echo "<h2>$username ($user->real_name)</h2>";

    if($action == "update")
    {
        update_settings($username, $user_settings);
    }
    else
    {
        show_toggles_form($username, $user_settings);
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function show_username_form($username)
{
    echo "<form method='GET'>\n";
    echo "<p>" . _("Username") . ": ";
    echo "<input name='username' type='text' value='$username' size='20'>\n";
    echo "</p>";
    echo "<input type='submit' value='" . attr_safe(_("Look up this user")) . "'>\n";
    echo "</form>\n";
}

// ------------------------------------------------

function show_toggles_form($username, $user_settings)
{
    global $boolean_user_settings, $value_user_settings;

    echo "<form method='POST'>\n";
    echo "<input type='hidden' name='username' value='$username'>\n";
    echo "<input type='hidden' name='action' value='update'>\n";
    echo "<table>\n";
    {
        echo "<tr>\n";
        echo "<th>" . _("Set?") . "</th>\n";
        echo "<th>" . _("Access") . "</th>\n";
        echo "<th>" . _("Description") . "</th>\n";
        echo "</tr>\n";
    }
    foreach ( $boolean_user_settings as $setting_name => $setting_description )
    {
        $user_current_value = $user_settings->get_boolean($setting_name);
        $checked_attr = ($user_current_value == 'yes' ? 'checked' : '');
        echo "<tr>\n";
        echo "<td><input name='$setting_name' type='checkbox' $checked_attr></td>\n";
        echo "<td>$setting_name</td>\n";
        echo "<td>$setting_description</td>\n";
        echo "</tr>\n";
    }

    foreach ( $value_user_settings as $setting_name => $options )
    {
        echo "<tr>\n";
        echo "<td></td>";
        echo "<td>$setting_name</td>\n";
        echo "<td>";
        foreach ( $options as $value => $label )
        {
            echo "<input type='radio' name='$setting_name' value='$value'";
            if($user_settings->get_value($setting_name, '') == $value)
                echo 'checked';
            echo ">";
            if($value)
                echo " $value: $label<br>";
            else
                echo " $label<br>";
        }
        echo "</td>";
        echo "</tr>\n";
    }
    echo "</table>\n";
    echo "<p><input type='submit' name='submit' value='" . attr_safe(_("Update")) . "'></p>\n";
    echo "</form>\n";
}

// -----------------------------------------------------------------------------

function update_settings($username, $user_settings)
{
    global $boolean_user_settings, $value_user_settings;

    $disposition = array(
        'turn on'  => array(),
        'turn off' => array(),
        'leave on'  => array(),
        'leave off' => array(),
        'set' => array(),
    );

    foreach ( $boolean_user_settings as $setting_name => $setting_description )
    {
        $user_current_value = $user_settings->get_boolean($setting_name);
        if (isset($_POST[$setting_name]))
        {
            assert($_POST[$setting_name] == 'on');
            // The admin is requesting that this setting be on/yes.

            if ( $user_current_value )
            {
                $which = 'leave on';
            }
            else
            {
                $which = 'turn on';
            }
        }
        else
        {
            // The admin is requesting that this setting be off/no/absent.

            if ( !$user_current_value )
            {
                $which = 'leave off';
            }
            else
            {
                $which = 'turn off';
            }
        }

        $disposition[$which][] = $setting_name;
    }

    foreach ( $value_user_settings as $setting_name => $options )
    {
        if (isset($_POST[$setting_name]))
        {
            if($user_settings->get_value($setting_name) == $_POST[$setting_name])
                continue;

            $disposition['set'][$setting_name] = $_POST[$setting_name];
        }
    }

    echo "<p>" . _("You have made the following request for the above user:") . "</p>\n";
    foreach ($disposition as $which => $setting_names)
    {
        echo "<p><b>$which:</b></p>\n";
        echo "<ul>\n";
        if ($which == 'set')
        {
            foreach ( $setting_names as $setting => $value)
            {
                echo "<li>$setting = $value</li>";
            }
        }
        else
        {
            foreach ( $setting_names as $setting_name )
            {
                echo "<li>$setting_name</li>\n";
            }
        }

        if ( !count($setting_names) )
        {
            echo "<li>(none)</li>\n";
        }
        echo "</ul>\n";
    }

    if( count($disposition['turn on']) == 0 and
        count($disposition['turn off']) == 0 and
        count($disposition['set']) == 0 )
    {
        echo "<p>" . _("No changes requested!") . "</p>\n";
        return;
    }

    // --------------------------------------------------------

    echo "<p>" . _("Now performing the requested changes...") . "</p>";

    foreach ( $disposition['turn on'] as $setting_name )
    {
        $user_settings->set_true($setting_name);
    }

    foreach ( $disposition['turn off'] as $setting_name )
    {
        $user_settings->set_value($setting_name, NULL);
    }

    foreach ( $disposition['set'] as $setting_name => $value )
    {
        if($value == '')
            $value = NULL;

        $user_settings->set_value($setting_name, $value);
    }

    echo "<p>" . _("Done.") . "</p>";
}

// vim: sw=4 ts=4 expandtab
