<?
$relPath="./../../pinc/";
include_once($relPath.'misc.inc');
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'projectinfo.inc');
include_once($relPath.'project_edit.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'iso_lang_list.inc');
include_once($relPath.'SettingsClass.inc');
include_once($relPath.'special_colors.inc');
include_once('projectmgr.inc');
include_once('projectmgr_select.inc');



if (empty($_GET['show'])) {
	if ($userP['i_pmdefault'] == 0) {
		metarefresh(0,"projectmgr.php?show=all","","");
		exit();
	} elseif ($userP['i_pmdefault'] == 1) {
		metarefresh(0,"projectmgr.php?show=user_active","", "");
		exit();
	}
}

$can_see_all = user_is_a_sitemanager() || user_is_proj_facilitator();

theme(_("Project Managers"), "header");

abort_if_not_manager();



	if ( !isset($_GET['show']) || $_GET['show'] == 'search_form' || $_GET['show'] == '' ) {
		echo_manager_header('project_search_page');

		echo "
		    <center>
		    "._("Search for projects matching the following criteria:")."<br>
		    <form method=get action='projectmgr.php'>
			<input type='hidden' name='show' value='search'>
			<table>
			<tr>
			    <td>"._("Title")."</td>
			    <td><input type='text' name='title'></td>
			</tr>
			<tr>
			    <td>"._("Author")."</td>
			    <td><input type='text' name='author'></td>
			</tr>
			<tr>
			    <td>"._("Language")."</td>
			    <td>
			        <select name='language'>
			            <option value='' selected>"._("Any")."</option>
		";
		foreach($lang_list as $k=>$v)
			echo "<option value='{$v['lang_name']}'>{$v['lang_name']}</option>\n";
		echo "
			        </select>
			    </td>
			</tr>
		";
        	if ($can_see_all)
		{
		    echo "
			<tr>
			    <td>"._("Project Manager")."</td>
			    <td><input type='text' name='project_manager'></td>
			</tr>
		    ";
		}
		// In the <select> tag, we set the name attribute to 'state[]'.
		// I'm pretty sure this doesn't mean anything to HTML/HTTP,
		// but PHP takes it as a cue to make the multiple values of
		// the select control available as an array.
		// That is, $_GET['state'] will be an array containing
		// all selected values.
		echo "
			<tr>
			    <td>"._("State")."</td>
			    <td>
			    <select name='state[]' multiple>
				<option value=''>any state</option>
		";
		foreach ($PROJECT_STATES_IN_ORDER as $proj_state_in_order)
		{
		    echo "<option value='$proj_state_in_order'>";
		    echo project_states_text($proj_state_in_order);
		    echo "</option>\n";
		}
		echo "
			    </select>
			    </td>
			</tr>
			<tr>
				<td></td>
				<td>
					<table width='100%'>
					<tr>
						<td align='left'><input type='submit' value='"._("Search")."'></td>
						<td align='right'><input type='reset' value='"._("Clear form")."'></td>
					</tr>
					</table>
				</td>
			</tr>
			</table>
		    </form>
		    "._("Matching [except for State] is case-insensitive and unanchored;<br>
		    so, for instance, 'jim' matches both 'Jimmy Olsen' and 'piggyjimjams'.<br>
		    <br>
		    If desired, you should be able to select<br>
		    multiple values for State (e.g., by holding down Ctrl).")."
		    </center>
		";
	} else {
		echo_manager_header('project_listings_page');

		// Construct and submit the search query.

        	if ($_GET['show'] == "site" && $can_see_all) {
			$condition = "state != '".PROJ_SUBMIT_PG_POSTED."'";
        	} elseif ($_GET['show'] == "all") {
			$condition = "username = '$pguser'";
		} elseif ($_GET['show'] == 'search') {
			$condition = '1';
			if ( $_GET['title'] != '' )
			{
			    $condition .= " AND nameofwork LIKE '%{$_GET['title']}%'";
			}
			if ( $_GET['author'] != '' )
			{
			    $condition .= " AND authorsname LIKE '%{$_GET['author']}%'";
			}
			if ( $_GET['language'] != '' )
			{
			    $condition .= " AND language LIKE '%{$_GET['language']}%'";
			}
			if ($can_see_all)
			{
			    if ( $_GET['project_manager' ] != '' )
			    {
				$condition .= " AND username LIKE '%{$_GET['project_manager']}%'";
			    }
			}
			else
			{
			    // The user is a project manager, not a site admin or project facilitator
			    // so they can only see their own projects.
			    $condition .= " AND username='$pguser'";
			}
			if ( isset($_GET['state']) && count($_GET['state']) > 0 )
			{
			    $condition .= " AND (0";
			    foreach( $_GET['state'] as $state )
			    {
				if ( $state == '' )
				{
				    $condition .= " OR 1";
				}
				else
				{
				    $condition .= " OR state='$state'";
				}
			    }
			    $condition .= ")";
			}
        	} else {
			$condition = "state != '".PROJ_SUBMIT_PG_POSTED."' AND username = '$pguser'";
        	}
		$result = mysql_query("
			SELECT projectid, nameofwork, authorsname, difficulty, checkedoutby, state, username, comments
			FROM projects
			WHERE $condition
			ORDER BY nameofwork asc
		") or die(mysql_error());

		$numrows = mysql_num_rows($result);
		if ( $numrows == 0 )
		{
		    echo _("<b>No projects matched the search criteria.</b>");
		    theme("","footer");
		    return;
		}

		// -------------------------------------------------------------

		function option_to_move( $curr_state, $new_state )
		{
		    global $result;

		    $projectids = array();
		    while ( $project = mysql_fetch_assoc($result) )
		    {
			if ( $project['state'] == $curr_state )
			{
			    $projectids[] = $project['projectid'];
			}
		    }
		    mysql_data_seek($result, 0);

		    if ( count($projectids) > 0 )
		    {
			$curr_blurb = project_states_text($curr_state);
			$new_blurb  = project_states_text($new_state);
			$projectids_str = implode( ',', $projectids );

			echo "<a href='move_projects.php?curr_state=$curr_state&new_state=$new_state&projects=$projectids_str'>";
			echo _("Move all")." <b>$curr_blurb</b> "._("projects on this page to")." <b>$new_blurb</b>";
			echo "</a>";
			echo "<br>";
			echo "<br>";
		    }
		}

		option_to_move( PROJ_NEW, PROJ_P1_UNAVAILABLE );
		option_to_move( PROJ_P1_UNAVAILABLE, PROJ_P1_WAITING_FOR_RELEASE );

		// -------------------------------------------------------------

		// Present the results of the search query.

		$show_pages_left = 0;
		$show_pages_total = 1;

		echo "<center><table border=1 width=630 cellpadding=0 cellspacing=0 style='border-collapse: collapse' bordercolor=#111111>";

		function echo_header_cell( $width, $text )
		{
		    global $theme;
		    echo "<td width='$width' align='center' bgcolor='{$theme['color_headerbar_bg']}'>";
		    echo "<font color='{$theme['color_headerbar_font']}'>";
		    echo "<b>$text</b>";
		    echo "</font>";
		    echo "</td>";
		}

    		echo "<tr>";
      		echo_header_cell( 175, _("Title") );
      		echo_header_cell( 100, _("Author") );
      		echo_header_cell( 25, _("Diff.") );
		if ( $show_pages_left )
		{
		    echo_header_cell( 50, _("Left") );
		}
		if ( $show_pages_total )
		{
		    echo_header_cell( 50, _("Total") );
		}
      		echo_header_cell(  75, ($_GET['show'] == "site" ? _("PM") : _("Owner") ) );
      		echo_header_cell( 180, _("Project Status") );
      		echo_header_cell(  50, _("Options") );
      		echo "</tr>";

		// Determine whether to use special colors or not
		// (this does not affect the alternating between two
		// background colors) in the project listing.
		$userSettings = Settings::get_Settings($pguser);
		$show_special_colors = !$userSettings->get_boolean('hide_special_colors');

		$tr_num = 0;
		foreach ($PROJECT_STATES_IN_ORDER as $proj_state_in_order)
		{
                   // Reset internal row pointer (we know that $numrows > 0 so this is ok)
                   mysql_data_seek($result, 0);
        	   while ($project = mysql_fetch_assoc($result)) {
            	     $state = $project['state'];
		     if ($state == $proj_state_in_order)
		     {
            		$name = $project['nameofwork'];
            		$author = $project['authorsname'];
                        $diff = strtoupper(substr($project['difficulty'],0,1));
            		$projectid = $project['projectid'];
            		$outby = $project['checkedoutby'];
			$comments = $project['comments'];

			if ($tr_num % 2 ) {
                		$bgcolor = $theme['color_mainbody_bg'];
                	} else {
                		$bgcolor = $theme['color_navbar_bg'];
            		}

			// Special colours for special books of various types
			if ($show_special_colors)
			{
				$special_color = get_special_color_for_project($project);
				if (!is_null($special_color)) {
					$bgcolor = "'$special_color'";
				}
			}

            		echo "<tr bgcolor=$bgcolor>\n";

			// Title
			echo "<td><a href=\"project_detail.php?project=$projectid\">$name</a></td>\n";

			// Author
			echo "<td>$author</td>\n";

			// Difficulty
			echo "<td align=\"center\">$diff</td>\n";


			// Left
			if ( $show_pages_left )
			{
			    $num_available_pages = Project_getNumAvailablePagesInRound($projectid, $state);
			    echo "<td align=\"center\">$num_available_pages</td>\n";
			}


			// Total
			if ( $show_pages_total )
			{

				// get the total from the HEAP table if possible, only look at projectID table if have to
				$totqry = mysql_query("SELECT total_pages FROM page_counts WHERE projectid = '$projectid'");
				if (mysql_num_rows($totqry))
	 				{
						$totpag = mysql_result($totqry,0,"total_pages");
					}
				else
					{
						$dbQ = mysql_query("SELECT count(fileid) AS totalpages FROM $projectid");
						if ($dbQ != "") { $totpag=mysql_result($dbQ,0,"totalpages"); }
						else
							{
								$totpag = 0;
							}
					}

			    echo "<td align=\"center\">$totpag</td>\n";
			}


			// Owner
			echo "<td align=\"center\">";
            		if ($_GET['show'] == 'site') {
                		print $project['username'];
            		} else if ($outby != "") {
				// Maybe we should get this info via a
				// left outer join in the big select query.
                		$tempsql = mysql_query("SELECT email FROM users WHERE username = '$outby'");
                		$outbyemail = mysql_result($tempsql, 0, "email");
                		print "<a href=mailto:$outbyemail>$outby</a>";
            		}
			echo "</td>\n";

			// Project Status

			if (user_is_a_sitemanager() or user_is_PM_of($projectid) or user_is_proj_facilitator()) {

				echo "
				    <td valign=center>
					<form
					    name='$projectid'
					    method='get'
					    action='changestate.php'>
					    <input
						type='hidden'
						name='project'
						value='$projectid'>
					    <select
						name='state'
						onchange='this.form.submit()'>
				";
	            		getSelect($state);
	            		echo "</select></form></td>\n";
			} else {
				echo "<td valign=center>$state</td>\n";
			}

			// Options
			echo "<td align=center>";
			print "<a href=\"editproject.php?project=$projectid\">Edit</a>";
            		if ($state==PROJ_POST_FIRST_UNAVAILABLE || $state==PROJ_POST_FIRST_AVAILABLE || $state==PROJ_POST_FIRST_CHECKED_OUT) print " <a href = \"$projects_url/$projectid/$projectid.zip\">D/L</A>";
            		if (($state == PROJ_POST_SECOND_CHECKED_OUT) || ($state == PROJ_POST_COMPLETE)) print " <a href=\"$projects_url/$projectid/".$projectid."_second.zip\">D/L</A>";
            		echo "</td>\n";

			echo "</tr>\n";

			$tr_num++;
		     }
        	   }
		}
		echo "<tr><td colspan=6 bgcolor='".$theme['color_headerbar_bg']."'>&nbsp;</td></tr></table></center>";

		// special colours legend
		// Don't display if the user has selected the
		// setting "Show Special Colors: No".
		// The legend has been put at the bottom of the page
		// because the use of colors is presumably mostly
		// useful as a check that no typo was made. The
		// exact color probably doesn't matter and,
		// furthermore, the PM 'knows' the project and
		// what's so special about it.
		if (!$userSettings->get_boolean('hide_special_colors')) {
		    echo "<p><font face='{$theme['font_mainbody']}'>\n";
		    include('../proofers/special_legend.php');
		    echo "</font></p><br>\n";
		}
	}

echo "<br>";
theme("","footer");
?>
