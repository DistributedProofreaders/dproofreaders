<?PHP
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'page_states.inc');
include_once($relPath.'f_project_states.inc'); // projectStateRound

function echo_page_table(
	$projectid,
	$show_image_size,
	$disable_editing = FALSE,
	$pages_of_interest = NULL )
{
	global $theme, $projects_dir, $code_url;
	
	$can_edit = (user_is_PM_of( $projectid) || user_is_a_sitemanager() || user_is_proj_facilitator());

	// want PPers able to see names
	$can_see_names_for_all_pages = ($can_edit || user_is_PP_of( $projectid));

	if ( $disable_editing )
	{
		// Although the user may be allowed to edit the project,
		// this particular page should not let them do so.
		$can_edit = FALSE;
		// But they still get to see names.
	}

	$proj_res = mysql_query("SELECT state FROM projects WHERE projectid = '$projectid'") or die(mysql_error());
	$state = mysql_result($proj_res, 0, "state");

	$inRound = projectStateRound($state);

	$show_delete = (($inRound=='NEW' || $inRound=='PR' || $inRound=='FIRST' || $inRound=='SECOND') && $can_edit);

	$upload_colspan = 2 + $show_image_size;

	if ( $can_edit )
	{
		echo "<form method='get' action='$code_url/tools/project_manager/edit_pages.php'>\n";
		echo "<input type='hidden' name='projectid' value='$projectid'>\n";
	}

	echo "<table border=1>\n";

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
		echo "    <td align='center' colspan='$n_cols_per_round'>"._("Round 1")."</td>\n";
		if ($inRound <> 'FIRST')
		{
			echo "    <td align='center' colspan='$n_cols_per_round'>"._("Round 2")."</td>\n";
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
		if ($inRound <> 'FIRST')
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
		if ($can_edit) echo "{$td_start}"._("Bad/Fix")."{$td_end}";

		if ($show_delete)
		{
			echo "{$td_start}"._("Delete")."{$td_end}";
		}
	}

	echo "</tr>";


	$path = "$projects_dir/$projectid/";

	if ($inRound <> 'FIRST')
	{
		$fields_to_get = '
		fileid, image, length(master_text),
		state,
		replace(master_text,\'\r\n\',\'\n\')=replace(round1_text,\'\r\n\',\'\n\') as round1_diff,
		round1_time, round1_user, length(round1_text),
		replace(round1_text,\'\r\n\',\'\n\')=replace(round2_text,\'\r\n\',\'\n\') as round2_diff,
		round2_time, round2_user, length(round2_text)';
	} else 
	{
		$fields_to_get = '
		fileid, image, length(master_text),
		state,
		replace(master_text,\'\r\n\',\'\n\')=replace(round1_text,\'\r\n\',\'\n\') as round1_diff,
		round1_time, round1_user, length(round1_text)';
	}

	$res = mysql_query( "SELECT $fields_to_get FROM $projectid ORDER BY image ASC" );
	$num_rows = mysql_num_rows($res);

	for ( $rownum=0; $rownum < $num_rows; $rownum++ )
	{
		$page_res = mysql_fetch_array( $res, MYSQL_ASSOC );

		$fileid = $page_res['fileid'];

		if ( is_null($pages_of_interest) )
		{
			// All pages are of interest.
		}
		else if ( array_key_exists( $fileid, $pages_of_interest ) )
		{
			// This is a page of interest.
		}
		else
		{
			// This is not a page of interest.
			continue;
		}

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
		echo "<td bgcolor='$bgcolor'><a href=displayimage.php?project=$projectid&imagefile=$imagename>$imagename</a></td>\n";

		// Image Size
		if ($show_image_size)
		{
			echo "<td bgcolor='$bgcolor' align='right'>$imagesize</td>";
		}

		// Master Text
		$master_text_length = $page_res['length(master_text)'];
		echo "<td align='right'><a href=downloadproofed.php?project=$projectid&fileid=$fileid&state=".UNAVAIL_FIRST.">$master_text_length&nbsp;b</a></td>\n";

		// --------------------------------------------

		// Page State
		$page_state = $page_res['state'];
		echo "<td>$page_state</td>\n";

		// --------------------------------------------

		echo_cells_for_round(1, $page_res, $projectid, $inRound, $can_edit, $can_see_names_for_all_pages);

		if ($inRound <> 'FIRST') echo_cells_for_round(2, $page_res, $projectid, $inRound, $can_edit, $can_see_names_for_all_pages);

		// (It's annoying that we have to pass all those args,
		// or else make them global, which I don't want to do.)

		// --------------------------------------------

		if ($can_edit) {

			// Bad Page
			echo "<td>";
			if (($page_state == BAD_FIRST) || ($page_state == BAD_SECOND)) {
				echo "<center><a href='badpage.php?projectid=$projectid&fileid=$fileid'>Bad</a></center>";
			} else {
				echo "<center><a href='badpage.php?projectid=$projectid&fileid=$fileid'>Fix</a></center>\n";
			}
			echo "</td>\n";

			// Delete
			if ($show_delete)
			{
				echo "<td><a href='edit_pages.php?projectid=$projectid&selected_pages[$fileid]=on&operation=delete'>Delete</a></td>\n";
			}
		}

		echo "</tr>";
	}
	echo "</table>";

	if ( $can_edit )
	{
		echo "<br>\n";
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

function echo_cells_for_round( $round_num, // <- This is the only "real" param.
	$page_res,
	$projectid, $inRound, $can_edit, $can_see_names_for_all_pages)
{
	global $userP, $forums_url, $pguser;

	// test
	//$can_edit = FALSE;

	$fileid     = $page_res['fileid'];
	$page_state = $page_res['state'];

	if ($round_num == 1)
	{
		$R_diff_field_name = 'round1_diff';
		$R_time_field_name = 'round1_time';
		$R_user_field_name = 'round1_user';
    $R_other_user_field_name = 'round2_user';
		$R_text_length_field_name = 'length(round1_text)';
		$R_save_state = SAVE_FIRST;
		$R_temp_state = TEMP_FIRST;
		$R_out_state = OUT_FIRST;
		$R_avail_state = AVAIL_FIRST;
	}
	elseif ($round_num == 2)
	{
		$R_diff_field_name = 'round2_diff';
		$R_time_field_name = 'round2_time';
		$R_user_field_name = 'round2_user';
    $R_other_user_field_name = 'round1_user';
		$R_text_length_field_name = 'length(round2_text)';
		$R_save_state = SAVE_SECOND;
		$R_temp_state = TEMP_SECOND;
		$R_out_state = OUT_SECOND;
		$R_avail_state = AVAIL_SECOND;
	}
	else
	{
		assert(FALSE);
	}

        // who are the people who proofread this page in each round?
        // we will let people see proofreader names of pages they themselves proofread

        $R_username = $page_res[$R_user_field_name];
        if (!empty($page_res[$R_other_user_field_name])) { $R_other_username = $page_res[$R_other_user_field_name]; } else { $R_other_username = ""; }


       // colour-code pages proofread by the user
        if ($R_username == $pguser) {

                // a page by the user but not available for editing has a reddish background
                $cellcolorclause = "bgcolor='#FF3366'";

                // a page saved as DONE by the user has a greenish background
                if ($page_state == $R_save_state) {
                        $cellcolorclause = "bgcolor='#99FF66'";
                }

                // a page saved as IN PROGRESS by the user has an orangey background
                if ($page_state == $R_temp_state || $page_state == $R_out_state) {
                        $cellcolorclause = "bgcolor='#FFCC66'";
                }

        } else
                $cellcolorclause = " ";


	// ------------------------------
	// Diff

	echo "<td align='center'>";
	if ($page_state != $R_avail_state) {
		if($page_res[$R_diff_field_name]) {
			echo _("no diff");
		} else {
			echo "<a href='diff.php?project=$projectid&file=$fileid&round=$round_num'>"._("diff")."</a>";
		}
	}
	echo "</td>\n";


	// ------------------------------
	// Date

	$R_time = $page_res[$R_time_field_name];
	if ($R_time == 0)
	{
		$R_time_str = '';
	}
	else
	{
		$R_time_str = strftime(_("%b %e %H:%M"), $R_time);
	}
	echo "<td>$R_time_str</td>\n";

	// ------------------------------
	// User

        if ($R_username == '')
        {
                echo "<td></td>\n";
        }
        else if ($can_see_names_for_all_pages or ($pguser ==  $R_username) or ($pguser == $R_other_username))
        {

                $R_ures = mysql_query("SELECT pagescompleted FROM users WHERE username = '$R_username'");
                if (mysql_num_rows($R_ures) == 0) {
                        $R_pages_completed = 0;
                } else {
                        $R_pages_completed = mysql_result($R_ures, 0, "pagescompleted");
                }

                $R_bbres = mysql_query("SELECT user_id FROM phpbb_users WHERE username = '$R_username'");
                if (mysql_num_rows($R_bbres) == 0) {
                        $R_bb_user_id = "";
                } else {
                        $R_bb_user_id = mysql_result($R_bbres, 0, "user_id");
                }

                echo "<td align='center' $cellcolorclause ><a href=$forums_url/privmsg.php?mode=post&u=$R_bb_user_id>$R_username</a> ($R_pages_completed)</td>\n";
        }
        else
        {
                echo "<td></td>\n";
        }

	// ------------------------------
	// Text

	$R_text_length = $page_res[$R_text_length_field_name];

	if ( $R_text_length == 0 )
	{
		echo "<td $cellcolorclause > </td>\n";
	}
	else
	{
		echo "<td align='right' $cellcolorclause ><a href=downloadproofed.php?project=$projectid&fileid=$fileid&state=$R_save_state>$R_text_length&nbsp;b</a></td>\n";
	}

	// ------------------------------
	// Clear

	if ($can_edit) {
		// Anticipate the tests in page_clear()
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
			echo "<td><a href='edit_pages.php?projectid=$projectid&selected_pages[$fileid]=on&operation=clear'>"._("Clear")."</a></td>";
		}
		else
		{
			// page_clear() won't let anything happen
			echo "<td></td>";
		}
	}
	echo "\n";
}


?>
