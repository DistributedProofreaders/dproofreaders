<?
$relPath="./pinc/";
include($relPath.'dp_main.inc');

$uid = $userP['user_id'];

$p_l= array('no rounds','first round','second round','both rounds');
$u_l= array('English','French','German','Spanish');
$i_r= array('640x480','800x600','1024x768','1152x864','1280x1024','1600x1200');
$f_f= array('Browser Default','Courier','Times','Arial','Lucida','Monospaced');
$f_s= array('Browser Default','8pt','9pt','10pt','11pt','12pt','13pt','14pt','15pt','16pt','18pt','20pt');

function radio_select($db_name, $db_value, $value, $text_name) {
if (strtolower($db_value) == strtolower($value)) {
echo "<input type='radio' name='$db_name' value='$value' CHECKED>$text_name&nbsp;&nbsp;";
} else {
echo "<input type='radio' name='$db_name' value='$value'>$text_name&nbsp;&nbsp;";
} }

function dropdown_select($db_name, $db_value, $array) {
$array_list = explode('|', $array);
echo "<select name='$db_name' ID='$db_name'>";
for ($i=0;$i<count($array_list);$i++)  {
echo "<option value='$i'";
if ($db_value == $i) { echo " SELECTED"; }
echo ">$array_list[$i]</option>";
} echo "</select>"; }




//just a way to get them back to someplace on quit button
if (isset($quitnc))
{
if (isset($project) && isset($proofstate))
{echo "<html><head><META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=tools/proofers/projects.php?project=$project&proofstate=$proofstate\"></head><body></body></html>"; }
else {echo "<html><head><META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=tools/proofers/proof_per.php\"></head><body></body></html>";}
exit;}

// restore cookie values from db
if (isset($restorec))
{
$cookieC->setUserPrefs($pguser);
if (isset($project) && isset($proofstate))
{echo "<html><head><META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=tools/proofers/projects.php?project=$project&proofstate=$proofstate\"></head><body></body></html>"; }
else {echo "<html><head><META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=tools/proofers/proof_per.php\"></head><body></body></html>";}
exit;}

if (@$_POST["insertdb"] == "") {
echo "<html><head><title> User Preferences</title></head><body>";
$result=mysql_query("SELECT * FROM users WHERE id='$uid' AND username='$pguser'");
$real_name = mysql_result($result,0,"real_name");
$email = mysql_result($result,0,"email");
$email_updates = $userP['email_updates'];
$project_listing = $userP['project_listing'];
$pagescompleted = mysql_result($result,0,"pagescompleted");

echo "<form action='userprefs.php' method='post'>";
echo "<center>Preferences Page for $pguser</center><br><br>";
echo "<table border='0' cellpadding='0' cellspacing='0' width='100%'>";

echo "<tr><td width='21%'>Name:</td>";
echo "<td width='79%'><input type='text' name='real_name' value='$real_name'></td></tr>";

echo "<tr><td width='21%'>Email:</td>";
echo "<td width='79%'><input type='text' name='email' value='$email'></td></tr>";

echo "<tr><td width='21%'>Email Updates:</td><td width='79%'>";
radio_select('email_updates', $email_updates, '1', 'Yes');
radio_select('email_updates', $email_updates, '0', 'No');
echo "</td></tr>";

if ($pagescompleted >= 50) {
echo "<tr><td width='21%'>Show projects from:</td><td width='79%'>";
$array = implode('|', $p_l);
dropdown_select('project_listing', $userP['project_listing'], $array);
echo "</td></tr>";
}

echo "<tr><td width='21%'>Language:</td><td width='79%'>";
$array = implode('|', $u_l);
dropdown_select('u_lang', $userP['u_lang'], $array);
echo "</td></tr>";

echo "<tr><td colspan=\"2\" align=\"center\">Interface Preferences</td></tr>";

echo "<tr><td width='21%'>Screen Resolution:</td><td width='79%'>";
$array = implode('|', $i_r);
dropdown_select('i_res', $userP['i_res'], $array);
echo "</td></tr>";

echo "<tr><td width='21%'>Interface Type:</td><td width='79%'>";
radio_select('i_type', $userP['i_type'], 0, 'Standard');
radio_select('i_type', $userP['i_type'], 1, 'Enhanced');
echo "</td></tr>";

echo "<tr><td width='21%'>Interface Layout:</td><td width='79%'>";
radio_select('i_layout', $userP['i_layout'], 0, '<img src="tools/proofers/gfx/bt5.png" width="26" alt="Horizontal">');
radio_select('i_layout', $userP['i_layout'], 1, '<img src="tools/proofers/gfx/bt4.png" width="26" alt="Vertical">');
echo "</td></tr>";

echo "<tr><td width='21%'>Launch in New Window:</td><td width='79%'>";
radio_select('i_newwin', $userP['i_newwin'], 1, 'Yes');
radio_select('i_newwin', $userP['i_newwin'], 0, 'No');
echo "</td></tr>";

echo "<tr><td width='21%'>Show Toolbar:</td><td width='79%'>";
radio_select('i_toolbar', $userP['i_toolbar'], 1, 'Yes');
radio_select('i_toolbar', $userP['i_toolbar'], 0, 'No');
echo "</td></tr>";

echo "<tr><td width='21%'>Show Statusbar:</td><td width='79%'>";
radio_select('i_statusbar', $userP['i_statusbar'], 1, 'Yes');
radio_select('i_statusbar', $userP['i_statusbar'], 0, 'No');
echo "</td></tr>";

echo "<tr><td colspan=\"2\" align=\"center\">Vertical Interface Preferences</td></tr>";

echo "<tr><td width='21%'>Font Face:</td><td width='79%'>";
$array = implode('|', $f_f);
dropdown_select('v_fntf', $userP['v_fntf'], $array);
echo "</td></tr>";

echo "<tr><td width='21%'>Font Size:</td><td width='79%'>";
$array = implode('|', $f_s);
dropdown_select('v_fnts', $userP['v_fnts'], $array);
echo "</td></tr>";

echo "<tr><td width='21%'>Image Zoom:</td><td width='79%'>";
echo "<input type=\"text\" name=\"v_zoom\" value=\"{$userP['v_zoom']}\" size=\"3\">% of 1000 pixels";
echo "</td></tr>";

echo "<tr><td width='21%'>Text Frame Size:</td><td width='79%'>";
echo "<input type=\"text\" name=\"v_tframe\" value=\"{$userP['v_tframe']}\" size=\"3\">% of browser width";
echo "</td></tr>";

echo "<tr><td width='21%'>Scroll Text Frame:</td><td width='79%'>";
radio_select('v_tscroll', $userP['v_tscroll'], 1, 'Yes');
radio_select('v_tscroll', $userP['v_tscroll'], 0, 'No');
echo "</td></tr>";

echo "<tr><td width='21%'>Number of Text Lines:</td><td width='79%'>";
echo "<input type=\"text\" name=\"v_tlines\" value=\"{$userP['v_tlines']}\" size=\"3\">";
echo "</td></tr>";

echo "<tr><td width='21%'>Length of Text Lines:</td><td width='79%'>";
echo "<input type=\"text\" name=\"v_tchars\" value=\"{$userP['v_tchars']}\" size=\"3\"> characters";
echo "</td></tr>";

echo "<tr><td width='21%'>Wrap Text:</td><td width='79%'>";
radio_select('v_twrap', $userP['v_twrap'], 1, 'Yes');
radio_select('v_twrap', $userP['v_twrap'], 0, 'No');
echo "</td></tr>";

echo "<tr><td colspan=\"2\" align=\"center\">Horizontal Interface Preferences</td></tr>";

echo "<tr><td width='21%'>Font Face:</td><td width='79%'>";
$array = implode('|', $f_f);
dropdown_select('h_fntf', $userP['h_fntf'], $array);
echo "</td></tr>";

echo "<tr><td width='21%'>Font Size:</td><td width='79%'>";
$array = implode('|', $f_s);
dropdown_select('h_fnts', $userP['h_fnts'], $array);
echo "</td></tr>";

echo "<tr><td width='21%'>Image Zoom:</td><td width='79%'>";
echo "<input type=\"text\" name=\"h_zoom\" value=\"{$userP['h_zoom']}\" size=\"3\">% of 1000 pixels";
echo "</td></tr>";

echo "<tr><td width='21%'>Text Frame Size:</td><td width='79%'>";
echo "<input type=\"text\" name=\"h_tframe\" value=\"{$userP['h_tframe']}\" size=\"3\">% of browser height";
echo "</td></tr>";

echo "<tr><td width='21%'>Scroll Text Frame:</td><td width='79%'>";
radio_select('h_tscroll', $userP['h_tscroll'], 1, 'Yes');
radio_select('h_tscroll', $userP['h_tscroll'], 0, 'No');
echo "</td></tr>";

echo "<tr><td width='21%'>Number of Text Lines:</td><td width='79%'>";
echo "<input type=\"text\" name=\"h_tlines\" value=\"{$userP['h_tlines']}\" size=\"3\">";
echo "</td></tr>";

echo "<tr><td width='21%'>Length of Text Lines:</td><td width='79%'>";
echo "<input type=\"text\" name=\"h_tchars\" value=\"{$userP['h_tchars']}\" size=\"3\"> characters";
echo "</td></tr>";

echo "<tr><td width='21%'>Wrap Text:</td><td width='79%'>";
radio_select('h_twrap', $userP['h_twrap'], 1, 'Yes');
radio_select('h_twrap', $userP['h_twrap'], 0, 'No');
echo "</td></tr>";
echo "</table>";


if (isset($project) && isset($proofstate))
{echo "<input type='hidden' name='project' value='$project'>";
echo "<input type='hidden' name='proofstate' value='$proofstate'>";}
echo "<input type='hidden' name='insertdb' value='true'><br><br>";
echo "<input type='hidden' name='user_id' value='$uid'>";
if ($userP['prefschanged']==1)
{echo "<center><input type='submit' value='Restore to Saved Preferences' name='restorec'> ";}
echo "<center><input type='submit' value='Save Preferences' name='change'>";
echo "<input type='submit' value='Quit' name='quitnc'></center>";
echo "</form></body></html>";
} else {
$user_id = $_POST['user_id'];
$real_name = $_POST['real_name'];
$email = $_POST['email'];
$email_updates = $_POST['email_updates'];
$project_listing = $_POST['project_listing'];

$result = mysql_query("UPDATE users SET real_name='$real_name', email='$email', 
email_updates='$email_updates', project_listing='$project_listing', 
u_lang='$u_lang', i_res='$i_res', i_type='$i_type', i_layout='$i_layout', 
i_newwin='$i_newwin', i_toolbar='$i_toolbar', i_statusbar='$i_statusbar', 
v_fntf='$v_fntf', v_fnts='$v_fnts', v_zoom='$v_zoom', v_tframe='$v_tframe', v_tscroll='$v_tscroll', 
v_tlines='$v_tlines', v_tchars='$v_tchars', v_twrap='$v_twrap', 
h_fntf='$h_fntf', h_fnts='$h_fnts', h_zoom='$h_zoom', h_tframe='$h_tframe', h_tscroll='$h_tscroll', 
h_tlines='$h_tlines', h_tchars='$h_tchars', h_twrap='$h_twrap'
, i_prefs='1' 
WHERE id='$user_id' AND username='$pguser'");
echo mysql_error();
$cookieC->setUserPrefs($pguser);
if (isset($project) && isset($proofstate))
{echo "<html><head><META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=tools/proofers/projects.php?project=$project&proofstate=$proofstate\"></head><body></body></html>"; }
else {echo "<html><head><META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=tools/proofers/proof_per.php\"></head><body></body></html>";}
}
?>
