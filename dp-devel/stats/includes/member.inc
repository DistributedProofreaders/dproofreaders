<?
//A file full of member statistic functions

include_once($relPath.'prefs_options.inc'); // PRIVACY_*

function showMbrProfile($curMbr) {
	global $theme;

	$now = time();
	$daysInExistenceInteger = floor(($now - $curMbr['date_created'])/86400);
	$daysInExistenceString = number_format($daysInExistenceInteger)._(" days"); // '1,234 days'
	$daysSinceLogin = number_format(floor(($now - $curMbr['last_login'])/86400));
	if (empty($daysSinceLogin)) { $daysSinceLogin = _("Today!"); } else { $daysSinceLogin = $daysSinceLogin._(" days"); }

	$rankArray = mbrRank($curMbr['username']);
	$bestDay = bestDayEver($curMbr['u_id']);
	$result = mysql_query("SELECT date_updated, rank FROM member_stats WHERE u_id = ".$curMbr['u_id']." ORDER BY rank ASC LIMIT 1");
	$highestRank = mysql_result($result, 0, "rank");
	$highestRankDate = mysql_result($result, 0, "date_updated");

	if ($daysInExistenceInteger > 0) {
	        $daily_Average = $curMbr['pagescompleted']/$daysInExistenceInteger;
	} else {
		$daily_Average = 0;
	}

	if ($rankArray['curMbrRank'] <= $highestRank) { $highestRank = $rankArray['curMbrRank']; $highestRankDate = time(); }

	if (empty($curMbr['user_avatar'])) { $avatar = $GLOBALS['code_url']."/graphics/default_avatar.gif"; } else { $avatar = $GLOBALS['forums_url']."/images/avatars/".$curMbr['user_avatar']; }

	echo "<table border='1' bordercolor='#111111' cellspacing='0' cellpadding='0' style='border-collapse: collapse' width='99%'>";
	echo "<tr bgcolor='".$theme['color_mainbody_bg']."'><td width='35%' align='center' height='90'><img border='0' src='$avatar' alt='avatar'></td>";
	echo "<td rowspan='2' bgcolor='".$theme['color_navbar_bg']."' align='left' valign='top' width='65%'><table border='0' width='100%' cellspacing='0' cellpadding='1'>";
	echo "<tr bgcolor='".$theme['color_headerbar_bg']."'><td colspan='2' align='left' valign='top' bgcolor='".$theme['color_headerbar_bg']."'><center><font face='".$theme['font_headerbar']."' color='".$theme['color_headerbar_font']."' size='2'><b>"._("Statistics")." </b></font><a href='".$GLOBALS['code_url']."/stats/members/mbr_xml.php?username=".$curMbr['username']."'><img src='".$GLOBALS['code_url']."/graphics/xml.gif' border='0' width='36' height='14' style='vertical-align:middle'></a></center></td></tr>";
        echo "<tr bgcolor='".$theme['color_navbar_bg']."'><td width='30%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'><b>"._("Date Joined")."</b>:</font></td><td width='70%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'>".date("m/d/Y", $curMbr['date_created'])."&nbsp;($daysInExistenceString)</font></td></tr>";
	echo "<tr bgcolor='".$theme['color_navbar_bg']."'><td width='30%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'><b>"._("Last Login")."</b>:</font></td><td width='70%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'>".date("m/d/Y", $curMbr['last_login'])."&nbsp;($daysSinceLogin)</font></td></tr>";
        echo "<tr bgcolor='".$theme['color_navbar_bg']."'><td width='30%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'><b>"._("Total Pages")."</b>:</font></td><td width='70%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'>".number_format($curMbr['pagescompleted'])." "._("pages")." (".pageCountPercentage($curMbr['pagescompleted'])._("% of total").")</font></td></tr>";
        echo "<tr bgcolor='".$theme['color_navbar_bg']."'><td width='30%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'><b>"._("Overall Rank")."</b>:</font></td><td width='70%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'>".number_format($rankArray['curMbrRank'])." "._("out of")." ".number_format($rankArray['totalRanks']).rankPosition($rankArray['curMbrRank'], $curMbr['u_id'], 0, $rankArray)."</font></td></tr>";
        echo "<tr bgcolor='".$theme['color_navbar_bg']."'><td width='30%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'><b>"._("Highest Rank")."</b>:</font></td><td width='70%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'>".number_format($highestRank)."&nbsp;<i>(".date("M. jS, Y", $highestRankDate).")</i>&nbsp;".rankPosition($highestRank, $curMbr['u_id'], 1, $rankArray)."</font></td></tr>";
        echo "<tr bgcolor='".$theme['color_navbar_bg']."'><td width='30%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'><b>"._("Best Day Ever")."</b>:</font></td><td width='70%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'>".number_format($bestDay['count'])." "._("pages")." <i>(".$bestDay['time'].")</i></font></td></tr>";
        echo "<tr bgcolor='".$theme['color_navbar_bg']."'><td width='30%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'><b>"._("Daily Average")."</b>:</font></td><td width='70%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'>".number_format($daily_Average)." "._("pages")."</font></td></tr>";

	if (!empty($curMbr['user_from']) || !empty($curMbr['user_occ']) || !empty($curMbr['user_interests']) || !empty($curMbr['user_website'])) { echo "<tr bgcolor='".$theme['color_navbar_bg']."'><td colspan='2' align='center' valign='middle'><hr width='90%'></td></tr>"; }
        if (!empty($curMbr['user_from'])) { echo "<tr bgcolor='".$theme['color_navbar_bg']."'><td width='30%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'><b>Location</b>:</font></td><td width='70%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'>".$curMbr['user_from']."</a></font></td></tr>"; }
        if (!empty($curMbr['user_occ'])) { echo "<tr bgcolor='".$theme['color_navbar_bg']."'><td width='30%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'><b>Occupation</b>:</font></td><td width='70%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'>".$curMbr['user_occ']."</a></font></td></tr>"; }
	if (!empty($curMbr['user_interests'])) { echo "<tr bgcolor='".$theme['color_navbar_bg']."'><td width='30%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'><b>Interests</b>:</font></td><td width='70%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'>".$curMbr['user_interests']."</font></td></tr>"; }
        if (!empty($curMbr['user_website'])) { echo "<tr bgcolor='".$theme['color_navbar_bg']."'><td width='30%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'><b>Website</b>:</font></td><td width='70%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'><a href='".$curMbr['user_website']."'>".$curMbr['user_website']."</a></font></td></tr>"; }

        if (isset($GLOBALS['pguser']) && $curMbr['username'] == $GLOBALS['pguser']) {
        	echo "<tr bgcolor='".$theme['color_navbar_bg']."'><td width='30%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'><b>Edit</b>:</font></td><td width='70%' align='left' valign='top'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'><a href='".$GLOBALS['code_url']."/userprefs.php'>"._("Preferences")."</a> | <a href='".$GLOBALS['forums_url']."/profile.php?mode=editprofile&u=".$curMbr['user_id']."'>"._("Profile")."</a></font></td></tr>";
        }

	echo "</table></td></tr>";
	if ($curMbr['u_privacy'] != PRIVACY_ANONYMOUS || $curMbr['username'] == $GLOBALS['pguser']) {
		echo "<tr bgcolor='".$theme['color_mainbody_bg']."'><td width='35%' valign='top' align='left'><center><font face='".$theme['font_mainbody']."' size='2'><b>"._("Contact")." ".$curMbr['username'].":</b></font></center>";
		messageCenter($curMbr);
	}
	echo "</td></tr></table><p>";
}

function showMbrStatus($siteadmin, $manager, $postprocessor, $pagescompleted) {
	global $code_url;
	$mbrStatus = "";

	if ($siteadmin == "yes") { $mbrStatus .= "<img style='vertical-align: middle' src='$code_url/graphics/icon_sa.gif' border='0' alt='"._("Site Administrator")."' title='"._("Site Administrator")."' width='25' height='21'>&nbsp;"; }
	if ($manager == "yes") { $mbrStatus .= "<img style='vertical-align: middle' src='$code_url/graphics/icon_pm.gif' border='0' alt='"._("Project Manager")."' title='"._("Project Manager")."' width='50' height='25'>&nbsp;"; }
	if ($postprocessor == "yes") { $mbrStatus .= "<img style='vertical-align: middle' src='$code_url/graphics/icon_pp.gif' border='0' alt='"._("Post-Processor")."' title='"._("Post-Processor")."' width='25' height='22'>&nbsp;"; }
	if ($pagescompleted >= 50) { $mbrStatus .= "<img style='vertical-align: middle' src='$code_url/graphics/icon_proofer.gif' border='0' alt='"._("Proofer")."' title='"._("Proofer")."' width='11' height='25'>&nbsp;"; }
	if ($pagescompleted < 50) { $mbrStatus .= "<img style='vertical-align: middle' src='$code_url/graphics/icon_newbie.gif' border='0' alt='"._("Newbie")."' title='"._("Newbie")."' width='25' height='18'>&nbsp;"; }

	$mbrStatus .= "&nbsp;";

	return $mbrStatus;
}

function dailyAverage($u_id) {
	$avgPageCount = mysql_query("SELECT AVG(daily_pagescompleted) AS avgCount FROM member_stats WHERE u_id = $u_id");
	$daily_average = mysql_result($avgPageCount, 0, "avgCount");
	if (empty($daily_average)) { $daily_average = 0; }
	return $daily_average;
}

function bestDayEver($u_id) {
	$result = mysql_query("SELECT date_updated, daily_pagescompleted FROM member_stats WHERE u_id = $u_id ORDER BY daily_pagescompleted DESC LIMIT 1");
	$bestDay['count'] = mysql_result($result, 0, "daily_pagescompleted");
	$bestDay['time'] = date("M. jS, Y", (mysql_result($result, 0, "date_updated")-86400));
	if (empty($bestDay['count'])) { $bestDay['count'] = 0; $bestDay['time'] = date("M. jS, Y", time()); }
	return $bestDay;
}

function messageCenter($curMbr) {
	global $forums_url, $theme;

	echo "<table border='0' width='100%' cellspacing='0' cellpadding='1'>";
	echo "<tr><td align='center' colspan='2'>".showMbrStatus($curMbr['sitemanager'], $curMbr['manager'], $curMbr['postprocessor'], $curMbr['pagescompleted'])."</td></tr>";
	if (!empty($curMbr['user_viewemail'])) { echo "<tr><td align='left'><font face='".$theme['font_mainbody']."' size='2'>"._("E-mail:")."</font></td><td align='left'><a href='mailto:".$curMbr['email']."'><img src='$forums_url/templates/subSilver/images/lang_english/icon_email.gif' alt='E-mail ".$curMbr['username']."' border='0'></a></td></tr>"; }
	echo "<tr><td align='left'><font face='".$theme['font_mainbody']."' size='2'>Private Message:</font></td><td align='left'><a href='$forums_url/privmsg.php?mode=post&u=".$curMbr['user_id']."'><img src='$forums_url/templates/subSilver/images/lang_english/icon_pm.gif' alt='"._("Private Message")." ".$curMbr['username']."' border='0'></a></td></tr>";
	if (!empty($curMbr['user_aim'])) { echo "<tr><td align='left'><font face='".$theme['font_mainbody']."' size='2'>AIM:</font></td><td align='left'><a href='aim:goim?screenname=".$curMbr['user_aim']."&message=Hello!+Are+you+there?++I+am+IMing+you+from+DP'><img src='$forums_url/templates/subSilver/images/lang_english/icon_aim.gif' alt='AIM ".$curMbr['username']."' border='0'></a></td></tr>"; }
	if (!empty($curMbr['user_msnm'])) { echo "<tr><td align='left'><font face='".$theme['font_mainbody']."' size='2'>MSN:</font></td><td align='left'><a href='javascript:alert(\"".$curMbr['user_msnm']."\")'><img src='$forums_url/templates/subSilver/images/lang_english/icon_msnm.gif' alt='MSN ".$curMbr['username']."' border='0'></a></td></tr>"; }
	if (!empty($curMbr['user_yim'])) { echo "<tr><td align='left'><font face='".$theme['font_mainbody']."' size='2'>Yahoo:</font></td><td align='left'><a href='http://edit.yahoo.com/config/send_webmesg?.target=".$curMbr['user_yim']."&.src=pg' target='_new'><img src='$forums_url/templates/subSilver/images/lang_english/icon_yim.gif' alt='Yahoo Message ".$curMbr['username']."' border='0'></a></td></tr>"; }
	if (!empty($curMbr['user_icq'])) { echo "<tr><td align='left'><font face='".$theme['font_mainbody']."' size='2'>ICQ:</font></td><td align='left'><a href='http://wwp.icq.com/scripts/search.dll?to=".$curMbr['user_icq']."' target='_new'><img src='$forums_url/templates/subSilver/images/lang_english/icon_icq.gif' alt='ICQ ".$curMbr['username']."' border='0'></a></td></tr>"; }
	echo "</table>";
}

function pageCountPercentage($pagescompleted) {
	$result = mysql_query("SELECT SUM(pages) AS total_pagescompleted FROM pagestats");
	$total_pagescompleted = mysql_result($result, 0, "total_pagescompleted");
	if ($total_pagescompleted == 0)
	    $percentTotal = number_format(0,2);
	else
	    $percentTotal = number_format((($pagescompleted/$total_pagescompleted)*100), 2);
	if ($pagescompleted > 0 && $percentTotal == 0.00) { $percentTotal = 0.01; }
	return $percentTotal;
}

function mbrRank($username) {
	$result = mysql_query("SELECT username, pagescompleted FROM users WHERE pagescompleted > 0 ORDER BY pagescompleted DESC");
	$i = 0;
	$lastcompleted = -1;

	while ($row = mysql_fetch_assoc($result)) {
		if ($row['username'] == $username) {
			$userindex = $i;
		}
	    	if ($row['pagescompleted'] == $lastcompleted) {
        		$rankArray['rank'][$i] = $rankArray['rank'][$i - 1];
    		} else {
        		$rankArray['rank'][$i] = $i+1;
    		}

    		$rankArray['username'][$i] = $row['username'];
    		$rankArray['pages'][$i] = $row['pagescompleted'];
    		$lastcompleted = $row['pagescompleted'];
    		$i++;
	}

	if (!isset($userindex)) {
		$userindex = $i+1;
		$rankArray['username'][$userindex] = $username;
		$rankArray['pages'][$userindex] = 0;
		$rankArray['rank'][$userindex] = $userindex;
		$rankArray['totalRanks'] = $userindex;
	} else {
		$rankArray['totalRanks'] = $rankArray['rank'][$i-1];
	}

	$rankArray['curMbrRank'] = $rankArray['rank'][$userindex];
	$rankArray['curMbrIndex'] = $userindex;

	return $rankArray;
}

function rankPosition($rank, $u_id, $highestrank, $rankArray) {

		if (empty($highestrank)) {
			// $rank is current rank. Compare it to rank as of latest midnight.
			$currentRank = $rank;
			$result = mysql_query("SELECT rank FROM member_stats WHERE u_id = $u_id ORDER BY date_updated DESC LIMIT 1");
			$prevRank = mysql_result($result, 0, "rank");
		} else {
			// $rank is highest rank achieved. Compare current rank to it.
			$prevRank = $rank;
			$currentRank = $rankArray['curMbrRank'];
		}

	if (empty($currentRank) || empty($prevRank)) {
		// Normally, this means that the rank is zero,
		// which indicates that the user's page tally is zero.
		// (Uniformity would prefer that a page tally of zero
		// gave a very high rank number, but it doesn't.)
		$rankPos = "&nbsp;";
	} elseif ($currentRank < $prevRank) {
		$rankPos = "&nbsp;<font color='green'>(<img src='".$GLOBALS['code_url']."/graphics/up.gif' border='0' alt='+".($prevRank-$currentRank)."'>&nbsp;".number_format(($prevRank-$currentRank)).")</font>";
	} elseif ($currentRank > $prevRank) {
		$rankPos = "&nbsp;<font color='red'>(<img src='".$GLOBALS['code_url']."/graphics/down.gif' border='0' alt='-".($currentRank-$prevRank)."'>&nbsp;".number_format(($currentRank-$prevRank)).")</font>";
	} else {
		$rankPos = "&nbsp;";
	}

	return $rankPos;
}

function showMbrNeighbors($curMbr) {
	global $theme;
	$rankArray = mbrRank($curMbr['username']);
	$now = time();

	echo "<table border='1' bordercolor='#111111' cellspacing='0' cellpadding='2' style='border-collapse: collapse' width='99%'>";
	echo "<tr><td bgcolor='".$theme['color_headerbar_bg']."'><center><font face='".$theme['font_headerbar']."' color='".$theme['color_headerbar_font']."' size='2'><b>"._("Neighbors")."</b></font></center></td></tr>";
	echo "<tr bgcolor='".$theme['color_navbar_bg']."'><td><table border='0' width='100%' cellspacing='0' cellpadding='0'>";
	echo "<tr bgcolor='".$theme['color_navbar_bg']."'><td width='15%'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'><b><u><i>"._("Rank")."</i></u></b></font></td><td width='28%'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'><b><u><i>"._("Username")."</i></u></b></font></td><td width='28%'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'><b><u><i>"._("Date Joined DP")."</i></u></b></font></td><td width='29%'><font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'><b><u><i>"._("Overall Pages Completed")."</i></u></b></font></td></tr>";

	for ( $i = -4;  $i <= 4; $i++ ) {
		$j = $rankArray['curMbrIndex']+$i;

		if (!isset($rankArray['rank'][$j])) { continue; }

		$rank = $rankArray['rank'][$j];
		$username = $rankArray['username'][$j];
		$result = mysql_query("SELECT u_id, date_created, u_privacy FROM users WHERE username = '$username'");
		$u_id = mysql_result($result, 0, "u_id");
		$date_created = mysql_result($result, 0, "date_created");
		$privacy_enabled = mysql_result($result, 0, "u_privacy");
		$pagescompleted = number_format($rankArray['pages'][$j]);

		echo "<tr bgcolor='".$theme['color_navbar_bg']."' onmouseover='javascript:style.background=\"".$theme['color_mainbody_bg']."\"' onmouseout='javascript:style.background=\"".$theme['color_navbar_bg']."\"'>";

		if (($privacy_enabled == PRIVACY_PRIVATE && isset($GLOBALS['pguser'])) ||
		    ($privacy_enabled == PRIVACY_ANONYMOUS && isset($GLOBALS['pguser']) && $username == $GLOBALS['pguser']) ||
		    ($privacy_enabled == PRIVACY_PUBLIC)) {

			echo "<td width='15%'>$rank".rankPosition($rank, $u_id, 0, $rankArray)."</td><td width='28%'><a href='".$GLOBALS['code_url']."/stats/members/mdetail.php?id=$u_id'>";
			echo $username;
			echo "</a></td><td width='28%'>".date("m/d/Y", $date_created)." <i>(".number_format(floor(($now - $date_created)/86400))." "._("days").")</i></td><td width='29%'>$pagescompleted</td></tr>";

		} else {
			echo "<td width='15%'>$rank".rankPosition($rank, $u_id, 0, $rankArray)."</td><td width='28%'>";
			echo "Anonymous";
			echo "</td><td width='28%'>--/--/-- <i>(--- "._("days").")</i></td><td width='29%'>$pagescompleted</td></tr>";
		}
	}

	echo "</td></tr></table></table><p>";
}

function showMbrTeams($curMbr) {
	global $theme;

	echo "<table border='1' bordercolor='#111111' cellspacing='0' cellpadding='2' style='border-collapse: collapse' width='99%'>";
	echo "<tr><td bgcolor='".$theme['color_headerbar_bg']."'><center><font face='".$theme['font_headerbar']."' color='".$theme['color_headerbar_font']."' size='2'><b>"._("Teams")."</b></font></center></td></tr>";
	echo "<tr><td bgcolor='".$theme['color_navbar_bg']."'><table border='0' width='100%' cellspacing='0' cellpadding='0'>";
	echo "<tr bgcolor='".$theme['color_navbar_bg']."'><td width='40%'><b><i><u>"._("Team Name")."</u></b></i></td><td width='30%'><b><i><u>"._("Pages Completed")."</u></b></i></td><td width='30%'><b><i><u>"._("Active Members")."</u></b></i></td></tr>";
	$result = mysql_query("SELECT id, teamname, active_members, page_count FROM user_teams WHERE id = ".$curMbr['team_1']." || id = ".$curMbr['team_2']." || id = ".$curMbr['team_3']."");
	while ($row = mysql_fetch_assoc($result)) {
		echo "<tr  onmouseover='javascript:style.background=\"".$theme['color_mainbody_bg']."\"' onmouseout='javascript:style.background=\"".$theme['color_navbar_bg']."\"' bgcolor='".$theme['color_navbar_bg']."'><td width='40%'><a href='".$GLOBALS['code_url']."/stats/teams/tdetail.php?tid=".$row['id']."'>".$row['teamname']."</a></td><td width='30%'>".number_format($row['page_count'])." "._("pages")."</td><td width='30%'>".number_format($row['active_members'])." "._("members")."</td></tr>";
	}
	echo "</td></tr></table></table><p>";
}

function showMbrHistory($curMbr) {
	global $theme;

	if (empty($_GET['range'])) { $range = 30; } else { $range = $_GET['range']; }
	echo "<table border='1' bordercolor='#111111' cellspacing='0' cellpadding='0' style='border-collapse: collapse' width='99%'>";
	echo "<tr><td bgcolor='".$theme['color_headerbar_bg']."'><center><font face='".$theme['font_headerbar']."' color='".$theme['color_headerbar_font']."' size='2'><b>"._("History")."</b></font></center></td></tr>";
	echo "<tr><td bgcolor='".$theme['color_navbar_bg']."'><center><br>";
	echo "<p><font size='2'><a href='mdetail.php?id=".$curMbr['u_id']."&range=7'>"._("Last 7 Days")."</a> | <a href='mdetail.php?id=".$curMbr['u_id']."&range=14'>"._("Last 14 Days")."</a> | <a href='mdetail.php?id=".$curMbr['u_id']."&range=30'>"._("Last 30 Days")."</a> | <a href='mdetail.php?id=".$curMbr['u_id']."&range=60'>"._("Last 60 Days")."</a> | <a href='mdetail.php?id=".$curMbr['u_id']."&range=365'>"._("Last 365 Days")."</a> | <a href='mdetail.php?id=".$curMbr['u_id']."&range=all'>"._("Lifetime")."</a></font>";
	echo "<p><img src='".$GLOBALS['code_url']."/stats/jpgraph_files/mbr_pages_history.php?id=".$curMbr['u_id']."&range=$range' width='600' height='300'></center><br></td></tr>";
	echo "</table><p>";
}

?>
