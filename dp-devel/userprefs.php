<?
$relPath="./pinc/";
include($relPath.'dp_main.inc');

//TODO Make pretty
// TODO Make some of the repetitive stuff functions

$uid = $userP['user_id'];

$p_l= array('no rounds','first round','second round','both rounds');
$u_l= array('English','French','German','Spanish');
$i_r= array('640x480','800x600','1024x768','1152x864','1280x1024','1600x1200');
$f_f= array('Browser Default','Courier','Times','Arial','Lucida','Monospaced');
$f_s= array('Browser Default','8pt','9pt','10pt','11pt','12pt','13pt','14pt','15pt','16pt','18pt','20pt');

if (@$_POST["insertdb"] == "") {
$result=mysql_query("SELECT real_name, email FROM users WHERE id='$uid' AND username='$pguser'");
$real_name = mysql_result($result,0,"real_name");
$email = mysql_result($result,0,"email");
$email_updates = $userP['email_updates'];
$project_listing = $userP['project_listing'];

echo "<form action='userprefs.php' method='post'>";
echo "<center>Preferences Page</center><br><br>";
echo "<table border='0' cellpadding='0' cellspacing='0' width='100%'>";
echo "<tr>";
echo "<td width='21%'>Name:</td>";
echo "<td width='79%'><input type='text' name='real_name' value='$real_name'></td>";
echo "</tr>";
echo "<tr>";
echo "<td width='21%'>Email:</td>";
echo "<td width='79%'><input type='text' name='email' value='$email'></td>";
echo "</tr>";
echo "<tr>";
echo "<td width='21%'>Email Updates:</td>";
if ($email_updates == "0") {
echo "<td width='79%'><input type='radio' name='email_updates' value='1'>Yes&nbsp;&nbsp;<input type='radio' checked name='email_updates' value='0'>No</td>";
} else {
echo "<td width='79%'><input type='radio' checked name='email_updates' value='1'>Yes&nbsp;&nbsp;<input type='radio' name='email_updates' value='0'>No</td>";
}
echo "</tr>";
echo "<tr>";
echo "<td width='21%'>Show projects from:</td>";
echo "<td width='79%'>";
echo "<select name=\"project_listing\" ID=\"project_listing\">";
for ($i=0;$i<count($p_l);$i++)
{echo "<option value=\"$i\"";
if ($userP['project_listing']==$i)
{echo " selected";}
echo ">$p_l[$i]</option>";}
echo "</select>";
echo "</td></tr>";

echo "<tr>";
echo "<td width='21%'>Language:</td>";
echo "<td width='79%'>";
echo "<select name=\"u_lang\" ID=\"u_lang\">";
for ($i=0;$i<count($u_l);$i++)
{echo "<option value=\"$i\"";
if ($userP['u_lang']==$i)
{echo " selected";}
echo ">$u_l[$i]</option>";}
echo "</select>";
echo "</td></tr>";

echo "<tr><td colspan=\"2\" align=\"center\">Interface Preferences</td></tr>";
echo "<tr>";
echo "<td width='21%'>Screen Resolution:</td>";
echo "<td width='79%'>";
echo "<select name=\"i_res\" ID=\"i_res\">";
for ($i=0;$i<count($i_r);$i++)
{echo "<option value=\"$i\"";
if ($userP['i_res']==$i)
{echo " selected";}
echo ">$i_r[$i]</option>";}
echo "</select>";
echo "</td></tr>";

echo "<tr>";
echo "<td width='21%'>Interface Type:</td>";
if ($userP['i_type'] == "0") {
echo "<td width='79%'><input type='radio' checked name='i_type' value='0'>Standard&nbsp;&nbsp;<input type='radio' name='i_type' value='1'>Enhanced</td>";
} else {
echo "<td width='79%'><input type='radio' name='i_type' value='0'>Standard&nbsp;&nbsp;<input type='radio' checked name='i_type' value='1'>Enhanced</td>";
}
echo "</tr>";

echo "<tr>";
echo "<td width='21%'>Interface Layout:</td>";
if ($userP['i_layout'] == "0") {
echo "<td width='79%'><input type='radio' checked name='i_layout' value='0'><img src=\"tools/proofers/gfx/bt5.png\" width=\"26\" alt=\"Horizontal\">&nbsp;&nbsp;<input type='radio' name='i_layout' value='1'><img src=\"tools/proofers/gfx/bt4.png\" width=\"26\" alt=\"Vertical\"></td>";
} else {
echo "<td width='79%'><input type='radio' name='i_layout' value='0'><img src=\"tools/proofers/gfx/bt5.png\" width=\"26\" alt=\"Horizontal\">&nbsp;&nbsp;<input type='radio' checked name='i_layout' value='1'><img src=\"tools/proofers/gfx/bt4.png\" width=\"26\" alt=\"Vertical\"></td>";
}
echo "</tr>";

echo "<tr>";
echo "<td width='21%'>Launch in New Window:</td>";
if ($userP['i_newwin'] == "0") {
echo "<td width='79%'><input type='radio' name='i_newwin' value='1'>Yes&nbsp;&nbsp;<input type='radio' checked name='i_newwin' value='0'>No</td>";
} else {
echo "<td width='79%'><input type='radio' checked name='i_newwin' value='1'>Yes&nbsp;&nbsp;<input type='radio' name='i_newwin' value='0'>No</td>";
}
echo "</tr>";

echo "<tr>";
echo "<td width='21%'>Show Toolbar:</td>";
if ($userP['i_toolbar'] == "0") {
echo "<td width='79%'><input type='radio' name='i_toolbar' value='1'>Yes&nbsp;&nbsp;<input type='radio' checked name='i_toolbar' value='0'>No</td>";
} else {
echo "<td width='79%'><input type='radio' checked name='i_toolbar' value='1'>Yes&nbsp;&nbsp;<input type='radio' name='i_toolbar' value='0'>No</td>";
}
echo "</tr>";

echo "<tr>";
echo "<td width='21%'>Show Statusbar:</td>";
if ($userP['i_statusbar'] == "0") {
echo "<td width='79%'><input type='radio' name='i_statusbar' value='1'>Yes&nbsp;&nbsp;<input type='radio' checked name='i_statusbar' value='0'>No</td>";
} else {
echo "<td width='79%'><input type='radio' checked name='i_statusbar' value='1'>Yes&nbsp;&nbsp;<input type='radio' name='i_statusbar' value='0'>No</td>";
}
echo "</tr>";

echo "<tr><td colspan=\"2\" align=\"center\">Vertical Interface Preferences</td></tr>";
echo "<tr>";
echo "<td width='21%'>Font Face:</td>";
echo "<td width='79%'>";
echo "<select name=\"v_fntf\" ID=\"v_fntf\">";
for ($i=0;$i<count($f_f);$i++)
{echo "<option value=\"$i\"";
if ($userP['v_fntf']==$i)
{echo " selected";}
echo ">$f_f[$i]</option>";}
echo "</select>";
echo "</td></tr>";

echo "<tr>";
echo "<td width='21%'>Font Size:</td>";
echo "<td width='79%'>";
echo "<select name=\"v_fnts\" ID=\"v_fnts\">";
for ($i=0;$i<count($f_s);$i++)
{echo "<option value=\"$i\"";
if ($userP['v_fntf']==$i)
{echo " selected";}
echo ">$f_s[$i]</option>";}
echo "</select>";
echo "</td></tr>";

echo "<tr>";
echo "<td width='21%'>Image Zoom:</td>";
echo "<td width='79%'>";
echo "<input type=\"text\" name=\"v_zoom\" value=\"{$userP['v_zoom']}\" size=\"3\">% of 1000 pixels";
echo "</td></tr>";

echo "<tr>";
echo "<td width='21%'>Text Frame Size:</td>";
echo "<td width='79%'>";
echo "<input type=\"text\" name=\"v_tframe\" value=\"{$userP['v_tframe']}\" size=\"3\">% of browser width";
echo "</td></tr>";

echo "<tr>";
echo "<td width='21%'>Scroll Text Frame:</td>";
if ($userP['v_tscroll'] == "0") {
echo "<td width='79%'><input type='radio' name='v_tscroll' value='1'>Yes&nbsp;&nbsp;<input type='radio' checked name='v_tscroll' value='0'>No</td>";
} else {
echo "<td width='79%'><input type='radio' checked name='v_tscroll' value='1'>Yes&nbsp;&nbsp;<input type='radio' name='v_tscroll' value='0'>No</td>";
}
echo "</tr>";

echo "<tr>";
echo "<td width='21%'>Number of Text Lines:</td>";
echo "<td width='79%'>";
echo "<input type=\"text\" name=\"v_tlines\" value=\"{$userP['v_tlines']}\" size=\"3\">";
echo "</td></tr>";

echo "<tr>";
echo "<td width='21%'>Length of Text Lines:</td>";
echo "<td width='79%'>";
echo "<input type=\"text\" name=\"v_tchars\" value=\"{$userP['v_tchars']}\" size=\"3\"> characters";
echo "</td></tr>";

echo "<tr>";
echo "<td width='21%'>Wrap Text:</td>";
if ($userP['v_twrap'] == "0") {
echo "<td width='79%'><input type='radio' name='v_twrap' value='1'>Yes&nbsp;&nbsp;<input type='radio' checked name='v_twrap' value='0'>No</td>";
} else {
echo "<td width='79%'><input type='radio' checked name='v_twrap' value='1'>Yes&nbsp;&nbsp;<input type='radio' name='v_twrap' value='0'>No</td>";
}
echo "</tr>";

echo "<tr><td colspan=\"2\" align=\"center\">Horizontal Interface Preferences</td></tr>";
echo "<tr>";
echo "<td width='21%'>Font Face:</td>";
echo "<td width='79%'>";
echo "<select name=\"h_fntf\" ID=\"h_fntf\">";
for ($i=0;$i<count($f_f);$i++)
{echo "<option value=\"$i\"";
if ($userP['h_fntf']==$i)
{echo " selected";}
echo ">$f_f[$i]</option>";}
echo "</select>";
echo "</td></tr>";

echo "<tr>";
echo "<td width='21%'>Font Size:</td>";
echo "<td width='79%'>";
echo "<select name=\"h_fnts\" ID=\"h_fnts\">";
for ($i=0;$i<count($f_s);$i++)
{echo "<option value=\"$i\"";
if ($userP['h_fntf']==$i)
{echo " selected";}
echo ">$f_s[$i]</option>";}
echo "</select>";
echo "</td></tr>";

echo "<tr>";
echo "<td width='21%'>Image Zoom:</td>";
echo "<td width='79%'>";
echo "<input type=\"text\" name=\"h_zoom\" value=\"{$userP['h_zoom']}\" size=\"3\">% of 1000 pixels";
echo "</td></tr>";

echo "<tr>";
echo "<td width='21%'>Text Frame Size:</td>";
echo "<td width='79%'>";
echo "<input type=\"text\" name=\"h_tframe\" value=\"{$userP['h_tframe']}\" size=\"3\">% of browser height";
echo "</td></tr>";

echo "<tr>";
echo "<td width='21%'>Scroll Text Frame:</td>";
if ($userP['h_tscroll'] == "0") {
echo "<td width='79%'><input type='radio' name='h_tscroll' value='1'>Yes&nbsp;&nbsp;<input type='radio' checked name='h_tscroll' value='0'>No</td>";
} else {
echo "<td width='79%'><input type='radio' checked name='h_tscroll' value='1'>Yes&nbsp;&nbsp;<input type='radio' name='h_tscroll' value='0'>No</td>";
}
echo "</tr>";

echo "<tr>";
echo "<td width='21%'>Number of Text Lines:</td>";
echo "<td width='79%'>";
echo "<input type=\"text\" name=\"h_tlines\" value=\"{$userP['h_tlines']}\" size=\"3\">";
echo "</td></tr>";

echo "<tr>";
echo "<td width='21%'>Length of Text Lines:</td>";
echo "<td width='79%'>";
echo "<input type=\"text\" name=\"h_tchars\" value=\"{$userP['h_tchars']}\" size=\"3\"> characters";
echo "</td></tr>";

echo "<tr>";
echo "<td width='21%'>Wrap Text:</td>";
if ($userP['h_twrap'] == "0") {
echo "<td width='79%'><input type='radio' name='h_twrap' value='1'>Yes&nbsp;&nbsp;<input type='radio' checked name='h_twrap' value='0'>No</td>";
} else {
echo "<td width='79%'><input type='radio' checked name='h_twrap' value='1'>Yes&nbsp;&nbsp;<input type='radio' name='h_twrap' value='0'>No</td>";
}
echo "</tr>";




echo "</table>";
echo "<input type='hidden' name='insertdb' value='true'><br><br>";
echo "<input type='hidden' name='user_id' value='$uid'>";
echo "<center><input type='submit' value='Submit'></center>";
echo "</form>";
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
}
?>