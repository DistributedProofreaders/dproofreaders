<?
$relPath="./pinc/";
include($relPath.'dp_main.inc');
include($relPath.'html_main.inc');

$uid = $userP['user_id'];

$p_l= array('no rounds','first round','second round','both rounds');
$u_l= array('English','French','German','Spanish', 'Italian');
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

$htmlC->startHeader("User Preferences");
$htmlC->startBody(0,0,0,0);
$tb=$htmlC->startTable(0,0,0,1);
$tr=$htmlC->startTR(0,0,1);
$td1=$htmlC->startTD(2,0,4,0,"center",0,0,1);
$td2=$htmlC->startTD(1,0,0,0,"right",0,0,1);
$td3=$htmlC->startTD(0,0,0,0,"left",0,0,1);
$td4=$htmlC->startTD(1,0,2,0,"center",0,0,1);
$td5=$htmlC->startTD(0,0,4,0,"center",0,0,1);

$result=mysql_query("SELECT * FROM users WHERE id='$uid' AND username='$pguser'");
$real_name = mysql_result($result,0,"real_name");
$email = mysql_result($result,0,"email");

echo "<form action='userprefs.php' method='post'>";
echo $tb;
echo $tr.$td1;

echo "<font size=+2><b>Preferences Page for $pguser</b></font>";

echo $tr.$td2;
echo "<strong>Name:</strong>";
echo $td3;
echo "<input type='text' name='real_name' value='$real_name'>";
echo $td2;
echo "<strong>Screen Resolution:</strong>";
echo $td3;
$array = implode('|', $i_r);
dropdown_select('i_res', $userP['i_res'], $array);

echo $tr.$td2;
echo "<strong>Email:</strong>";
echo $td3;
echo "<input type='text' name='email' value='$email'>";
echo $td2;
echo "<strong>Interface Type:</strong>";
echo $td3;
radio_select('i_type', $userP['i_type'], 0, 'Standard');
radio_select('i_type', $userP['i_type'], 1, 'Enhanced');

echo $tr.$td2;
echo "<strong>Email Updates:</strong>";
echo $td3;
radio_select('email_updates', $userP['email_updates'], '1', 'Yes');
radio_select('email_updates', $userP['email_updates'], '0', 'No');
echo $td2;
echo "<strong>Interface Layout:</strong>";
echo $td3;
radio_select('i_layout', $userP['i_layout'], 0, '<img src="tools/proofers/gfx/bt5.png" width="26" alt="Horizontal">');
radio_select('i_layout', $userP['i_layout'], 1, '<img src="tools/proofers/gfx/bt4.png" width="26" alt="Vertical">');

echo $tr.$td2;
echo "<strong>Show projects from:</strong>";
echo $td3;
$array = implode('|', $p_l);
dropdown_select('u_plist', $userP['u_plist'], $array);
echo $td2;
echo "<strong>Launch in New Window:</strong>";
echo $td3;
radio_select('i_newwin', $userP['i_newwin'], 1, 'Yes');
radio_select('i_newwin', $userP['i_newwin'], 0, 'No');

echo $tr.$td2;
echo "<strong>Language:</strong>";
echo $td3;
$array = implode('|', $u_l);
dropdown_select('u_lang', $userP['u_lang'], $array);
echo $td2;
echo "<strong>Show Toolbar:</strong>";
echo $td3;
radio_select('i_toolbar', $userP['i_toolbar'], 1, 'Yes');
radio_select('i_toolbar', $userP['i_toolbar'], 0, 'No');
echo "</td></tr>";

echo $tr.$td2;
echo "<strong>Password:</strong>";
echo $td3;
echo "<a href='http://texts01.archive.org/dp/phpBB2/profile.php?mode=sendpassword'>Reset Password</a>";
echo $td2;
echo "<strong>Show Statusbar:</strong>";
echo $td3;
radio_select('i_statusbar', $userP['i_statusbar'], 1, 'Yes');
radio_select('i_statusbar', $userP['i_statusbar'], 0, 'No');
echo "</td></tr>";

echo $tr.$td4;
echo "<img src='tools/proofers/gfx/bt4.png'><b>Vertical Interface Preferences</b>";
echo $td4;
echo "<img src='tools/proofers/gfx/bt5.png'><b>Horizontal Interface Preferences</b>";

echo $tr.$td2;
echo "<strong>Font Face:</strong>";
echo $td3;
$array = implode('|', $f_f);
dropdown_select('v_fntf', $userP['v_fntf'], $array);
echo $td2;
echo "<strong>Font Face:</strong>";
echo $td3;
$array = implode('|', $f_f);
dropdown_select('h_fntf', $userP['h_fntf'], $array);
echo "</td></tr>";

echo $tr.$td2;
echo "<strong>Font Size:</strong>";
echo $td3;
$array = implode('|', $f_s);
dropdown_select('v_fnts', $userP['v_fnts'], $array);
echo $td2;
echo "<strong>Font Size:</strong>";
echo $td3;
$array = implode('|', $f_s);
dropdown_select('h_fnts', $userP['h_fnts'], $array);
echo "</td></tr>";

echo $tr.$td2;
echo "<strong>Image Zoom:</strong>";
echo $td3;
echo "<input type=\"text\" name=\"v_zoom\" value=\"{$userP['v_zoom']}\" size=\"3\">% of 1000 pixels";
echo $td2;
echo "<strong>Image Zoom:</strong>";
echo $td3;
echo "<input type=\"text\" name=\"h_zoom\" value=\"{$userP['h_zoom']}\" size=\"3\">% of 1000 pixels";
echo "</td></tr>";

echo $tr.$td2;
echo "<strong>Text Frame Size:</strong>";
echo $td3;
echo "<input type=\"text\" name=\"v_tframe\" value=\"{$userP['v_tframe']}\" size=\"3\">% of browser width";
echo $td2;
echo "<strong>Text Frame Size:</strong>";
echo $td3;
echo "<input type=\"text\" name=\"h_tframe\" value=\"{$userP['h_tframe']}\" size=\"3\">% of browser height";
echo "</td></tr>";

echo $tr.$td2;
echo "<strong>Scroll Text Frame:</strong>";
echo $td3;
radio_select('v_tscroll', $userP['v_tscroll'], 1, 'Yes');
radio_select('v_tscroll', $userP['v_tscroll'], 0, 'No');
echo $td2;
echo "<strong>Scroll Text Frame:</strong>";
echo $td3;
radio_select('h_tscroll', $userP['h_tscroll'], 1, 'Yes');
radio_select('h_tscroll', $userP['h_tscroll'], 0, 'No');
echo "</td></tr>";

echo $tr.$td2;
echo "<strong>Number of Text Lines:</strong>";
echo $td3;
echo "<input type=\"text\" name=\"v_tlines\" value=\"{$userP['v_tlines']}\" size=\"3\">";
echo $td2;
echo "<strong>Number of Text Lines:</strong>";
echo $td3;
echo "<input type=\"text\" name=\"h_tlines\" value=\"{$userP['h_tlines']}\" size=\"3\">";
echo "</td></tr>";

echo $tr.$td2;
echo "<strong>Length of Text Lines:</strong>";
echo $td3;
echo "<input type=\"text\" name=\"v_tchars\" value=\"{$userP['v_tchars']}\" size=\"3\"> characters";
echo $td2;
echo "<strong>Length of Text Lines:</strong>";
echo $td3;
echo "<input type=\"text\" name=\"h_tchars\" value=\"{$userP['h_tchars']}\" size=\"3\"> characters";
echo "</td></tr>";

echo $tr.$td2;
echo "<strong>Wrap Text:</strong>";
echo $td3;
radio_select('v_twrap', $userP['v_twrap'], 1, 'Yes');
radio_select('v_twrap', $userP['v_twrap'], 0, 'No');
echo $td2;
echo "<strong>Wrap Text:</strong>";
echo $td3;
radio_select('h_twrap', $userP['h_twrap'], 1, 'Yes');
radio_select('h_twrap', $userP['h_twrap'], 0, 'No');
echo "</td></tr>";

echo $td5;
if (isset($project) && isset($proofstate))
{echo "<input type='hidden' name='project' value='$project'>";
echo "<input type='hidden' name='proofstate' value='$proofstate'>";}
echo "<input type='hidden' name='insertdb' value='true'>";
echo "<input type='hidden' name='user_id' value='$uid'>";
if ($userP['prefschanged']==1)
{echo "<center><input type='submit' value='Restore to Saved Preferences' name='restorec'> &nbsp;";}
echo "<center><input type='submit' value='Save Preferences' name='change'> &nbsp;";
echo "<input type='submit' value='Quit' name='quitnc'></center>";
echo "</td></tr></table></form></body></html>";
} else {
$user_id = $_POST['user_id'];
$real_name = $_POST['real_name'];
$email = $_POST['email'];
$email_updates = $_POST['email_updates'];
$project_listing = $_POST['project_listing'];

$result = mysql_query("UPDATE users SET real_name='$real_name', email='$email', 
email_updates='$email_updates', u_plist='$u_plist', 
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
