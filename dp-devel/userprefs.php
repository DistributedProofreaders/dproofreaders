<?
$relPath="./pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'doctype.inc');
include_once($relPath.'v_resolution.inc');
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

include_once($relPath.'v_resolution.inc');

function radio_select($field_name, $current_value, $value, $text_name) {
  if (strtolower($current_value) == strtolower($value)) {
    echo "<input type='radio' name='$field_name' value='$value' CHECKED>$text_name&nbsp;&nbsp;";
  } else {
    echo "<input type='radio' name='$field_name' value='$value'>$text_name&nbsp;&nbsp;";
  }
}

function dropdown_select($field_name, $current_value, $array) {
  $array_list = explode('|', $array);
  echo "<select name='$field_name' ID='$field_name'>";
  for ($i=0;$i<count($array_list);$i++)  {
    echo "<option value='$i'";
    if ($current_value == $i) { echo " SELECTED"; }
    echo ">$array_list[$i]</option>";
  }
  echo "</select>";
}

$event_id = 0;
$window_onload_event= '';

// Unlike in dropdown_select, the third argument should be a 'real' array.
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
function dropdown_select_values_and_labels($field_name, $current_value, $values, $labels, $on_change='') {
  global $event_id, $window_onload_event;

  $function_name = 'event' . ++$event_id;
  $jscode = "var f=document.forms[0];\nvar t=f.$field_name;\n$on_change";

  echo "<script language='javascript'><!--\nfunction $function_name() { $jscode }\n--></script>\n";

  echo "<select name='$field_name' ID='$field_name' onChange=\"$function_name()\">";
  for ($i=0;$i<count($values);$i++)  {
    echo "<option value='$values[$i]'";
    if ($current_value == $values[$i]) { echo " SELECTED"; }
    echo ">".htmlspecialchars($labels[$i])."</option>";
  }
  echo "</select>";

  $window_onload_event .= "$function_name();\n";
}

function dropdown_select_yesno($field_name, $yes_selected) {
  echo "<select name='$field_name' ID='$field_name'>\n";
  echo "<option value='yes'";
  if ($yes_selected) { echo ' SELECTED'; }
  echo '>'._('Yes')."</option>\n";
  echo "<option value='no'";
  if (!$yes_selected) { echo ' SELECTED'; }
  echo '>'._('No')."</option>\n";
  echo "</select>\n";
}

function dropdown_select_complex($field_name, $current_value, $array, $values) {
  $array_list = explode('|', $array);
  echo "<select name='$field_name' ID='$field_name'>";
  for ($i=0;$i<count($array_list);$i++)  {
    echo "<option value='$values[$i]'";
    if ($current_value == $values[$i]) { echo " SELECTED"; }
    echo ">$array_list[$i]</option>";
  }
  echo "</select>";
}

function textfield_for_setting($setting, $default='') {
  global $userSettings;
  echo "<input type='text' name='$setting' value='".htmlspecialchars($userSettings->get_value($setting, $default), ENT_QUOTES)."' />\n";
}

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

  // Show the same tab that was just saved
  $url = "?tab=$selected_tab";
  if (isset($origin))
    $url .= '&origin=' . urlencode($origin);
  metarefresh(0, $url, _('Saving preferences'), _('Reloading current tab....'));
  exit;
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

function echo_bottom_button_row() {
  echo "<tr><td bgcolor='#ffffff' colspan='6' align='center'>";
  echo "<input type='submit' value="._("'Save Preferences'")." name='change'> &nbsp;";
  echo "<input type='submit' value="._("'Quit'")." name='quitnc'>";
  echo "</td></tr>\n";
}


/*************** GENERAL TAB ***************/

function echo_general_tab() {
  global $theme, $uid, $pguser, $userP, $reset_password_url;
  global $u_il, $u_iloc, $u_n, $i_stats, $u_l, $i_pm;
  global $userSettings;
  global $credit_names, $credit_names_labels;

  $result=mysql_query("SELECT * FROM users WHERE  u_id=$uid AND username='$pguser'");
  $real_name = mysql_result($result,0,"real_name");
  $email = mysql_result($result,0,"email");

  echo "<tr>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Name:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  echo "<input type='text' name='real_name' value='$real_name'>";
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('name');\">?</a>&nbsp;</b>";
  echo "</td>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Language:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  $array = implode('|', $u_l);
  dropdown_select('u_lang', $userP['u_lang'], $array);
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('lang');\">?</a>&nbsp;</b>";
  echo "</td>\n";
  echo "</tr>\n";

  echo "<tr>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Email:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  echo "<input type='text' name='email' value='$email'>";
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('email');\">?</a>&nbsp;</b>";
  echo "</td>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Interface Language:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  $array = implode('|', $u_il);
  dropdown_select_complex('u_intlang', $userP['u_intlang'], $array, $u_iloc);
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('intlang');\">?</a>&nbsp;</b>";
  echo "</td>\n";
  echo "</tr>\n";

  echo "<tr>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Email Updates:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  radio_select('email_updates', $userP['email_updates'], '1', _("Yes"));
  radio_select('email_updates', $userP['email_updates'], '0', _("No"));
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('updates');\">?</a>&nbsp;</b>";
  echo "</td>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Theme:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  echo "<select name='i_theme' ID='i_theme'>";
  $result = mysql_query("SELECT * FROM themes");
  while ($row = mysql_fetch_array($result)) {
	echo "<option value='".$row['unixname']."'";
	if ($row['unixname'] == $userP['i_theme']) { echo " SELECTED"; }
	echo ">".$row['name']."</option>";
  }
  echo "</select>";
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('theme');\">?</a>&nbsp;</b>";
  echo "</td>\n";
  echo "</tr>\n";

  echo "<tr>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Password:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  echo "<a href='$reset_password_url'>"._("Reset Password")."</a>";
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('password');\">?</a>&nbsp;</b>";
  echo "</td>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Statistics Bar Alignment:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  radio_select('u_align', $userP['u_align'], 1, _("Left"));
  radio_select('u_align', $userP['u_align'], 0, _("Right"));
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('align');\">?</a>&nbsp;</b>";
  echo "</td>\n";
  echo "</tr>\n";

  echo "<tr>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Statistics")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  $array = implode('|', $i_stats);
  dropdown_select('u_privacy', $userP['u_privacy'], $array);
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('privacy');\">?</a>&nbsp;</b>";
  echo "</td>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Show Rank Neighbors:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  $array = implode('|', $u_n);
  dropdown_select('u_neigh', $userP['u_neigh'], $array);
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('neighbors');\">?</a>&nbsp;</b>";
  echo "</td>\n";
  echo "</tr>\n";

  $cp_credit_checked = $userSettings->get_boolean('cp_anonymous') ? '' : 'checked ';
  $pm_credit_checked = $userSettings->get_boolean('pm_anonymous') ? '' : 'checked ';
  $pp_credit_checked = $userSettings->get_boolean('pp_anonymous') ? '' : 'checked ';

  echo "<tr>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right' valign='top' rowspan='2'>";
  echo "<strong>"._("Credits Wanted:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left' rowspan='2'>";
  echo "<input type='checkbox' name='cp_credit' value='yes' $cp_credit_checked/> CP\n";
  if (user_is_PM())
    echo "<input type='checkbox' name='pm_credit' value='yes' $pm_credit_checked/> PM\n";
  echo "<input type='checkbox' name='pp_credit' value='yes' $pp_credit_checked/> PP\n";
  echo "<br /><a href='#' onClick=\"check_boxes(true, 'cp_credit', 'pm_credit', 'pp_credit');\">Check all</a> | <a href='#' onClick=\"check_boxes(false, 'cp_credit', 'pm_credit', 'pp_credit');\">Uncheck all</a>";
  echo "</td><td bgcolor='#ffffff' align='center' valign='top' rowspan='2'><b>&nbsp;<a href=\"JavaScript:newHelpWin('creditswanted');\">?</a>&nbsp;</b>";
  echo "</td>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"."&nbsp;"."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;</b>";
  echo "</td>\n";
  echo "</tr>\n";

  echo "<tr>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"."&nbsp;"."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;</b>";
  echo "</td>\n";
  echo "</tr>\n";

  echo "<tr>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Credit Name:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  $on_change = "f.credit_other.disabled = (t.options[t.selectedIndex].value!='other');";
  dropdown_select_values_and_labels('credit_name', $userSettings->get_value('credit_name', 'real_name'), $credit_names, $credit_names_labels, $on_change);
  echo " ";
  textfield_for_setting('credit_other');
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('creditname');\">?</a>&nbsp;</b>";
  echo "</td>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"."&nbsp;"."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  echo "&nbsp;";
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;</b>";
  echo "</td>\n";
  echo "</tr>\n";

  echo_bottom_button_row();
}

function save_general_tab() {
  global $_POST, $uid, $userP, $pguser;
  global $real_name, $email, $email_updates;
  global $u_top10, $u_align, $u_neigh, $u_lang, $i_theme, $i_pmdefault, $u_intlang, $u_privacy;
  global $userSettings;
  global $cp_credit, $pm_credit, $pp_credit;
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

  // Opt-out of credits when Content-Providing, Project-Managing and/or Post-Processing.
  $userSettings->set_boolean('cp_anonymous', !isset($cp_credit));
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
  global $theme, $uid, $pguser, $userP;
  global $i_r, $p_l, $f_f, $f_s;
  global $userSettings;

  // see if they already have 10 profiles, etc.
  $pf_query=mysql_query("SELECT profilename, id FROM user_profiles WHERE u_ref='{$userP['u_id']}' ORDER BY id ASC");
  $pf_num=mysql_num_rows($pf_query);

  echo "<tr>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  // Now that we have the activity hub, which links to single-round pages,
  // this preference is no longer meaningful. But rather than remove it
  // entirely, just comment it out, in case we have to reinstate
  // multi-round pages.
  // echo "<strong>"._("Show Projects From:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  // $array = implode('|', $p_l);
  // dropdown_select('u_plist', $userP['u_plist'], $array);
  echo "</td><td bgcolor='#ffffff' align='center'>";
  // echo "<b>&nbsp;<a href=\"JavaScript:newHelpWin('showrounds');\">?</a>&nbsp;</b>";
  echo "</td>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._('Show Special Colors:')."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  // About 'show'/'hide': It seems better to present to the user the option
  // 'show', rather than 'hide' since 'hide: no' seems double-negated (to me).
  $show_special_colors = !$userSettings->get_boolean('hide_special_colors');
  dropdown_select_yesno('show_special_colors', $show_special_colors);
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('showspecialcolors');\">?</a>&nbsp;</b>";
  echo "</td>\n";
  echo "</tr>\n";

  echo "<tr>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' colspan='6' align='center'><strong>";
  echo _('Profiles');
  echo "</strong></td>";
  echo "</tr>\n";

  echo "<tr>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Current Profile:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  echo "<input type='text' name='profilename' value='{$userP['profilename']}'>";
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('profilename');\">?</a>&nbsp;</b>";
  echo "</td>\n<td bgcolor='#ffffff' colspan='2' align='center'>";
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
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('switch');\">?</a>&nbsp;</b>";
  echo "</td>\n";
  echo "</tr>\n";

  echo "<tr>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Screen Resolution:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  $array = implode('|', $i_r);
  dropdown_select('i_res', $userP['i_res'], $array);
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('screenres');\">?</a>&nbsp;</b>";
  echo "</td>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Launch in New Window:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  radio_select('i_newwin', $userP['i_newwin'], 1, _("Yes"));
  radio_select('i_newwin', $userP['i_newwin'], 0, _("No"));
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('newwindow');\">?</a>&nbsp;</b>";
  echo "</td>\n";
  echo "</tr>\n";

  echo "<tr>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Interface Type:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  radio_select('i_type', $userP['i_type'], 0, _("Standard"));
  radio_select('i_type', $userP['i_type'], 1, _("Enhanced"));
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('facetype');\">?</a>&nbsp;</b>";
  echo "</td>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Show Toolbar:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  radio_select('i_toolbar', $userP['i_toolbar'], 1, _("Yes"));
  radio_select('i_toolbar', $userP['i_toolbar'], 0, _("No"));
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('toolbar');\">?</a>&nbsp;</b>";
  echo "</td>\n";
  echo "</tr>\n";

  echo "<tr>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Interface Layout:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  radio_select('i_layout', $userP['i_layout'], 1, '<img src="tools/proofers/gfx/bt4.png" width="26" alt="'._("Vertical").'">');
  radio_select('i_layout', $userP['i_layout'], 0, '<img src="tools/proofers/gfx/bt5.png" width="26" alt="'._("Horizontal").'">');
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('layout');\">?</a>&nbsp;</b>";
  echo "</td>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Show Status Bar:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  radio_select('i_statusbar', $userP['i_statusbar'], 1, _("Yes"));
  radio_select('i_statusbar', $userP['i_statusbar'], 0, _("No"));
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('statusbar');\">?</a>&nbsp;</b>";
  echo "</td>\n";
  echo "</tr>\n";

  echo "<tr>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' colspan='2' align='center'>";
  echo "<img src='tools/proofers/gfx/bt4.png'><b>"._("Vertical Interface Preferences")."</b>";
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('vertprefs');\">?</a>&nbsp;</b>";
  echo "</td>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' colspan='2' align='center'>";
  echo "<img src='tools/proofers/gfx/bt5.png'><b>"._("Horizontal Interface Preferences")."</b>";
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('horzprefs');\">?</a>&nbsp;</b>";
  echo "</td>\n";
  echo "</tr>\n";

  echo "<tr>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Font Face:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  $array = implode('|', $f_f);
  dropdown_select('v_fntf', $userP['v_fntf'], $array);
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('v_fontface');\">?</a>&nbsp;</b>";
  echo "</td>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Font Face:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  $array = implode('|', $f_f);
  dropdown_select('h_fntf', $userP['h_fntf'], $array);
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('h_fontface');\">?</a>&nbsp;</b>";
  echo "</td>\n";
  echo "</tr>\n";

  echo "<tr>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Font Size:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  $array = implode('|', $f_s);
  dropdown_select('v_fnts', $userP['v_fnts'], $array);
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('v_fontsize');\">?</a>&nbsp;</b>";
  echo "</td>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Font Size:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  $array = implode('|', $f_s);
  dropdown_select('h_fnts', $userP['h_fnts'], $array);
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('h_fontsize');\">?</a>&nbsp;</b>";
  echo "</td>\n";
  echo "</tr>\n";

  echo "<tr>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Image Zoom:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  echo "<input type=\"text\" name=\"v_zoom\" value=\"{$userP['v_zoom']}\" size=\"3\">"._("% of 1000 pixels");
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('v_zoom');\">?</a>&nbsp;</b>";
  echo "</td>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Image Zoom:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  echo "<input type=\"text\" name=\"h_zoom\" value=\"{$userP['h_zoom']}\" size=\"3\">"._("% of 1000 pixels");
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('h_zoom');\">?</a>&nbsp;</b>";
  echo "</td>\n";
  echo "</tr>\n";

  echo "<tr>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Text Frame Size:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  echo "<input type=\"text\" name=\"v_tframe\" value=\"{$userP['v_tframe']}\" size=\"3\">"._("% of browser width");
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('v_textsize');\">?</a>&nbsp;</b>";
  echo "</td>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Text Frame Size:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  echo "<input type=\"text\" name=\"h_tframe\" value=\"{$userP['h_tframe']}\" size=\"3\">"._("% of browser height");
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('h_textsize');\">?</a>&nbsp;</b>";
  echo "</td>\n";
  echo "</tr>\n";

  echo "<tr>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Scroll Text Frame:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  radio_select('v_tscroll', $userP['v_tscroll'], 1, _("Yes"));
  radio_select('v_tscroll', $userP['v_tscroll'], 0, _("No"));
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('v_scroll');\">?</a>&nbsp;</b>";
  echo "</td>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Scroll Text Frame:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  radio_select('h_tscroll', $userP['h_tscroll'], 1, _("Yes"));
  radio_select('h_tscroll', $userP['h_tscroll'], 0, _("No"));
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('h_scroll');\">?</a>&nbsp;</b>";
  echo "</td>\n";
  echo "</tr>\n";

  echo "<tr>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Number of Text Lines:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  echo "<input type=\"text\" name=\"v_tlines\" value=\"{$userP['v_tlines']}\" size=\"3\">";
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('v_textlines');\">?</a>&nbsp;</b>";
  echo "</td>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Number of Text Lines:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  echo "<input type=\"text\" name=\"h_tlines\" value=\"{$userP['h_tlines']}\" size=\"3\">";
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('h_textlines');\">?</a>&nbsp;</b>";
  echo "</td>\n";
  echo "</tr>\n";

  echo "<tr>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Length of Text Lines:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  echo "<input type=\"text\" name=\"v_tchars\" value=\"{$userP['v_tchars']}\" size=\"3\"> "._("characters");
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('v_textlength');\">?</a>&nbsp;</b>";
  echo "</td>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Length of Text Lines:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  echo "<input type=\"text\" name=\"h_tchars\" value=\"{$userP['h_tchars']}\" size=\"3\"> "._("characters");
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('h_textlength');\">?</a>&nbsp;</b>";
  echo "</td>\n";
  echo "</tr>\n";

  echo "<tr>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Wrap Text:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  radio_select('v_twrap', $userP['v_twrap'], 1, _("Yes"));
  radio_select('v_twrap', $userP['v_twrap'], 0, _("No"));
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('v_wrap');\">?</a>&nbsp;</b>";
  echo "</td>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Wrap Text:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  radio_select('h_twrap', $userP['h_twrap'], 1, _("Yes"));
  radio_select('h_twrap', $userP['h_twrap'], 0, _("No"));
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('h_wrap');\">?</a>&nbsp;</b>";
  echo "</td>\n";
  echo "</tr>\n";

  // buttons
  echo "<tr><td bgcolor='#ffffff' colspan='6' align='center'>";
  if ($userP['prefschanged']==1)
  {
    echo "<input type='submit' value="._("'Restore to Saved Preferences'")." name='restorec'> &nbsp;";
  }
  echo "<input type='submit' value="._("'Save Preferences'")." name='change'> &nbsp;";
  if ($pf_num < 10)
  {
    echo "<input type='submit' value="._("'Save as New Profile'")." name='mkProfile'> &nbsp;";
  }
  echo "<input type='submit' value="._("'Quit'")." name='quitnc'>";
  echo "</td></tr>\n";
}

function save_proofreading_tab() {
  global $mkProfile, $userP, $uid, $pguser, $db_link;
  global $u_plist;
  global $profilename, $i_res, $i_type, $i_layout, $i_newwin, $i_toolbar, $i_statusbar;
  global $v_fntf, $v_fnts, $v_zoom, $v_tframe, $v_tscroll, $v_tlines, $v_tchars, $v_twrap;
  global $h_fntf, $h_fnts, $h_zoom, $h_tframe, $h_tscroll, $h_tlines, $h_tchars, $h_twrap;
  global $userSettings;
  global $show_special_colors;

  // set/create user_profile values
  if (isset($mkProfile))
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
  if (!isset($mkProfile))
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
  if (isset($mkProfile))
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

  global $theme, $userP;
  global $i_pm;
  global $userSettings;

  echo "<tr>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Default PM Page:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  $array = implode('|', $i_pm);
  dropdown_select('i_pmdefault', $userP['i_pmdefault'], $array);
  echo "</td><td bgcolor='#ffffff' align='center'>";
  echo "<b>&nbsp;<a href=\"JavaScript:newHelpWin('pmdefault');\">?</a>&nbsp;</b>";
  echo "</td>\n";
  echo "<td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._('Automatically watch your project threads:')."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  $auto_proj_thread = $userSettings->get_boolean('auto_proj_thread');
  dropdown_select_yesno('auto_proj_thread', $auto_proj_thread);
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('auto_thread');\">?</a>&nbsp;</b>";
  echo "</td>\n";
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
?>
