<?PHP
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'page_states.inc');
include_once($relPath.'f_project_states.inc'); // projectStateRound

function echo_page_table( $projectid )
{
	global $theme, $projects_dir;

	$proj_res = mysql_query("SELECT state FROM projects WHERE projectid = '$projectid'") or die(mysql_error());
	$state = mysql_result($proj_res, 0, "state");

	$inRound = projectStateRound($state);

	$show_delete = ($inRound=='NEW' || $inRound=='PR' || $inRound='FIRST' || $inRound=='SECOND');

	$show_image_size = 1;
	$upload_colspan = 2 + $show_image_size;

	echo "<table border=1>\n";

	// Top header row
	{
		echo "<tr>\n";
		echo "    <td align='center' colspan='1'>&nbsp;</td>\n";
		echo "    <td align='center' colspan='$upload_colspan'>Upload</td>\n";
		echo "    <td align='center' colspan='1'>&nbsp;</td>\n";
		echo "    <td align='center' colspan='4'>Round 1</td>\n";
		echo "    <td align='center' colspan='4'>Round 2</td>\n";
		echo "</tr>\n";
	}

	// Bottom header row
	{
		echo "<tr bgcolor='".$theme['color_headerbar_bg']."'>\n";

		$td_start = "<td align='center'><font color='{$theme['color_headerbar_font']}'>";
		$td_end   = "</font></td>\n";

		echo "{$td_start}I{$td_end}";

		echo "{$td_start}Image{$td_end}";
		if ($show_image_size)
		{
			echo "{$td_start}Size{$td_end}";
		}

		echo "{$td_start}Text{$td_end}";

		echo "{$td_start}Page State{$td_end}";

		echo "{$td_start}Date{$td_end}";
		echo "{$td_start}User{$td_end}";
		echo "{$td_start}Text{$td_end}";
		echo "{$td_start}Clear{$td_end}";

		echo "{$td_start}Date{$td_end}";
		echo "{$td_start}User{$td_end}";
		echo "{$td_start}Text{$td_end}";
		echo "{$td_start}Clear{$td_end}";

		echo "{$td_start}Bad{$td_end}";

		if ($show_delete) {
			echo "{$td_start}Delete{$td_end}";
		}

		echo "</tr>";
	}

	$path = "$projects_dir/$projectid/";

	$fields_to_get = '
		fileid, image, length(master_text),
		state,
		round1_time, round1_user, length(round1_text),
		round2_time, round2_user, length(round2_text)';

	$res = mysql_query( "SELECT $fields_to_get FROM $projectid ORDER BY image ASC" );
	$num_rows = mysql_num_rows($res);

	for ( $rownum=0; $rownum < $num_rows; $rownum++ )
	{
		$fileid = mysql_result($res, $rownum, "fileid");

		if ($rownum % 2 ) {
			$row_color = $theme['color_main_bg'];
		} else {
			$row_color = $theme['color_navbar_bg'];
		}
		echo "<tr bgcolor='$row_color'>";

		// --------------------------------------------
		// Index
		$index = $rownum+1;
		echo "<td align='right'>$index</td>\n";

		// --------------------------------------------
		// Upload Block

		// Image
		$imagename = mysql_result($res, $rownum, "image");
		if (file_exists($path.$imagename)) {
			$bgcolor = $row_color;
			if ($show_image_size) $imagesize = filesize(realpath($path.$imagename));
		} else {
			$bgcolor = "#FF0000";
			if ($show_image_size) $imagesize = 0;
		}
		echo "<td bgcolor='$bgcolor'><a href=displayimage.php?project=$projectid&imagefile=$imagename>$imagename</a></td>\n";

		// Image Size
		if ($show_image_size)
		{
			echo "<td bgcolor='$bgcolor' align='right'>$imagesize</td>";
		}

		// Master Text
		$master_text_length = mysql_result($res, $rownum, "length(master_text)");
		echo "<td align='right'><a href=downloadproofed.php?project=$projectid&fileid=$fileid&state=".UNAVAIL_FIRST.">$master_text_length&nbsp;b</a></td>\n";

		// --------------------------------------------

		// Page State
		$page_state = mysql_result($res, $rownum, "state");
		echo "<td>$page_state</td>\n";

		// --------------------------------------------

		echo_cells_for_round(1, $res, $rownum, $fileid, $page_state, $projectid, $inRound );

		echo_cells_for_round(2, $res, $rownum, $fileid, $page_state, $projectid, $inRound );

		// (It's annoying that we have to pass all those args,
		// or else make them global, which I don't want to do.)

		// --------------------------------------------

		// Bad Page
		echo "<td>";
		if (($page_state == BAD_FIRST) || ($page_state == BAD_SECOND)) {
			echo "<center><a href='badpage.php?projectid=$projectid&fileid=$fileid'>X</a></center>";
		} else {
			echo "&nbsp;";
		}
		echo "</td>\n";

		// Delete
		if ($show_delete)
		{
			echo "<td><a href=deletefile.php?project=$projectid&fileid=$fileid>Delete</a></td>\n";
		}

		echo "</tr>";
	}
	echo "</table>";
}

// -----------------------------------------------------------------------------

function echo_cells_for_round( $round_num, // <- This is the only "real" param.
	$res, $rownum, $fileid, $page_state,
	$projectid, $inRound )
{
	global $userP;

	if ($round_num == 1)
	{
		$R_time_field_name = 'round1_time';
		$R_user_field_name = 'round1_user';
		$R_text_length_field_name = 'length(round1_text)';
		$R_save_state = SAVE_FIRST;
	}
	elseif ($round_num == 2)
	{
		$R_time_field_name = 'round2_time';
		$R_user_field_name = 'round2_user';
		$R_text_length_field_name = 'length(round2_text)';
		$R_save_state = SAVE_SECOND;
	}
	else
	{
		assert(FALSE);
	}

	// ------------------------------
	// Date

	$R_time = mysql_result($res, $rownum, $R_time_field_name);
	if ($R_time == 0)
	{
		$R_time_str = '';
	}
	else
	{
		$R_time_str = date("M j H:i", $R_time);
	}
	echo "<td>$R_time_str</td>\n";

	// ------------------------------
	// User

	$R_username = mysql_result($res, $rownum, $R_user_field_name);
	if ($R_username == '')
	{
		echo "<td></td>\n";
	}
	else
	{
		$R_ures = mysql_query("SELECT real_name, email, pagescompleted FROM users WHERE username = '$R_username'");
		if (mysql_num_rows($R_ures) == 0) {
			$R_real_name = $R_username;
			$R_email = "";
			$R_pages_completed = 0;
		} else {
			$R_real_name = mysql_result($R_ures, 0, "real_name");
			$R_email = mysql_result($R_ures, 0, "email");
			$R_pages_completed = mysql_result($R_ures, 0, "pagescompleted");
		}

		if ($userP['sitemanager'] == "yes") {
			$R_display_name = $R_real_name;
		} else {
			$R_display_name = $R_username;
		}

		echo "<td align='center'><a href=mailto:$R_email>$R_display_name</a> ($R_pages_completed)</td>\n";
	}

	// ------------------------------
	// Text

	$R_text_length = mysql_result($res, $rownum, $R_text_length_field_name);

	if ( $R_text_length == 0 )
	{
		echo "<td></td>\n";
	}
	else
	{
		echo "<td align='right'><a href=downloadproofed.php?project=$projectid&fileid=$fileid&state=$R_save_state>$R_text_length&nbsp;b</a></td>\n";
	}

	// ------------------------------
	// Clear

	// Anticipate the tests in checkin.php:
	if (
		$round_num == 1 &&
		$page_state == SAVE_FIRST &&
		($inRound=='NEW' || $inRound=='PR' || $inRound=='FIRST')
		||
		$round_num == 2 &&
		$page_state == SAVE_SECOND &&
		($inRound=='SECOND')
	)
	{
		echo "<td><a href=checkin.php?project=$projectid&fileid=$fileid&state=$R_save_state>Clear</a></td>\n";
	}
	else
	{
		// checkin.php won't let anything happen
		echo "<td></td>\n";
	}
}

?>
