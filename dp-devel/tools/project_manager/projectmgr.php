<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'projectinfo.inc');
include_once($relPath.'page_states.inc');
include_once($relPath.'project_edit.inc');
$projectinfo = new projectinfo();
include_once('projectmgr_select.inc');
include_once($relPath.'f_project_states.inc');
theme("Project Managers", "header");

function echo_cells_for_round($round_num)
{
	global $res, $rownum, $userP, $projectid, $fileid, $inRound, $page_state;

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

	$text_length = mysql_result($res, $rownum, $R_text_length_field_name);

	if ( $text_length == 0 )
	{
		echo "<td></td>\n";
	}
	else
	{
		echo "<td><a href=downloadproofed.php?project=$projectid&fileid=$fileid&state=$R_save_state>$text_length&nbsp;b</a></td>\n";
	}

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

// -----------------------------------------------------------------------------

//If they are not a manager let them know with a link back to the Personal Page
	if ($userP['manager'] != "yes") {
		echo "<P>You are not listed as a project manager. Please contact the <a href='mailto:$site_manager_email_addr'>site manager</A> about resolving this problem.";
        	echo "<P>Back to <A HREF=\"../../default.php\">home page</A>";
        	theme("","footer");
        	exit();
	}

//Display the introduction & links bar
	echo "<p><center><a href='projectmgr.php'>Show Your Active Projects</a> | <a href='projectmgr.php?show=all'>Show All of Your Projects</a> | <a href='editproject.php'>Create Project</a>";
	if ($userP['sitemanager'] == "yes") { 
		echo " | <a href='projectmgr.php?show=site'>Show All Projects</a>"; 
	}
	echo "</center><br>";
	if (!isset($_GET['project']) || $_GET['show'] == "all") {
?>
	<p><b>Project Manager Notice:</b><br>There is a new way to upload files to the site without needing anyone to help you:
	<ol>
		<li>FTP to pgdp01.archive.org with username dpscans and password image$
		<li>Make a directory named what the projectID is (look in the URL of the project when you click on the title, labeled project=projectIDXXX, use entire projectIDXXX for the folder)
		<li>Upload the text and images to that folder, titled 001.txt, 001.png, 002.txt, 002.png, etc...
		<li>Click on the title of the book to view it's details.
		<li>Click on the link at the top titled "Add Images And Text From dpscans Account".
		<li>It will load them into the database, look over the list of pages before setting it to "Waiting to be Released".
	</ol>
	<p>Recent bug fixes include being able to put any characters in the project comments box and preventing books from the same author to be available in the First Round (meaning if you have 4 volumes of a series, you can put them into Waiting to be Released and they will be released as each passes through the First Round).
	<p>If you want the projects to go to post-processing automatically, e-mail the <a href='mailto:<? echo $site_manager_email_addr; ?>'>site manager</A>.<br><br>
	<hr width="75%" align="center"><br>

<?
}

//If they have selected a specific project first see if they are the PM for it
//and if they are display the details of that project.  If they are not the PM
//then display an error stating so.
	if (isset($_GET['project'])) {
		$projectid = $_GET['project'];

		abort_if_cant_edit_project( $projectid );

		$result = mysql_query("SELECT nameofwork, authorsname, language, username, state FROM projects WHERE projectid = '$projectid'");
        	$manager = mysql_result($result, 0, "username");
        	$state = mysql_result($result, 0, "state");
        	$name = mysql_result($result, 0, "nameofwork");
        	$author = mysql_result($result, 0, "authorsname");
        	$language = mysql_result($result, 0, "language");

        	$projectinfo->update($projectid, $state);
	
		echo "<center><table border=1>";
		echo "<tr><td bgcolor='".$theme['color_headerbar_bg']."' colspan=4><b><font color='".$theme['color_headerbar_font']."' size=+1>Project Name: $name</font></b> <font color='".$theme['color_headerbar_font']."'>($projectid)</font></td></tr>";
		echo "<tr><td bgcolor='".$theme['color_navbar_bg']."'>Author:</td><td>$author</td><td bgcolor='".$theme['color_navbar_bg']."'>Total Number of Master Pages:</td><td>$projectinfo->total_pages</td></tr>";
		echo "<tr><td bgcolor='".$theme['color_navbar_bg']."'>Language:</td><td>$language</td><td bgcolor='".$theme['color_navbar_bg']."'>Pages Remaining to be Proofed:</td><td>$projectinfo->availablepages</td></tr>";

		if ($state == PROJ_NEW || $state == PROJ_PROOF_FIRST_UNAVAILABLE)
		{
			echo "<tr><td bgcolor='".$theme['color_navbar_bg']."' colspan=2><a href='add_files.php?project=$projectid'>";
			if ($userP['sitemanager'] == "yes") {
				echo "Add All Text From projects Folder";
			} else {
				echo "Add All Text/Images from dpscans Account";
			}
			echo "</a><td bgcolor='".$theme['color_navbar_bg']."' colspan=2><a href='deletefile.php?project=$projectid'>Delete All Text</a></td></tr>";
			$something = 0;
		}
		elseif ( $state == PROJ_PROOF_FIRST_AVAILABLE
			|| $state == PROJ_PROOF_FIRST_WAITING_FOR_RELEASE
			|| $state == PROJ_PROOF_FIRST_BAD_PROJECT
			|| $state == PROJ_PROOF_FIRST_VERIFY
			|| $state == PROJ_PROOF_FIRST_COMPLETE )
		{
			$something = 1;
		}
		else
		{
			$something = 2;
		}
		echo "</table>";

		$inRound=projectStateRound($state);

		$show_image_size = 1;
		$show_delete = ($inRound=='NEW' || $inRound=='PR' || $inRound='FIRST' || $inRound=='SECOND');
		$upload_colspan = 2 + $show_image_size;

		echo "<h3>Per-Page Info</h3>\n";

		echo "<table border=1>\n";

		// Top header row
		{
			echo "<tr>\n";
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

			echo "{$td_start}Bad Page{$td_end}";

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
				echo "<td bgcolor='$bgcolor'>$imagesize";
			}

			// Master Text
			$master_text_length = mysql_result($res, $rownum, "length(master_text)");
			echo "<td><a href=downloadproofed.php?project=$projectid&fileid=$fileid&state=".UNAVAIL_FIRST.">$master_text_length&nbsp;b</a></td>\n";

			// --------------------------------------------

			// Page State
			$page_state = mysql_result($res, $rownum, "state");
			echo "<td>$page_state</td>\n";

			// --------------------------------------------

			echo_cells_for_round(1);

			echo_cells_for_round(2);

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

        	echo "</center>";
	} else {
		echo "<center><table border=1 width=630 cellpadding=0 cellspacing=0 style='border-collapse: collapse' bordercolor=#111111>";
    		echo "<tr>";
      		echo "<td width='175' align='center' bgcolor='".$theme['color_headerbar_bg']."'><font color='".$theme['color_headerbar_font']."'><b>Title</b></font></td>";
      		echo "<td width='100' align='center' bgcolor='".$theme['color_headerbar_bg']."'><font color='".$theme['color_headerbar_font']."'><b>Author</b></font></td>";
      		echo "<td width='50' align='center' bgcolor='".$theme['color_headerbar_bg']."'><font color='".$theme['color_headerbar_font']."'><b>Left</b></font></td>";
      		echo "<td width='75' align='center' bgcolor='".$theme['color_headerbar_bg']."'><font color='".$theme['color_headerbar_font']."'><b>";
	        if ($_GET['show'] == "site") {
			echo "PM";
        	} else {
        		echo "Owner";
        	}
		echo "</b></font></td>";
      		echo "<td width='180' align='center' bgcolor='".$theme['color_headerbar_bg']."'><font color='".$theme['color_headerbar_font']."'><b>Project Status</b></font></td>";
      		echo "<td width='50' align='center' bgcolor='".$theme['color_headerbar_bg']."'><font color='".$theme['color_headerbar_font']."'><b>Options</b></font></td>";
      		echo "</tr>";

        	$numrows = 0;
        	if ($_GET['show'] == "site" && $userP['sitemanager'] == "yes") {
            		$result = mysql_query("SELECT projectid, nameofwork, authorsname, checkedoutby, state, username FROM projects WHERE state != '".PROJ_SUBMIT_PG_POSTED."' ORDER BY state asc, nameofwork asc");
        	} elseif ($_GET['show'] == "all") {
            		$result = mysql_query("SELECT projectid, nameofwork, authorsname, checkedoutby, state, username FROM projects WHERE username = '$pguser' ORDER BY state asc, nameofwork asc");
        	} else {
        		$result = mysql_query("SELECT projectid, nameofwork, authorsname, checkedoutby, state, username FROM projects WHERE state != '".PROJ_SUBMIT_PG_POSTED."' AND username = '$pguser' ORDER BY state asc, nameofwork asc");
        	}
        	if ($result != "") $numrows = (mysql_num_rows($result));

        	$rownum = 0;
        	while ($rownum < $numrows) {
            		$name = mysql_result($result, $rownum, "nameofwork");
            		$author = mysql_result($result, $rownum, "authorsname");
            		$projectid = mysql_result($result, $rownum, "projectid");
            		$outby = mysql_result($result, $rownum, "checkedoutby");
            		$state = mysql_result($result, $rownum, "state");
	
        		if ($outby != "") {
                		$tempsql = mysql_query("SELECT email FROM users WHERE username = '$outby'");
                		$outbyemail = mysql_result($tempsql, 0, "email");
            		}

			if ($rownum % 2 ) {
                		$bgcolor = $theme['color_main_bg'];
                	} else {
                		$bgcolor = $theme['color_navbar_bg'];
            		}

			$projectinfo->update_avail($projectid, $state);

            		print "<tr bgcolor=$bgcolor><td><a href=\"projectmgr.php?project=$projectid\">$name</a></td><td>$author</td><td align=\"center\">$projectinfo->availablepages</td><td align=\"center\">";
            		if ($show == 'site') {
                		print mysql_result($result, $rownum, "username");
            		} else if ($outby != "") {
                		print "<a href=mailto:$outbyemail>$outby</a>";
            		}

			print "</td><td valign=center><form name=\"$projectid\" method=get action=\"changestate.php\"><input type=hidden name=project value=\"$projectid\"><select name=state onchange=\"this.form.submit()\">";
            		getSelect($state);
            		echo "</select></form></td><td align=center>";

			print "<a href=\"editproject.php?project=$projectid\">Edit</a>";
            		if ($state==PROJ_POST_UNAVAILABLE || $state==PROJ_POST_AVAILABLE || $state==PROJ_POST_CHECKED_OUT) print " <a href = \"$projects_url/$projectid/$projectid.zip\">D/L</A>";
            		if (($state == PROJ_POST_VERIFYING) || ($state == PROJ_POST_COMPLETE)) print " <a href=\"$projects_url/$projectid/post.zip\">D/L</A>";
            		echo "</td></tr>\n";
            		$rownum++;
        	}
		echo "<tr><td colspan=6 bgcolor='".$theme['color_headerbar_bg']."'>&nbsp;</td></tr></table>";
	}

echo "<br>";
theme("","footer");
?>
