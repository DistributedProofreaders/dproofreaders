<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once('../includes/team.php');
$popHelpDir='faq/pophelp/teams/edit_';

if (isset($_POST['mkPreview'])) {
	include($relPath.'js_newpophelp.inc');
	theme("Preview ".$_POST['teamname'], "header");
	$teamimages = uploadImages(1,"","both");
	$curTeam['teamname'] = stripAllString($_POST['teamname']);
	$curTeam['team_info'] = stripAllString($_POST['text_data']);
	$curTeam['webpage'] = stripAllString($_POST['teamwebpage']);
	$curTeam['createdby'] = $pguser;
	$curTeam['created'] = time();
	$curTeam['page_count'] = 0;
	if (!empty($_FILES['teamavatar']['tmp_name'])) {
		$curTeam['avatar'] = $teamimages['avatar'];
		$tavatar = 1;
	}
	if (!empty($_FILES['teamicon']['tmp_name'])) {
    		$ticon = 1;
	}
	echo "<center><br>";
	showEdit(htmlentities(stripslashes($_POST['teamname'])),stripslashes($_POST['text_data']),stripslashes($_POST['teamwebpage']),1,0,$tavatar,$ticon);
	echo "<br>";
	showTeamProfile($curTeam);
	echo "</center><br>";
	theme("", "footer");
} else if (isset($_POST['mkMake'])) {
	mysql_query("INSERT INTO user_teams (teamname,team_info,webpage,createdby,owner,created) VALUES('".addslashes(stripAllString($_POST['teamname']))."','".addslashes(stripAllString($_POST['text_data']))."','".addslashes(stripAllString($_POST['teamwebpage']))."','$pguser','{$userP['u_id']}','".time()."')");
	$tid = mysql_insert_id($db_link);
	if (!empty($_POST['tavatar'])) {
    		mysql_query("UPDATE user_teams SET avatar='".$_POST['tavatar']."' WHERE id = $tid");
    	} elseif (!empty($_FILES['teamavatar'])) {
    		uploadImages(0, $tid, "avatar");
    	}
	if (!empty($_POST['ticon'])) {
    		mysql_query("UPDATE user_teams SET icon='".$_POST['ticon']."' WHERE id = $tid");
    	} elseif (!empty($_FILES['teamicon'])) {
    		uploadImages(0, $tid, "icon");
    	}
	createThread($_POST['teamname'],$_POST['text_data'],$pguser,$tid);
	//figure out which team to overwrite
	$otid=0;
	if (!isset($_POST['teamall'])) {
		if ($userP['team_1'] == $_POST['tteams']) {
			$otid=1;
		} elseif ($userP['team_2'] == $_POST['tteams']) {
			$otid=2;
		} else if ($userP['team_3'] == $_POST['tteams']) {
			$otid=3;
		}
         }
	// update cookie
        if ($use_cookies) { $cookieC->setUserPrefs($pguser); }
        else { updateSessionPreferences($pguser); }
	metarefresh(0,"../members/jointeam.php?tid=$tid&otid=$otid",'Join the Team','Creating the team....');
} else {
	include($relPath.'js_newpophelp.inc');
	theme("Create a New Team", "header");
	echo "<center><br>";
	showEdit("","","",1,0,0,0);
	echo "</center>";
	theme("", "footer");
}

?>
