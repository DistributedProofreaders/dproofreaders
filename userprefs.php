<?php
$relPath="./pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'resolution.inc');
include_once($relPath.'prefs_options.inc');
include_once($relPath.'languages.inc'); // bilingual_name()
include_once($relPath.'theme.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'SettingsClass.inc');
include_once($relPath.'misc.inc'); // startswith(...), attr_safe(), html_safe()
include_once($relPath.'js_newpophelp.inc');
include_once($relPath.'misc.inc'); // get_integer_param()
include_once($relPath.'forum_interface.inc'); // get_forum_user_details(), get_url_to_edit_profile()
include_once($relPath.'User.inc');

require_login();

// The url the user viewed immediately before coming to the preferences.
// Not all browsers provide this, though.
// If the user came to userprefs.php by entering the URL manually,
// $origin will be uninitialized, in which case it could be set
// to ".../userprefs.php..." at the next calls. Avoid this by setting
// the fallback origin to the Activity Hub.
$origin = array_get($_REQUEST, "origin", "");
if (empty($origin))
{
    if(array_key_exists('HTTP_REFERER', $_SERVER)
        && !startswith($_SERVER['HTTP_REFERER'], "$code_url/userprefs.php"))
    {
        $origin = $_SERVER['HTTP_REFERER'];
    }
    else
    {
        $origin = "$code_url/activity_hub.php";
    }
}
else
{
    $origin = urldecode($origin);
}
// From now on, keep the value of $origin through the browsing of tabs, saving prefs, etc.

// Define the available tabs.
// The indexes of the array are used elsewhere in this script.
$tabs = array(0 => _('General'),
    1 => _('Proofreading'));
if (user_is_PM())
    $tabs[2] = _('Project managing');

$selected_tab = get_integer_param($_REQUEST, "tab", 0, 0, max(array_keys($tabs)));

$uid = $userP['u_id'];

$userSettings =& Settings::get_Settings($pguser);

if (isset($_POST["swProfile"]))
{
    // User clicked "Switch profile"
    // get profile from database
    $c_profile=get_integer_param($_POST, "c_profile", NULL, 0, NULL);
    mysqli_query(DPDatabase::get_connection(), sprintf("
        UPDATE users
        SET u_profile='$c_profile'
        WHERE u_id=$uid  AND username='%s'",
        mysqli_real_escape_string(DPDatabase::get_connection(), $pguser))
    );
    dpsession_set_preferences_from_db();
    $eURL="$code_url/userprefs.php?tab=$selected_tab&amp;origin=" . urlencode($origin);
    metarefresh(0,$eURL,_('Profile Selection'),_('Loading Selected Profile....'));
}


$event_id = 0;
$window_onload_event= '';

//just a way to get them back to someplace on quit button
if (isset($_POST["quitnc"]))
{
    metarefresh(0, $origin, _("Quit"), "");
}

// restore session values from db
if (isset($_POST["restorec"]))
{
    dpsession_set_preferences_from_db();
    metarefresh(0, $origin, _("Restore"), "");
}

if (array_get($_POST, "insertdb", "") != "") {
    // one of the tabs was displayed and now it has been posted
    // determine which and let that tab save 'itself'.

    if ($selected_tab == 0)
        save_general_tab();
    else if ($selected_tab == 1)
        save_proofreading_tab();
    else if ($selected_tab == 2)
        save_pm_tab();

    if (isset($_POST["saveAndQuit"]) || isset($_POST["mkProfileAndQuit"]))
    {
        // Quit immediately after saving
        metarefresh(0, $origin, _("Quit"), "");
    }
    else if (isset($_POST["deletenc"]))
    {
        // Delete the profile which was displayed on the previous screen.
        // This is slightly cumbersome because the user has to switch to a profile
        // profile in order to be able to delete it, meaning the code has to handle
        // the aftereffects by setting a new current profile once that has been done.
        // Deletion is prevented when the user has only one profile by disabling
        // the button in the options at the bottom of the proofreading tab.

        // Get and delete currently selected profile.
        $profile = new UserProfile($userP['u_profile']);
        $profile->delete();

        // Set the first remaining available profile to be active.
        $profiles = UserProfile::load_user_profiles($uid);
        $new_profile_id = $profiles[0]->id;
    
        mysqli_query(DPDatabase::get_connection(), sprintf("
            UPDATE users
            SET u_profile = %d
            WHERE u_id = %d
        ", $new_profile_id, $uid));
        // Reload preferences to reflect changed active profile.
        dpsession_set_preferences_from_db();

        // Bounce user back to the proofreading preferences tab.
        $selected_tab=1;
        $url = "$code_url/userprefs.php?tab=$selected_tab&amp;origin=" . urlencode($origin);
        metarefresh(0, $url, _('Delete profile'), _('Reloading current tab....'));
    }
    else
    {
        // Show the same tab that was just saved
        $url = "$code_url/userprefs.php?tab=$selected_tab&amp;origin=" . urlencode($origin);
        metarefresh(0, $url, _('Saving preferences'), _('Reloading current tab....'));
    }
}

// header, start of table, form, etc. common to all tabs
$header = _("Personal Preferences");
$theme_extra_args["js_data"] =
    get_newHelpWin_javascript("$code_url/pophelp.php?category=prefs&name=set_") . "

    // function that can be used to check/uncheck a lot
    // of checkboxes at a time.
    // First parameter: true/false.
    // Following parameters: The name of the checkboxes.
    // The code checks that a checkbox really exists
    // before accessing it.
    function check_boxes(value) {
        var f = document.forms[0];
        for (var i = 1; i < arguments.length; i++) {
            var name = arguments[i];
            eval('if (f.'+name+') f.'+name+'.checked=value');
        }
    }

    function do_font_sample_update(font_index, size_index, layout) {
        if(font_index != null) {
            var font_family;
            if(font_index == 0) {
                font_family = '';
            } else if(font_index == 1) {
                input_accessor = 'input[name=\"' + layout + '_fntf_other\"]'
                font_family = $(input_accessor).val().trim();
            } else {
                font_family = font_face_mapping[font_index]
            }
            if(font_family) {
                font_family += ', ' + font_face_fallback;
            } else {
                font_family = font_face_fallback;
            }
            $('#' + layout + '_font_sample').css(\"font-family\", font_family);
        }
        if(size_index != null) {
            var font_size = 'unset';
            if(size_index != 0) {
                font_size = font_size_mapping[size_index];
            }
            $('#' + layout + '_font_sample').css(\"font-size\", font_size);
        }
    }

    // add listeners to the font controls when the page is ready to update the
    // font_sample when they change.
    $(function() {
        $('input[name=\"v_fntf\"]').change(function() {
            do_font_sample_update(this.value, null, 'v');
        });
        $('input[name=\"v_fntf_other\"]').on('input', function() {
            $('input[name=\"v_fntf\"]').filter('[value=1]').prop('checked', true);
            var value = $('input[name=\"v_fntf\"]:checked').val();
            do_font_sample_update(value, null, 'v');
        });
        $('input[name=\"h_fntf\"]').change(function() {
            do_font_sample_update(this.value, null, 'h');
        });
        $('input[name=\"h_fntf_other\"]').on('input', function() {
            $('input[name=\"h_fntf\"]').filter('[value=1]').prop('checked', true);
            var value = $('input[name=\"h_fntf\"]:checked').val();
            do_font_sample_update(value, null, 'h');
        });
        $('#v_fnts').change(function() {
            do_font_sample_update(null, this.value, 'v');
        });
        $('#h_fnts').change(function() {
            do_font_sample_update(null, this.value, 'h');
        });
    });

    var font_face_mapping = " . json_encode(get_available_proofreading_font_faces()) . ";
    var font_size_mapping = " . json_encode(get_available_proofreading_font_sizes()) . ";
    var font_face_fallback = \"" . get_proofreading_font_family_fallback() . "\";
";
    
output_header($header, NO_STATSBAR, $theme_extra_args);
echo "<h1>$header</h1>";

echo_tabs($tabs, $selected_tab);

echo "<p>" . _("Click the ? for help on that specific preference.") . "</p>";

echo "<form action='userprefs.php' method='post'>";

echo "<input type='hidden' name='tab' value='$selected_tab'>";
// Keep remembering the URL from which the preferences where entered.
echo "<input type='hidden' name='origin' value='".attr_safe($origin)."'>\n";
echo "<input type='hidden' name='insertdb' value='true'>";
echo "<input type='hidden' name='user_id' value='$uid'>";

echo "<table class='preferences'>";

// display one of the tabs

if ($selected_tab == 1)
    echo_proofreading_tab();
else if ($selected_tab == 2 && user_is_PM())
    echo_pm_tab();
else // $selected _tab == 0 OR someone tried to access e.g. the PM-tab without being a PM.
    echo_general_tab();

echo "</table></form>\n";
echo "<br>";

// When the window loads, run all the event handlers that e.g disable preferences.
echo "\n\n<script><!--\nwindow.onload = function() { $window_onload_event };\n--></script>\n\n";

// End main code. Functions below.
// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

/*************** TO GENERATE TABS ***************/

// Produce tabs (display as an unordered list of links to non-CSS browsers)
function echo_tabs($tab_names, $selected_tab) {
    global $origin;

    echo "<div class='tabs'>";
    echo "<ul>";
    foreach (array_keys($tab_names) as $index) {
        if ($index == $selected_tab) {
            echo "<li class='current-tab'>";
        } else {
            echo "<li>";
        }
        $url = "?tab=$index&amp;origin=" . urlencode($origin);
        echo "<a href='$url'>{$tab_names[$index]}</a>";
    }
    echo "</ul>";
    echo "</div>";
    echo "<div style='clear: left;'></div>";
}

/*************** GENERAL TAB ***************/

function echo_general_tab() {
    global $uid, $pguser, $userP;
    global $userSettings;
    global $u_n;

    $options = get_locale_translation_selection_options();

    $u_intlang_options[""]=BROWSER_DEFAULT_STR;
    $u_intlang_options = array_merge($u_intlang_options, $options);

    $i_stats_privacy = array(
        PRIVACY_PUBLIC    => _("Public"),
        PRIVACY_ANONYMOUS => _("Anonymous"),
        PRIVACY_PRIVATE   => _("Private"),
    );

    $user = new User($pguser);

    echo "<tr>\n";
    show_preference(
        _('Name'), 'real_name', 'name',
        $user->real_name,
        'textfield',
        array( '100%', 'required', '' )
        // About 98% of pgdp.net's users have length(real_name) <= 20
    );
    show_blank();
    echo "</tr>\n";

    // Check for DP/forum email mismatch, warn user if not the same
    $bb_user_info = get_forum_user_details($pguser);
    $email_warning = '';
    if ( $bb_user_info["email"] != $user->email) {
        $edit_url = get_url_to_edit_profile();
        $email_warning = "<p><b>".sprintf(_("Warning: The email in your <a href='%s'>forum profile</a> is different."),$edit_url)."</b><br>";
        $email_warning .= _("Please update if necessary to ensure you receive messages as intended.")."</p>\n";
    }

    echo "<tr>\n";
    show_preference(
        _('E-mail'), 'email', 'email',
        $user->email,
        'emailfield',
        array( '100%', 'required', $email_warning )
    );
    show_preference(
        _('Interface Language'), 'u_intlang', 'intlang',
        $userP['u_intlang'],
        'dropdown',
        $u_intlang_options
    );
    echo "</tr>\n";

    echo "<tr>\n";
    show_preference(
        _('E-mail Updates'), 'email_updates', 'updates',
        $userP['email_updates'],
        'radio_group',
        array( 1 => _("Yes"), 0 => _("No") )
    );
    $theme_options = array();
    $result = mysqli_query(DPDatabase::get_connection(), "SELECT * FROM themes");
    while ($row = mysqli_fetch_array($result)) {
        $option_value = $row['unixname'];
        $option_label = $row['name'];
        $theme_options[$option_value] = $option_label;
    }
    show_preference(
        _('Theme'), 'i_theme', 'theme',
        $userP['i_theme'],
        'dropdown',
        $theme_options
    );
    echo "</tr>\n";

    echo "<tr>\n";
    show_link_as_if_preference(
        _('Password'),
        'password',
        get_reset_password_url(),
        _("Reset Password")
    );
    show_preference(
        _('Statistics Bar Alignment'), 'u_align', 'align',
        $userP['u_align'],
        'radio_group',
        array( 1 => _("Left"), 0 => _("Right") )
    );
    echo "</tr>\n";

    echo "<tr>\n";
    show_preference(
        _('Statistics'), 'u_privacy', 'privacy',
        $userP['u_privacy'],
        'dropdown',
        $i_stats_privacy
    );
    show_preference(
        _('Show Rank Neighbors'), 'u_neigh', 'neighbors',
        $userP['u_neigh'],
        'dropdown',
        $u_n
    );
    echo "</tr>\n";

    echo "<tr>\n";
    show_preference(
        _('Credits Wanted'), NULL, 'creditswanted',
        NULL,
        'credits_wanted_adhoc',
        NULL
    );
    // About 'show'/'hide': It seems better to present to the user the option
    // 'show', rather than 'hide' since 'hide: no' seems double-negated (to me).
    $show_special_colors = !$userSettings->get_boolean('hide_special_colors');
    show_preference(
        _('Show Special Colors'), 'show_special_colors', 'showspecialcolors',
        ($show_special_colors ? 'yes' : 'no'),
        'dropdown',
        array( 'yes' => _('Yes'), 'no' => _('No') )
    );
    echo "</tr>\n";

    echo "<tr>\n";
    show_preference(
        _('Credit Name'), NULL, 'creditname',
        NULL,
        'credit_name_adhoc',
        NULL
    );
    show_blank();
    echo "</tr>\n";

    echo_bottom_button_row();
}

function save_general_tab() {
    global $uid, $pguser;
    global $userSettings;

    // set users values
    $input_string_fields = array("real_name", "email", "email_updates", "i_theme", "u_intlang");
    $input_numeric_fields = array("u_align", "u_neigh", "u_privacy");

    $update_string = _create_mysql_update_string($_POST, $input_string_fields, $input_numeric_fields);
    $update_string .= ", i_prefs=1";

    $users_query=sprintf("
        UPDATE users
        SET $update_string
        WHERE u_id=$uid AND username='%s'",
        mysqli_real_escape_string(DPDatabase::get_connection(), $pguser));
    mysqli_query(DPDatabase::get_connection(), $users_query);

    // Opt-out of credits when Content-Providing (deprecated), Image Preparing, 
    // Text Preparing, Project-Managing and/or Post-Processing.
    $userSettings->set_boolean('cp_anonymous', !isset($_POST["cp_credit"]));
    $userSettings->set_boolean('ip_anonymous', !isset($_POST["ip_credit"]));
    $userSettings->set_boolean('tp_anonymous', !isset($_POST["tp_credit"]));
    $userSettings->set_boolean('pm_anonymous', !isset($_POST["pm_credit"]));
    $userSettings->set_boolean('pp_anonymous', !isset($_POST["pp_credit"]));
    // Credit Real Name, Username or Other (specify)
    $userSettings->set_value('credit_name', $_POST["credit_name"]);
    if (isset($_POST["credit_other"]))
        $userSettings->set_value('credit_other', $_POST["credit_other"]);

    $userSettings->set_boolean('hide_special_colors', $_POST["show_special_colors"]=='no');

    echo mysqli_error(DPDatabase::get_connection());
    dpsession_set_preferences_from_db();

}

/*************** PROOFREADING TAB ***************/

function echo_proofreading_tab() {
    global $userP;
    global $i_resolutions;

    // see if they already have 10 profiles, etc.
    $profiles = UserProfile::load_user_profiles($userP['u_id']);
    $pf_num = count($profiles);

    echo "<tr>\n";
    th_label_long( 6, _('Profiles') );
    echo "</tr>\n";

    echo "<tr>\n";
    show_preference(
        _('Current Profile'), 'profilename', 'profilename',
        $userP['profilename'],
        'textfield',
        array( '100%', 'required', '' )
    );
    echo "<td colspan='2' class='center-align'>";
    // show all profiles
    echo "<select name='c_profile' ID='c_profile'>";
    foreach($profiles as $profile)
    {
        echo "<option value='$profile->id'";
        if ($profile->id == $userP['u_profile']) { echo " SELECTED"; }
        echo ">$profile->profilename</option>";
    }
    echo "</select>";
    echo " <input type=\"submit\" value=\"".attr_safe(_("Switch Profiles"))."\" name=\"swProfile\">&nbsp;";
    echo "</td>";
    td_pophelp( 'switch' );
    echo "</tr>\n";

    echo "<tr>\n";
    show_preference(
        _('Screen Resolution'), 'i_res', 'screenres',
        $userP['i_res'],
        'dropdown',
        $i_resolutions
    );
    show_preference(
        _('Launch in New Window'), 'i_newwin', 'newwindow',
        $userP['i_newwin'],
        'radio_group',
        array( 1 => _("Yes"), 0 => _("No") )
    );
    echo "</tr>\n";

    echo "<tr>\n";
    show_preference(
        _('Interface Type'), 'i_type', 'facetype',
        $userP['i_type'],
        'radio_group',
        array( 0 => _("Standard"), 1 => _("Enhanced") )
    );
    show_preference(
        _('Show Toolbar'), 'i_toolbar', 'toolbar',
        $userP['i_toolbar'],
        'radio_group',
        array( 1 => _("Yes"), 0 => _("No") )
    );
    echo "</tr>\n";

    echo "<tr>\n";
    show_preference(
        _('Interface Layout'), 'i_layout', 'layout',
        $userP['i_layout'],
        'radio_group',
        array(
            1 => '<img src="tools/proofers/gfx/bt4.png" width="26" alt="'.attr_safe(_("Vertical")).'">',
            0 => '<img src="tools/proofers/gfx/bt5.png" width="26" alt="'.attr_safe(_("Horizontal")).'">'
        )
    );
    show_preference(
        _('Show Status Bar'), 'i_statusbar', 'statusbar',
        $userP['i_statusbar'],
        'radio_group',
        array( 1 => _("Yes"), 0 => _("No") )
    );
    echo "</tr>\n";

    echo "<tr>\n";
    th_label_long( 2,
        "<img src='tools/proofers/gfx/bt4.png'>" . _("Vertical Interface Preferences") );
    td_pophelp( 'vertprefs' );
    th_label_long( 2,
        "<img src='tools/proofers/gfx/bt5.png'>" . _("Horizontal Interface Preferences") );
    td_pophelp( 'horzprefs' );
    echo "</tr>\n";

    $proofreading_font_faces = get_available_proofreading_font_faces();
    echo "<tr>\n";
    show_preference(
        _('Font Face'), 'v_fntf', 'v_fontface',
        $userP['v_fntf'],
        'fontface_selection',
        @$userP['v_fntf_other']
    );
    show_preference(
        _('Font Face'), 'h_fntf', 'h_fontface',
        $userP['h_fntf'],
        'fontface_selection',
        @$userP['h_fntf_other']
    );
    echo "</tr>\n";

    $font_sample = wordwrap(sprintf("
        The lazy brown fox was puzzled by these commonly-confused
        characters: %s",
        "O0o l1iI BE3 RK"), 40, "<br>"
    );

    $proofreading_font_sizes = get_available_proofreading_font_sizes();
    echo "<tr>\n";
    $proofreading_font_sizes[0] = BROWSER_DEFAULT_STR;
    show_preference(
        _('Font Size'), 'v_fnts', 'v_fontsize',
        $userP['v_fnts'],
        'dropdown',
        $proofreading_font_sizes
    );
    show_preference(
        _('Font Size'), 'h_fnts', 'h_fontsize',
        $userP['h_fnts'],
        'dropdown',
        $proofreading_font_sizes
    );
    echo "</tr>\n";

    echo "<tr>\n";
    list( , , $font_family, $font_size) = get_user_proofreading_font(1);
    th_label(_("Font Sample"));
    echo "<td id='v_font_sample' style=\"font-family: $font_family; font-size: $font_size\">" . $font_sample . "</td>";
    td_pophelp( "font_sample" );

    list( , , $font_family, $font_size) = get_user_proofreading_font(0);
    th_label(_("Font Sample"));
    echo "<td id='h_font_sample' style=\"font-family: $font_family; font-size: $font_size\">" . $font_sample . "</td>";
    td_pophelp( "font_sample" );
    echo "</tr>\n";


    echo "<tr>\n";
    show_preference(
        _('Image Zoom'), 'v_zoom', 'v_zoom',
        $userP['v_zoom'],
        'numberfield',
        # xgettext:no-php-format
        array( '3em', 'required', _("% of 1000 pixels") )
    );
    show_preference(
        _('Image Zoom'), 'h_zoom', 'h_zoom',
        $userP['h_zoom'],
        'numberfield',
        # xgettext:no-php-format
        array( '3em', 'required', _("% of 1000 pixels") )
    );
    echo "</tr>\n";

    echo "<tr>\n";
    show_preference(
        _('Text Frame Size'), 'v_tframe', 'v_textsize',
        $userP['v_tframe'],
        'numberfield',
        # xgettext:no-php-format
        array( '3em', 'required', _("% of browser width") )
    );
    show_preference(
        _('Text Frame Size'), 'h_tframe', 'h_textsize',
        $userP['h_tframe'],
        'numberfield',
        # xgettext:no-php-format
        array( '3em', 'required', _("% of browser height") )
    );
    echo "</tr>\n";

    echo "<tr>\n";
    show_preference(
        _('Scroll Text Frame'), 'v_tscroll', 'v_scroll',
        $userP['v_tscroll'],
        'radio_group',
        array( 1 => _("Yes"), 0 => _("No") )
    );
    show_preference(
        _('Scroll Text Frame'), 'h_tscroll', 'h_scroll',
        $userP['h_tscroll'],
        'radio_group',
        array( 1 => _("Yes"), 0 => _("No") )
    );
    echo "</tr>\n";

    echo "<tr>\n";
    show_preference(
        _('Number of Text Lines'), 'v_tlines', 'v_textlines',
        $userP['v_tlines'],
        'numberfield',
        array( '3em', 'required', "" )
    );
    show_preference(
        _('Number of Text Lines'), 'h_tlines', 'h_textlines',
        $userP['h_tlines'],
        'numberfield',
        array( '3em', 'required', "" )
    );
    echo "</tr>\n";

    echo "<tr>\n";
    show_preference(
        _('Length of Text Lines'), 'v_tchars', 'v_textlength',
        $userP['v_tchars'],
        'numberfield',
        array( '3em', 'required', " "._("characters") )
    );
    show_preference(
        _('Length of Text Lines'), 'h_tchars', 'h_textlength',
        $userP['h_tchars'],
        'numberfield',
        array( '3em', 'required', " "._("characters") )
    );
    echo "</tr>\n";

    echo "<tr>\n";
    show_preference(
        _('Wrap Text'), 'v_twrap', 'v_wrap',
        $userP['v_twrap'],
        'radio_group',
        array( 1 => _("Yes"), 0 => _("No") )
    );
    show_preference(
        _('Wrap Text'), 'h_twrap', 'h_wrap',
        $userP['h_twrap'],
        'radio_group',
        array( 1 => _("Yes"), 0 => _("No") )
    );
    echo "</tr>\n";

    // buttons
    echo "<tr><td colspan='6' class='center-align'>";
    if ($userP['prefschanged']==1)
    {
        echo "<input type='submit' value='" 
            . attr_safe(_("Restore to Saved Preferences")) 
            . "' name='restorec'> &nbsp;";
    }
    echo "<input type='submit' value='" . attr_safe(_("Save Preferences"))
        . "' name='change'> &nbsp;";
    echo "<input type='submit' value='" 
        . attr_safe(_("Save Preferences and Quit"))
        . "' name='saveAndQuit'> &nbsp;";
    if ($pf_num < 10)
    {
        echo "<input type='submit' value='"
            . attr_safe(_("Save as New Profile")) 
            . "' name='mkProfile'> &nbsp;";
        echo "<input type='submit' value='" 
            . attr_safe(_("Save as New Profile and Quit"))
            . "' name='mkProfileAndQuit'> &nbsp;";
    }
    echo "<input type='submit' value='" . attr_safe(_("Quit")) 
        . "' name='quitnc'> &nbsp;";
    // Grey out the delete option if user has only one profile
    $disabled = ($pf_num <= 1) ? "disabled" : "";
    echo "<input type='submit' value='" . attr_safe(_("Delete this Profile"))
        . "' name='deletenc' $disabled>";
    echo "</td></tr>\n";
}

function save_proofreading_tab() {
    global $uid, $userP;

    $create_new_profile = FALSE;
    if(isset($_POST["mkProfile"]) || isset($_POST["mkProfileAndQuit"]))
    {
        $create_new_profile = TRUE;
    }

    $profile_fields = [
        "profilename", "v_fntf_other", "h_fntf_other", "i_res", "i_type", 
        "i_layout", "i_newwin", "i_toolbar", "i_statusbar", "v_fntf", "v_fnts",
        "v_zoom", "v_tframe", "v_tscroll", "v_tlines", "v_tchars", "v_twrap",
        "h_fntf", "h_fnts", "h_zoom", "h_tframe", "h_tscroll", "h_tlines",
        "h_tchars", "h_twrap"
    ];

    $profile = new UserProfile();
    $profile->u_ref = $uid;
    foreach($profile_fields as $field)
    {
        $profile->$field = $_POST[$field];
    }

    if (!$create_new_profile)
    {
        $profile->id = $userP['u_profile'];
    }
    $profile->save();

    // set users values
    if ($create_new_profile)
    {
        $users_query = "
            UPDATE users
            SET u_profile = $profile->id
            WHERE u_id = $uid
        ";
        mysqli_query(DPDatabase::get_connection(), $users_query);
        echo mysqli_error(DPDatabase::get_connection());
    }

    dpsession_set_preferences_from_db();
}

/*************** PM TAB ***************/

function echo_pm_tab() {

    global $userP;
    global $userSettings;

    $i_pm= array(_("All Projects"), _("Active Projects"), _("Basic Page"));

    echo "<tr>\n";
    show_preference(
        // TRANSLATORS: PM = project manager
        _('Default PM Page'), 'i_pmdefault', 'pmdefault',
        $userP['i_pmdefault'],
        'dropdown',
        $i_pm
    );
    echo "</tr>\n";

    $auto_proj_thread = $userSettings->get_boolean('auto_proj_thread');

    echo "<tr>\n";
    show_preference(
        _('Automatically watch your project threads'), 'auto_proj_thread', 'auto_thread',
        ($auto_proj_thread ? 'yes' : 'no'),
        'dropdown',
        array( 'yes' => _('Yes'), 'no' => _('No') )
    );
    echo "</tr>\n";

    $send_to_post = $userSettings->get_boolean('send_to_post'); 
 
    echo "<tr>\n"; 
    show_preference( 
        _('Automatically send your projects to the post-processing pool'), 'send_to_post', 'pmto_post', 
        ($send_to_post ? 'yes' : 'no'), 
        'dropdown', 
        array( 'yes' => _('Yes'), 'no' => _('No') ) 
    ); 
    echo "</tr>\n"; 

    echo_bottom_button_row();
}

function save_pm_tab() {
    global $uid, $pguser;
    global $userSettings;

    // set users values
    $input_string_fields = array();
    //  i_pmdefault is "Default PM Page"
    $input_numeric_fields = array("i_pmdefault");

    $update_string = _create_mysql_update_string($_POST, $input_string_fields, $input_numeric_fields);

    $users_query=sprintf("
        UPDATE users
        SET $update_string
        WHERE u_id=$uid AND username='%s'",
        mysqli_real_escape_string(DPDatabase::get_connection(), $pguser));
    mysqli_query(DPDatabase::get_connection(), $users_query);
    echo mysqli_error(DPDatabase::get_connection());

    // remember if the PM wants to be automatically signed up for email notifications of
    // replies made to their project threads

    $userSettings->set_boolean('auto_proj_thread', $_POST["auto_proj_thread"] == 'yes');
 
    // remember if the PM wants to have their projects automatically assigned 
    // to them for PP 
     
    $userSettings->set_boolean('send_to_post', $_POST["send_to_post"] == 'yes');

    dpsession_set_preferences_from_db();
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function echo_bottom_button_row()
{
    echo "<tr><td colspan='6' class='center-align'>";
    echo "<input type='submit' value='".attr_safe(_("Save Preferences"))."' name='change'> &nbsp;";
    echo "<input type='submit' value='".attr_safe(_("Save Preferences and Quit"))."' name='saveAndQuit'> &nbsp;";
    echo "<input type='submit' value='".attr_safe(_("Quit"))."' name='quitnc'>";
    echo "</td></tr>\n";
}

// ---------------------------------------------------------

function show_preference(
    $label, $field_name, $pophelp_name,
    $current_value,
    $type,
    $extras )
{
    th_label($label);

    echo "<td>";
    // This is a bit sneaky, calling a function via a non-static name.
    // (Be careful if you want to rename a function whose name starts with '_show_'.)
    $function_name = '_show_' . $type;
    $function_name( $field_name, $current_value, $extras );
    echo "</td>";

    td_pophelp( $pophelp_name );
}

// ---------------------------------------------------------

function _show_fontface_selection($field_name, $current_value, $font_other)
{
    $available_fonts = get_available_proofreading_font_faces();
    foreach($available_fonts as $index => $font_name)
    {
        if($index == 0)
            $font_name = BROWSER_DEFAULT_STR;
        if($index == 1)
            $font_name = _("Other");

        echo "<input type='radio' name='{$field_name}' value='$index'";
        if($current_value == $index)
        {
            echo " CHECKED";
        }
        echo "> $font_name";
        if($index == 1)
        {
            echo ": <input type='text' name='{$field_name}_other' value='" . attr_safe($font_other) . "'>";
        }
        echo "<br>";
    }
}

// ---------------------------------------------------------

function _show_credits_wanted_adhoc()
{
    global $userSettings;

    $cp_credit_checked = $userSettings->get_boolean('cp_anonymous') ? '' : 'checked ';
    $ip_credit_checked = $userSettings->get_boolean('ip_anonymous') ? '' : 'checked ';
    $tp_credit_checked = $userSettings->get_boolean('tp_anonymous') ? '' : 'checked ';
    $pm_credit_checked = $userSettings->get_boolean('pm_anonymous') ? '' : 'checked ';
    $pp_credit_checked = $userSettings->get_boolean('pp_anonymous') ? '' : 'checked ';

    echo "<input type='checkbox' name='cp_credit' value='yes' $cp_credit_checked> CP\n";
    echo "<input type='checkbox' name='ip_credit' value='yes' $ip_credit_checked> IP\n";
    echo "<input type='checkbox' name='tp_credit' value='yes' $tp_credit_checked> TP\n";
    if (user_is_PM())
        echo "<input type='checkbox' name='pm_credit' value='yes' $pm_credit_checked> PM\n";
    echo "<input type='checkbox' name='pp_credit' value='yes' $pp_credit_checked> PP\n";

    echo "<br>";
    echo "<a href='#' onClick=\"check_boxes(true, 'cp_credit', 'ip_credit', 'tp_credit', 'pm_credit', 'pp_credit');\">Check all</a>";
    echo " | ";
    echo "<a href='#' onClick=\"check_boxes(false, 'cp_credit', 'ip_credit', 'tp_credit', 'pm_credit', 'pp_credit');\">Uncheck all</a>";
}

// ---------------------------------------------------------

function _show_credit_name_adhoc()
// Handles 'credit_name' and 'credit_other'.
{
    global $userSettings;

    $credit_names = array('real_name', 'username', 'other');
    $credit_names_labels = array(_('Real Name'), _('Username'), _('Other') . ":");
    $credit_name_value = $userSettings->get_value('credit_name', 'real_name');
    $on_change = "f.credit_other.disabled = (t.options[t.selectedIndex].value!='other');";
    dropdown_select_values_and_labels('credit_name', $credit_name_value, $credit_names, $credit_names_labels, $on_change);
    echo " ";

    $credit_other_value = attr_safe($userSettings->get_value('credit_other', ''));
    echo "<input type='text' name='credit_other' value='$credit_other_value'>\n";
}

// The third argument should be a 'real' array.
// The labels will be displayed to the user,
// one of the values will be passed back from the browser as the selected value.
//
// The fifth (optional argument), $on_change, is used as a javascript event handler
// on the dropdown. It will be made into a function so quote marks should not
// be any problems.
// Example value: "alert('Hi'+\"!\");"
// Using this as the $on_change-argument will popup an alert displaying the string
// 'Hi!' (without quotes).
// The use of these event handlers are foremost to enable/disable certain preferences
// depending on the values set in other preferences.
//
// The event handler will also be run on page-load and in order to achieve this,
// something resembling a hack has been introduced. Always refer to the form
// as the variable f, and always use the variable t to refer to the dropdown.
// DO NOT USE this.form and this, respectively!!!
function dropdown_select_values_and_labels($field_name, $current_value, $values, $labels, $on_change='')
{
    global $event_id, $window_onload_event;

    $function_name = 'event' . ++$event_id;
    $jscode = "var f=document.forms[0];\nvar t=f.$field_name;\n$on_change";

    echo "<script><!--\nfunction $function_name() { $jscode }\n--></script>\n";

    echo "<select name='$field_name' ID='$field_name' onChange=\"$function_name()\">";
    for ($i=0;$i<count($values);$i++)
    {
        echo "<option value='$values[$i]'";
        if ($current_value == $values[$i]) { echo " SELECTED"; }
        echo ">".html_safe($labels[$i])."</option>";
    }
    echo "</select>";

    $window_onload_event .= "$function_name();\n";
}

// ---------------------------------------------------------

function _show_dropdown($field_name, $current_value, $options)
{
    echo "<select name='$field_name' ID='$field_name'>";
    foreach ( $options as $option_value => $option_label )
    {
        echo "<option value='$option_value'";
        if ($option_value == $current_value) { echo " SELECTED"; }
        echo ">$option_label</option>";
    }
    echo "</select>";
}

function _show_radio_group( $field_name, $current_value, $options )
{
    foreach ( $options as $option_value => $option_label )
    {
        echo "<input type='radio' name='$field_name' value='$option_value'";
        if ( strtolower($option_value) == strtolower($current_value) )
        {
            echo " CHECKED";
        }
        echo ">&nbsp;$option_label &nbsp;";
    }
}

function _show_text_input_field($field_type, $field_name, $current_value, $extras)
{
    list($size, $required, $rest) = $extras;
    $current_value_esc = attr_safe($current_value);
    if($field_type == "number")
        $min_attr = "min='1'";
    else
        $min_attr = "";
    echo "<input type='$field_type' style='width: ${size}' name='$field_name' value='$current_value_esc' $min_attr $required>$rest";
}

function _show_textfield( $field_name, $current_value, $extras )
{
    _show_text_input_field('text', $field_name, $current_value, $extras);
}

function _show_emailfield( $field_name, $current_value, $extras )
{
    _show_text_input_Field('email', $field_name, $current_value, $extras);
}

function _show_numberfield( $field_name, $current_value, $extras )
{
    _show_text_input_field('number', $field_name, $current_value, $extras);
}

// ---------------------------------------------------------

function show_link_as_if_preference(
    $label,
    $pophelp_name,
    $link_url,
    $link_text )
{
    th_label($label);
    echo "<td>";
    echo "<a href='$link_url'>$link_text</a>";
    echo "</td>";
    td_pophelp( $pophelp_name );
}

function show_blank()
{
    th_label( "&nbsp;" );
    echo "<td>&nbsp;</td>";
    echo "<td>&nbsp;</td>\n";
}

// ---------------------------------------------------------

function th_label( $label )
{
    echo "<th class='label'>$label</th>";
}

function th_label_long( $colspan, $label )
{
    echo "<th class='longlabel' colspan='$colspan'>$label</th>";
}

function td_pophelp( $pophelp_name )
{
    echo "<td class='center-align'>";
    echo "<b>&nbsp;<a href=\"javascript:newHelpWin('$pophelp_name');\">?</a>&nbsp;</b>";
    echo "</td>\n";
}

// ---------------------------------------------------------

function _create_mysql_update_string($source_array, $string_fields = array(), $numeric_fields = array())
// $source_array is an array such as $_REQUEST or $_POST.
// $string_fields
//    is a list of keys such that $source_array[$key] should be a string.
// $numeric_fields
//    is a list of keys such that $source_array[$key] should be a numeric value.
// This function checks that those expectations are satisfied, and constructs a
// string of column=value 'assignments' that can be used in an SQL UPDATE
// command (where each $key is assumed to be a column name). (String values
// will be properly escaped in this string.)
//
// Currently this function will set default values ("" for strings, 0 for
// numeric values) for all fields that are not within $source_array.
{
    $fields = array_merge($string_fields, $numeric_fields);

    $data = [];
    foreach($fields as $field)
    {
        $data[$field] = $source_array[$field];
    }

    return create_mysql_update_string($data, $string_fields);
}

// vim: sw=4 ts=4 expandtab
