<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once('../includes/team.php');
$popHelpDir='faq/pophelp/teams/edit_';

//Do we need this anymore?
if (!empty($_POST['tsid'])) { $ntid = $_POST['tsid']; } else { $ntid = $_GET['etid']; }

$result = mysql_query("SELECT * FROM user_teams WHERE id = $tid");
$curTeam = mysql_fetch_assoc($result);

if ($userP['u_id'] != $curTeam['owner']) {
	metarefresh(4,"tdetail.php?tid=$tid",'Authorization Failed','You are not authorized to edit this team....');
	exit;
}

if (isset($_GET['tid'])) {
	include($relPath.'js_newpophelp.inc');
	theme("Edit ".$curTeam['teamname'], "header");
	echo "<center><br>";
	showEdit(unstripAllString($curTeam['teamname'],0),unstripAllString($curTeam['team_info'],1),unstripAllString($curTeam['webpage'],1),0,$tid,0,0);
	echo "</center>";
	theme("", "footer");
} elseif (isset($_POST['edPreview'])) {
	include($relPath.'js_newpophelp.inc');
    	theme("Preview ".$_POST['teamname'], "header");
    	$teamimages = uploadImages(1,$tid,"both");
    	$curTeam['teamname'] = stripAllString($_POST['teamname']);
    	$curTeam['team_info'] = stripAllString($_POST['text_data']);
    	$curTeam['webpage'] = stripAllString($_POST['teamwebpage']);
    	if (!empty($_FILES['teamavatar']['tmp_name'])) {
    		$curTeam['avatar'] = $teamimages['avatar'];
    		$tavatar = 1;
    	}
    	if (!empty($_FILES['teamicon']['tmp_name'])) {
    		$ticon = 1;
    	}
    	echo "<center><br>";
	showEdit(htmlentities(stripslashes($_POST['teamname'])),stripslashes($_POST['text_data']),stripslashes($_POST['teamwebpage']),0,$tid,$tavatar,$ticon);
    	echo "<br>";
    	showTeamProfile($curTeam);
    	echo "</center><br>";
    	theme("", "footer");
} elseif (isset($_POST['edMake'])) {
	if (!empty($_POST['tavatar'])) {
    		mysql_query("UPDATE user_teams SET avatar='".$_POST['tavatar']."' WHERE id = $tid");
    	} elseif (!empty($_FILES['teamavatar'])) {
    		uploadImages(0,$tid,"avatar");
    	}
	if (!empty($_POST['ticon'])) {
    		mysql_query("UPDATE user_teams SET icon='".$_POST['ticon']."' WHERE id = $tid");
    	} elseif (!empty($_FILES['teamicon'])) {
    		uploadImages(0,$tid,"icon");
    	}

    	mysql_query("UPDATE user_teams SET teamname='".addslashes(stripAllString($_POST['teamname']))."', team_info='".addslashes(stripAllString($_POST['text_data']))."', webpage='".addslashes(stripAllString($_POST['teamwebpage']))."' WHERE id='$tid'");
      	metarefresh(0,"tdetail.php?tid=$tid",'Saving Team Update','Updating team....');
}

?>