<?
$relPath="./pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'html_main.inc');

if (empty($tstart)) { $tstart = 0; }
$popHelpDir='faq/pophelp/teams/edit_';

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

function showTeamProfile($curTeam) {
	global $theme, $userP;

	if ($curTeam['createdby'] == $GLOBALS['pguser']) { $editlink = "&nbsp;<font color='".$theme['color_headerbar_font']."' size='2'>[</font><a href='userteams.php?etid=".$curTeam['id']."'><font color='".$theme['color_headerbar_font']."' size='2'>Edit</font></a><font color='".$theme['color_headerbar_font']."' size='2'>]</font>"; } else { $editlink = ""; }

	if ($curTeam['id'] != 1 && $userP['team_1'] != $curTeam['id'] && $userP['team_2'] != $curTeam['id'] && $userP['team_3'] != $curTeam['id']) {
		$joinquitlink = "&nbsp;<font color='".$theme['color_headerbar_font']."' size='2'>[</font><a href='userteams.php?jtid=".$curTeam['id']."'><font color='".$theme['color_headerbar_font']."' size='2'>Join</font></a><font color='".$theme['color_headerbar_font']."' size='2'>]</font>";
	} elseif ($curTeam['id'] != 1) {
		$joinquitlink = "&nbsp;<font color='".$theme['color_headerbar_font']."' size='2'>[</font><a href='userteams.php?qtid=".$curTeam['id']."'><font color='".$theme['color_headerbar_font']."' size='2'>Quit</font></a><font color='".$theme['color_headerbar_font']."' size='2'>]</font>";
	}

	$last_post = mysql_query("SELECT post_time FROM phpbb_posts WHERE topic_id = ".$curTeam['topic_id']." ORDER BY post_time DESC LIMIT 1");
    	$last_post_date = mysql_result($last_post,0,"post_time");
    	$last_post_date = date("n/j/Y g:i:sA", $last_post_date);

	echo "<table border='1' bordercolor='#111111' cellspacing='0' cellpadding='2' style='border-collapse: collapse' width='95%'>";
	echo "<tr bgcolor='".$theme['color_headerbar_bg']."'><td rowspan='6' align='center' width='30%' bgcolor='".$theme['color_mainbody_bg']."'>";
	echo "<img border='0' src='./users/teams/avatar/".$curTeam['avatar']."' alt='".strip_tags($curTeam['teamname'])."'></td>";
	echo "<td align='left' width='70%' valign='top'><center><font color='".$theme['color_headerbar_font']."'><b>".stripslashes($curTeam['teamname'])."</b></font>".$editlink.$forumlink.$joinquitlink."</center></td></tr>";
	echo "<tr bgcolor='".$theme['color_navbar_bg']."'><td align='left' width='70%' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'><b>Created</b>: ".date("m/d/Y", $curTeam['created'])."</font></td></tr>";
	echo "<tr bgcolor='".$theme['color_navbar_bg']."'><td align='left' width='70%' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'><b>Leader</b>: ".$curTeam['createdby']."</font>&nbsp;<a href='".$GLOBALS['forums_url']."/privmsg.php?mode=post&u=".$curTeam['owner']."'><img src='".$GLOBALS['forums_url']."/templates/subSilver/images/lang_english/icon_pm.gif' width='59' height='18' align='absbottom' border='0' alt='Private Message ".$curTeam['createdby']."'></a></td></tr>";
	echo "<tr bgcolor='".$theme['color_navbar_bg']."'><td align='left' width='70%' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'><b>Total Pages</b>: ".number_format($curTeam['page_count'])."</font></td></tr>";
	echo "<tr bgcolor='".$theme['color_navbar_bg']."'><td align='left' width='70%' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'><b>Description</b>: ".stripslashes($curTeam['team_info'])."</font></td></tr>";
	echo "<tr bgcolor='".$theme['color_navbar_bg']."'><td align='left' width='70%' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'><b>Website</b>: <a href='".$curTeam['webpage']."' target='_new'>".$curTeam['webpage']."</a><br><b>Forums</b>: <a href='".$GLOBALS['forums_url']."/viewtopic.php?t=".$curTeam['topic_id']."'>Team Discussion</a> (Last Post: $last_post_date)</font></td></tr>";
	echo "</table><p>";
}

function showTeamStats($curTeam) {
	global $theme;

	$result = mysql_query("SELECT MAX(date_updated) FROM user_teams_stats");
	$lastupdated = date("n/d/Y h:i:s A T", mysql_result($result,0,0));

	$result = mysql_query("SELECT id FROM user_teams WHERE id != 1 ORDER BY member_count DESC");
	$i = 1;
	while ($row = mysql_fetch_assoc($result)) {
        	if ($row['id'] == $curTeam['id']) { $mbrCountRank = $i; break; }
		$i++;
	}

	$result = mysql_query("SELECT id FROM user_teams WHERE id != 1 ORDER BY page_count DESC");
	$i = 1;
	while ($row = mysql_fetch_assoc($result)) {
        	if ($row['id'] == $curTeam['id']) { $pageCountRank = $i; break; }
		$i++;
	}

	echo "<table border='1' bordercolor='#111111' cellspacing='0' cellpadding='2' style='border-collapse: collapse' width='95%'>";
	echo "<tr><td bgcolor='".$theme['color_headerbar_bg']."'><center><font face='".$theme['font_headerbar']."' color='".$theme['color_headerbar_font']."' size='2'><b>Team Statistics</b><br><font size='1'>(Last Updated: $lastupdated)</font></font></center></td></tr>";
	echo "<tr><td><table border=0' width='100%' cellspacing='0' cellpadding='0'>";
	echo "<tr bgcolor='".$theme['color_navbar_bg']."' onmouseover='javascript:style.background=\"".$theme['color_mainbody_bg']."\"' onmouseout='javascript:style.background=\"".$theme['color_navbar_bg']."\"'>";
		echo "<td width='60%'>Members <i>(Rank)</i></td>";
		echo "<td width='40%'><b>".number_format($curTeam['member_count'])."</b>&nbsp;<i>(#$mbrCountRank)</i></td></tr>";
	echo "<tr bgcolor='".$theme['color_navbar_bg']."' onmouseover='javascript:style.background=\"".$theme['color_mainbody_bg']."\"' onmouseout='javascript:style.background=\"".$theme['color_navbar_bg']."\"'>";
		echo "<td width='60%'>Current Members</td>";
		echo "<td width='40%'><b>".number_format($curTeam['active_members'])."</b></td></tr>";
	echo "<tr bgcolor='".$theme['color_navbar_bg']."' onmouseover='javascript:style.background=\"".$theme['color_mainbody_bg']."\"' onmouseout='javascript:style.background=\"".$theme['color_navbar_bg']."\"'>";
		echo "<td width='60%'>Retired Members</td>";
		echo "<td width='40%'><b>".number_format($curTeam['member_count'] - $curTeam['active_members'])."</b></td></tr>";
	echo "<tr bgcolor='".$theme['color_navbar_bg']."' onmouseover='javascript:style.background=\"".$theme['color_mainbody_bg']."\"' onmouseout='javascript:style.background=\"".$theme['color_navbar_bg']."\"'>";
		echo "<td width='60%'>Total Pages <i>(Rank)</i><font size='2'></font></td>";
		echo "<td width='40%'><b>".number_format($curTeam['page_count'])."</b>&nbsp;<i>(#$pageCountRank)</i></td></tr>";
	echo "<tr bgcolor='".$theme['color_navbar_bg']."' onmouseover='javascript:style.background=\"".$theme['color_mainbody_bg']."\"' onmouseout='javascript:style.background=\"".$theme['color_navbar_bg']."\"'>";
		echo "<td width='60%'>Avg. Pages per Day<font size='2'></font></td>";
		echo "<td width='40%'><b>".number_format($curTeam['daily_average'])."</b></td></tr>";
	echo "</td></tr></table></table><p>";
}

function showTeamMbrs($curTeam) {
	global $theme, $forums_url;

	if (empty($_GET['order'])) { $order = "mbr"; } else { $order = $_GET['order']; }
	if (empty($_GET['direction'])) { $direction = "asc"; } else { $direction = $_GET['direction']; }

	echo "<table border='1' bordercolor='#111111' cellspacing='0' cellpadding='0' style='border-collapse: collapse' width='95%'>";
	echo "<tr><td bgcolor='".$theme['color_headerbar_bg']."'><center><font face='".$theme['font_headerbar']."' color='".$theme['color_headerbar_font']."' size='2'><b>Team Member Details</b></font></center></td></tr>";
	echo "<tr><td><table border='0' width='100%' cellspacing='2' cellpadding='0'>";
	echo "<tr bgcolor='#cccccc'>";

	if ($order == "mbr") {
		if ($direction == "asc") { $newdirection = "desc"; } else { $newdirection = "asc"; }
		echo "<td width='35%' bgcolor='".$theme['color_headerbar_bg']."'><b><a href='userteams.php?tid=".$curTeam['id']."&order=mbr&direction=$newdirection'><font face='".$theme['font_headerbar']."' color='".$theme['color_headerbar_font']."' size='2'>Username</a></font></b></td>";
		$orderby = "username";
	} else {
		echo "<td width='35%' bgcolor='".$theme['color_navbar_bg']."'><b><a href='userteams.php?tid=".$curTeam['id']."&order=mbr&direction=asc'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'>Username</a></font></b></td>";
	}

	if ($order == "pages") {
		if ($direction == "asc") { $newdirection = "desc"; } else { $newdirection = "asc"; }
		echo "<td width='35%' bgcolor='".$theme['color_headerbar_bg']."'><b><a href='userteams.php?tid=".$curTeam['id']."&order=pages&direction=$newdirection'><font face='".$theme['font_headerbar']."' color='".$theme['color_headerbar_font']."' size='2'>Pages Completed</a></font></b></td>";
		$orderby = "pagescompleted";
	} else {
		echo "<td width='35%' bgcolor='".$theme['color_navbar_bg']."'><b><a href='userteams.php?tid=".$curTeam['id']."&order=pages&direction=desc'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'>Pages Completed</a></font></b></td>";
	}

	if ($order == "date") {
		if ($direction == "asc") { $newdirection = "desc"; } else { $newdirection = "asc"; }
		echo "<td width='30%' bgcolor='".$theme['color_headerbar_bg']."'><b><a href='userteams.php?tid=".$curTeam['id']."&order=date&direction=$newdirection'><font face='".$theme['font_headerbar']."' color='".$theme['color_headerbar_font']."' size='2'>Date Joined DP</font></a></b></td>";
		$orderby = "date_created";
	} else {
		echo "<td width='30%' bgcolor='".$theme['color_navbar_bg']."'><b><a href='userteams.php?tid=".$curTeam['id']."&order=date&direction=asc'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'>Date Joined DP</font></a></b></td>";
	}

	echo "</tr></table><table border='0' width='100%' cellspacing='2' cellpadding='0'>";
	$mbrQuery = mysql_query("SELECT * FROM users WHERE team_1 = ".$_GET['tid']." || team_2 = ".$_GET['tid']." || team_3 = ".$_GET['tid']." ORDER BY $orderby $direction");
	$pagesQuery = mysql_query("SELECT username FROM users WHERE team_1 = ".$_GET['tid']." || team_2 = ".$_GET['tid']." || team_3 = ".$_GET['tid']." ORDER BY pagescompleted DESC");

	while ($curMbr = mysql_fetch_assoc($mbrQuery)) {
        	$userIDQuery = mysql_query("SELECT user_id FROM phpbb_users WHERE username = '".$curMbr['username']."'");
		$user_id = mysql_result($userIDQuery,0,"user_id");
		echo "<tr bgcolor='".$theme['color_navbar_bg']."' onmouseover='javascript:style.background=\"".$theme['color_mainbody_bg']."\"' onmouseout='javascript:style.background=\"".$theme['color_navbar_bg']."\"'>";
		echo "<td width='35%'><a href='$forums_url/privmsg.php?mode=post&u=$user_id'>".$curMbr['username']."</a></td>";
		echo "<td width='35%'>".number_format($curMbr['pagescompleted'])."</td>";
		echo "<td width='30%'>".date("m/d/Y", $curMbr['date_created'])."</td></tr>";
	}
	echo "</td></tr></table></table><p>";
}

function showTeamHistory($curTeam) {
	global $theme;
	echo "<table border='1' bordercolor='#111111' cellspacing='0' cellpadding='0' style='border-collapse: collapse' width='95%'>";
	echo "<tr><td bgcolor='".$theme['color_headerbar_bg']."'><center><font face='".$theme['font_headerbar']."' color='".$theme['color_headerbar_font']."' size='2'><b>Team Statistics History</b></font></center></td></tr>";
	echo "<tr><td bgcolor='".$theme['color_navbar_bg']."'><br><center><img src='".$GLOBALS['dynstats_url']."/team_pages_history.php?tid=".$curTeam['id']."' width='600' height='300'></center><br></td></tr>";
	echo "</table><p>";
}

function showEdit($userP,$tstart,$tname,$ttext,$twebpage,$tedit,$tsid,$tavatar,$ticon) {
	global $theme, $teamimages;

	echo "<form enctype='multipart/form-data' id='mkTeam' name='mkTeam' action='userteams.php' method='post' target='_top'>";
	echo "<input type='hidden' name='tstart' value='$tstart'>";
	echo "<input type='hidden' name='tsid' value='$tsid'>";
	if ($tavatar == 1) { echo "<input type='hidden' name='tavatar' value='".$teamimages['avatar']."'>"; }
	if ($ticon == 1) { echo "<input type='hidden' name='ticon' value='".$teamimages['icon']."'>"; }
	echo "<table border='1' bordercolor='#111111' cellspacing='0' cellpadding='3' style='border-collapse: collapse' width='95%'>";
	echo "<tr bgcolor='".$theme['color_headerbar_bg']."'><td><center><font color='".$theme['color_headerbar_font']."'><b>";
	if ($tedit != 1) { echo "Edit Team Information</b></font></td></tr>"; } else { echo "New Proofing Team</b></font></td></tr>"; }
	echo "<tr><td><table border='0' cellspacing='0' cellpadding='0' width='100%'>";
	echo "<tr><td width='35%' align='right'><font color='".$theme['color_mainbody_font']."'><b>Team Name</b>:&nbsp;</td>";
	echo "<td width='65%' align='left'><input type='text' value='$tname' name='teamname' size='50'>&nbsp;<b><a href=\"JavaScript:newHelpWin('teamname');\">?</a></b></td></tr>";
	echo "<tr><td width='35%' align='right'><font color='".$theme['color_mainbody_font']."'><b>Team Webpage</b>:&nbsp;</td>";
	echo "<td width='65%' align='left'><input type='text' value='$twebpage' name='teamwebpage' size='50'>&nbsp;<b><a href=\"JavaScript:newHelpWin('teamwebpage');\">?</a></b></td></tr>";
	echo "<tr><td width='35%' align='right'><font color='".$theme['color_mainbody_font']."'><b>Team Avatar</b>:&nbsp;</td>";
	echo "<td width='65%' align='left'><input type='file' name='teamavatar' size='50'>&nbsp;<b><a href=\"JavaScript:newHelpWin('teamavatar');\">?</a></b></td></tr>";
	echo "<tr><td width='35%' align='right'><font color='".$theme['color_mainbody_font']."'><b>Team Icon</b>:&nbsp;</td>";
	echo "<td width='65%' align='left'><input type='file' name='teamicon' size='50'>&nbsp;<b><a href=\"JavaScript:newHelpWin('teamicon');\">?</a></b></td></tr>";
	echo "</table></td></tr>";
	echo "<tr bgcolor='".$theme['color_navbar_bg']."'><td><center><font color='".$theme['color_navbar_font']."'><b>Team Description</b>&nbsp;";
	echo "<b><a href=\"JavaScript:newHelpWin('teamdesc');\">?</a></b><br><textarea name='text_data' cols='40' rows='6'>$ttext</textarea></center><br></td></tr>";

	if ($tedit == 1 && $userP['team_1'] != 0 && $userP['team_2'] != 0 && $userP['team_3'] != 0) {
    		echo "<tr bgcolor='".$theme['color_mainbody_bg']."'><td><center>You must join the team to create it, which team space would you like to use?<br>";
    		echo "<select name='tteams' title='Team List'>";
    		$teamQuery = mysql_query("SELECT teamname, id FROM user_teams WHERE id = ".$userP['team_1']." || id = ".$userP['team_2']." || id = ".$userP['team_3']."");
    		while ($row = mysql_fetch_assoc($teamQuery)) {
    			echo "<option value='".$row['id']."'>".unstripAllString(strip_tags($row['teamname']),1)."</option>";
    		}
    		echo "</select></center></td></tr>";
	} else {
		echo "<input type='hidden' name='teamall' value='1'>";
	}

  	if($tedit == 1) {
  		echo "<tr bgcolor='".$theme['color_headerbar_bg']."'><td><center>";
  		echo "<input type='submit' name='mkPreview' value='Preview Team Display'>&nbsp;&nbsp;&nbsp;";
  		echo "<input type='submit' name='mkMake' value='Make Team'>&nbsp;&nbsp;&nbsp;";
  		echo "<input type='submit' name='Quit' value='Quit'>";
  		echo "</center></td></tr></table></form>";
  	} else {
  		echo "<tr bgcolor='".$theme['color_headerbar_bg']."'><td><center>";
  		echo "<input type='submit' name='edPreview' value='Preview Changes'>&nbsp;&nbsp;&nbsp;";
  		echo "<input type='submit' name='edMake' value='Save Changes'>&nbsp;&nbsp;&nbsp;";
  		echo "<input type='submit' name='edQuit' value='Quit'>";
  		echo "</center></td></tr></table></form>";
  	}
}

function joinTeam($userP,$pguser,$otid,$jtid) {
	$joinQ = "";
  	if ($userP['team_1'] != $jtid && $userP['team_2'] != $jtid && $userP['team_3'] != $jtid) {
		if ($userP['team_1'] == 0 || $otid == 1) {
			$joinQ = "team_1 = $jtid";
			$qtid = $userP['team_1'];
		} elseif ($userP['team_2'] == 0 || $otid == 2) {
			$joinQ = "team_2 = $jtid";
			$qtid = $userP['team_2'];
		} elseif ($userP['team_3'] == 0 || $otid == 3) {
			$joinQ = "team_3 = $jtid";
			$qtid = $userP['team_3'];
		}

		if (!empty($joinQ)) {
			$teamResult = mysql_query("UPDATE users SET ".$joinQ." WHERE username = '".$GLOBALS['pguser']."' AND u_id = ".$userP['u_id']."");
			if ($teamResult == 1) {
				mysql_query("UPDATE user_teams SET member_count = member_count+1 WHERE id = $jtid");
				mysql_query("UPDATE user_teams SET active_members = active_members+1 WHERE id = $jtid");
				if ($otid != 0) {
					mysql_query("UPDATE user_teams SET active_members = active_members-1 WHERE id = $qtid");
				}
			}
		}
    	} else {
    		$joinQ='mem';
    	}
	return $joinQ;
}

function createThread($tname, $tinfo, $towner, $tid) {
	//Declare variables
	global $code_url;
	$timeposted = time();
	$owner = 527;
	$title = $tname;
	$message = "Team Name: $tname<br>Created By: $towner<br>Info: $tinfo<br>Team Page: <a href='$code_url/userteams.php?tid=$tid'>$code_url/userteams.php?tid=$tid</a><br><br>Use this area to have a discussion with your fellow teammates! :-D<br>";
	$message = addslashes($message);

	$ip_sep = explode('.', $_SERVER['REMOTE_ADDR']);
	$post_ip = sprintf('%02x%02x%02x%02x', $ip_sep[0], $ip_sep[1], $ip_sep[2], $ip_sep[3]);

	//Add Topic into phpbb_topics
	$insert_topic = mysql_query("INSERT INTO phpbb_topics (topic_id, forum_id, topic_title, topic_poster, topic_time, topic_views, topic_replies, topic_status, topic_vote, topic_type, topic_first_post_id, topic_last_post_id, topic_moved_id) VALUES (NULL, 11, '$title', $owner, $timeposted, 0, 0, 0, 0, 0, 1, 1, 0)");
	$topic_id = mysql_insert_id();

	//Add Post into phpbb_posts
	$insert_post = mysql_query("INSERT INTO phpbb_posts (post_id, topic_id, forum_id, poster_id, post_time, poster_ip, post_username, enable_bbcode, enable_html, enable_smilies, enable_sig, post_edit_time, post_edit_count) VALUES (NULL,$topic_id, 11, $owner, $timeposted, '$post_ip', '', 1, 0, 1, 0, NULL, 0)");
	$post_id = mysql_insert_id();

	//Add Post Text into phpbb_posts_text
	$insert_post_text = mysql_query("INSERT INTO phpbb_posts_text (post_id, bbcode_uid, post_subject, post_text) VALUES ($post_id, '', '$title', '$message')");

	//Update phpbb_topics with post_id
	$update_topic = mysql_query("UPDATE phpbb_topics SET topic_first_post_id=$post_id, topic_last_post_id=$post_id WHERE topic_id=$topic_id");

	//Update forum post count
	$get_count = mysql_query("SELECT forum_posts, forum_topics FROM phpbb_forums WHERE forum_id=11");
	while($row = mysql_fetch_array($get_count)) {
	$forum_posts = $row['forum_posts'];
	$forum_topics = $row['forum_topics'];
	$forum_posts++;
	$forum_topics++;
	$update_count = mysql_query("UPDATE phpbb_forums SET forum_posts=$forum_posts, forum_topics=$forum_topics, forum_last_post_id=$post_id WHERE forum_id=11");
	}

	//Update teams db with topic_id so it can be deleted
	$update_project = mysql_query("UPDATE user_teams SET topic_id=$topic_id WHERE id='$tid'");
}

function uploadImages($preview,$tid,$type) {
	if (!empty($_FILES['teamavatar']['tmp_name']) && ($type == "both" || $type == "avatar")) {
		if (strtolower(substr($_FILES['teamavatar']['name'], -4)) == ".png" || strtolower(substr($_FILES['teamavatar']['name'], -4)) == ".jpg" || strtolower(substr($_FILES['teamavatar']['name'], -4)) == ".gif") {
			if ($_FILES['teamavatar']['size'] > 2097152) {
				echo "<center><br><b><font color='#ff0000'>The avatar uploaded is too large.  Please limit the file size to 2MB or less.</font></b></center>";
				return;
			}
			$avatarID = uniqid("avatar_").substr($_FILES['teamavatar']['name'], -4);
			$upload_avatar_dir = $GLOBALS['code_dir']."/users/teams/avatar/".$avatarID;
			move_uploaded_file($_FILES['teamavatar']['tmp_name'], $upload_avatar_dir);
			if ($preview != 1) {
				mysql_query("UPDATE user_teams SET avatar='$avatarID' WHERE id = $tid") or die(mysql_error());
			}
			$teamimages['avatar'] = $avatarID;
		} else {
			echo "<center><br><b><font color='#ff0000'>The avatar uploaded must be either a JPEG, GIF, or PNG file.</font></b></center>";
			return;
		}
	}

	if (!empty($_FILES['teamicon']['tmp_name']) && ($type =="both" || $type == "icon")) {
		if (strtolower(substr($_FILES['teamicon']['name'], -4)) == ".png" || strtolower(substr($_FILES['teamicon']['name'], -4)) == ".jpg" || strtolower(substr($_FILES['teamicon']['name'], -4)) == ".gif") {
			if ($_FILES['teamicon']['size'] > 1048576) {
				echo "<center><br><b><font color='#ff0000'>The icon uploaded is too large.  Please limit the file size to 1MB or less.</font></b></center>";
				return;
			}
			$iconID = uniqid("icon_").substr($_FILES['teamicon']['name'], -4);
			$upload_icon_dir = $GLOBALS['code_dir']."/users/teams/icon/".$iconID;
			move_uploaded_file($_FILES['teamicon']['tmp_name'], $upload_icon_dir);
			if ($preview != 1) {
				mysql_query("UPDATE user_teams SET icon='$iconID' WHERE id = $tid");
			}
			$teamimages['icon'] = $iconID;
		} else {
			echo "<center><br><b><font color='#ff0000'>The icon uploaded must be either a JPEG, GIF, or PNG file.</font></b></center>";
			return;
		}
	}

	deleteImages();
	return $teamimages;
}

function deleteImages() {
	global $code_dir;
	$oneHourAgo = time() - 10;
	//Delete unused avatars
	$result = mysql_query("SELECT id,avatar FROM user_teams");
	while ($row = mysql_fetch_assoc($result)) {
		$id = $row['id'];
		$activeAvatars[$id] = $row['avatar'];
	}

	$dir = opendir($code_dir."/users/teams/avatar/");
 	while (false !== ($file = readdir($dir))) {
        	if ($file != "." && $file != ".." && $file != "CVS" && $file != "avatar_default.png" && $file != "avatar_default2.png" && $file != "dp_avatar.png") {
			if (!in_array ($file, $activeAvatars)) {
				if (filemtime($code_dir."/users/teams/avatar/".$file) <= $oneHourAgo) { unlink($code_dir."/users/teams/avatar/".$file); }
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

	$dir = opendir($code_dir."/users/teams/icon/");
 	while (false !== ($file = readdir($dir))) {
        	if ($file != "." && $file != ".." && $file != "CVS" && $file != "icon_default.png" && $file != "icon_default2.png" && file != "dp_icon.png") {
			if (!in_array ($file, $activeIcons)) {
				if (filemtime($code_dir."/users/teams/icon/".$file) <= $oneHourAgo) { unlink($code_dir."/users/teams/icon/".$file); }
			}
		}
	}
	closedir($dir);
}

if (!empty($_GET['tid'])) {
	include_once($relPath.'theme.inc');
	$tResult=mysql_query("SELECT * FROM user_teams WHERE id = $tid");
	$curTeam=mysql_fetch_assoc($tResult);
	theme($curTeam['teamname']." Stats", "header");
	echo "<br><center>";
	showTeamProfile($curTeam);
	if ($_GET['tid'] != 1) {
		showTeamStats($curTeam);
		showTeamMbrs($curTeam);
		showTeamHistory($curTeam);
	}
	echo "</center>";
	theme("", "footer");
	exit();
}  elseif (!empty($_GET['jtid'])) {
	$otid=isset($otid)?$otid:0;
	if (empty($_GET['otid'])) { $otid = 0; } else { $otid = $_GET['otid']; }
  	if ($_GET['jtid'] != 1) {
    		$joinQ=joinTeam($userP,$pguser,$otid,$_GET['jtid']);
    		if (empty($joinQ)) {
			include_once($relPath.'theme.inc');
			theme("Three Team Maximum!", "header");
			echo "<br><center>";
			echo "<table border='1' bordercolor='#111111' cellspacing='0' cellpadding='3' style='border-collapse: collapse' width='95%'>";
			echo "<tr bgcolor='".$theme['color_headerbar_bg']."'><td colspan='3'><b><center><font face='".$theme['font_headerbar']."' color='".$theme['color_headerbar_font']."'>Three Team Maximum</font></center></b></td></tr>";
			echo "<tr bgcolor='".$theme['color_mainbody_bg']."'><td colspan='3'><center><font face='".$theme['font_mainbody']."' color='".$theme['color_mainbody_font']."' size='2'>You have already joined three teams.<br>Which team would you like to replace?</font></center></td></tr>";
			echo "<tr bgcolor='".$theme['color_navbar_bg']."'>";
			$teamR=mysql_query("SELECT teamname FROM user_teams WHERE id='".$userP['team_1']."'");
			echo "<td width='33%'><center><b><a href='userteams.php?jtid=".$_GET['jtid']."&otid=1'>".mysql_result($teamR,0,'teamname')."</a></b></center></td>";
			$teamR=mysql_query("SELECT teamname FROM user_teams WHERE id='".$userP['team_2']."'");
			echo "<td width='33%'><center><b><a href='userteams.php?jtid=".$_GET['jtid']."&otid=2'>".mysql_result($teamR,0,'teamname')."</a></b></center></td>";
			$teamR=mysql_query("SELECT teamname FROM user_teams WHERE id='".$userP['team_3']."'");
			echo "<td width='34%'><center><b><a href='userteams.php?jtid=".$_GET['jtid']."&otid=3'>".mysql_result($teamR,0,'teamname')."</a></b></center></td>";
			echo "</tr><tr bgcolor='".$theme['color_headerbar_bg']."'><td colspan='3'><center><b><a href='userteams.php?tid=".$_GET['jtid']."'><font face='".$theme['font_headerbar']."' color='".$theme['color_headerbar_font']."' size='2'>Do Not Join Team</font></a></b></center></td></tr></table></center>";
			theme("", "footer");
      		} else if ($joinQ == "mem") {
			metarefresh(4,"userteams.php?tid=".$_GET['jtid']."",'Unable to Join the Team','You are already a member of this team....');
		} else {
	        	$cookieC->setUserPrefs($pguser);
			metarefresh(0,"userteams.php?tid=".$_GET['jtid']."",'Join the Team','Joining the team....');
		}
	} else {
		metarefresh(3,"userteams.php?tid=".$_GET['jtid']."",'Cannot Join Team','Unable to join team. You are already a member.');
	}
	exit;
} elseif (!empty($_GET['qtid'])) {
	if ($_GET['qtid'] != 1 && ($userP['team_1'] == $_GET['qtid'] || $userP['team_2'] == $_GET['qtid'] || $userP['team_3'] == $_GET['qtid'])) {
	    	$quitQuery="UPDATE users SET ";
    		if ($userP['team_1'] == $_GET['qtid']) { $quitQuery.="team_1 = '0'"; }
    		if ($userP['team_2'] == $_GET['qtid']) { $quitQuery.="team_2 = '0'"; }
    		if ($userP['team_3'] == $_GET['qtid']) { $quitQuery.="team_3 = '0'"; }
    		$quitQuery.=" WHERE username='$pguser' AND u_id='".$userP['u_id']."'";
    		$teamResult=mysql_query($quitQuery);
    		if ($teamResult==1) {
        		mysql_query("UPDATE user_teams SET active_members = active_members-1 WHERE id='".$_GET['qtid']."'");
        		$cookieC->setUserPrefs($pguser);
        		metarefresh(0,"userteams.php?tid=".$_GET['qtid']."",'Quit the Team','Quitting the team....');
        		exit;
      		}
	}  else {
		metarefresh(3,"userteams.php?tid=".$_GET['qtid']."",'Not a member','Unable to quit team....');
		exit;
	}
} elseif (!empty($_GET['etid']) || !empty($_POST['edPreview']) || !empty($_POST['edMake'])) {
	if (!empty($_POST['tsid'])) { $ntid = $_POST['tsid']; } else { $ntid = $_GET['etid']; }
  	$tResult=mysql_query("SELECT * FROM user_teams WHERE id=$ntid");
  	$curTeam=mysql_fetch_assoc($tResult);

  	if ($userP['u_id'] != $curTeam['owner']) {
    		metarefresh(4,"userteams.php?tid=$ntid",'Authorization Failed','You are not authorized to edit this team....');
		exit;
	}

	if (isset($_GET['etid'])) {
		include($relPath.'js_newpophelp.inc');
		include_once($relPath.'theme.inc');
		theme("Edit ".$curTeam['teamname'], "header");
		echo "<center><br>";
		showEdit($userP,$tstart,unstripAllString($curTeam['teamname'],0),unstripAllString($curTeam['team_info'],1),unstripAllString($curTeam['webpage'],1),0,$ntid,0,0);
		echo "</center>";
		theme("", "footer");
	} elseif (isset($_POST['edPreview'])) {
    		include($relPath.'js_newpophelp.inc');
    		include_once($relPath.'theme.inc');
    		theme("Preview ".$_POST['teamname'], "header");
    		$teamimages = uploadImages(1,$ntid,"both");
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
		showEdit($userP,$tstart,htmlentities(stripslashes($_POST['teamname'])),stripslashes($_POST['text_data']),stripslashes($_POST['teamwebpage']),0,$ntid,$tavatar,$ticon);
    		echo "<br>";
    		showTeamProfile($curTeam);
    		echo "</center><br>";
    		theme("", "footer");
    	} elseif (isset($_POST['edMake'])) {
    		if (!empty($_POST['tavatar'])) {
    			mysql_query("UPDATE user_teams SET avatar='".$_POST['tavatar']."' WHERE id = $ntid");
    		} elseif (!empty($_FILES['teamavatar'])) {
    			uploadImages(0,$ntid,"avatar");
    		}

    		if (!empty($_POST['ticon'])) {
    			mysql_query("UPDATE user_teams SET icon='".$_POST['ticon']."' WHERE id = $ntid");
    		} elseif (!empty($_FILES['teamicon'])) {
    			uploadImages(0,$ntid,"icon");
    		}

    		mysql_query("UPDATE user_teams SET teamname='".addslashes(stripAllString($_POST['teamname']))."', team_info='".addslashes(stripAllString($_POST['text_data']))."', webpage='".addslashes(stripAllString($_POST['teamwebpage']))."' WHERE id='$ntid'");
      		metarefresh(0,"userteams.php?tid=$ntid",'Saving Team Update','Updating team....');
    	}
	exit;
} elseif (!empty($_GET['ctid']) || !empty($_POST['mkPreview']) || !empty($_POST['mkMake'])) {
	if (isset($_GET['ctid'])) {
		include($relPath.'js_newpophelp.inc');
		include_once($relPath.'theme.inc');
		theme("Create a New Team", "header");
		echo "<center><br>";
		showEdit($userP,$tstart,"","","",1,0,0,0);
		echo "</center>";
		theme("", "footer");
	} else if (isset($_POST['mkPreview'])) {
		include($relPath.'js_newpophelp.inc');
		include_once($relPath.'theme.inc');
		theme("Preview ".$_POST['teamname'], "header");
		uploadImages(1,$ntid);
		$curTeam['teamname'] = stripAllString($_POST['teamname']);
    		$curTeam['team_info'] = stripAllString($_POST['text_data']);
    		$curTeam['webpage'] = stripAllString($_POST['teamwebpage']);
    		$curTeam['createdby'] = $pguser;
    		$curTeam['created'] = time();
    		$curTeam['page_count'] = 0;
    		if (!empty($_FILES['teamavatar']['tmp_name'])) {
    			$curTeam['avatar'] = $_FILES['teamavatar']['name'];
    			$tavatar = 1;
    		}
    		if (!empty($_FILES['teamicon']['tmp_name'])) {
    			$ticon = 1;
    		}

		echo "<center><br>";
		showEdit($userP,$tstart,htmlentities(stripslashes($_POST['teamname'])),stripslashes($_POST['text_data']),stripslashes($_POST['teamwebpage']),1,0,$tavatar,$ticon);
		echo "<br>";
		showTeamProfile($curTeam);
		echo "</center><br>";
		theme("", "footer");
	} else if (isset($_POST['mkMake'])) {
      		mysql_query("INSERT INTO user_teams (teamname,team_info,webpage,createdby,owner,created) VALUES('".addslashes(stripAllString($_POST['teamname']))."','".addslashes(stripAllString($_POST['text_data']))."','".addslashes(stripAllString($_POST['teamwebpage']))."','$pguser','{$userP['u_id']}','".time()."')");
		$jtid=mysql_insert_id($db_link);

		if (!empty($_POST['tavatar'])) {
    			mysql_query("UPDATE user_teams SET avatar='".$_POST['tavatar']."' WHERE id = $jtid");
    		} elseif (!empty($_FILES['teamavatar'])) {
    			uploadImages(0,$jtid);
    		}

    		if (!empty($_POST['ticon'])) {
    			mysql_query("UPDATE user_teams SET icon='".$_POST['ticon']."' WHERE id = $jtid");
    		} elseif (!empty($_FILES['teamicon'])) {
    			uploadImages(0,$jtid);
    		}

		createThread($_POST['teamname'],$_POST['text_data'],$pguser,$jtid);
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
		joinTeam($userP,$pguser,$otid,$jtid);
		// update cookie
        	$cookieC->setUserPrefs($pguser);
		metarefresh(0,"userteams.php?tid=$jtid",'Join the Team','Joining the team....');
	}
	exit;
}

include_once($relPath.'theme.inc');
theme("View Teams", "header");
echo "<center><br>";

if (empty($_GET['order'])) {
	$order = "id";
	$direction = "asc";
} else {
	$order = $_GET['order'];
	$direction = $_GET['direction'];
}

if (!empty($_GET['tstart'])) { $tstart = $_GET['tstart']; } else { $tstart = 0; }

$tResult=mysql_query("SELECT teamname, id, icon, member_count, page_count FROM user_teams ORDER BY $order $direction LIMIT $tstart,20");
$tRows=mysql_num_rows($tResult);

//Display of user teams
echo "<table border='1' bordercolor='#111111' cellspacing='0' cellpadding='4' style='border-collapse: collapse' width='95%'>";
echo "<tr bgcolor='".$theme['color_headerbar_bg']."'><td colspan='6' align='center'><b><font color='".$theme['color_headerbar_font']."'>User Teams</font></b></td></tr>";
echo "<tr bgcolor='".$theme['color_navbar_bg']."'>";
	echo "<td align='center'><b>Icon</b></td>";
	if ($order == "id" && $direction == "asc") { $newdirection = "desc"; } else { $newdirection = "asc"; }
		echo "<td align='center'><b><a href='userteams.php?tstart=$tstart&order=id&direction=$newdirection'>ID</a></b></td>";
	if ($order == "teamname" && $direction == "asc") { $newdirection = "desc"; } else { $newdirection = "asc"; }
		echo "<td align='center'><b><a href='userteams.php?tstart=$tstart&order=teamname&direction=$newdirection'>Team Name</a></b></td>";
	if ($order == "member_count" && $direction == "asc") { $newdirection = "desc"; } else { $newdirection = "asc"; }
		echo "<td align='center'><b><a href='userteams.php?tstart=$tstart&order=member_count&direction=$newdirection'>Total Members</a></b></td>";
	if ($order == "page_count" && $direction == "asc") { $newdirection = "desc"; } else { $newdirection = "asc"; }
		echo "<td align='center'><b><a href='userteams.php?tstart=$tstart&order=page_count&direction=$newdirection'>Page Count</a></b></td>";
	echo "<td align='center'><b>Options</b></td>";
echo "</tr>";
if (!empty($tRows)) {
	while ($row = mysql_fetch_assoc($tResult)) {
        	if (($i % 2) == 0) { echo "<tr bgcolor='".$theme['color_mainbody_bg']."'>"; } else { echo "<tr bgcolor='".$theme['color_navbar_bg']."'>"; }
		echo "<td align='center'><a href='userteams.php?tid=".$row['id']."'><img src='./users/teams/icon/".$row['icon']."' width='25' height='25' alt='".strip_tags($row['teamname'])."' border='0'></a></td>";
		echo "<td align='center'><b>".$row['id']."</b></td>";
		echo "<td>".$row['teamname']."</td>";
		echo "<td align='center'>".$row['member_count']."</td>";
		echo "<td align='center'>".$row['page_count']."</td>";
		echo "<td align='center'><b><a href='userteams.php?tid=".$row['id']."'>View</a>&nbsp;";
		if ($row['id'] != 1 && $userP['team_1'] != $row['id'] && $userP['team_2'] != $row['id'] && $userP['team_3'] != $row['id']) {
			echo "<a href='userteams.php?jtid=".$row['id']."'>Join</a></b></td>";
		} elseif ($row['id'] != 1) {
			echo "<a href='userteams.php?qtid=".$row['id']."'>Quit</a></b></td>";
		}
		echo "</tr>";
		$i++;
	}
} else {
	echo "<tr bgcolor='".$theme['color_mainbody_bg']."'><td colspan='6' align='center'><b>No more teams available.</b></td></tr>";
}

echo "<tr bgcolor='".$theme['color_mainbody_bg']."'><td colspan='3' align='left'>";
if (!empty($tstart)) {
	echo "<b><a href='userteams.php?order=$order&direction=$direction&tstart=".($tstart-20)."'>Previous</a></b>";
}
echo "&nbsp;</td><td colspan='3' align='right'>&nbsp;";
if ($tRows == 20) {
	echo "<b><a href='userteams.php?order=$order&direction=$direction&tstart=".($tstart+20)."'>Next</a></b>";
}
echo "</td></tr>";
echo "<tr bgcolor='".$theme['color_headerbar_bg']."'><td colspan='6' align='center'><b><a href='userteams.php?ctid=1&tstart=$tstart'><font color='".$theme['color_headerbar_font']."'>Create a New Team</font></a></b></td></tr>";
echo "</table><p>";
theme("", "footer");
?>