<?
$relPath="./pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'doctype.inc');
include_once($relPath.'resolution.inc');
include_once($relPath.'prefs_options.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'tabs.inc');
include_once($relPath.'SettingsClass.inc');
include_once($relPath.'misc.inc'); // startswith(...)

// The url the user viewed immediately before coming to the preferences.
// Not all browsers provide this, though.
// If the user came to userprefs.php by entering the URL manually,
// $origin will be uninitialized, in which case it could be set
// to ".../userprefs.php..." at the next calls. Avoid this.
if (!isset($origin)
        && array_key_exists('HTTP_REFERER', $_SERVER)
        && !startswith($_SERVER['HTTP_REFERER'], "$code_url/userprefs.php"))
    $origin = $_SERVER['HTTP_REFERER'];
// From now on, keep the value of $origin through the browsing of tabs, saving prefs, etc.

$uid = $userP['u_id'];

$userSettings = Settings::get_Settings($pguser);

if (isset($swProfile))
{
    // User clicked "Switch profile"
    // get profile from database
    $curProfile=mysql_query("UPDATE users SET u_profile='$c_profile' WHERE  u_id=$uid  AND username='$pguser'");
    dpsession_set_preferences_from_db();
    $eURL="userprefs.php?tab=$tab";
    if (isset($origin))
        $eURL .= '&origin=' . urlencode($origin);
    metarefresh(0,$eURL,_('Profile Selection'),_('Loading Selected Profile....'));
    exit;
}

include_once($relPath.'resolution.inc');

$event_id = 0;
$window_onload_event= '';

$eURL = isset($origin) ? $origin : 'activity_hub.php';

//just a way to get them back to someplace on quit button
if (isset($quitnc))
{
    metarefresh(0, $eURL, _("Quit"), "");
    exit;
}

// restore session values from db
if (isset($restorec))
{
    dpsession_set_preferences_from_db();
    metarefresh(0, $eURL, _("Restore"), "");
    exit;
}

// Note that these indices are used in two if-else-clauses below
$tabs = array(0 => _('General'),
    1 => _('Proofreading'));
if (user_is_PM())
    $tabs[2] = _('Project managing');

$selected_tab = (isset($_REQUEST['tab']) && array_key_exists($_REQUEST['tab'], $tabs))
    ? $_REQUEST['tab'] : 0;

if (@$_POST["insertdb"] != "") {
    // one of the tabs was displayed and now it has been posted
    // determine which and let that tab save 'itself'.

    if ($selected_tab == 0)
        save_general_tab();
    else if ($selected_tab == 1)
        save_proofreading_tab();
    else if ($selected_tab == 2)
        save_pm_tab();

    if (isset($saveAndQuit) || isset($mkProfileAndQuit))
    {
        // Quit immediately after saving
        metarefresh(0, $eURL, _("Quit"), "");
        exit;
    }
    else
    {
        // Show the same tab that was just saved
        $url = "?tab=$selected_tab";
        if (isset($origin))
            $url .= '&origin=' . urlencode($origin);
        metarefresh(0, $url, _('Saving preferences'), _('Reloading current tab....'));
        exit;
    }
}

// header, start of table, form, etc. common to all tabs
$header = _("Personal Preferences");
theme($header, "header");
echo_stylesheet_for_tabs();
echo "<br><center>";
$popHelpDir="$code_url/faq/pophelp/prefs/set_";
include($relPath.'js_newpophelp.inc');

?>
<script language='javascript'><!--
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
            eval("if (f."+name+") f."+name+".checked=value");
        }
    }
// --></script>
<?
echo "<form action='userprefs.php' method='post'>";
echo "<table width='90%' bgcolor='#ffffff' border='1' bordercolor='#111111' cellspacing='0' cellpadding='0' style='border-collapse: collapse'>";

echo "<tr><td bgcolor='".$theme['color_headerbar_bg']."' colspan='6' align='center'>";

echo "<font size=\"+2\" color='".$theme['color_headerbar_font']."'><b>"._("Preferences Page for ")."$pguser</font></b>\n";
echo "<br><font color='".$theme['color_headerbar_font']."'><i>"._("Your preferences are grouped into tabs. Switch between the tabs by clicking on e.g. 'General' or 'Proofreading'.")."</font></i>\n";
echo "<br><font color='".$theme['color_headerbar_font']."'><i>"._("(click the ? for help on that specific preference)")."</font></i></td></tr>";

echo_tabs($tabs, $selected_tab);

echo "<input type='hidden' name='tab' value='$selected_tab' />";

// display one of the tabs

if ($selected_tab == 1)
    echo_proofreading_tab();
else if ($selected_tab == 2 && user_is_PM())
    echo_pm_tab();
else // $selected _tab == 0 OR someone tried to access e.g. the PM-tab without being a PM.
    echo_general_tab();

// Keep remembering the URL from which the preferences where entered.
if (isset($origin))
    echo "<input type='hidden' name='origin' value='".htmlspecialchars($origin, ENT_QUOTES)."' />\n";

echo "<input type='hidden' name='insertdb' value='true'>";
echo "<input type='hidden' name='user_id' value='$uid'>";

echo "</table></form>\n";
echo "<br></center>";

// When the window loads, run all the event handlers that e.g disable preferences.
echo "\n\n<script language='javascript'><!--\nwindow.onload = function() \{$window_onload_event};\n--></script>\n\n";

theme("", "footer");

// End main code. Functions below.
// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

/*************** TO GENERATE TABS ***************/

// Produce tabs (display as an unordered list of links to non-CSS browsers)
function echo_tabs($tab_names, $selected_tab) {
    global $origin;

    echo "<tr><td colspan='6' align='left'>\n";
    echo "  <div id='tabs'>\n    <ul>\n";
    foreach (array_keys($tab_names) as $index) {
        if ($index == $selected_tab) {
            echo "<li id='current'>";
        } else {
            echo "<li>";
        }
        $url = "?tab=$index";
        if (isset($origin))
            $url .= '&origin=' . urlencode($origin);
        echo "<a href='$url'>{$tab_names[$index]}</a></li>\n";
    }
    echo "    </ul>\n  </div>\n</td></tr>\n";
}

/*************** GENERAL TAB ***************/

function echo_general_tab() {
    global $uid, $pguser, $userP, $reset_password_url;
    global $u_intlang_options, $u_n, $i_stats, $u_l, $i_pm;

    $result=mysql_query("SELECT * FROM users WHERE  u_id=$uid AND username='$pguser'");
    $real_name = mysql_result($result,0,"real_name");
    $email = mysql_result($result,0,"email");

    echo "<tr>\n";
    show_preference(
        _('Name'), 'real_name', 'name',
        $real_name,
        'textfield',
        array( '', '' )
    );
    show_preference(
        _('Language'), 'u_lang', 'lang',
        $userP['u_lang'],
        'dropdown',
        $u_l
    );
    echo "</tr>\n";

    echo "<tr>\n";
    show_preference(
        _('Email'), 'email', 'email',
        $email,
        'textfield',
        array( '', '' )
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
        _('Email Updates'), 'email_updates', 'updates',
        $userP['email_updates'],
        'radio_group',
        array( 1 => _("Yes"), 0 => _("No") )
    );
    $theme_options = array();
    $result = mysql_query("SELECT * FROM themes");
    while ($row = mysql_fetch_array($result)) {
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
        $reset_password_url,
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
        $i_stats
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
    show_blank();
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
    global $_POST, $uid, $userP, $pguser;
    global $real_name, $email, $email_updates;
    global $u_top10, $u_align, $u_neigh, $u_lang, $i_theme, $i_pmdefault, $u_intlang, $u_privacy;
    global $userSettings;
    global $cp_credit, $ip_credit, $tp_credit, $pm_credit, $pp_credit;
    global $credit_name, $credit_other;

    $user_id = $_POST['user_id'];
    $real_name = $_POST['real_name'];
    $email = $_POST['email'];
    $email_updates = $_POST['email_updates'];

    // set users values
    $users_query="UPDATE users SET real_name='$real_name', email='$email',
    email_updates='$email_updates', u_align='$u_align', u_neigh='$u_neigh', u_lang='$u_lang' ,
    i_prefs='1', i_theme='$i_theme', u_intlang='$u_intlang', u_privacy='$u_privacy'
    WHERE  u_id=$uid AND username='$pguser'";
    $result = mysql_query($users_query);

    // Opt-out of credits when Content-Providing (deprecated), Image Preparing, 
    // Text Preparing, Project-Managing and/or Post-Processing.
    $userSettings->set_boolean('cp_anonymous', !isset($cp_credit));
    $userSettings->set_boolean('ip_anonymous', !isset($ip_credit));
    $userSettings->set_boolean('tp_anonymous', !isset($tp_credit));
    $userSettings->set_boolean('pm_anonymous', !isset($pm_credit));
    $userSettings->set_boolean('pp_anonymous', !isset($pp_credit));
    // Credit Real Name, Username or Other (specify)
    $userSettings->set_value('credit_name', $credit_name);
    if (isset($credit_other))
        $userSettings->set_value('credit_other', $credit_other);

    echo mysql_error();
    dpsession_set_preferences_from_db();

}

/*************** PROOFREADING TAB ***************/

function echo_proofreading_tab() {
    global $uid, $pguser, $userP;
    global $i_r, $p_l, $f_f, $f_s;
    global $userSettings;

    // see if they already have 10 profiles, etc.
    $pf_query=mysql_query("SELECT profilename, id FROM user_profiles WHERE u_ref='{$userP['u_id']}' ORDER BY id ASC");
    $pf_num=mysql_num_rows($pf_query);

    echo "<tr>\n";
    show_blank();
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
    td_label_long( 6, _('Profiles') );
    echo "</tr>\n";

    echo "<tr>\n";
    show_preference(
        _('Current Profile'), 'profilename', 'profilename',
        $userP['profilename'],
        'textfield',
        array( '', '' )
    );
    echo "<td bgcolor='#ffffff' colspan='2' align='center'>";
    // show all profiles
    echo "<select name='c_profile' ID='c_profile'>";
    for ($i=0;$i<$pf_num;$i++)
    {
        $pf_Dex=mysql_result($pf_query,$i,'id');
        $pf_Val=mysql_result($pf_query,$i,'profilename');
        echo "<option value=\"$pf_Dex\"";
        if ($pf_Dex == $userP['u_profile']) { echo " SELECTED"; }
        echo ">$pf_Val</option>";
    }
    echo "</select>";
    echo " <input type=\"submit\" value=\""._("Switch Profiles")."\" name=\"swProfile\">&nbsp;";
    echo "</td>";
    td_pophelp( 'switch' );
    echo "</tr>\n";

    echo "<tr>\n";
    show_preference(
        _('Screen Resolution'), 'i_res', 'screenres',
        $userP['i_res'],
        'dropdown',
        $i_r
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
            1 => '<img src="tools/proofers/gfx/bt4.png" width="26" alt="'._("Vertical").'">',
            0 => '<img src="tools/proofers/gfx/bt5.png" width="26" alt="'._("Horizontal").'">'
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
    td_label_long( 2,
        "<img src='tools/proofers/gfx/bt4.png'>" . _("Vertical Interface Preferences") );
    td_pophelp( 'vertprefs' );
    td_label_long( 2,
        "<img src='tools/proofers/gfx/bt5.png'>" . _("Horizontal Interface Preferences") );
    td_pophelp( 'horzprefs' );
    echo "</tr>\n";

    echo "<tr>\n";
    show_preference(
        _('Font Face'), 'v_fntf', 'v_fontface',
        $userP['v_fntf'],
        'dropdown',
        $f_f
    );
    show_preference(
        _('Font Face'), 'h_fntf', 'h_fontface',
        $userP['h_fntf'],
        'dropdown',
        $f_f
    );
    echo "</tr>\n";

    echo "<tr>\n";
    show_preference(
        _('Font Size'), 'v_fnts', 'v_fontsize',
        $userP['v_fnts'],
        'dropdown',
        $f_s
    );
    show_preference(
        _('Font Size'), 'h_fnts', 'h_fontsize',
        $userP['h_fnts'],
        'dropdown',
        $f_s
    );
    echo "</tr>\n";

    echo "<tr>\n";
    show_preference(
        _('Image Zoom'), 'v_zoom', 'v_zoom',
        $userP['v_zoom'],
        'textfield',
        array( 3, _("% of 1000 pixels") )
    );
    show_preference(
        _('Image Zoom'), 'h_zoom', 'h_zoom',
        $userP['h_zoom'],
        'textfield',
        array( 3, _("% of 1000 pixels") )
    );
    echo "</tr>\n";

    echo "<tr>\n";
    show_preference(
        _('Text Frame Size'), 'v_tframe', 'v_textsize',
        $userP['v_tframe'],
        'textfield',
        array( 3, _("% of browser width") )
    );
    show_preference(
        _('Text Frame Size'), 'h_tframe', 'h_textsize',
        $userP['h_tframe'],
        'textfield',
        array( 3, _("% of browser height") )
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
        'textfield',
        array( 3, "" )
    );
    show_preference(
        _('Number of Text Lines'), 'h_tlines', 'h_textlines',
        $userP['h_tlines'],
        'textfield',
        array( 3, "" )
    );
    echo "</tr>\n";

    echo "<tr>\n";
    show_preference(
        _('Length of Text Lines'), 'v_tchars', 'v_textlength',
        $userP['v_tchars'],
        'textfield',
        array( 3, " "._("characters") )
    );
    show_preference(
        _('Length of Text Lines'), 'h_tchars', 'h_textlength',
        $userP['h_tchars'],
        'textfield',
        array( 3, " "._("characters") )
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
    echo "<tr><td bgcolor='#ffffff' colspan='6' align='center'>";
    if ($userP['prefschanged']==1)
    {
        echo "<input type='submit' value="._("'Restore to Saved Preferences'")." name='restorec'> &nbsp;";
    }
    echo "<input type='submit' value="._("'Save Preferences'")." name='change'> &nbsp;";
    echo "<input type='submit' value="._("'Save Preferences and Quit'")." name='saveAndQuit'> &nbsp;";
    if ($pf_num < 10)
    {
        echo "<input type='submit' value="._("'Save as New Profile'")." name='mkProfile'> &nbsp;";
        echo "<input type='submit' value="._("'Save as New Profile and Quit'")." name='mkProfileAndQuit'> &nbsp;";
    }
    echo "<input type='submit' value="._("'Quit'")." name='quitnc'>";
    echo "</td></tr>\n";
}

function save_proofreading_tab() {
    global $mkProfile, $mkProfileAndQuit, $userP, $uid, $pguser, $db_link;
    global $u_plist;
    global $profilename, $i_res, $i_type, $i_layout, $i_newwin, $i_toolbar, $i_statusbar;
    global $v_fntf, $v_fnts, $v_zoom, $v_tframe, $v_tscroll, $v_tlines, $v_tchars, $v_twrap;
    global $h_fntf, $h_fnts, $h_zoom, $h_tframe, $h_tscroll, $h_tlines, $h_tchars, $h_twrap;
    global $userSettings;
    global $show_special_colors;

    // set/create user_profile values
    if (isset($mkProfile) || isset($mkProfileAndQuit))
    {
        $prefs_query="INSERT INTO user_profiles SET u_ref='{$userP['u_id']}', ";
    }
    else
    {
        $prefs_query="UPDATE user_profiles SET ";
    }

    $prefs_query.="profilename='$profilename', i_res='$i_res', i_type='$i_type', i_layout='$i_layout',
    i_newwin='$i_newwin', i_toolbar='$i_toolbar', i_statusbar='$i_statusbar',
    v_fntf='$v_fntf', v_fnts='$v_fnts', v_zoom='$v_zoom', v_tframe='$v_tframe', v_tscroll='$v_tscroll',
    v_tlines='$v_tlines', v_tchars='$v_tchars', v_twrap='$v_twrap',
    h_fntf='$h_fntf', h_fnts='$h_fnts', h_zoom='$h_zoom', h_tframe='$h_tframe', h_tscroll='$h_tscroll',
    h_tlines='$h_tlines', h_tchars='$h_tchars', h_twrap='$h_twrap'";
    if (!(isset($mkProfile) || isset($mkProfileAndQuit)))
    {
        $prefs_query.=" WHERE u_ref='{$userP['u_id']}' AND id='{$userP['u_profile']}'";
    }

    $result = mysql_query($prefs_query);
    echo mysql_error();

    // set users values
    /*
        u_plist is "Show projects from XXX round(s)"
    */
    $users_query="UPDATE users SET u_plist='$u_plist'";
    if (isset($mkProfile) || isset($mkProfileAndQuit))
    {
        $users_query.=", u_profile='".mysql_insert_id($db_link)."'";
    }
    $users_query .= " WHERE u_id=$uid AND username='$pguser'";
    $result = mysql_query($users_query);

    echo mysql_error();

    $userSettings->set_boolean('hide_special_colors', $show_special_colors=='no');

    dpsession_set_preferences_from_db();
}

/*************** PM TAB ***************/

function echo_pm_tab() {

    global $userP;
    global $i_pm;
    global $userSettings;

    echo "<tr>\n";
    show_preference(
        _('Default PM Page'), 'i_pmdefault', 'pmdefault',
        $userP['i_pmdefault'],
        'dropdown',
        $i_pm
    );
    $auto_proj_thread = $userSettings->get_boolean('auto_proj_thread');
    show_preference(
        _('Automatically watch your project threads'), 'auto_proj_thread', 'auto_thread',
        ($auto_proj_thread ? 'yes' : 'no'),
        'dropdown',
        array( 'yes' => _('Yes'), 'no' => _('No') )
    );
    echo "</tr>\n";

    echo "<tr><td bgcolor='#ffffff' colspan='6' align='center'>";
    echo_bottom_button_row();
    echo "</td></tr>\n";
}

function save_pm_tab() {
    global $uid, $pguser;
    global $i_pmdefault;
    global $auto_proj_thread;
    global $userSettings;

    /*
        i_pmdefault is "Default PM Page"
    */
    $users_query="UPDATE users SET i_pmdefault='$i_pmdefault'
                                WHERE u_id=$uid AND username='$pguser'";
    $result = mysql_query($users_query);

    echo mysql_error();

    // remeber if the PM wants to be automatically signed up for email notifications of
    // replies made to their project threads

    $userSettings->set_boolean('auto_proj_thread', $auto_proj_thread == 'yes');

    dpsession_set_preferences_from_db();
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function echo_bottom_button_row()
{
    echo "<tr><td bgcolor='#ffffff' colspan='6' align='center'>";
    echo "<input type='submit' value="._("'Save Preferences'")." name='change'> &nbsp;";
    echo "<input type='submit' value="._("'Save Preferences and Quit'")." name='saveAndQuit'> &nbsp;";
    echo "<input type='submit' value="._("'Quit'")." name='quitnc'>";
    echo "</td></tr>\n";
}

// ---------------------------------------------------------

function show_preference(
    $label, $field_name, $pophelp_name,
    $current_value,
    $type,
    $extras )
{
    td_label( "$label:" );

    echo "<td bgcolor='#ffffff' align='left'>";
    // This is a bit sneaky, calling a function via a non-static name.
    // (Be careful if you want to rename a function whose name starts with '_show_'.)
    $function_name = '_show_' . $type;
    $function_name( $field_name, $current_value, $extras );
    echo "</td>";

    td_pophelp( $pophelp_name );
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

    echo "<input type='checkbox' name='cp_credit' value='yes' $cp_credit_checked/> CP\n";
    echo "<input type='checkbox' name='ip_credit' value='yes' $ip_credit_checked/> IP\n";
    echo "<input type='checkbox' name='tp_credit' value='yes' $tp_credit_checked/> TP\n";
    if (user_is_PM())
        echo "<input type='checkbox' name='pm_credit' value='yes' $pm_credit_checked/> PM\n";
    echo "<input type='checkbox' name='pp_credit' value='yes' $pp_credit_checked/> PP\n";

    echo "<br />";
    echo "<a href='#' onClick=\"check_boxes(true, 'cp_credit', 'ip_credit', 'tp_credit', 'pm_credit', 'pp_credit');\">Check all</a>";
    echo " | ";
    echo "<a href='#' onClick=\"check_boxes(false, 'cp_credit', 'ip_credit', 'tp_credit', 'pm_credit', 'pp_credit');\">Uncheck all</a>";
}

// ---------------------------------------------------------

function _show_credit_name_adhoc()
// Handles 'credit_name' and 'credit_other'.
{
    global $userSettings;
    global $credit_names, $credit_names_labels;

    $credit_name_value = $userSettings->get_value('credit_name', 'real_name');
    $on_change = "f.credit_other.disabled = (t.options[t.selectedIndex].value!='other');";
    dropdown_select_values_and_labels('credit_name', $credit_name_value, $credit_names, $credit_names_labels, $on_change);
    echo " ";

    $credit_other_value = htmlspecialchars( $userSettings->get_value('credit_other', ''), ENT_QUOTES );
    echo "<input type='text' name='credit_other' value='$credit_other_value' />\n";
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

    echo "<script language='javascript'><!--\nfunction $function_name() { $jscode }\n--></script>\n";

    echo "<select name='$field_name' ID='$field_name' onChange=\"$function_name()\">";
    for ($i=0;$i<count($values);$i++)
    {
        echo "<option value='$values[$i]'";
        if ($current_value == $values[$i]) { echo " SELECTED"; }
        echo ">".htmlspecialchars($labels[$i])."</option>";
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
        echo ">$option_label&nbsp;&nbsp;";
    }
}

function _show_textfield( $field_name, $current_value, $extras )
{
    list($size, $rest) = $extras;
    echo "<input type='text' name='$field_name' value='$current_value' size='$size'>$rest";
}

// ---------------------------------------------------------

function show_link_as_if_preference(
    $label,
    $pophelp_name,
    $link_url,
    $link_text )
{
    td_label( "$label:" );
    echo "<td bgcolor='#ffffff' align='left'>";
    echo "<a href='$link_url'>$link_text</a>";
    echo "</td>";
    td_pophelp( $pophelp_name );
}

function show_blank()
{
    td_label( "&nbsp;" );
    echo "<td bgcolor='#ffffff' align='left'>&nbsp;</td>";
    echo "<td bgcolor='#ffffff' align='center'>&nbsp;</td>\n";
}

// ---------------------------------------------------------

function td_label( $label )
{
    global $theme;
    echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
    echo "<strong>$label</strong>";
    echo "</td>";
}

function td_label_long( $colspan, $label )
{
    global $theme;
    echo "<td bgcolor='".$theme['color_logobar_bg']."' colspan='$colspan' align='center'>";
    echo "<strong>$label</strong>";
    echo "</td>";
}

function td_pophelp( $pophelp_name )
{
    echo "<td bgcolor='#ffffff' align='center'>";
    echo "<b>&nbsp;<a href=\"JavaScript:newHelpWin('$pophelp_name');\">?</a>&nbsp;</b>";
    echo "</td>\n";
}

// ---------------------------------------------------------

// vim: sw=4 ts=4 expandtab
?>
