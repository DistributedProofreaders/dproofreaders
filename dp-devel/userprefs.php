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

$uid = $userP['u_id'];

// see if they already have 10 profiles, etc.
    $pf_query=mysql_query("SELECT profilename, id FROM user_profiles WHERE u_ref='{$userP['u_id']}' ORDER BY id ASC");
    $pf_num=mysql_num_rows($pf_query);

if (isset($swProfile))
  {
    // get profile from database
    $curProfile=mysql_query("UPDATE users SET u_profile='$c_profile' WHERE  u_id=$uid  AND username='$pguser'");
    dpsession_set_preferences_from_db();
    $eURL="userprefs.php";
    if (isset($project) && isset($proofstate))
      {$eURL.="?project=$project&proofstate=$proofstate";}
    metarefresh(0,$eURL,'Profile Selection','Loading Selected Profile....');
    exit;
  }

include_once($relPath.'v_resolution.inc');

function radio_select($db_name, $db_value, $value, $text_name) {
  if (strtolower($db_value) == strtolower($value)) {
    echo "<input type='radio' name='$db_name' value='$value' CHECKED>$text_name&nbsp;&nbsp;";
  } else {
    echo "<input type='radio' name='$db_name' value='$value'>$text_name&nbsp;&nbsp;";
  }
}

function dropdown_select($db_name, $db_value, $array) {
  $array_list = explode('|', $array);
  echo "<select name='$db_name' ID='$db_name'>";
  for ($i=0;$i<count($array_list);$i++)  {
    echo "<option value='$i'";
    if ($db_value == $i) { echo " SELECTED"; }
    echo ">$array_list[$i]</option>";
  }
  echo "</select>";
}

function dropdown_select_complex($db_name, $db_value, $array, $values) {
  $array_list = explode('|', $array);
  echo "<select name='$db_name' ID='$db_name'>";
  for ($i=0;$i<count($array_list);$i++)  {
    echo "<option value='$values[$i]'";
    if ($db_value == $values[$i]) { echo " SELECTED"; }
    echo ">$array_list[$i]</option>";
  }
  echo "</select>";
}

if (isset($project) && isset($proofstate))
{
    $eURL = "tools/proofers/projects.php?project=$project&proofstate=$proofstate";
}
else
{
    $eURL = "activity_hub.php";
}

//just a way to get them back to someplace on quit button
if (isset($quitnc))
{
  metarefresh(0, $eURL, "Quit", "");
  exit;
}

// restore session values from db
if (isset($restorec))
{
  dpsession_set_preferences_from_db();
  metarefresh(0, $eURL, "Restore", "");
  exit;
}

if (@$_POST["insertdb"] == "") {
  $header = _("Personal Preferences");
  theme($header, "header");
  echo "<br><center>";
  $popHelpDir="$code_url/faq/pophelp/prefs/set_";
  include($relPath.'js_newpophelp.inc');

  $result=mysql_query("SELECT * FROM users WHERE  u_id=$uid AND username='$pguser'");
  $real_name = mysql_result($result,0,"real_name");
  $email = mysql_result($result,0,"email");

  echo "<form action='userprefs.php' method='post'>";
  echo "<table bgcolor='#ffffff' border='1' bordercolor='#111111' cellspacing='0' cellpadding='0' style='border-collapse: collapse'>";
  echo "<tr><td bgcolor='".$theme['color_headerbar_bg']."' colspan='6' align='center'>";

  echo "<font size=\"+2\" color='".$theme['color_headerbar_font']."'><b>"._("Preferences Page for ")."$pguser</font></b><br><font color='".$theme['color_headerbar_font']."'><i>"._("(click the ? for help on that specific preference)")."</font></i>";

  echo "</td></tr><tr><td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Current Profile:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  echo "<input type='text' name='profilename' value='{$userP['profilename']}'>";
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('profilename');\">?</a>&nbsp;</b>";
  echo "</td><td bgcolor='#ffffff' colspan='2' align='center'>";
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
  echo "</td></tr><tr><td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Name:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  echo "<input type='text' name='real_name' value='$real_name'>";
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('name');\">?</a>&nbsp;</b>";
  echo "</td><td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Screen Resolution:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  $array = implode('|', $i_r);
  dropdown_select('i_res', $userP['i_res'], $array);
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('screenres');\">?</a>&nbsp;</b>";

  echo "</td></tr><tr><td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Email:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  echo "<input type='text' name='email' value='$email'>";
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('email');\">?</a>&nbsp;</b>";
  echo "</td><td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Interface Type:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  radio_select('i_type', $userP['i_type'], 0, _("Standard"));
  radio_select('i_type', $userP['i_type'], 1, _("Enhanced"));
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('facetype');\">?</a>&nbsp;</b>";

  echo "</td></tr><tr><td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Email Updates:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  radio_select('email_updates', $userP['email_updates'], '1', _("Yes"));
  radio_select('email_updates', $userP['email_updates'], '0', _("No"));
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('updates');\">?</a>&nbsp;</b>";
  echo "</td><td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Interface Layout:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  radio_select('i_layout', $userP['i_layout'], 1, '<img src="tools/proofers/gfx/bt4.png" width="26" alt="'._("Vertical").'">');
  radio_select('i_layout', $userP['i_layout'], 0, '<img src="tools/proofers/gfx/bt5.png" width="26" alt="'._("Horizontal").'">');
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('layout');\">?</a>&nbsp;</b>";

  echo "</td></tr><tr><td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Show Projects From:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  $array = implode('|', $p_l);
  dropdown_select('u_plist', $userP['u_plist'], $array);
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('showrounds');\">?</a>&nbsp;</b>";
  echo "</td><td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Launch in New Window:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  radio_select('i_newwin', $userP['i_newwin'], 1, _("Yes"));
  radio_select('i_newwin', $userP['i_newwin'], 0, _("No"));
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('newwindow');\">?</a>&nbsp;</b>";

  echo "</td></tr><tr><td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Language:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  $array = implode('|', $u_l);
  dropdown_select('u_lang', $userP['u_lang'], $array);
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('lang');\">?</a>&nbsp;</b>";
  echo "</td><td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Show Toolbar:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  radio_select('i_toolbar', $userP['i_toolbar'], 1, _("Yes"));
  radio_select('i_toolbar', $userP['i_toolbar'], 0, _("No"));
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('toolbar');\">?</a>&nbsp;</b>";

  echo "</td></tr><tr><td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Password:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  echo "<a href='$reset_password_url'>"._("Reset Password")."</a>";
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('password');\">?</a>&nbsp;</b>";
  echo "</td><td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Show Status Bar:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  radio_select('i_statusbar', $userP['i_statusbar'], 1, _("Yes"));
  radio_select('i_statusbar', $userP['i_statusbar'], 0, _("No"));
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('statusbar');\">?</a>&nbsp;</b>";

  echo "</td></tr><tr><td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Show Rank Neighbors:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  $array = implode('|', $u_n);
  dropdown_select('u_neigh', $userP['u_neigh'], $array);
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('neighbors');\">?</a>&nbsp;</b>";
  echo "</td><td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Show Top 10:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  radio_select('u_top10', $userP['u_top10'], 1, _("Yes"));
  radio_select('u_top10', $userP['u_top10'], 0, _("No"));
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('topten');\">?</a>&nbsp;</b>";

  echo "</td></tr><tr><td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
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
  echo "</td><td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Statistics Bar Alignment:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  radio_select('u_align', $userP['u_align'], 1, _("Left"));
  radio_select('u_align', $userP['u_align'], 0, _("Right"));
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('align');\">?</a>&nbsp;</b>";

  echo "</td></tr><tr><td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Interface Language:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  $array = implode('|', $u_il);
  dropdown_select_complex('u_intlang', $userP['u_intlang'], $array, $u_iloc);
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('intlang');\">?</a>&nbsp;</b>";
  echo "</td><td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  $user_is_PM = user_is_PM();
  if ($user_is_PM) {
    echo "<strong>"._("Default PM Page:")."</strong>";
  } else {
    echo "";
  }
  echo "</td><td bgcolor='#ffffff' align='left'>";
  $array = implode('|', $i_pm);
  if ($user_is_PM) {
    dropdown_select('i_pmdefault', $userP['i_pmdefault'], $array);
  } else {
    echo "<input type='hidden' name='i_pmdefault' value='".$userP['i_pmdefault']."'>";
  }
  echo "</td><td bgcolor='#ffffff' align='center'>";
  if ($user_is_PM) {
    echo "<b>&nbsp;<a href=\"JavaScript:newHelpWin('pmdefault');\">?</a>&nbsp;</b>";
  } else {
    echo "";
  }

  echo "</td></tr><tr><td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Statistics")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  $array = implode('|', $i_stats);
  dropdown_select('u_privacy', $userP['u_privacy'], $array);
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('privacy');\">?</a>&nbsp;</b>";
  echo "</td><td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "&nbsp;";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  echo "&nbsp;";
  echo "</td><td bgcolor='#ffffff' align='center'>";

  echo "</td></tr><tr><td bgcolor='".$theme['color_logobar_bg']."' colspan='2' align='center'>";
  echo "<img src='tools/proofers/gfx/bt4.png'><b>"._("Vertical Interface Preferences")."</b>";
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('vertprefs');\">?</a>&nbsp;</b>";
  echo "</td><td bgcolor='".$theme['color_logobar_bg']."' colspan='2' align='center'>";
  echo "<img src='tools/proofers/gfx/bt5.png'><b>"._("Horizontal Interface Preferences")."</b>";
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('horzprefs');\">?</a>&nbsp;</b>";

  echo "</td></tr><tr><td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Font Face:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  $array = implode('|', $f_f);
  dropdown_select('v_fntf', $userP['v_fntf'], $array);
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('v_fontface');\">?</a>&nbsp;</b>";
  echo "</td><td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Font Face:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  $array = implode('|', $f_f);
  dropdown_select('h_fntf', $userP['h_fntf'], $array);
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('h_fontface');\">?</a>&nbsp;</b>";

  echo "</td></tr><tr><td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Font Size:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  $array = implode('|', $f_s);
  dropdown_select('v_fnts', $userP['v_fnts'], $array);
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('v_fontsize');\">?</a>&nbsp;</b>";
  echo "</td><td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Font Size:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  $array = implode('|', $f_s);
  dropdown_select('h_fnts', $userP['h_fnts'], $array);
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('h_fontsize');\">?</a>&nbsp;</b>";

  echo "</td></tr><tr><td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Image Zoom:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  echo "<input type=\"text\" name=\"v_zoom\" value=\"{$userP['v_zoom']}\" size=\"3\">"._("% of 1000 pixels");
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('v_zoom');\">?</a>&nbsp;</b>";
  echo "</td><td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Image Zoom:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  echo "<input type=\"text\" name=\"h_zoom\" value=\"{$userP['h_zoom']}\" size=\"3\">"._("% of 1000 pixels");
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('h_zoom');\">?</a>&nbsp;</b>";

  echo "</td></tr><tr><td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Text Frame Size:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  echo "<input type=\"text\" name=\"v_tframe\" value=\"{$userP['v_tframe']}\" size=\"3\">"._("% of browser width");
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('v_textsize');\">?</a>&nbsp;</b>";
  echo "</td><td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Text Frame Size:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  echo "<input type=\"text\" name=\"h_tframe\" value=\"{$userP['h_tframe']}\" size=\"3\">"._("% of browser height");
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('h_textsize');\">?</a>&nbsp;</b>";

  echo "</td></tr><tr><td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Scroll Text Frame:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  radio_select('v_tscroll', $userP['v_tscroll'], 1, _("Yes"));
  radio_select('v_tscroll', $userP['v_tscroll'], 0, _("No"));
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('v_scroll');\">?</a>&nbsp;</b>";
  echo "</td><td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Scroll Text Frame:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  radio_select('h_tscroll', $userP['h_tscroll'], 1, _("Yes"));
  radio_select('h_tscroll', $userP['h_tscroll'], 0, _("No"));
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('h_scroll');\">?</a>&nbsp;</b>";

  echo "</td></tr><tr><td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Number of Text Lines:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  echo "<input type=\"text\" name=\"v_tlines\" value=\"{$userP['v_tlines']}\" size=\"3\">";
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('v_textlines');\">?</a>&nbsp;</b>";
  echo "</td><td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Number of Text Lines:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  echo "<input type=\"text\" name=\"h_tlines\" value=\"{$userP['h_tlines']}\" size=\"3\">";
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('h_textlines');\">?</a>&nbsp;</b>";

  echo "</td></tr><tr><td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Length of Text Lines:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  echo "<input type=\"text\" name=\"v_tchars\" value=\"{$userP['v_tchars']}\" size=\"3\"> "._("characters");
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('v_textlength');\">?</a>&nbsp;</b>";
  echo "</td><td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Length of Text Lines:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  echo "<input type=\"text\" name=\"h_tchars\" value=\"{$userP['h_tchars']}\" size=\"3\"> "._("characters");
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('h_textlength');\">?</a>&nbsp;</b>";

  echo "</td></tr><tr><td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Wrap Text:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  radio_select('v_twrap', $userP['v_twrap'], 1, _("Yes"));
  radio_select('v_twrap', $userP['v_twrap'], 0, _("No"));
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('v_wrap');\">?</a>&nbsp;</b>";
  echo "</td><td bgcolor='".$theme['color_logobar_bg']."' align='right'>";
  echo "<strong>"._("Wrap Text:")."</strong>";
  echo "</td><td bgcolor='#ffffff' align='left'>";
  radio_select('h_twrap', $userP['h_twrap'], 1, _("Yes"));
  radio_select('h_twrap', $userP['h_twrap'], 0, _("No"));
  echo "</td><td bgcolor='#ffffff' align='center'><b>&nbsp;<a href=\"JavaScript:newHelpWin('h_wrap');\">?</a>&nbsp;</b>";

  echo "</td></tr><tr><td bgcolor='#ffffff' colspan='6' align='center'>";
  if (isset($project) && isset($proofstate))
  {
    echo "<input type='hidden' name='project' value='$project'>";
    echo "<input type='hidden' name='proofstate' value='$proofstate'>";
  }
  echo "<input type='hidden' name='insertdb' value='true'>";
  echo "<input type='hidden' name='user_id' value='$uid'>";
  if ($userP['prefschanged']==1)
  {
    echo "<center><input type='submit' value="._("'Restore to Saved Preferences'")." name='restorec'> &nbsp;";
  }
  echo "<center><input type='submit' value="._("'Save Preferences'")." name='change'> &nbsp;";
  if ($pf_num < 10)
  {
    echo "<input type='submit' value="._("'Save as New Profile'")." name='mkProfile'> &nbsp;";
  }
  echo "<input type='submit' value="._("'Quit'")." name='quitnc'></center>";
  echo "</td></tr></table></form>";
  echo "<br></center>";
  theme("", "footer");
} else {
  $user_id = $_POST['user_id'];
  $real_name = $_POST['real_name'];
  $email = $_POST['email'];
  $email_updates = $_POST['email_updates'];
  $project_listing = $_POST['u_plist'];

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
  $users_query="UPDATE users SET real_name='$real_name', email='$email',
  email_updates='$email_updates', u_plist='$u_plist', u_top10='$u_top10', u_align='$u_align', u_neigh='$u_neigh',
  u_lang='$u_lang' , i_prefs='1', i_theme='$i_theme', i_pmdefault='$i_pmdefault', u_intlang='$u_intlang', u_privacy='$u_privacy'";
  if (isset($mkProfile))
  {
    $users_query.=", u_profile='".mysql_insert_id($db_link)."'";
  }
  $users_query.=" WHERE  u_id=$uid AND username='$pguser'";
  $result = mysql_query($users_query);

  echo mysql_error();
  dpsession_set_preferences_from_db();

  metarefresh(0, $eURL, "Save", "");
}
?>