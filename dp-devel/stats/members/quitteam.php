<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once('../includes/team.php');

if ($_GET['tid'] != 1 && ($userP['team_1'] == $_GET['tid'] || $userP['team_2'] == $_GET['tid'] || $userP['team_3'] == $_GET['tid'])) {
    	$quitQuery = "UPDATE users SET ";
	if ($userP['team_1'] == $_GET['tid']) { $quitQuery .= "team_1 = '0'"; }
	if ($userP['team_2'] == $_GET['tid']) { $quitQuery .= "team_2 = '0'"; }
	if ($userP['team_3'] == $_GET['tid']) { $quitQuery .= "team_3 = '0'"; }
    	$quitQuery.=" WHERE username='$pguser' AND u_id='".$userP['u_id']."'";
    	$teamResult=mysql_query($quitQuery);
    	mysql_query("UPDATE user_teams SET active_members = active_members-1 WHERE id='".$_GET['tid']."'");
        if ($use_cookies) { $cookieC->setUserPrefs($pguser); }
        else { updateSessionPreferences($pguser); }
        metarefresh(0,"../teams/tdetail.php?tid=".$_GET['tid']."",'Quit the Team','Quitting the team....');
}  else {
	metarefresh(3,"../teams/tdetail.php?tid=".$_GET['tid']."",'Not a member','Unable to quit team....');
}
?>
