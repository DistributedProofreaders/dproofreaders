<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once('../includes/team.php');

if ($userP['team_1'] == $_GET['tid'] || $userP['team_2'] == $_GET['tid'] || $userP['team_3'] == $_GET['tid']) {
    	$quitQuery = "UPDATE users SET ";
	if ($userP['team_1'] == $_GET['tid']) { $quitQuery .= "team_1 = '0'"; }
	if ($userP['team_2'] == $_GET['tid']) { $quitQuery .= "team_2 = '0'"; }
	if ($userP['team_3'] == $_GET['tid']) { $quitQuery .= "team_3 = '0'"; }
    	$quitQuery.=" WHERE username='$pguser' AND u_id='".$userP['u_id']."'";
    	$teamResult=mysql_query($quitQuery);
    	mysql_query("UPDATE user_teams SET active_members = active_members-1 WHERE id='".$_GET['tid']."'");
        dpsession_set_preferences_from_db();
	$title = _("Quit the Team");
	$desc = _("Quitting the team....");
        metarefresh(0,"../teams/tdetail.php?tid=".$_GET['tid']."",$title,$desc);
}  else {
	$title = _("Not a member");
	$desc = _("Unable to quit team....");
	metarefresh(3,"../teams/tdetail.php?tid=".$_GET['tid']."",$title,$desc);
}
?>
