<?
//A file full of functions used in team stats

include_once($relPath.'prefs_options.inc'); // PRIVACY_*
include_once($relPath.'v_site.inc');
include_once($relPath.'page_tally.php');
include_once($relPath.'ThemedTable.inc');
include_once($relPath.'../stats/includes/common.inc');

$team_avatars_dir = "$dyn_dir/teams/avatar";
$team_avatars_url = "$dyn_url/teams/avatar";

$team_icons_dir   = "$dyn_dir/teams/icon";
$team_icons_url   = "$dyn_url/teams/icon";

function select_from_teams( $where_body, $other_clauses='' )
{
	global $teams_P_page_tallyboard;
	list($joined_with_team_P_page_tallies,$team_P_page_tally_column) =
		$teams_P_page_tallyboard->get_sql_joinery_for_current_tallies('id');

	if ($where_body == '') $where_body = '1';

	$q = "
		SELECT
			user_teams.*,
			$team_P_page_tally_column AS page_count
		FROM user_teams
			$joined_with_team_P_page_tallies
		WHERE $where_body
		$other_clauses
	";
	$res = mysql_query($q);
	if (!$res) die(mysql_error());
	return $res;
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function showTeamInformation($curTeam)
{
	showTeamProfile($curTeam);

	if ($curTeam['id'] != 1)
	{
		showTeamStats($curTeam);
		showTeamMbrs($curTeam);

		// Only show the team history if they are more than a day old
		$now = time();
		if (($now - $curTeam['created']) > 86400) { showTeamHistory($curTeam); }
	}
}

// -----------------------------------------------------------------------------

function showTeamProfile($curTeam) {
	global $theme, $userP;
	global $team_avatars_url;
	global $code_url;

	$team_id = $curTeam['id'];

	$xml_doc_url = "$code_url/stats/teams/teams_xml.php?id=$team_id";
	$xml_img_url = "$code_url/graphics/xml.gif";
	$xml_img_tag = "<img src='$xml_img_url' border='0' width='36' height='14' style='vertical-align: middle;'>";
	$xml_button = "<a href='$xml_doc_url'>$xml_img_tag</a>";

	if ($curTeam['createdby'] == $GLOBALS['pguser'])
	{
		$op_url = "tedit.php?tid=$team_id";
		$text = _('Edit');
		$colored_text = "<font color='".$theme['color_headerbar_font']."' size='2'>$text</font>";
		$editlink = "&nbsp;[<a href='$op_url'>$colored_text</a>]";
	}
	else
	{
		$editlink = '';
	}

	if (empty($userP))
	{
		// The requestor is not logged in,
		// so they can't join or quit the team.
		$joinquitlink = '';
	}
	elseif ($team_id == 1)
	{
		// This is team #1, with automatic membership.
	       	// You can neither join nor quit.
		$joinquitlink = '';
	}
	else
	{
		if ($userP['team_1'] != $team_id && $userP['team_2'] != $team_id && $userP['team_3'] != $team_id)
		{
			$op = 'jointeam';
			$text = _('Join');
		}
		else
		{
			$op = 'quitteam';
			$text = _('Quit');
		}
		$op_url = "../members/$op.php?tid=$team_id";
		$colored_text = "<font color='".$theme['color_headerbar_font']."' size='2'>$text</font>";
		$joinquitlink = "&nbsp;[<a href='$op_url'>$colored_text</a>]";
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

	echo "\n<table cellspacing='0' cellpadding='0' width='95%'>";
	echo "\n<tr>";
	echo "<td align='center' width='30%' bgcolor='" . $theme['color_mainbody_bg']."'>";
	echo "<img border='0' src='$team_avatars_url/";
	echo $curTeam['avatar'] . "' alt='".strip_tags($curTeam['teamname'])."'></td>";
	echo "<td width='70%'>";

	$t = new ThemedTable(
		2,
		stripslashes($curTeam['teamname']) . "&nbsp;$xml_button$editlink$joinquitlink",
		array( 'width' => 100 )
	);
	$t->set_column_widths( 25, 75 );

	$t->row(
		_("Created"),
		date("m/d/Y", $curTeam['created'])
		. "&nbsp;($daysInExistence "._("days").")"
	);

	$owner_url = "$code_url/stats/members/mdetail.php?id=" . $curTeam['owner'];
	$t->row(
		_("Leader"),
		"<a href='$owner_url'>" . $curTeam['createdby'] . "</a>"
	);

	$t->row(
		_("Description"),
		stripslashes($curTeam['team_info'])
	);

	$webpage = $curTeam['webpage'];
	$t->row(
		_("Website"),
		"<a href='$webpage' target='_new'>$webpage</a>"
	);

	$topic_url = "$code_url/stats/teams/team_topic.php?team=$team_id";
	$t->row(
		_("Forums"),
		"<a href='$topic_url'>"._("Team Discussion")."</a> "
		. ( is_null($last_post_date) ? '' : "("._("Last Post:")." $last_post_date)" )
	);

	$mbrCountRank = team_get_member_count_rank($team_id);
	$t->row(
		_("Members")
			. " <i>("._("Rank").")</i>",
		number_format($curTeam['member_count'])
			. "&nbsp;<i>(#$mbrCountRank)</i>"
	);
	$t->row(
		_("Current Members"),
		number_format($curTeam['active_members'])
	);
	$t->row(
		_("Retired Members"),
		number_format($curTeam['member_count'] - $curTeam['active_members'])
	);

	$t->end();

	echo "</td></tr></table><p>";
}

function team_get_member_count_rank( $team_id )
{
	$result = mysql_query("
		SELECT id
		FROM user_teams
		WHERE id != 1
		ORDER BY member_count DESC
	");
	$i = 1;
	while ( list($id) = mysql_fetch_row($result) ) {
		if ($id == $team_id) { return $i; }
		$i++;
	}
}

function showTeamStats($curTeam) {

	$team_id = $curTeam['id'];

	$teams_tallyboard = new TallyBoard( 'P', 'T' );

	$snapshot_info =
		$teams_tallyboard->get_info_from_latest_snapshot($team_id);

	$lastupdated = date("n/d/Y h:i:s A T", ($snapshot_info['timestamp']-1));

	$prevRank = $snapshot_info['tally_rank'];

	$pageCount = $teams_tallyboard->get_current_tally( $team_id );
	$pageCountRank = $teams_tallyboard->get_rank($team_id);

	list($bestDayCount, $bestDayTimestamp) =
		$teams_tallyboard->get_info_re_largest_delta($team_id);
	$bestDayTime = date("M. jS, Y", ($bestDayTimestamp-86400));

	$t = new ThemedTable(
		2,
		_("Team Statistics"),
		array(
			'width' => 95,
			'subtitle' => "("._('Last Updated:')." $lastupdated)",
			'rollover_highlight' => TRUE
		)
	);
	$t->set_column_widths( 60, 40 );

	$t->row(
		_("Total Pages")
			. " <i>("._("Rank").")</i>",
		"<b>" . number_format($pageCount) . "</b>"
			. "&nbsp;<i>(#$pageCountRank)</i>"
			. showChangeInRank( $prevRank, $pageCountRank )
	);

	$avg_pages_per_day = get_daily_average($curTeam['created'],$pageCount);
	$t->row(
		_("Avg. Pages per Day"),
		"<b>" . number_format($avg_pages_per_day,1) . "</b>"
	);
	$t->row(
		_("Most Pages Done per Day"),
		"<b>" . number_format($bestDayCount) . "</b>"
			. "&nbsp;<i>($bestDayTime)</i></b>"
	);

	$t->end();
}

function showTeamMbrs($curTeam) {
	global $code_url;

	if (empty($_GET['order'])) { $order = "mbr"; } else { $order = $_GET['order']; }
	if (empty($_GET['direction'])) { $direction = "asc"; } else { $direction = $_GET['direction']; }

	$subtitle = null;
	$latestMbr = mysql_query("SELECT users.username AS Username, users.u_privacy AS Privacy FROM user_teams INNER JOIN users ON user_teams.latestUser = users.u_id WHERE user_teams.id = ".$curTeam['id']." AND user_teams.latestUser <> 0");
	if (mysql_num_rows($latestMbr) > 0) { 
		if (mysql_result($latestMbr, 0, "Privacy") == PRIVACY_PUBLIC || (mysql_result($latestMbr, 0, "Privacy") == PRIVACY_PRIVATE && isset($GLOBALS['pguser']))) {
			$subtitle = "Please welcome <b>".mysql_result($latestMbr, 0, "Username")."</b> as the latest member to join this team!";
		}
	}

	$t = new ThemedTable(
		3,
		_("Team Member Details"),
		array(
			'width' => 95,
			'subtitle' => $subtitle,
			'rollover_highlight' => TRUE
		)
	);
	$t->set_column_widths( 35, 35, 30 );

	$header_content_ = array();

	$column_descriptors =
		array(
			array( 'mbr',   'username',      'asc',  _("Username")        ),
			array( 'pages', 'current_tally', 'desc', _("Pages Completed") ),
			array( 'date',  'date_created',  'asc',  _("Date Joined DP")  )
		);
	foreach ( $column_descriptors as $column_descriptor )
	{
		list( $cd_order, $cd_dbcolumn, $cd_default_dir, $cd_header_text ) =
			$column_descriptor;
		if ( $cd_order == $order ) {
			// This is the column by which we are to sort the table.

			// So here's the db-column to ORDER BY in the SQL query.
			$orderby = $cd_dbcolumn;

			// Mark this column-header with a direction-indicator.
			$arrow = ($direction == "asc") ? 'down' : 'up';
			$dir_indicator = " <img src='$code_url/graphics/$arrow.gif'>";
			// (You might think that if the column is sorted in ascending
			// order, we should use an up-arrow. But I'm following the
			// Netscape/Mozilla convention, which is the opposite.)

			// And provide a link to sort by this column in the opposite direction.
			$dir = ($direction == "asc") ? "desc" : "asc";

		} else {
			// This is a non-sorting column (for this request).

			$dir_indicator = '';

			// And provide a link to sort by this column in its default direction.
			$dir = $cd_default_dir;
		}
		$url = "tdetail.php?tid=".$curTeam['id']."&order=$cd_order&direction=$dir";

		$header_content_[] = "<a href='$url'>$cd_header_text</a>$dir_indicator";
	}

	$t->column_headers( $header_content_ );

	$users_tallyboard = new TallyBoard( 'P', 'U' );

	list( $joined_with_user_tallies, $user_tally_column ) =
		$users_tallyboard->get_sql_joinery_for_current_tallies( 'u_id' );
	$users_table_with_tallies = "users $joined_with_user_tallies";

	$mbrQuery = mysql_query("
		SELECT *, $user_tally_column AS current_tally
		FROM $users_table_with_tallies
		WHERE {$_GET['tid']} IN (team_1, team_2, team_3)
		ORDER BY $orderby $direction
	");

	$totalAnonUsers = 0;
	$totalPagesAnonUsers = 0;
	while ($curMbr = mysql_fetch_assoc($mbrQuery)) {
		if ($curMbr['u_privacy'] == PRIVACY_ANONYMOUS)
		{
			$totalAnonUsers++;
			$totalPagesAnonUsers += $curMbr['current_tally'];
		}
		elseif ($curMbr['u_privacy'] == PRIVACY_PRIVATE && !isset($GLOBALS['pguser']))
		{
			$totalAnonUsers++;
			$totalPagesAnonUsers += $curMbr['current_tally'];
		}
		else
		{
			$username = $curMbr['username'];
			$member_url = "$code_url/stats/members/mdetail.php?id=" . $curMbr['u_id'];
			$t->row(
				"<a href='$member_url'>$username</a>",
				number_format($curMbr['current_tally']),
				date("m/d/Y", $curMbr['date_created'])
			);
		}
	}
	if ($totalAnonUsers > 0)
	{
		$t->row(
			_("Anonymous")
				. ( isset($GLOBALS['pguser']) ? '' : ("/"._("Private")) )
				. " "
				. _("users"),
			number_format($totalPagesAnonUsers),
			"&nbsp;"
		);
	}

	$t->end();
}

function showTeamHistory($curTeam) {
	if (empty($_GET['range'])) { $range = 30; } else { $range = $_GET['range']; }

	$choices = array();
	foreach ( array( 7, 14, 30, 60, 365, 'all' ) as $d )
	{
		$text =
			($d == 'all')
			? _('Lifetime')
			: sprintf( _('Last %d Days'), $d );
		$choices[] = "<a href='tdetail.php?tid=".$curTeam['id']."&range=$d'>$text</a>";
	}
	$choices_str = join( $choices, ' | ' );

	$image_url = $GLOBALS['code_url']."/stats/jpgraph_files/tallyboard_deltas.php?tally_name=P&holder_type=T&holder_id=".$curTeam['id']."&days_back=$range";

	$t = new ThemedTable(
		1,
		_("Team Statistics History"),
		array( 'width' => 95 )
	);

	$t->row(
		"<center><br><p>"
		. "<font size='2'>$choices_str</font>"
		. "<p><img src='$image_url' width='600' height='300'></center><br>"
	);

	$t->end();
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
    		$teamQuery = mysql_query("SELECT teamname, id FROM user_teams WHERE id IN ({$userP['team_1']}, {$userP['team_2']}, {$userP['team_3']})");
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
