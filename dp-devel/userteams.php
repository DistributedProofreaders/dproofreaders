<?
$relPath="./pinc/";
include($relPath.'dp_main.inc');
include($relPath.'html_main.inc');
include($relPath.'doctype.inc');

if (!isset($tstart))
  {$tstart=0;}

$beginPage=$docType."<html><head><title>User Teams</title></head><body><div align=\"center\">";

// see if they just want a team profile
if (isset($tid))
  {
  echo $beginPage;
  $tQuery="SELECT * FROM user_teams WHERE id=$tid";
  $tResult=mysql_query($tQuery);
  $curTeam=mysql_fetch_assoc($tResult);
  // get owner
    $ownerID=$curTeam['owner'];
    $ownerQuery=mysql_query("SELECT username from users WHERE u_id='$ownerID'");
    $ownerName=mysql_result($ownerQuery,0,'username');
  // start display
    echo "<table id=\"teamtabletop\" border=\"1\" cellpadding=\"10\" width=\"650\">".
      "<tr><td align=\"center\">".
      "<img src=\"./users/teams/icons/{$curTeam['avatar']}\" width=\"100\" height=\"100\" alt=\"{$curTeam['teamname']}\"></td>".
      "</td><td align=\"center\"><b>{$curTeam['teamname']}</b>".
      "<p>Created: ".date("l, F jS, Y \a\\t g:i:sA",$curTeam['created']);
    echo "</td></tr></table><br>";
    echo "<table id=\"teamtable\" border=\"1\" cellpadding=\"10\" width=\"650\">";
    echo "<tr><td colspan=\"2\" align=\"center\"><strong>Description</strong>";
    echo "</td></tr><tr><td colspan=\"2\">".
      stripslashes($curTeam['team_info']);
    echo "</td></tr><tr><td>".
      "Created by: <b>{$curTeam['createdby']}</b>";
    echo "</td><td>".
      "Owned by: <b>$ownerName</b>";
    // if owner, let them edit
      if ($ownerName==$pguser)
        {echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"userteams.php?etid={$curTeam['id']}\">Edit</a>";}
    echo "</td></tr><tr><td>".
      "Number of Members: <b>{$curTeam['member_count']}</b>";
    echo "</td><td>".
      "Pages Proofed: <b>{$curTeam['page_count']}</b>";
  // end display
    echo "<tr><td align=\"left\">".
      "<b><a href=\"userteams.php?tstart=$tstart\">View Teams</a>";
    echo "</td><td align=\"right\">";
    if ($curTeam['id']!=1 && $userP['team_1']!=$curTeam['id'] && $userP['team_2']!=$curTeam['id'] && $userP['team_3']!=$curTeam['id'])
      {echo "<a href=\"userteams.php?jtid={$curTeam['id']}\">Join</a></b>";}
    else if ($curTeam['id'] !=1)
      {echo "<a href=\"userteams.php?qtid={$curTeam['id']}\">Quit the Team</a></b>";}
    echo "</td></tr></table></div></body></html>";
  exit;
  } // end of show team profile

// join a team
if (isset($jtid))
  {
  $joinQ='';
  if ($userP['team_1']==0 || $otid==1)
    {$joinQ="team_1='$jtid'";}
  else if ($userP['team_2']==0 || $otid==2)
    {$joinQ="team_2='$jtid'";}
  else if ($userP['team_3']==0 || $otid==3)
    {$joinQ="team_3='$jtid'";}
  if ($joinQ=='')
  {
    // all the spaces are filled, which would the like to overwrite?
      echo $beginPage;
      echo "You have already joined three teams.<br>".
        "Which team would you like to replace?<P>";
      $teamQ1="SELECT teamname FROM user_teams WHERE id='{$userP['team_1']}'";
      $teamQ2="SELECT teamname FROM user_teams WHERE id='{$userP['team_2']}'";
      $teamQ3="SELECT teamname FROM user_teams WHERE id='{$userP['team_3']}'";
      $teamR=mysql_query($teamQ1);
      echo "<a href=\"userteams.php?qtid=$qtid&amp;otid=1\">".mysql_result($teamR,0,'teamname')."</a>&nbsp;&nbsp;&nbsp;&nbsp;";
      $teamR=mysql_query($teamQ2);
      echo "<a href=\"userteams.php?qtid=$qtid&amp;otid=2\">".mysql_result($teamR,0,'teamname')."</a>&nbsp;&nbsp;&nbsp;&nbsp;";
      $teamR=mysql_query($teamQ3);
      echo "<a href=\"userteams.php?qtid=$qtid&amp;otid=3\">".mysql_result($teamR,0,'teamname')."</a>&nbsp;&nbsp;&nbsp;&nbsp;";
      echo "<br><a href=\"../../userteams.php?tid=$gtid\">Do Not Join Team</a>";
      echo "</td></tr></table></div></body></html>";
  }  
  else
  {
    $joinQuery="UPDATE users SET ".$joinQ." WHERE username='$pguser' AND u_id='{$userP['u_id']}'";
    mysql_query($joinQuery);
    // update cookie
      $cookieC->setUserPrefs($pguser);
    metarefresh(0,"userteams.php?tid=$jtid",'Join the Team','Joining the team....');
  }
  exit;
  }

// quit a team
if (isset($qtid))
  {
  $quitQuery="UPDATE users SET ";
  if ($userP['team_1']==$qtid)
    {$quitQuery.="team_1='0'";}
  if ($userP['team_2']==$qtid)
    {$quitQuery.="team_2='0'";}
  if ($userP['team_3']==$qtid)
    {$quitQuery.="team_3='0'";}
  $quitQuery.=" WHERE username='$pguser' AND u_id='{$userP['u_id']}'";
  mysql_query($quitQuery);
  // update cookie
    $cookieC->setUserPrefs($pguser);
  metarefresh(0,"userteams.php?tid=$qtid",'Quit the Team','Quitting the team....');
  exit;
  }

// edit a team
if (isset($etid))
  {
echo $etid;
  exit;
  }

// create a team
if (isset($qtid))
  {
echo $ctid;
  exit;
  }

// show a list of teams
echo $beginPage;
// the teams table query
  $tQuery="SELECT teamname, id, icon FROM user_teams WHERE id>$tstart ORDER BY id ASC LIMIT 20";
  $tResult=mysql_query($tQuery);
  $tRows=mysql_num_rows($tResult);
  $cellCount=0;
// start display
  echo "<table id=\"teamtable\" border=\"1\" cellpadding=\"10\" width=\"650\"><tr><td colspan=\"8\" align=\"center\"><b>Teams</b></td>";
  if ($tRows!=0)
  {
    for ($i=0;$i<$tRows;$i++)
      {
        $curTeam=mysql_fetch_assoc($tResult);
        $teamIcon=$curTeam['icon'];
        $teamID=$curTeam['id'];
        $teamName=$curTeam['teamname'];
        $newRow=($i%2)==0?1:0;
        if ($newRow)
          {echo "</tr>\r\n<tr>";}
        echo "<td><img src=\"./users/teams/icons/$teamIcon\" width=\"25\" height=\"25\" alt=\"$teamName\"></td>".
          "<td><strong>$teamID</strong></td><td>$teamName</td><td>".
          "<b><a href=\"userteams.php?tid=$teamID&amp;tstart=$tstart\">View</a> &nbsp;&nbsp;&nbsp;".
          "<a href=\"userteams.php?jtid=$teamID\">Join</a></b>".
          "</td>";
        $cellCount++;
      } // end team display loop

  // finish off table
    for ($i=$cellCount;$i<20;$i++)
      {
        $newRow=($i%2)==0?1:0;
        if ($newRow)
          {echo "</tr>\r\n<tr>";}
        echo "<td>. </td><td>. </td><td>. </td><td>. </td>";
      } // end finish cells
  } // end of are there any rows to display if
  else
  {
    echo "</tr><tr><td colspan=\"8\" align=\"center\">";
    echo "<strong>No more teams available.</strong></td>";
  }
echo "</tr><tr><td colspan=\"4\">";
// show back if needed
if ($tstart!=0)
  {echo "<strong><a href=\"userteams.php?tstart=".($tstart-20)."\">Previous</a></strong>";}

echo "</td><td colspan=\"4\" align=\"right\">";
if ($tRows!=0)
  {echo "<strong><a href=\"userteams.php?tstart=".($tstart+20)."\">Next</a></strong>";}
echo "</td></tr></table></div></body></html>";








/*
$uid = $userP['user_id'];

// see if they already have 10 profiles, etc.
    $pf_query=mysql_query("SELECT profilename, id FROM user_profiles WHERE u_ref='{$userP['u_id']}' ORDER BY id ASC");
    $pf_num=mysql_num_rows($pf_query);

if (isset($swProfile))
  {
    // get profile from database
    $curProfile=mysql_query("UPDATE users SET u_profile='$c_profile' WHERE id='$user_id' AND username='$pguser'");
    $cookieC->setUserPrefs($pguser);
    include_once($relPath.'metarefresh.inc');
    $eURL="userprefs.php";
    if (isset($project) && isset($proofstate))
      {$eURL.="?project=$project&proofstate=$proofstate";}
    metarefresh(0,$eURL,'Profile Selection','Loading Selected Profile....');
    exit;
  }
$uid = $userP['user_id'];

$p_l= array('no rounds','first round','second round','both rounds');
$u_l= array('English','French','German','Spanish', 'Italian');
$i_r= array('640x480','800x600','1024x768','1152x864','1280x1024','1600x1200');
$f_f= array('Browser Default','Courier','Times','Arial','Lucida','Monospaced');
$f_s= array('Browser Default','8pt','9pt','10pt','11pt','12pt','13pt','14pt','15pt','16pt','18pt','20pt');
$u_n= array('0', '2', '4', '6', '8', '10', '12', '14', '16', '18', '20');

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
{echo "$docType<html><head><META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=tools/proofers/projects.php?project=$project&proofstate=$proofstate\"></head><body></body></html>"; }
else {echo "$docType<html><head><META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=tools/proofers/proof_per.php\"></head><body></body></html>";}
exit;}

// restore cookie values from db
if (isset($restorec))
{
$cookieC->setUserPrefs($pguser);
if (isset($project) && isset($proofstate))
{echo "$docType<html><head><META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=tools/proofers/projects.php?project=$project&proofstate=$proofstate\"></head><body></body></html>"; }
else {echo "$docType<html><head><META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=tools/proofers/proof_per.php\"></head><body></body></html>";}
exit;}

if (@$_POST["insertdb"] == "") {

$htmlC->startHeader("User Preferences");
$htmlC->startBody(0,0,0,0);
$tb=$htmlC->startTable(0,0,0,1);
$tr=$htmlC->startTR(0,0,1);
$td1=$htmlC->startTD(2,0,4,0,"center",0,0,1);
$td1a=$htmlC->startTD(0,0,2,0,"center",0,0,1);
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

echo "<font size=+2><b>Preferences Page for $pguser</b></font></td></tr>";

echo $tr.$td2;
echo "<strong>Current Profile:</strong></td>";
echo $td3;
echo "<input type='text' name='profilename' value='{$userP['profilename']}'></td>";
echo $td1a;
// show all profiles
echo "<select name='c_profile' ID='c_profile'>";
  for ($i=0;$i<$pf_num;$i++)
  {
    $pf_Dex=mysql_result($pf_query,$i,'id');
    $pf_Val=mysql_result($pf_query,$i,'profilename');
    echo "<option value='$pf_Dex'";
    if ($pf_Dex == $userP['u_profile']) { echo " SELECTED"; }
    echo ">$pf_Val</option>";
  }
echo "</select>";

//dropdown_select('c_profile', $userP['u_profile'], implode('|',$pf_arr));
echo " <input type='submit' value='Switch Profiles' name='swProfile'> &nbsp;".
"</td></tr>";

echo $tr.$td2;
echo "<strong>Name:</strong></td>";
echo $td3;
echo "<input type='text' name='real_name' value='$real_name'></td>";
echo $td2;
echo "<strong>Screen Resolution:</strong></td>";
echo $td3;
$array = implode('|', $i_r);
dropdown_select('i_res', $userP['i_res'], $array);

echo "</td></tr>".$tr.$td2;
echo "<strong>Email:</strong>";
echo $td3;
echo "<input type='text' name='email' value='$email'>";
echo $td2;
echo "<strong>Interface Type:</strong>";
echo $td3;
radio_select('i_type', $userP['i_type'], 0, 'Standard');
radio_select('i_type', $userP['i_type'], 1, 'Enhanced');

echo "</td></tr>".$tr.$td2;
echo "<strong>Email Updates:</strong></td>";
echo $td3;
radio_select('email_updates', $userP['email_updates'], '1', 'Yes');
radio_select('email_updates', $userP['email_updates'], '0', 'No');
echo "</td>".$td2;
echo "<strong>Interface Layout:</strong>";
echo $td3;
radio_select('i_layout', $userP['i_layout'], 0, '<img src="tools/proofers/gfx/bt5.png" width="26" alt="Horizontal">');
radio_select('i_layout', $userP['i_layout'], 1, '<img src="tools/proofers/gfx/bt4.png" width="26" alt="Vertical">');

echo "</td></tr>".$tr.$td2;
echo "<strong>Show Projects From:</strong></td>";
echo $td3;
$array = implode('|', $p_l);
dropdown_select('u_plist', $userP['u_plist'], $array);
echo "</td>".$td2;
echo "<strong>Launch in New Window:</strong></td>";
echo $td3;
radio_select('i_newwin', $userP['i_newwin'], 1, 'Yes');
radio_select('i_newwin', $userP['i_newwin'], 0, 'No');

echo "</td></tr>".$tr.$td2;
echo "<strong>Language:</strong></td>";
echo $td3;
$array = implode('|', $u_l);
dropdown_select('u_lang', $userP['u_lang'], $array);
echo "</td>".$td2;
echo "<strong>Show Toolbar:</strong>";
echo $td3;
radio_select('i_toolbar', $userP['i_toolbar'], 1, 'Yes');
radio_select('i_toolbar', $userP['i_toolbar'], 0, 'No');
echo "</td></tr>";

echo $tr.$td2;
echo "<strong>Password:</strong></td>";
echo $td3;
echo "<a href='http://texts01.archive.org/dp/phpBB2/profile.php?mode=sendpassword'>Reset Password</a></td>";
echo $td2;
echo "<strong>Show Statusbar:</strong></td>";
echo $td3;
radio_select('i_statusbar', $userP['i_statusbar'], 1, 'Yes');
radio_select('i_statusbar', $userP['i_statusbar'], 0, 'No');
echo "</td></tr>";

echo $tr.$td2;
echo "<strong>Show Rank Neighbors:</strong></td>";
echo $td3;
$array = implode('|', $u_n);
dropdown_select('u_neigh', $userP['u_neigh'], $array);
echo "</td>".$td2;
echo "<strong>Show Top 10:</strong></td>";
echo $td3;
radio_select('u_top10', $userP['u_top10'], 1, 'Yes');
radio_select('u_top10', $userP['u_top10'], 0, 'No');
echo "</td></tr>";

echo $tr.$td4;
echo "<img src='tools/proofers/gfx/bt4.png'><b>Vertical Interface Preferences</b></td>";
echo $td4;
echo "<img src='tools/proofers/gfx/bt5.png'><b>Horizontal Interface Preferences</b>";

echo "</td></tr>".$tr.$td2;
echo "<strong>Font Face:</strong></td>";
echo $td3;
$array = implode('|', $f_f);
dropdown_select('v_fntf', $userP['v_fntf'], $array);
echo "</td>".$td2;
echo "<strong>Font Face:</strong></td>";
echo $td3;
$array = implode('|', $f_f);
dropdown_select('h_fntf', $userP['h_fntf'], $array);
echo "</td></tr>";

echo $tr.$td2;
echo "<strong>Font Size:</strong></td>";
echo $td3;
$array = implode('|', $f_s);
dropdown_select('v_fnts', $userP['v_fnts'], $array);
echo "</td>".$td2;
echo "<strong>Font Size:</strong></td>";
echo $td3;
$array = implode('|', $f_s);
dropdown_select('h_fnts', $userP['h_fnts'], $array);
echo "</td></tr>";

echo $tr.$td2;
echo "<strong>Image Zoom:</strong></td>";
echo $td3;
echo "<input type=\"text\" name=\"v_zoom\" value=\"{$userP['v_zoom']}\" size=\"3\">% of 1000 pixels</td>";
echo $td2;
echo "<strong>Image Zoom:</strong></td>";
echo $td3;
echo "<input type=\"text\" name=\"h_zoom\" value=\"{$userP['h_zoom']}\" size=\"3\">% of 1000 pixels";
echo "</td></tr>";

echo $tr.$td2;
echo "<strong>Text Frame Size:</strong></td>";
echo $td3;
echo "<input type=\"text\" name=\"v_tframe\" value=\"{$userP['v_tframe']}\" size=\"3\">% of browser width</td>";
echo $td2;
echo "<strong>Text Frame Size:</strong></td>";
echo $td3;
echo "<input type=\"text\" name=\"h_tframe\" value=\"{$userP['h_tframe']}\" size=\"3\">% of browser height";
echo "</td></tr>";

echo $tr.$td2;
echo "<strong>Scroll Text Frame:</strong></td>";
echo $td3;
radio_select('v_tscroll', $userP['v_tscroll'], 1, 'Yes');
radio_select('v_tscroll', $userP['v_tscroll'], 0, 'No');
echo "</td>".$td2;
echo "<strong>Scroll Text Frame:</strong></td>";
echo $td3;
radio_select('h_tscroll', $userP['h_tscroll'], 1, 'Yes');
radio_select('h_tscroll', $userP['h_tscroll'], 0, 'No');
echo "</td></tr>";

echo $tr.$td2;
echo "<strong>Number of Text Lines:</strong></td>";
echo $td3;
echo "<input type=\"text\" name=\"v_tlines\" value=\"{$userP['v_tlines']}\" size=\"3\"></td>";
echo $td2;
echo "<strong>Number of Text Lines:</strong></td>";
echo $td3;
echo "<input type=\"text\" name=\"h_tlines\" value=\"{$userP['h_tlines']}\" size=\"3\">";
echo "</td></tr>";

echo $tr.$td2;
echo "<strong>Length of Text Lines:</strong></td>";
echo $td3;
echo "<input type=\"text\" name=\"v_tchars\" value=\"{$userP['v_tchars']}\" size=\"3\"> characters</td>";
echo $td2;
echo "<strong>Length of Text Lines:</strong></td>";
echo $td3;
echo "<input type=\"text\" name=\"h_tchars\" value=\"{$userP['h_tchars']}\" size=\"3\"> characters";
echo "</td></tr>";

echo $tr.$td2;
echo "<strong>Wrap Text:</strong></td>";
echo $td3;
radio_select('v_twrap', $userP['v_twrap'], 1, 'Yes');
radio_select('v_twrap', $userP['v_twrap'], 0, 'No');
echo "</td>".$td2;
echo "<strong>Wrap Text:</strong></td>";
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
if ($pf_num < 10)
  {echo "<input type='submit' value='Save as New Profile' name='mkProfile'> &nbsp;";}
echo "<input type='submit' value='Quit' name='quitnc'></center>";
echo "</td></tr></table></form></body></html>";
} else {
$user_id = $_POST['user_id'];
$real_name = $_POST['real_name'];
$email = $_POST['email'];
$email_updates = $_POST['email_updates'];
$project_listing = $_POST['u_plist'];

// set/create user_profile values
if (isset($mkProfile))
  {$prefs_query="INSERT INTO user_profiles SET u_ref='{$userP['u_id']}', ";}
else
  {$prefs_query="UPDATE user_profiles SET ";}

$prefs_query.="profilename='$profilename', i_res='$i_res', i_type='$i_type', i_layout='$i_layout', 
i_newwin='$i_newwin', i_toolbar='$i_toolbar', i_statusbar='$i_statusbar', 
v_fntf='$v_fntf', v_fnts='$v_fnts', v_zoom='$v_zoom', v_tframe='$v_tframe', v_tscroll='$v_tscroll', 
v_tlines='$v_tlines', v_tchars='$v_tchars', v_twrap='$v_twrap', 
h_fntf='$h_fntf', h_fnts='$h_fnts', h_zoom='$h_zoom', h_tframe='$h_tframe', h_tscroll='$h_tscroll', 
h_tlines='$h_tlines', h_tchars='$h_tchars', h_twrap='$h_twrap'";
if (!isset($mkProfile))
  {$prefs_query.=" WHERE u_ref='{$userP['u_id']}' AND id='{$userP['u_profile']}'";}

$result = mysql_query($prefs_query);
echo mysql_error();

// set users values
$users_query="UPDATE users SET real_name='$real_name', email='$email', 
email_updates='$email_updates', u_plist='$u_plist', u_top10='$u_top10', u_neigh='$u_neigh',
u_lang='$u_lang' , i_prefs='1'";
if (isset($mkProfile))
  {$users_query.=", u_profile='".mysql_insert_id($db_link)."'";}
$users_query.=" WHERE id='$user_id' AND username='$pguser'";
$result = mysql_query($users_query);

echo mysql_error();
$cookieC->setUserPrefs($pguser);

if (isset($project) && isset($proofstate))
{echo "$docType<html><head><META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=tools/proofers/projects.php?project=$project&proofstate=$proofstate\"></head><body></body></html>"; }
else {echo "$docType<html><head><META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=tools/proofers/proof_per.php\"></head><body></body></html>";}

}
*/
?>
