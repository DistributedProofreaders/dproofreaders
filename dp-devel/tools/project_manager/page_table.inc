<?PHP
include_once($relPath.'site_vars.php');
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'project_states.inc'); // get_phase_containing_project_state
include_once($relPath.'page_tally.inc');
include_once($relPath.'../tools/proofers/PPage.inc'); // url_for_pi_do_particular_page

function echo_page_table(
	$project,
	$show_image_size,
	$disable_editing = FALSE,
	$page_selector = NULL )
{
	global $theme, $projects_dir, $code_url, $pguser;

	$can_edit = $project->can_be_managed_by_current_user;

	// want PPers able to see names
	$can_see_names_for_all_pages = $project->names_can_be_seen_by_current_user;

	if ( $disable_editing )
	{
		// Although the user may be allowed to edit the project,
		// this particular page should not let them do so.
		$can_edit = FALSE;
		// But they still get to see names.
	}

	$projectid = $project->projectid;
	$state = $project->state;

	$project_phase = get_phase_containing_project_state($state);

	$show_delete = (($project_phase=='NEW' || $project_phase=='PR' || $project_phase=='PAGE_EDITING') && $can_edit);

	$project_round = get_Round_for_project_state($state);

	// This project may have skipped some rounds, and/or it may have rounds
	// yet to do, so there may be some round-columns with no data in them.
	// Figure out which ones to display.
	//
	$rounds_to_display = get_rounds_with_data( $projectid );

	// If the project is in a round, then users expect that round to appear here,
	// even if there's no data in it.
	//
	if ( !is_null($project_round) )
	{
		// Look for $project_round in $rounds_to_display.
		// If it does appear, it will normally be the last item,
		// but check everywhere, just in case.
		$found = FALSE;
		foreach ( $rounds_to_display as $round )
		{
			if ( $round->id == $project_round->id )
			{
				$found = TRUE;
				break;
			}
		}
		if ( !$found )
		{
			$rounds_to_display[] = $project_round;
		}
	}

	$upload_colspan = 2 + $show_image_size;

	if ( $can_edit )
	{
		echo "<form name='pagesform' method='post' action='$code_url/tools/project_manager/edit_pages.php'>\n";
		echo "<input type='hidden' name='projectid' value='$projectid'>\n";
	}

	echo "<table id='page_table' border=1>\n";

	$n_cols_per_round = 3; // Diff, Date, Text
	// Even if the user can't see names for all pages, they can see names
	// for the pages they've worked on, so we always include the User column.
	if (TRUE)           $n_cols_per_round += 1; // User
	if ($can_edit)      $n_cols_per_round += 1; // Clear

	// Top header row
	{
		echo "<tr>\n";
		if ($can_edit)
		{
			echo "    <td align='center' colspan='1'>&nbsp;</td>\n";
		}
		echo "    <td align='center' colspan='1'>&nbsp;</td>\n";
		echo "    <td align='center' colspan='$upload_colspan'>"._("Upload")."</td>\n";
		echo "    <td align='center' colspan='1'>&nbsp;</td>\n";
		foreach ( $rounds_to_display as $round )
		{
			if ($round == $project_round && $project_phase == 'PAGE_EDITING')
			{
				$n_cols_per_round++;
			}

			echo "    <td align='center' colspan='$n_cols_per_round'>$round->id</td>\n";
		}
		echo "</tr>\n";
	}

	// Bottom header row
	{
		echo "<tr bgcolor='".$theme['color_headerbar_bg']."'>\n";

		$td_start = "<td align='center'><font color='{$theme['color_headerbar_font']}'>";
		$td_end   = "</font></td>\n";

		if ($can_edit)
		{
			echo "{$td_start}X{$td_end}";
		}
		echo "{$td_start}I{$td_end}";

		echo "{$td_start}"._("Image")."{$td_end}";
		if ($show_image_size)
		{
			echo "{$td_start}"._("Size")."{$td_end}";
		}

		echo "{$td_start}"._("Text")."{$td_end}";

		echo "{$td_start}"._("Page State")."{$td_end}";

		foreach ( $rounds_to_display as $round )
		{
			echo "{$td_start}"._("Diff")."{$td_end}";
			echo "{$td_start}"._("Date")."{$td_end}";
			if (TRUE)
			{
				echo "{$td_start}"._("User")."{$td_end}";
			}
			echo "{$td_start}"._("Text")."{$td_end}";
			if ($can_edit)
			{
				echo "{$td_start}"._("Clear")."{$td_end}";
			}
		}

		if ($project_phase == 'PAGE_EDITING')
		{
			echo "{$td_start}"._("Edit")."{$td_end}";
		}

		if ($can_edit) echo "{$td_start}"._("Bad/Fix")."{$td_end}";

		if ($show_delete)
		{
			echo "{$td_start}"._("Delete")."{$td_end}";
		}
	}

	echo "</tr>";

	$avail_pages="";

	$path = "$projects_dir/$projectid/";

	$fields_to_get = "fileid, image, length(master_text), state";
	$tables = $projectid;
	$prev_text_column_name = 'master_text';
	foreach ( $rounds_to_display as $round )
	{
		$rn = $round->round_number;

		$tallyboard = new TallyBoard( $round->id, 'U' );
		list($joined_with_user_page_tallies,$user_page_tally_column) =
			$tallyboard->get_sql_joinery_for_current_tallies( "users$rn.u_id" );

		$fields_to_get .= ",
			BINARY REPLACE($prev_text_column_name,'\r\n','\n')=REPLACE($round->text_column_name,'\r\n','\n') AS $round->textdiff_column_name,
			$round->time_column_name,
			$round->user_column_name,
			phpbb_users$rn.user_id AS `$rn.phpbb_user_id`,
			$user_page_tally_column AS `$rn.user_page_tally`,
			length($round->text_column_name)
		";
		$tables .= "
			LEFT OUTER JOIN phpbb_users AS phpbb_users$rn
				ON (phpbb_users$rn.username = $round->user_column_name)
			LEFT OUTER JOIN users AS users$rn
				ON (users$rn.username = $round->user_column_name)
			$joined_with_user_page_tallies
		";
		$prev_text_column_name = $round->text_column_name;
	}

	if ( is_null($page_selector) )
	{
		// Select all pages
		$where_condition = "1";
	}
	elseif ( is_string($page_selector) )
	{
		// Select all pages of a particular user.
		$username_for_selecting_pages = $page_selector;

		$where_condition = "0";
		foreach ( $rounds_to_display as $round )
		{
			$where_condition .= "
				OR $round->user_column_name='$username_for_selecting_pages'
			";
		}

		$page_selector = NULL;
	}
	else
	{
		// $page_selector is an array whose keys are fileids of the pages to select
		$fileids_str = '';
		foreach ( $page_selector as $fileid => $dummy )
		{
			if ( !empty($fileids_str) ) $fileids_str .= ',';
			$fileids_str .= "'$fileid'";
		}
		$where_condition = "fileid IN ($fileids_str)";
	}

	$res = mysql_query("
		SELECT $fields_to_get
	       	FROM $tables
	       	WHERE $where_condition
	       	ORDER BY image ASC
	") or die(mysql_error());
	$num_rows = mysql_num_rows($res);

	for ( $rownum=0; $rownum < $num_rows; $rownum++ )
	{
		$page_res = mysql_fetch_array( $res, MYSQL_ASSOC );

		$fileid = $page_res['fileid'];

		if ($rownum % 2 ) {
			$row_color = $theme['color_mainbody_bg'];
		} else {
			$row_color = $theme['color_navbar_bg'];
		}

		echo "<tr bgcolor='$row_color'>";

		// --------------------------------------------
		// Selector
		if ( $can_edit )
		{
			echo "<td><input type='checkbox' name='selected_pages[$fileid]'></td>\n";
		}

		// --------------------------------------------
		// Index
		$index = $rownum+1;
		echo "<td align='right'>$index</td>\n";

		// --------------------------------------------
		// Upload Block

		// Image
		$imagename = $page_res['image'];
		if (file_exists($path.$imagename)) {
			$bgcolor = $row_color;
			$imagesize = filesize(realpath($path.$imagename));
			if ($imagesize == 0) {
				$bgcolor = "#FF0000";
			}
		} else {
			$bgcolor = "#FF0000";
			if ($show_image_size) $imagesize = "X";
		}
		echo "<td bgcolor='$bgcolor'><a href='$code_url/tools/project_manager/displayimage.php?project=$projectid&imagefile=$imagename'>$imagename</a></td>\n";

		// Image Size
		if ($show_image_size)
		{
			echo "<td bgcolor='$bgcolor' align='right'>$imagesize</td>";
		}

		// Master Text
		$master_text_length = $page_res['length(master_text)'];
		echo "<td align='right'><a href='$code_url/tools/project_manager/downloadproofed.php?project=$projectid&image=$imagename&round_num=0'>$master_text_length&nbsp;b</a></td>\n";

		// --------------------------------------------

		// Page State
		$page_state = $page_res['state'];
		echo "<td>$page_state</td>\n";

		if (page_state_is_an_avail_state($page_state))
			$avail_pages.="$index,";

		// --------------------------------------------
		// Per-Round Info

		if ($can_see_names_for_all_pages)
		{
			$can_see_names_for_this_page = TRUE;
		}
		else
		{
			// The user can see the names of all the proofers for this page
			// only if they are one of those proofers.
			$can_see_names_for_this_page = FALSE;
			foreach ( $rounds_to_display as $round )
			{
				$proofer_name = $page_res[$round->user_column_name];
				if ( $proofer_name != '' && $proofer_name == $pguser )
				{
					$can_see_names_for_this_page = TRUE;
					break;
				}
			}
		}

		$prev_rn = 0;
		foreach ( $rounds_to_display as $round )
		{
			$rn = $round->round_number;
			echo_cells_for_round($rn, $prev_rn, $page_res, $projectid, $project_phase, $can_edit, $can_see_names_for_this_page);
			$prev_rn = $rn;
		}

		// (It's annoying that we have to pass all those args,
		// or else make them global, which I don't want to do.)

		// --------------------------------------------
		// Link to proofing interface
		if ($project_phase == 'PAGE_EDITING')
		{
			if ($page_res[$project_round->user_column_name] == $pguser
				&& $page_state != $project_round->page_avail_state)  
			{
				$url = url_for_pi_do_particular_page(
					$projectid, $state, $imagename, $page_state );
				echo "<td><a href=$url>"._("Edit")."</a></td>\n";
			}
			else
				echo "<td></td>";
		}

		// --------------------------------------------
		// PM/Admin project editing
		if ($can_edit) {

			// Bad Page
			$page_is_in_bad_state = page_state_is_a_bad_state($page_state);

			echo "<td>";
			if ($page_is_in_bad_state) {
				echo "<center><b>";
				echo "<a href='$code_url/tools/project_manager/handle_bad_page.php?projectid=$projectid&image=$imagename'>Bad</a>";
				echo "</b></center>";
			} else {
				echo "<center><a href='$code_url/tools/project_manager/handle_bad_page.php?projectid=$projectid&image=$imagename'>Fix</a></center>\n";
			}
			echo "</td>\n";

			// Delete
			if ($show_delete)
			{
				echo "<td><a href='$code_url/tools/project_manager/edit_pages.php?projectid=$projectid&selected_pages[$fileid]=on&operation=delete'>Delete</a></td>\n";
			}
		}

		echo "</tr>";
	}
	echo "</table>";

	if ( $can_edit )
	{
?>
<script language="JavaScript" type="text/javascript"><!--
avail_pages=new Array(<? echo substr($avail_pages,0,-1); ?>);

function changeSelection(sel) {
	switch(sel) {
		case "all":
			for(i=1;i<=<? echo $num_rows; ?>;i++)
				document.pagesform.elements[i].checked=true;
			break;
		case "unproofed":
			changeSelection("clear");
			for(i in avail_pages)
				document.pagesform.elements[avail_pages[i]].checked=true;
			break;
		case "invert":
			for(i=1;i<=<? echo $num_rows; ?>;i++)
				document.pagesform.elements[i].checked=!document.pagesform.elements[i].checked;
			break;
		case "clear":
			for(i=1;i<=<? echo $num_rows; ?>;i++)
				document.pagesform.elements[i].checked=false;
			break;
		default:
			break;
	}
}
// --></script>
<?
		echo "<br>\n";
		echo "<select name='select' onChange='changeSelection(this.options[this.selectedIndex].value)'>\n";
		echo "<option value='0'>"._("Select...")."</option>";
		echo "<option value='all'>"._("Select all")."</option>";
		echo "<option value='unproofed'>"._("Select all unproofread")."</option>";
		echo "<option value='invert'>"._("Invert selection")."</option>";
		echo "<option value='clear'>"._("Clear selection")."</option>";
		echo "</select>. ";
		echo _("For each selected page:") . "\n";
		echo "<select name='operation'>\n";
		// echo "  <option value='bad'   >" . _("Mark as bad") . "</option>\n";
		echo "  <option value='clear' >" . _("Clear effects of current round") . "</option>\n";
		echo "  <option value='delete'>" . _("Delete") . "</option>\n";
		echo "</select>\n";
		echo "<input type='submit' value='" . _("Go") . "'>\n";
		echo "</form>\n";

		// We can't easily do a multipage mark-as-bad,
		// because it requires a per-page reason.
	}

}

// -----------------------------------------------------------------------------

function get_rounds_with_data( $projectid )
// Returns an array of Rounds representing those rounds
// for which the given project has some useful data.
{
	$rounds_with_data = array();

	// Currently, when a project skips a round, the time and text of the
	// corresponding slot are still set. It's this data that we exclude by
	// talking about "useful data" above.
	// So a column of empty users is the best indication of a skipped round.
	// (And also works for a not-yet-reached round.)

	global $Round_for_round_id_;

	$sums = array();
	foreach ( $Round_for_round_id_ as $round_id => $round )
	{
		$sums[] = "SUM({$round->user_column_name} != '') AS $round_id";
	}
	$body = join($sums,',');

	$res = mysql_query("
		SELECT $body
		FROM $projectid
	") or die(mysql_error());
	$num_filled_user_fields_for_round_ = mysql_fetch_assoc($res);

	foreach ( $Round_for_round_id_ as $round_id => $round )
	{
		if ( $num_filled_user_fields_for_round_[$round_id] == 0 )
		{
			// no real info in this slot
			// (skipped round or not-yet-reached round)
		}
		else
		{
			// There's some real info in this slot
			$rounds_with_data[] = $round;
		}
	}

	return $rounds_with_data;
}

// -----------------------------------------------------------------------------

function echo_cells_for_round( $round_num, $diff_round_num, // <- These are the only "real" params.
	$page_res,
	$projectid, $project_phase, $can_edit, $can_see_names_for_this_page)
{
	global $userP, $forums_url, $pguser, $code_url;

	// test
	//$can_edit = FALSE;

	$imagename  = $page_res['image'];
	$fileid     = $page_res['fileid'];
	$page_state = $page_res['state'];

	$round = get_Round_for_round_number($round_num);
	assert( !is_null($round) );

	$R_username = $page_res[$round->user_column_name];

        $cellcolorclause = " ";

       // colour-code pages proofread by the user
        if ($R_username == $pguser) {

                // a page saved as DONE by the user has a greenish background
                if ($page_state == $round->page_save_state) {
                        $cellcolorclause = "bgcolor='#99FF66'";
                }

                // a page saved as IN PROGRESS by the user has an orangey background
                else if ($page_state == $round->page_temp_state 
                    || $page_state == $round->page_out_state) {
                        $cellcolorclause = "bgcolor='#FFCC66'";
                }

                // a page by the user but not available for editing has a reddish background
                else if ($page_state != $round->page_avail_state)
                {
                        $cellcolorclause = "bgcolor='#FF3366'";
                }
        }

	// ------------------------------
	// Diff

	echo "<td align='center'>";
	if ($page_state != $round->page_avail_state) {
		if($page_res[$round->textdiff_column_name]) {
			echo _("no diff");
		} else {
			echo "<a href='$code_url/tools/project_manager/diff.php?project=$projectid&image=$imagename&L_round_num=$diff_round_num&R_round_num=$round_num'>"._("diff")."</a>";
		}
	}
	echo "</td>\n";


	// ------------------------------
	// Date

	$R_time = $page_res[$round->time_column_name];
	if ($R_time == 0)
	{
		$R_time_str = '';
	}
	else
	{
		$R_time_str = strftime(_("%Y %b %e %H:%M"), $R_time);
	}
	echo "<td>$R_time_str</td>\n";

	// ------------------------------
	// User

        if ($R_username == '')
        {
                echo "<td></td>\n";
        }
        else if ($can_see_names_for_this_page)
        {
		$R_pages_completed = $page_res["$round_num.user_page_tally"];

		$R_bb_user_id = $page_res["$round_num.phpbb_user_id"];

                echo "<td align='center' $cellcolorclause ><a href=$forums_url/privmsg.php?mode=post&u=$R_bb_user_id>$R_username</a> ($R_pages_completed)</td>\n";
        }
        else
        {
                echo "<td></td>\n";
        }

	// ------------------------------
	// Text

	$R_text_length = $page_res["length($round->text_column_name)"];

	if ( $R_text_length == 0 )
	{
		echo "<td $cellcolorclause > </td>\n";
	}
	else
	{
		echo "<td align='right' $cellcolorclause ><a href='$code_url/tools/project_manager/downloadproofed.php?project=$projectid&image=$imagename&round_num=$round_num'>$R_text_length&nbsp;b</a></td>\n";
	}

	// ------------------------------
	// Clear

	if ($can_edit) {
		// Anticipate the tests in page_clear()
		if (
			$project_phase=='PAGE_EDITING' &&
			$page_state == $round->page_save_state
		)
		{
			echo "<td><a href='$code_url/tools/project_manager/edit_pages.php?projectid=$projectid&selected_pages[$fileid]=on&operation=clear'>"._("Clear")."</a></td>";
		}
		else
		{
			// page_clear() won't let anything happen
			echo "<td></td>";
		}
	}
	echo "\n";
}

// -----------------------------------------------------------------------------

function page_state_is_an_avail_state($page_state)
{
	for ( $rn = 1; $rn <= MAX_NUM_PAGE_EDITING_ROUNDS; $rn++ )
	{
		$round = get_Round_for_round_number($rn);
		if ( $page_state == $round->page_avail_state )
		{
			return TRUE;
		}
	}
	return FALSE;
}

function page_state_is_a_bad_state($page_state)
{
	for ( $rn = 1; $rn <= MAX_NUM_PAGE_EDITING_ROUNDS; $rn++ )
	{
		$round = get_Round_for_round_number($rn);
		if ( $page_state == $round->page_bad_state )
		{
			return TRUE;
		}
	}
	return FALSE;
}

?>
