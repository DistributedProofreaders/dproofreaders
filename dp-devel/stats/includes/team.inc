<?
//A file full of functions used in team stats

include_once($relPath.'prefs_options.inc'); // PRIVACY_*
include($relPath.'v_site.inc');

$team_avatars_dir = "$dyn_dir/teams/avatar";
$team_avatars_url = "$dyn_url/teams/avatar";

$team_icons_dir   = "$dyn_dir/teams/icon";
$team_icons_url   = "$dyn_url/teams/icon";

function showTeamProfile($curTeam) {
	global $theme, $userP;
	global $team_avatars_url;
	global $code_url;

	if ($curTeam['createdby'] == $GLOBALS['pguser']) { $editlink = "&nbsp;<font color='".$theme['color_headerbar_font']."' size='2'>[</font><a href='tedit.php?tid=".$curTeam['id']."'><font color='".$theme['color_headerbar_font']."' size='2'>"._("Edit")."</font></a><font color='".$theme['color_headerbar_font']."' size='2'>]</font>"; } else { $editlink = ""; }

	if ($curTeam['id'] != 1 && $userP['team_1'] != $curTeam['id'] && $userP['team_2'] != $curTeam['id'] && $userP['team_3'] != $curTeam['id'] && !empty($userP)) {
		$joinquitlink = "&nbsp;<font color='".$theme['color_headerbar_font']."' size='2'>[</font><a href='../members/jointeam.php?tid=".$curTeam['id']."'><font color='".$theme['color_headerbar_font']."' size='2'>"._("Join")."</font></a><font color='".$theme['color_headerbar_font']."' size='2'>]</font>";
	} elseif ($curTeam['id'] != 1 && !empty($userP)) {
		$joinquitlink = "&nbsp;<font color='".$theme['color_headerbar_font']."' size='2'>[</font><a href='../members/quitteam.php?tid=".$curTeam['id']."'><font color='".$theme['color_headerbar_font']."' size='2'>"._("Quit")."</font></a><font color='".$theme['color_headerbar_font']."' size='2'>]</font>";
	} else {
		$joinquitlink = "";
	}

	$last_post = mysql_query("SELECT post_time FROM phpbb_posts WHERE topic_id = ".$curTeam['topic_id']." ORDER BY post_time DESC LIMIT 1");
    	if ($last_post && mysql_num_rows($last_post) > 0) {
    		$last_post_date = mysql_result($last_post,0,"post_time");
    		$last_post_date = date("n/j/Y g:i:sA", $last_post_date);
    	} else {
    		$last_post_date = NULL;
    	}

	$now = time();
	$daysInExistence = number_format(floor(($now - $curTeam['created'])/86400));

	if(empty($curTeam['avatar'])) { $curTeam['avatar'] = "avatar_default.png"; }

	echo "\n<table border='1' bordercolor='#111111' cellspacing='0' cellpadding='2' style='border-collapse: collapse;' width='95%'>";
	echo "\n<tr bgcolor='" . $theme['color_headerbar_bg'];
	echo "'><td rowspan='6' align='center' width='30%' bgcolor='" . $theme['color_mainbody_bg']."'>";
	echo "<img border='0' src='$team_avatars_url/";
	echo $curTeam['avatar'] . "' alt='".strip_tags($curTeam['teamname'])."'></td>";
	echo "<td align='left' width='70%' valign='top'><center><font color='" . $theme['color_headerbar_font'];
	echo "'><b>" . stripslashes($curTeam['teamname']) . "</b></font>&nbsp;<a href='";
	echo $GLOBALS['code_url'] . "/stats/teams/teams_xml.php?id=" . $curTeam['id'] . "'><img src='";
	echo $GLOBALS['code_url'] . "/graphics/xml.gif' border='0' width='36' height='14' style='vertical-align: middle;'></a>";
	echo $editlink . $joinquitlink . "</center></td></tr>";
	echo "\n<tr bgcolor='" . $theme['color_navbar_bg'] . "'><td align='left' width='70%' valign='top'><font face='";
	echo $theme['font_navbar'] . "' color='" . $theme['color_navbar_font'] . "' size='2'><b>"._("Created")."</b>: ";
	echo date("m/d/Y", $curTeam['created']) . "&nbsp;($daysInExistence "._("days").")</font></td></tr>";
	echo "\n<tr bgcolor='" . $theme['color_navbar_bg'] . "'><td align='left' width='70%' valign='top'><font face='";
	echo $theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'><b>"._("Leader")."</b>: <a href='";
	echo $GLOBALS['code_url'] . "/stats/members/mdetail.php?id=" . $curTeam['owner'] . "'>" . $curTeam['createdby'] . "</font></a></td></tr>";
	echo "\n<tr bgcolor='" . $theme['color_navbar_bg'] . "'><td align='left' width='70%' valign='top'><font face='";
	echo $theme['font_navbar'] . "' color='" . $theme['color_navbar_font'] . "' size='2'><b>"._("Total Pages")."</b>: ";
	echo number_format($curTeam['page_count']) . "</font></td></tr>";
	echo "\n<tr bgcolor='" . $theme['color_navbar_bg'] . "'><td align='left' width='70%' valign='top'><font face='";
	echo $theme['font_navbar'] . "' color='" . $theme['color_navbar_font'] . "' size='2'><b>"._("Description")."</b>: ";
	echo stripslashes($curTeam['team_info'])."</font></td></tr>";
	echo "\n<tr bgcolor='" . $theme['color_navbar_bg'] . "'><td align='left' width='70%' valign='top'><font face='";
	echo $theme['font_navbar'] . "' color='" . $theme['color_navbar_font'] . "' size='2'><b>"._("Website")."</b>: <a href='";
	echo $curTeam['webpage'] . "' target='_new'>" . $curTeam['webpage'] . "</a><br><b>"._("Forums")."</b>: <a href='";
	echo $code_url."/stats/teams/team_topic.php?team=".$curTeam['id']."'>"._("Team Discussion")."</a> ";
	if (!is_null($last_post_date)) {
		echo "("._("Last Post:")." $last_post_date)";
	}
	echo "</font></td></tr>";
	echo "</table><p>";
}

function showTeamStats($curTeam) {
	global $theme;

	$result = mysql_query("SELECT MAX(date_updated) FROM user_teams_stats");
	$lastupdated = date("n/d/Y h:i:s A T", (mysql_result($result,0,0)-1));

	$result = mysql_query("SELECT rank FROM user_teams_stats WHERE team_id = ".$curTeam['id']." ORDER BY date_updated DESC LIMIT 1");
	if (mysql_num_rows($result) > 0) {
		$prevRank = mysql_result($result, 0, "rank");
		$hasStats = TRUE;
	} else {
		$prevRank = 0;
		$hasStats = FALSE;
	}

	$result = mysql_query("SELECT id FROM user_teams WHERE id != 1 ORDER BY member_count DESC");
	$i = 1;
	while ($row = mysql_fetch_assoc($result)) {
        	if ($row['id'] == $curTeam['id']) { $mbrCountRank = $i; break; }
		$i++;
	}

	$pageCountRank = team_get_page_tally_rank( $curTeam['id'] );

	if ($hasStats) {
		$result = mysql_query("SELECT date_updated, daily_page_count FROM user_teams_stats WHERE team_id = ".$curTeam['id']." ORDER BY daily_page_count DESC LIMIT 1");	
		$bestDayCount = mysql_result($result, 0, "daily_page_count");
		$bestDayTime = date("M. jS, Y", (mysql_result($result, 0, "date_updated")-86400));
	} else {
			$bestDayCount = 0;
			$bestDayTime = date("M. jS, Y");
	}

	echo "\n<table border='1' bordercolor='#111111' cellspacing='0' cellpadding='2' style='border-collapse: collapse' width='95%'>";
	echo "\n<tr><td bgcolor='" . $theme['color_headerbar_bg'] . "'><center><font face='" . $theme['font_headerbar'];
	echo "' color='" . $theme['color_headerbar_font']."' size='2'><b>"._("Team Statistics")."</b><br><font size='1'>("._("Last Updated:")." $lastupdated)</font></font></center></td></tr>";
	echo "\n<tr><td><table border=0' width='100%' cellspacing='0' cellpadding='0'>";
	echo "\n<tr bgcolor='" . $theme['color_navbar_bg'] . "' onmouseover='javascript:style.background=\"";
	echo $theme['color_mainbody_bg'] . "\"' onmouseout='javascript:style.background=\"" . $theme['color_navbar_bg'] . "\"'>";
	echo "<td width='60%'>"._("Members")." <i>("._("Rank").")</i></td>";
	echo "<td width='40%'><b>" . number_format($curTeam['member_count']) . "</b>&nbsp;<i>(#$mbrCountRank)</i></td></tr>";
	echo "\n<tr bgcolor='" . $theme['color_navbar_bg'] . "' onmouseover='javascript:style.background=\"";
	echo $theme['color_mainbody_bg'] . "\"' onmouseout='javascript:style.background=\"" . $theme['color_navbar_bg'] . "\"'>";
	echo "<td width='60%'>"._("Current Members")."</td>";
	echo "<td width='40%'><b>" . number_format($curTeam['active_members']) . "</b></td></tr>";
	echo "\n<tr bgcolor='" . $theme['color_navbar_bg'] . "' onmouseover='javascript:style.background=\"";
	echo $theme['color_mainbody_bg'] . "\"' onmouseout='javascript:style.background=\"" . $theme['color_navbar_bg'] . "\"'>";
	echo "<td width='60%'>"._("Retired Members")."</td>";
	echo "<td width='40%'><b>" . number_format($curTeam['member_count'] - $curTeam['active_members']) . "</b></td></tr>";
	echo "\n<tr bgcolor='" . $theme['color_navbar_bg'] . "' onmouseover='javascript:style.background=\"";
	echo $theme['color_mainbody_bg'] . "\"' onmouseout='javascript:style.background=\"" . $theme['color_navbar_bg'] . "\"'>";
	echo "<td width='60%'>"._("Total Pages")." <i>("._("Rank").")</i><font size='2'></font></td>";
	echo "<td width='40%'><b>" . number_format($curTeam['page_count']) . "</b>&nbsp;<i>(#$pageCountRank)</i>";
			if ($prevRank > $pageCountRank && !empty($prevRank)) { echo "&nbsp;<img src='".$GLOBALS['code_url']."/graphics/up.gif' border='0' alt='+".($prevRank-$pageCountRank)."'>"; }
			elseif ($prevRank < $pageCountRank && !empty($prevRank)) { echo "&nbsp;<img src='".$GLOBALS['code_url']."/graphics/down.gif' border='0' alt='-".($pageCountRank-$prevRank)."'>"; }
			else { echo "&nbsp;"; }
		echo "</td></tr>";
	echo "\n<tr bgcolor='" . $theme['color_navbar_bg'] . "' onmouseover='javascript:style.background=\"";
	echo $theme['color_mainbody_bg'] . "\"' onmouseout='javascript:style.background=\"" . $theme['color_navbar_bg'] . "\"'>";
	echo "<td width='60%'>"._("Avg. Pages per Day")."<font size='2'></font></td>";
	echo "<td width='40%'><b>" . number_format($curTeam['daily_average']) . "</b></td></tr>";
	echo "\n<tr bgcolor='" . $theme['color_navbar_bg'] . "' onmouseover='javascript:style.background=\"";
	echo $theme['color_mainbody_bg']."\"' onmouseout='javascript:style.background=\"" . $theme['color_navbar_bg'] . "\"'>";
	echo "<td width='60%'>"._("Most Pages Done per Day")."</td>";
	echo "<td width='40%'><b>" . number_format($bestDayCount) . "</b>&nbsp;<i>($bestDayTime)</i></b></td></tr>";
	echo "</td></tr></table></table><p>";
}

function showTeamMbrs($curTeam) {
	global $theme, $code_url;

	if (empty($_GET['order'])) { $order = "mbr"; } else { $order = $_GET['order']; }
	if (empty($_GET['direction'])) { $direction = "asc"; } else { $direction = $_GET['direction']; }

	echo "\n<table border='1' bordercolor='#111111' cellspacing='0' cellpadding='0' style='border-collapse: collapse' width='95%'>";
	
	echo "<tr><td bgcolor='".$theme['color_headerbar_bg']."'><center><font face='".$theme['font_headerbar']."' color='".$theme['color_headerbar_font']."' size='2'><b>"._("Team Member Details")."</b>";
	$latestMbr = mysql_query("SELECT users.username AS Username, users.u_privacy AS Privacy FROM user_teams INNER JOIN users ON user_teams.latestUser = users.u_id WHERE user_teams.id = ".$curTeam['id']." AND user_teams.latestUser <> 0");
	if (mysql_num_rows($latestMbr) > 0) { 
		if (mysql_result($latestMbr, 0, "Privacy") == PRIVACY_PUBLIC || (mysql_result($latestMbr, 0, "Privacy") == PRIVACY_PRIVATE && isset($GLOBALS['pguser']))) { echo "<br>Please welcome <b>".mysql_result($latestMbr, 0, "Username")."</b> as the latest member to join this team!"; }
	}
	echo "</font></center></td></tr>";
	
	echo "\n<tr><td><table border='0' width='100%' cellspacing='2' cellpadding='0'>";
	echo "\n<tr bgcolor='#cccccc'>";

	if ($order == "mbr") {
		if ($direction == "asc") { $newdirection = "desc"; } else { $newdirection = "asc"; }
		echo "<td width='35%' bgcolor='".$theme['color_headerbar_bg']."'><b><a href='tdetail.php?tid=".$curTeam['id']."&order=mbr&direction=$newdirection'><font face='".$theme['font_headerbar']."' color='".$theme['color_headerbar_font']."' size='2'>"._("Username")."</a></font></b></td>";
		$orderby = "username";
	} else {
		echo "<td width='35%' bgcolor='".$theme['color_navbar_bg']."'><b><a href='tdetail.php?tid=".$curTeam['id']."&order=mbr&direction=asc'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'>"._("Username")."</a></font></b></td>";
	}

	if ($order == "pages") {
		if ($direction == "asc") { $newdirection = "desc"; } else { $newdirection = "asc"; }
		echo "<td width='35%' bgcolor='".$theme['color_headerbar_bg']."'><b><a href='tdetail.php?tid=".$curTeam['id']."&order=pages&direction=$newdirection'><font face='".$theme['font_headerbar']."' color='".$theme['color_headerbar_font']."' size='2'>"._("Pages Completed")."</a></font></b></td>";
		$orderby = "pagescompleted";
	} else {
		echo "<td width='35%' bgcolor='".$theme['color_navbar_bg']."'><b><a href='tdetail.php?tid=".$curTeam['id']."&order=pages&direction=desc'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'>"._("Pages Completed")."</a></font></b></td>";
	}

	if ($order == "date") {
		if ($direction == "asc") { $newdirection = "desc"; } else { $newdirection = "asc"; }
		echo "<td width='30%' bgcolor='".$theme['color_headerbar_bg']."'><b><a href='tdetail.php?tid=".$curTeam['id']."&order=date&direction=$newdirection'><font face='".$theme['font_headerbar']."' color='".$theme['color_headerbar_font']."' size='2'>"._("Date Joined DP")."</font></a></b></td>";
		$orderby = "date_created";
	} else {
		echo "<td width='30%' bgcolor='".$theme['color_navbar_bg']."'><b><a href='tdetail.php?tid=".$curTeam['id']."&order=date&direction=asc'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'>"._("Date Joined DP")."</font></a></b></td>";
	}

	echo "</tr></table>\n<table border='0' width='100%' cellspacing='2' cellpadding='0'>";
	$mbrQuery = mysql_query("SELECT * FROM users WHERE team_1 = ".$_GET['tid']." || team_2 = ".$_GET['tid']." || team_3 = ".$_GET['tid']." ORDER BY $orderby $direction");

	$totalAnonUsers = 0;
	$totalPagesAnonUsers = 0;
	while ($curMbr = mysql_fetch_assoc($mbrQuery)) {
		if ($curMbr['u_privacy'] == PRIVACY_ANONYMOUS)
		{
			$totalAnonUsers++;
			$totalPagesAnonUsers += $curMbr['pagescompleted'];
		}
		elseif ($curMbr['u_privacy'] == PRIVACY_PRIVATE && !isset($GLOBALS['pguser']))
		{
			$totalAnonUsers++;
			$totalPagesAnonUsers += $curMbr['pagescompleted'];
		}
		else
		{
			$username = $curMbr['username'];
			echo "\n<tr bgcolor='" . $theme['color_navbar_bg'] . "' onmouseover='javascript:style.background=\"" . $theme['color_mainbody_bg'] . "\"' onmouseout='javascript:style.background=\"" . $theme['color_navbar_bg']."\"'>";
			echo "<td width='35%'><a href='$code_url/stats/members/mdetail.php?id=" . $curMbr['u_id'] . "'>$username</a></td>";
			echo "<td width='35%'>" . number_format($curMbr['pagescompleted']) . "</td>";
			echo "<td width='30%'>" . date("m/d/Y", $curMbr['date_created']) . "</td></tr>";
		}
	}
	if ($totalAnonUsers > 0)
	{
		echo "\n<tr bgcolor='" . $theme['color_navbar_bg'] . "' onmouseover='javascript:style.background=\"" . $theme['color_mainbody_bg'] . "\"' onmouseout='javascript:style.background=\"" . $theme['color_navbar_bg']."\"'>";
		echo "<td width='35%'>"._("Anonymous");
		if(!isset($GLOBALS['pguser'])) { echo "/"._("Private"); }
		echo " "._("users")."</td>";
		echo "<td width='35%'>" . number_format($totalPagesAnonUsers) . "</td>";
		echo "<td width='30%'>&nbsp;</td></tr>";
	}
	echo "</td></tr></table></table><p>";
}

function showTeamHistory($curTeam) {
	global $theme;
	if (empty($_GET['range'])) { $range = 30; } else { $range = $_GET['range']; }
	echo "\n<table border='1' bordercolor='#111111' cellspacing='0' cellpadding='0' style='border-collapse: collapse' width='95%'>";
	echo "\n<tr><td bgcolor='".$theme['color_headerbar_bg']."'><center><font face='".$theme['font_headerbar']."' color='".$theme['color_headerbar_font']."' size='2'><b>"._("Team Statistics History")."</b></font></center></td></tr>";
	echo "<tr><td bgcolor='".$theme['color_navbar_bg']."'><center><br>";
	echo "<p><font size='2'><a href='tdetail.php?tid=".$curTeam['id']."&range=7'>"._("Last 7 Days")."</a> | <a href='tdetail.php?tid=".$curTeam['id']."&range=14'>"._("Last 14 Days")."</a> | <a href='tdetail.php?tid=".$curTeam['id']."&range=30'>"._("Last 30 Days")."</a> | <a href='tdetail.php?tid=".$curTeam['id']."&range=60'>"._("Last 60 Days")."</a> | <a href='tdetail.php?tid=".$curTeam['id']."&range=365'>"._("Last 365 Days")."</a> | <a href='tdetail.php?tid=".$curTeam['id']."&range=all'>"._("Lifetime")."</a></font>";
	echo "<p><img src='".$GLOBALS['code_url']."/stats/jpgraph_files/team_pages_history.php?tid=".$curTeam['id']."&range=$range' width='600' height='300'></center><br></td></tr>";
	echo "</table><p>";
}

function stripAllString($ttext) {
	return str_replace(array('[b]','[B]','[/b]','[/B]','[i]','[I]','[/i]','[/I]','[p]','[P]','[/p]','[/P]','[lb]','[LB]'),
	array('<b>','<b>','</b>','</b>','<i>','<i>','</i>','</i>','<p>','<p>','</p>','</p>','<br>','<br>'),
	htmlentities(strip_tags(stripslashes($ttext))));
}

function unstripAllString($ttext,$sType) {
	$ttext=str_replace(array('<b>','</b>','<i>','</i>','<p>','</p>','<br>'),
	array('[b]','[/b]','[i]','[/i]','[p]','[/p]','[lb]'),
	stripslashes($ttext));
	if ($sType==1) {
      		$htmlchars = array_flip(get_html_translation_table(HTML_ENTITIES));
		$ttext=strtr($ttext, $htmlchars);
      	}
	return $ttext;
}

function showEdit($tname,$ttext,$twebpage,$tedit,$tsid,$tavatar,$ticon) {
	global $theme, $teamimages, $userP;

	echo "<form enctype='multipart/form-data' id='mkTeam' name='mkTeam' action='";
	if ($tedit == 1) { echo "new_team.php"; } else { echo "tedit.php"; }
	echo "' method='post' target='_top'>";
	echo "<input type='hidden' name='tsid' value='$tsid'>";
	if ($tavatar == 1) { echo "<input type='hidden' name='tavatar' value='".$teamimages['avatar']."'>"; }
	if ($ticon == 1) { echo "<input type='hidden' name='ticon' value='".$teamimages['icon']."'>"; }
	echo "\n<table border='1' bordercolor='#111111' cellspacing='0' cellpadding='3' style='border-collapse: collapse' width='95%'>";
	echo "<tr bgcolor='".$theme['color_headerbar_bg']."'><td><center><font color='".$theme['color_headerbar_font']."'><b>";
	if ($tedit != 1) { echo _("Edit Team Information")."</b></font></td></tr>"; } else { echo _("New Proofreading Team")."</b></font></td></tr>"; }
	echo "\n<tr><td><table border='0' cellspacing='0' cellpadding='0' width='100%'>";
	echo "\n<tr><td width='35%' align='right'><font color='".$theme['color_mainbody_font']."'><b>"._("Team Name")."</b>:&nbsp;</td>";
	echo "<td width='65%' align='left'><input type='text' value='$tname' name='teamname' size='50'>&nbsp;<b><a href=\"JavaScript:newHelpWin('teamname');\">?</a></b></td></tr>";
	echo "\n<tr><td width='35%' align='right'><font color='".$theme['color_mainbody_font']."'><b>"._("Team Webpage")."</b>:&nbsp;</td>";
	echo "<td width='65%' align='left'><input type='text' value='$twebpage' name='teamwebpage' size='50'>&nbsp;<b><a href=\"JavaScript:newHelpWin('teamwebpage');\">?</a></b></td></tr>";
	echo "\n<tr><td width='35%' align='right'><font color='".$theme['color_mainbody_font']."'><b>"._("Team Avatar")."</b>:&nbsp;</td>";
	echo "<td width='65%' align='left'><input type='file' name='teamavatar' size='50'>&nbsp;<b><a href=\"JavaScript:newHelpWin('teamavatar');\">?</a></b></td></tr>";
	echo "\n<tr><td width='35%' align='right'><font color='".$theme['color_mainbody_font']."'><b>"._("Team Icon")."</b>:&nbsp;</td>";
	echo "<td width='65%' align='left'><input type='file' name='teamicon' size='50'>&nbsp;<b><a href=\"JavaScript:newHelpWin('teamicon');\">?</a></b></td></tr>";
	echo "</table></td></tr>";
	echo "\n<tr bgcolor='".$theme['color_navbar_bg']."'><td><center><font color='".$theme['color_navbar_font']."'><b>"._("Team Description")."</b>&nbsp;";
	echo "<b><a href=\"JavaScript:newHelpWin('teamdesc');\">?</a></b><br><textarea name='text_data' cols='40' rows='6'>$ttext</textarea></center><br></td></tr>";

	if ($tedit == 1 && $userP['team_1'] != 0 && $userP['team_2'] != 0 && $userP['team_3'] != 0) {
    		echo "\n<tr bgcolor='".$theme['color_mainbody_bg']."'><td><center>"._("You must join the team to create it, which team space would you like to use?")."<br>";
    		echo "<select name='tteams' title='"._("Team List")."'>";
    		$teamQuery = mysql_query("SELECT teamname, id FROM user_teams WHERE id = ".$userP['team_1']." || id = ".$userP['team_2']." || id = ".$userP['team_3']."");
    		while ($row = mysql_fetch_assoc($teamQuery)) {
    			echo "<option value='".$row['id']."'>".unstripAllString(strip_tags($row['teamname']),1)."</option>";
    		}
    		echo "</select></center></td></tr>";
	} else {
		echo "<input type='hidden' name='teamall' value='1'>";
	}

  	if($tedit == 1) {
  		echo "\n<tr bgcolor='".$theme['color_headerbar_bg']."'><td><center>";
  		echo "<input type='submit' name='mkPreview' value='"._("Preview Team Display")."'>&nbsp;&nbsp;&nbsp;";
  		echo "<input type='submit' name='mkMake' value='"._("Make Team")."'>&nbsp;&nbsp;&nbsp;";
  		echo "<input type='submit' name='Quit' value='"._("Quit")."'>";
  		echo "</center></td></tr></table></form>";
  	} else {
  		echo "\n<tr bgcolor='".$theme['color_headerbar_bg']."'><td><center>";
  		echo "<input type='submit' name='edPreview' value='"._("Preview Changes")."'>&nbsp;&nbsp;&nbsp;";
  		echo "<input type='submit' name='edMake' value='"._("Save Changes")."'>&nbsp;&nbsp;&nbsp;";
  		echo "<input type='submit' name='edQuit' value='"._("Quit")."'>";
  		echo "</center></td></tr></table></form>";
  	}
}


function uploadImages($preview,$tid,$type) {
	global $team_avatars_dir, $team_icons_dir;
	if (!empty($_FILES['teamavatar']['tmp_name']) && ($type == "both" || $type == "avatar")) {
		if (strtolower(substr($_FILES['teamavatar']['name'], -4)) == ".png" || strtolower(substr($_FILES['teamavatar']['name'], -4)) == ".jpg" || strtolower(substr($_FILES['teamavatar']['name'], -4)) == ".gif") {
			if ($_FILES['teamavatar']['size'] > 2097152) {
				echo "<center><br><b><font color='#ff0000'>"._("The avatar uploaded is too large.  Please limit the file size to 2MB or less.")."</font></b></center>";
				return;
			}
			$avatarID = uniqid("avatar_").substr($_FILES['teamavatar']['name'], -4);
			$upload_avatar_dir = "$team_avatars_dir/$avatarID";
			move_uploaded_file($_FILES['teamavatar']['tmp_name'], $upload_avatar_dir);
			if ($preview != 1) {
				mysql_query("UPDATE user_teams SET avatar='$avatarID' WHERE id = $tid") or die(mysql_error());
			}
			$teamimages['avatar'] = $avatarID;
		} else {
			echo "<center><br><b><font color='#ff0000'>"._("The avatar uploaded must be either a JPEG, GIF, or PNG file.")."</font></b></center>";
			return;
		}
	}

	if (!empty($_FILES['teamicon']['tmp_name']) && ($type =="both" || $type == "icon")) {
		if (strtolower(substr($_FILES['teamicon']['name'], -4)) == ".png" || strtolower(substr($_FILES['teamicon']['name'], -4)) == ".jpg" || strtolower(substr($_FILES['teamicon']['name'], -4)) == ".gif") {
			if ($_FILES['teamicon']['size'] > 1048576) {
				echo "<center><br><b><font color='#ff0000'>"._("The icon uploaded is too large. Please limit the file size to 1MB or less.")."</font></b></center>";
				return;
			}
			$iconID = uniqid("icon_").substr($_FILES['teamicon']['name'], -4);
			$upload_icon_dir = "$team_icons_dir/$iconID";
			move_uploaded_file($_FILES['teamicon']['tmp_name'], $upload_icon_dir);
			if ($preview != 1) {
				mysql_query("UPDATE user_teams SET icon='$iconID' WHERE id = $tid");
			}
			$teamimages['icon'] = $iconID;
		} else {
			echo "<center><br><b><font color='#ff0000'>"._("The icon uploaded must be either a JPEG, GIF, or PNG file.")."</font></b></center>";
			return;
		}
	}

	deleteImages();
	return $teamimages;
}

function deleteImages() {
	global $team_avatars_dir, $team_icons_dir;
	$oneHourAgo = time() - 600;
	//Delete unused avatars
	$result = mysql_query("SELECT id,avatar FROM user_teams");
	while ($row = mysql_fetch_assoc($result)) {
		$id = $row['id'];
		$activeAvatars[$id] = $row['avatar'];
	}

	$dir = opendir( $team_avatars_dir );
 	while (false !== ($file = readdir($dir))) {
        	if ($file != "." && $file != ".." && $file != "CVS" && $file != "avatar_default.png" && $file != "avatar_default2.png" && $file != "dp_avatar.png") {
			if (!in_array ($file, $activeAvatars)) {
				if (filemtime("$team_avatars_dir/$file") <= $oneHourAgo) { unlink("$team_avatars_dir/$file"); }
			}
		}
	}
	closedir($dir);

	//Delete unused icons
	$result = mysql_query("SELECT id,icon FROM user_teams");
	while ($row = mysql_fetch_assoc($result)) {
		$id = $row['id'];
		$activeIcons[$id] = $row['icon'];
	}

	$dir = opendir($team_icons_dir);
 	while (false !== ($file = readdir($dir))) {
        	if ($file != "." && $file != ".." && $file != "CVS" && $file != "icon_default.png" && $file != "icon_default2.png" && file != "dp_icon.png") {
			if (!in_array ($file, $activeIcons)) {
				if (filemtime("$team_icons_dir/$file") <= $oneHourAgo) { unlink("$team_icons_dir/$file"); }
			}
		}
	}
	closedir($dir);
}
?>
