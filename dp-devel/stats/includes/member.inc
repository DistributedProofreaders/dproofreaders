<?
//A file full of member statistic functions

include_once($relPath.'prefs_options.inc'); // PRIVACY_*
include_once($relPath.'page_tally.php');
include_once($relPath.'../stats/includes/common.inc');

function showMbrProfile($curMbr) {
	global $theme;
	global $users_P_page_tallyboard;

	$now = time();
	$daysInExistenceInteger = floor(($now - $curMbr['date_created'])/86400);
	$daysInExistenceString = number_format($daysInExistenceInteger)._(" days"); // '1,234 days'
	$daysSinceLogin = number_format(floor(($now - $curMbr['last_login'])/86400));
	if (empty($daysSinceLogin)) { $daysSinceLogin = _("Today!"); } else { $daysSinceLogin = $daysSinceLogin._(" days"); }

	list( $neighbors, $totalRanks ) =
		user_get_page_tally_neighborhood(
			$curMbr['username'], 0 );
	$currentRank = $neighbors[0]->get_current_page_tally_rank();
	$latestSavedRank = $neighbors[0]->get_latest_saved_page_tally_rank();

	list($bestDayCount,$bestDayTimestamp) =
		$users_P_page_tallyboard->get_info_re_largest_delta( $curMbr['u_id'] );

	$bestDayTime = date("M. jS, Y", ($bestDayTimestamp-86400));

	list($highestRank, $highestRankDate) =
		$users_P_page_tallyboard->get_info_re_best_rank( $curMbr['u_id'] );


	if ($daysInExistenceInteger > 0) {
	        $daily_Average = $curMbr['current_P_page_tally']/$daysInExistenceInteger;
	} else {
		$daily_Average = 0;
	}

	if ($currentRank <= $highestRank) { $highestRank = $currentRank; $highestRankDate = time(); }

	if (empty($curMbr['user_avatar'])) { $avatar = $GLOBALS['code_url']."/graphics/default_avatar.gif"; } else { $avatar = $GLOBALS['forums_url']."/images/avatars/".$curMbr['user_avatar']; }

	echo "<table border='1' bordercolor='#111111' cellspacing='0' cellpadding='0' style='border-collapse: collapse' width='99%'>";
	echo "<tr bgcolor='".$theme['color_mainbody_bg']."'><td width='35%' align='center' height='90'><img border='0' src='$avatar' alt='avatar'></td>";
	echo "<td rowspan='2' bgcolor='".$theme['color_navbar_bg']."' align='left' valign='top' width='65%'><table border='0' width='100%' cellspacing='0' cellpadding='1'>";
       	echo_tr_headerbar(
	       	_("Statistics")
		. " <a href='".$GLOBALS['code_url']."/stats/members/mbr_xml.php?username=".$curMbr['username']."'>"
		. "<img src='".$GLOBALS['code_url']."/graphics/xml.gif' border='0' width='36' height='14' style='vertical-align:middle'>"
		. "</a>",
		2
       	);

	function echo_tr_2_cols( $left, $right )
	{
		global $theme;
		echo "\n";
		echo "<tr bgcolor='".$theme['color_navbar_bg']."'>";
		echo   "<td width='30%' align='left' valign='top'>";
		echo     "<font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'>";
		echo       "<b>$left</b>:";
		echo     "</font>";
		echo   "</td>";
		echo   "<td width='70%' align='left' valign='top'>";
		echo     "<font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'>";
		echo       "$right";
		echo     "</font>";
		echo   "</td>";
		echo "</tr>";
	}

	echo_tr_2_cols(
		_("Date Joined"),
		date("m/d/Y", $curMbr['date_created']) . "&nbsp;($daysInExistenceString)"
	);
	echo_tr_2_cols(
		_("Last Login"),
		date("m/d/Y", $curMbr['last_login']) . "&nbsp;($daysSinceLogin)"
	);
	echo_tr_2_cols(
		_("Total Pages"),
		number_format($curMbr['current_P_page_tally']) . " " . _("pages")
		. " (" . pageCountPercentage($curMbr['current_P_page_tally']) . _("% of total") . ")"
	);
	echo_tr_2_cols(
		_("Overall Rank"),
		number_format($currentRank) . " " . _("out of") . " " . number_format($totalRanks)
		. showChangeInRank( $latestSavedRank, $currentRank )
	);
	echo_tr_2_cols(
		_("Highest Rank"),
		number_format($highestRank)
		. "&nbsp;<i>(" . date("M. jS, Y", $highestRankDate) . ")</i>"
		. "&nbsp;" . showChangeInRank($highestRank, $currentRank)
	);
	echo_tr_2_cols(
		_("Best Day Ever"),
		number_format($bestDayCount) . " " . _("pages")
		. " <i>(" . $bestDayTime . ")</i>"
	);
	echo_tr_2_cols(
		_("Daily Average"),
		number_format($daily_Average) . " " . _("pages")
	);

	if (!empty($curMbr['user_from']) || !empty($curMbr['user_occ']) || !empty($curMbr['user_interests']) || !empty($curMbr['user_website'])) { echo "<tr bgcolor='".$theme['color_navbar_bg']."'><td colspan='2' align='center' valign='middle'><hr width='90%'></td></tr>"; }
        if (!empty($curMbr['user_from']))      { echo_tr_2_cols( _("Location"),   $curMbr['user_from'] ); }
        if (!empty($curMbr['user_occ']))       { echo_tr_2_cols( _("Occupation"), $curMbr['user_occ'] ); }
	if (!empty($curMbr['user_interests'])) { echo_tr_2_cols( _("Interests"),  $curMbr['user_interests'] ); }
        if (!empty($curMbr['user_website']))   { echo_tr_2_cols( _("Website"),    "<a href='".$curMbr['user_website']."'>".$curMbr['user_website']."</a>" ); }

        if (isset($GLOBALS['pguser']) && $curMbr['username'] == $GLOBALS['pguser']) {
		echo_tr_2_cols(
			_("Edit"),
			"<a href='".$GLOBALS['code_url']."/userprefs.php'>"._("Preferences")."</a>"
			. " | "
			. "<a href='".$GLOBALS['forums_url']."/profile.php?mode=editprofile&u=".$curMbr['user_id']."'>"._("Profile")."</a>"
		);
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

function messageCenter($curMbr) {
	global $forums_url, $theme;

	echo "<table border='0' width='100%' cellspacing='0' cellpadding='1'>";
	echo "<tr><td align='center' colspan='2'>".showMbrStatus($curMbr['sitemanager'], $curMbr['manager'], $curMbr['postprocessor'], $curMbr['current_P_page_tally'])."</td></tr>";
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

function showMbrNeighbors($curMbr) {
	global $theme;
	global $rollover_highlight_attrs;
	$now = time();

	echo "<table border='1' bordercolor='#111111' cellspacing='0' cellpadding='2' style='border-collapse: collapse' width='99%'>";
	echo_tr_headerbar( _("Neighbors") );
	echo "<tr bgcolor='".$theme['color_navbar_bg']."'><td><table border='0' width='100%' cellspacing='0' cellpadding='0'>";

	function echo_td( $width, $content )
	{
		global $theme;
		echo "<td width='$width%'>";
		echo "<font face='".$theme['font_navbar']."' color='".$theme['color_navbar_font']."' size='2'>";
		echo "<b><u><i>";
		echo $content;
		echo "</i></u></b>";
		echo "</font>";
		echo "</td>";
	}

	echo "<tr bgcolor='".$theme['color_navbar_bg']."'>";
	echo_td( 15, _("Rank") );
	echo_td( 28, _("Username") );
	echo_td( 28, _("Date Joined DP") );
	echo_td( 29, _("Overall Pages Completed") );
	echo "</tr>";

	list( $neighbors, $max_page_tally_rank ) =
		user_get_page_tally_neighborhood(
			$curMbr['username'], 4 );
	foreach ( $neighbors as $rel_posn => $neighbor )
	{
		$rank = $neighbor->get_current_page_tally_rank();
		$latestSavedRank = $neighbor->get_latest_saved_page_tally_rank();
		$pagescompleted = number_format($neighbor->get_current_page_tally());

		echo "\n<tr $rollover_highlight_attrs>";

		if (!$neighbor->is_anonymized()) {

			$u_id = $neighbor->get_u_id();
			$username = $neighbor->get_username();
			$date_created = $neighbor->get_date_joined();

			$username_html = "<a href='".$GLOBALS['code_url']."/stats/members/mdetail.php?id=$u_id'>$username</a>";
			$day_html = date("m/d/Y", $date_created)." <i>(".number_format(floor(($now - $date_created)/86400))." "._("days").")</i>";

		} else {
			$username_html = "Anonymous";
			$day_html = "--/--/-- <i>(--- "._("days").")</i>";
		}
		echo "<td width='15%'>$rank" . showChangeInRank( $latestSavedRank, $rank ) . "</td>";
		echo "<td width='28%'>$username_html</td>";
		echo "<td width='28%'>$day_html</td>";
		echo "<td width='29%'>$pagescompleted</td>";
		echo "</tr>";
	}

	echo "</td></tr></table></table><p>";
}

function showMbrTeams($curMbr) {
	global $theme;
	global $rollover_highlight_attrs;

	echo "<table border='1' bordercolor='#111111' cellspacing='0' cellpadding='2' style='border-collapse: collapse' width='99%'>";
	echo_tr_headerbar( _("Teams") );
	echo "<tr><td bgcolor='".$theme['color_navbar_bg']."'><table border='0' width='100%' cellspacing='0' cellpadding='0'>";
	echo "<tr bgcolor='".$theme['color_navbar_bg']."'><td width='40%'><b><i><u>"._("Team Name")."</u></b></i></td><td width='30%'><b><i><u>"._("Pages Completed")."</u></b></i></td><td width='30%'><b><i><u>"._("Active Members")."</u></b></i></td></tr>";
	$result = select_from_teams("id IN ({$curMbr['team_1']}, {$curMbr['team_2']}, {$curMbr['team_3']})");
	while ($row = mysql_fetch_assoc($result)) {
		echo "\n<tr $rollover_highlight_attrs>";
		echo "<td width='40%'><a href='".$GLOBALS['code_url']."/stats/teams/tdetail.php?tid=".$row['id']."'>".$row['teamname']."</a></td><td width='30%'>".number_format($row['page_count'])." "._("pages")."</td><td width='30%'>".number_format($row['active_members'])." "._("members")."</td></tr>";
	}
	echo "</td></tr></table></table><p>";
}

function showMbrHistory($curMbr) {
	global $theme;

	if (empty($_GET['range'])) { $range = 30; } else { $range = $_GET['range']; }
	echo "<table border='1' bordercolor='#111111' cellspacing='0' cellpadding='0' style='border-collapse: collapse' width='99%'>";
	echo_tr_headerbar( _("History") );
	echo "<tr><td bgcolor='".$theme['color_navbar_bg']."'><center><br>";
	echo "<p><font size='2'><a href='mdetail.php?id=".$curMbr['u_id']."&range=7'>"._("Last 7 Days")."</a> | <a href='mdetail.php?id=".$curMbr['u_id']."&range=14'>"._("Last 14 Days")."</a> | <a href='mdetail.php?id=".$curMbr['u_id']."&range=30'>"._("Last 30 Days")."</a> | <a href='mdetail.php?id=".$curMbr['u_id']."&range=60'>"._("Last 60 Days")."</a> | <a href='mdetail.php?id=".$curMbr['u_id']."&range=365'>"._("Last 365 Days")."</a> | <a href='mdetail.php?id=".$curMbr['u_id']."&range=all'>"._("Lifetime")."</a></font>";
	echo "<p><img src='".$GLOBALS['code_url']."/stats/jpgraph_files/mbr_pages_history.php?id=".$curMbr['u_id']."&range=$range' width='600' height='300'></center><br></td></tr>";
	echo "</table><p>";
}

// -----------------------------------------------------------------------------

function echo_tr_headerbar( $blurb, $colspan=1 )
{
	global $theme;
	echo "\n";
	echo "<tr>";
	echo "<td colspan='$colspan' bgcolor='".$theme['color_headerbar_bg']."'>";
	echo "<center>";
	echo "<font face='".$theme['font_headerbar']."' color='".$theme['color_headerbar_font']."' size='2'>";
	echo "<b>";
	echo $blurb;
	echo "</b>";
	echo "</font>";
	echo "</center>";
	echo "</td>";
	echo "</tr>";
}

?>
