<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once('../includes/team.php');

if (empty($_GET['otid'])) { $otid = 0; } else { $otid = $_GET['otid']; }
if (empty($_GET['tid'])) { $tid = 1; } else { $tid = $_GET['tid']; }

if ($tid != 1) {
	if ($userP['team_1'] != $tid && $userP['team_2'] != $tid && $userP['team_3'] != $tid) {
		if ($userP['team_1'] == 0 || $otid == 1) {
			$teamResult = mysql_query("UPDATE users SET team_1 = $tid WHERE username = '".$GLOBALS['pguser']."' AND u_id = ".$userP['u_id']."");
			mysql_query("UPDATE user_teams SET member_count = member_count+1 WHERE id = $tid");
			mysql_query("UPDATE user_teams SET active_members = active_members+1 WHERE id = $tid");
			if ($otid != 0) { mysql_query("UPDATE user_teams SET active_members = active_members-1 WHERE id = ".$userP['team_1'].""); }
			$redirect_team = 1;
		} elseif ($userP['team_2'] == 0 || $otid == 2) {
			$teamResult = mysql_query("UPDATE users SET team_2 = $tid WHERE username = '".$GLOBALS['pguser']."' AND u_id = ".$userP['u_id']."");
			mysql_query("UPDATE user_teams SET member_count = member_count+1 WHERE id = $tid");
			mysql_query("UPDATE user_teams SET active_members = active_members+1 WHERE id = $tid");
			if ($otid != 0) { mysql_query("UPDATE user_teams SET active_members = active_members-1 WHERE id = ".$userP['team_2'].""); }
			$redirect_team = 1;
		} elseif ($userP['team_3'] == 0 || $otid == 3) {
			$teamResult = mysql_query("UPDATE users SET team_3 = $tid WHERE username = '".$GLOBALS['pguser']."' AND u_id = ".$userP['u_id']."");
			mysql_query("UPDATE user_teams SET member_count = member_count+1 WHERE id = $tid");
			mysql_query("UPDATE user_teams SET active_members = active_members+1 WHERE id = $tid");
			if ($otid != 0) { mysql_query("UPDATE user_teams SET active_members = active_members-1 WHERE id = ".$userP['team_3'].""); }
			$redirect_team = 1;
		} else {
			include_once($relPath.'theme.inc');
			theme("Three Team Maximum!", "header");
			echo "<br><center>";
			echo "<table border='1' bordercolor='#111111' cellspacing='0' cellpadding='3' style='border-collapse: collapse' width='95%'>";
			echo "<tr bgcolor='".$theme['color_headerbar_bg']."'><td colspan='3'><b><center><font face='".$theme['font_headerbar']."' color='".$theme['color_headerbar_font']."'>Three Team Maximum</font></center></b></td></tr>";
			echo "<tr bgcolor='".$theme['color_mainbody_bg']."'><td colspan='3'><center><font face='".$theme['font_mainbody']."' color='".$theme['color_mainbody_font']."' size='2'>You have already joined three teams.<br>Which team would you like to replace?</font></center></td></tr>";
			echo "<tr bgcolor='".$theme['color_navbar_bg']."'>";
			$teamR=mysql_query("SELECT teamname FROM user_teams WHERE id='".$userP['team_1']."'");
			echo "<td width='33%'><center><b><a href='jointeam.php?tid=$tid&otid=1'>".mysql_result($teamR,0,'teamname')."</a></b></center></td>";
			$teamR=mysql_query("SELECT teamname FROM user_teams WHERE id='".$userP['team_2']."'");
			echo "<td width='33%'><center><b><a href='jointeam.php?tid=$tid&otid=2'>".mysql_result($teamR,0,'teamname')."</a></b></center></td>";
			$teamR=mysql_query("SELECT teamname FROM user_teams WHERE id='".$userP['team_3']."'");
			echo "<td width='34%'><center><b><a href='jointeam.php?tid=$tid&otid=3'>".mysql_result($teamR,0,'teamname')."</a></b></center></td>";
			echo "</tr><tr bgcolor='".$theme['color_headerbar_bg']."'><td colspan='3'><center><b><a href='../teams/tdetail.php?tid=$tid'><font face='".$theme['font_headerbar']."' color='".$theme['color_headerbar_font']."' size='2'>Do Not Join Team</font></a></b></center></td></tr></table></center>";
			theme("", "footer");
		}
	} else {
		metarefresh(4,"../teams/tdetail.php?tid=$tid",'Unable to Join the Team','You are already a member of this team....');
		$redirect_team = 0;
	}
} else {
	metarefresh(4,"../teams/tdetail.php?tid=$tid",'Unable to Join the Team','You are already a member of this team....');
}

if ($redirect_team == 1) {
	dpsession_set_preferences_from_db();
	metarefresh(0,"../teams/tdetail.php?tid=$tid",'Join the Team','Joining the team....');
}
?>
