<?
//A file full of member statistic functions

include_once($relPath.'prefs_options.inc'); // PRIVACY_*
include_once($relPath.'page_tally.php');
include_once($relPath.'ThemedTable.inc');
include_once($relPath.'../stats/includes/common.inc');

function showMbrInformation( $curMbr ) {
	$result = mysql_query("SELECT * FROM phpbb_users WHERE username = '".$curMbr['username']."'");
	$curMbr = array_merge($curMbr, mysql_fetch_assoc($result));
	$now = time();
	showMbrProfile($curMbr);
	showMbrTeams($curMbr);
	if ($curMbr['current_P_page_tally'] > -100) { showMbrNeighbors($curMbr); }
	if (($now - $curMbr['date_created']) > 86400) { showMbrHistory($curMbr); }
}

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

	echo "\n";
	echo "<table width='99%'>";
	echo "\n";
	echo "<tr>";
	echo "<td align='center' valign='top'>";

	showBBProfile($curMbr);

	echo "</td>";

	echo "<td align='center' valign='top'>";
	echo "\n";

	$xml_doc_url = $GLOBALS['code_url']."/stats/members/mbr_xml.php?username=".$curMbr['username'];
	$xml_img_url = $GLOBALS['code_url']."/graphics/xml.gif";
	$xml_img_tag = "<img src='$xml_img_url' border='0' width='36' height='14' style='vertical-align:middle'>";
	$xml_button = "<a href='$xml_doc_url'>$xml_img_tag</a>";

	$t = new ThemedTable(
		2, 
		_("Statistics") . " $xml_button",
		array( 'width'=>100 )
	);
	$t->set_column_widths( 30, 70 );

	$t->row(
		_("Date Joined"),
		date("m/d/Y", $curMbr['date_created']) . "&nbsp;($daysInExistenceString)"
	);
	$t->row(
		_("Last Login"),
		date("m/d/Y", $curMbr['last_login']) . "&nbsp;($daysSinceLogin)"
	);
	$t->row(
		_("Total Pages"),
		number_format($curMbr['current_P_page_tally']) . " " . _("pages")
		. " (" . pageCountPercentage($curMbr['current_P_page_tally']) . _("% of total") . ")"
	);
	$t->row(
		_("Overall Rank"),
		number_format($currentRank) . " " . _("out of") . " " . number_format($totalRanks)
		. showChangeInRank( $latestSavedRank, $currentRank )
	);
	$t->row(
		_("Highest Rank"),
		number_format($highestRank)
		. "&nbsp;<i>(" . date("M. jS, Y", $highestRankDate) . ")</i>"
		. "&nbsp;" . showChangeInRank($highestRank, $currentRank)
	);
	$t->row(
		_("Best Day Ever"),
		number_format($bestDayCount) . " " . _("pages")
		. " <i>(" . $bestDayTime . ")</i>"
	);
	$t->row(
		_("Daily Average"),
		number_format($daily_Average) . " " . _("pages")
	);

	$t->row(
		_('Roles'),
		showMbrRoles($curMbr['sitemanager'], $curMbr['manager'], $curMbr['postprocessor'], $curMbr['current_P_page_tally'])
	);

        if (isset($GLOBALS['pguser']) && $curMbr['username'] == $GLOBALS['pguser']) {
		$t->row(
			_("Edit"),
			"<a href='".$GLOBALS['code_url']."/userprefs.php'>"._("Preferences")."</a>"
			. " | "
			. "<a href='".$GLOBALS['forums_url']."/profile.php?mode=editprofile&u=".$curMbr['user_id']."'>"._("Profile")."</a>"
		);
        }

	$t->end();

	echo "</td></tr></table><p>";
}

function showMbrRoles($siteadmin, $manager, $postprocessor, $pagescompleted) {
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

function showBBProfile($curMbr) {
	global $forums_url;

	if (empty($curMbr['user_avatar']))
	{
		$avatar = $GLOBALS['code_url']."/graphics/default_avatar.gif";
	}
	else
	{
		$avatar = $GLOBALS['forums_url']."/images/avatars/".$curMbr['user_avatar'];
	}

	$t = new ThemedTable(
		2,
		_("phpBB Profile"),
		array( 'width'=>100 )
	);
	$t->row(
		_("Avatar"),
		"<img border='0' src='$avatar' alt='avatar'>"
	);

	if (!empty($curMbr['user_from']) || !empty($curMbr['user_occ']) || !empty($curMbr['user_interests']) || !empty($curMbr['user_website'])) { $t->hr( 90 ); }
        if (!empty($curMbr['user_from']))      { $t->row( _("Location"),   $curMbr['user_from'] ); }
        if (!empty($curMbr['user_occ']))       { $t->row( _("Occupation"), $curMbr['user_occ'] ); }
	if (!empty($curMbr['user_interests'])) { $t->row( _("Interests"),  $curMbr['user_interests'] ); }
        if (!empty($curMbr['user_website']))   { $t->row( _("Website"),    "<a href='".$curMbr['user_website']."'>".$curMbr['user_website']."</a>" ); }

	$t->hr( 90 );

	$contact_data = array(
		array(
			!empty($curMbr['user_viewemail']),
			_("E-mail"),
			"icon_email.gif",
			"mailto:".$curMbr['email'],
			''
		),
		array(
			TRUE,
			_("Private Message"),
			"icon_pm.gif",
			"$forums_url/privmsg.php?mode=post&u=".$curMbr['user_id'],
			''
		),
		array(
			!empty($curMbr['user_aim']),
			"AIM",
			"icon_aim.gif",
			"aim:goim?screenname=".$curMbr['user_aim']."&message=Hello!+Are+you+there?++I+am+IMing+you+from+DP",
			''
		),
		array(
			!empty($curMbr['user_msnm']),
			"MSN",
			"icon_msnm.gif",
			"javascript:alert(\"".$curMbr['user_msnm']."\")",
			''
		),
		array(
			!empty($curMbr['user_yim']),
			"Yahoo Message",
			"icon_yim.gif",
			"http://edit.yahoo.com/config/send_webmesg?.target=".$curMbr['user_yim']."&.src=pg",
			"_new"
		),
		array(
			!empty($curMbr['user_icq']),
			"ICQ",
			"icon_icq.gif",
			"http://wwp.icq.com/scripts/search.dll?to=".$curMbr['user_icq'],
		       	"_new"
		),
	);

	while( list( $condition, $label, $icon_filename, $url, $target ) = next($contact_data) )
	{
		global $theme;

		if (!$condition) continue;
		
		$icon_path = "$forums_url/templates/subSilver/images/lang_english/$icon_filename";
		$img_tag = "<img src='$icon_path' alt='$label ".$curMbr['username']."' border='0'>";
		$target_str = ( empty($target) ? '' : " target='$target'" );

		$t->row(
			$label,
			"<a href='$url'$target_str>$img_tag</a>"
		);
	}

	$t->end();
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
	$now = time();

	$t = new ThemedTable(
		4,
		_("Neighbors"),
		array( 'width'=>99, 'rollover_highlight'=>TRUE )
	);
	$t->set_column_widths( 15, 28, 28, 29 );
	$t->column_headers(
		_("Rank"),
		_("Username"),
		_("Date Joined DP"),
		_("Overall Pages Completed")
	);

	list( $neighbors, $max_page_tally_rank ) =
		user_get_page_tally_neighborhood(
			$curMbr['username'], 4 );
	foreach ( $neighbors as $rel_posn => $neighbor )
	{
		$rank = $neighbor->get_current_page_tally_rank();
		$latestSavedRank = $neighbor->get_latest_saved_page_tally_rank();
		$pagescompleted = number_format($neighbor->get_current_page_tally());

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
		$t->row(
			$rank . showChangeInRank( $latestSavedRank, $rank ),
			$username_html,
			$day_html,
			$pagescompleted
		);
	}

	$t->end();
}

function showMbrTeams($curMbr) {

	if (empty($curMbr['team_1']) && empty($curMbr['team_2']) && empty($curMbr['team_3'])) { return; }

	$t = new ThemedTable(
		3,
		_("Teams"),
		array( 'width'=>99, 'rollover_highlight'=>TRUE )
	);
	$t->set_column_widths( 40, 30, 30 );
	$t->column_headers(
		_("Team Name"),
		_("Pages Completed"),
		_("Active Members")
	);

	$result = select_from_teams("id IN ({$curMbr['team_1']}, {$curMbr['team_2']}, {$curMbr['team_3']})");
	while ($row = mysql_fetch_assoc($result)) {
		$url = $GLOBALS['code_url']."/stats/teams/tdetail.php?tid=".$row['id'];
		$t->row(
			"<a href='$url'>".$row['teamname']."</a>",
			number_format($row['page_count'])." "._("pages"),
			number_format($row['active_members'])." "._("members")
		);
	}

	$t->end();
}

function showMbrHistory($curMbr) {

	if (empty($_GET['range'])) { $range = 30; } else { $range = $_GET['range']; }

	$choices = array();
	$u_id = $curMbr['u_id'];
	foreach( array( 7, 14, 30, 60, 365, 'all' ) as $d )
	{
		$text =
			($d == 'all')
			? _('Lifetime')
			: sprintf( _('Last %d Days'), $d );
		$choices[] = "<a href='mdetail.php?id=$u_id&range=$d'>$text</a>";
	}
	$choices_str = join( $choices, ' | ' );

	$image_url = $GLOBALS['code_url']."/stats/jpgraph_files/tallyboard_deltas.php?tally_name=P&holder_type=U&holder_id=$u_id&days_back=$range";

	$t = new ThemedTable(
		1,
		_("History"),
		array( 'width'=>99 )
	);
	$t->row( "<center><br><p><font size='2'>$choices_str</font><p><img src='$image_url' width='600' height='300'></center><br>" );
	$t->end();
}

?>
