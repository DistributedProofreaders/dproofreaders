<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'projectinfo.inc');
include_once($relPath.'project_edit.inc');
$projectinfo = new projectinfo();
include_once('projectmgr.inc');
include_once('projectmgr_select.inc');
include_once('page_table.inc');

if (isset($_GET['project'])) { $no_stats=1; }
theme("Project Managers", "header");

abort_if_not_manager();
echo_manager_header();

	if (!isset($_GET['project']) || $_GET['show'] == "all") {
?>
	<p><b>Project Manager Notice:</b><br>

	<p>
	The "Pages Left" column of the projects table has been removed.
	This should speed up assembly of the PM page dramatically,
	and also reduce the load on the server.
	Note that the "Pages Remaining to be Proofed" for a project
	is still available, on its Project Details page.

	<hr width='50%'>
	<p>
	On the Project Details page,
	you can now specify a directory
	(in the <? echo $uploads_account; ?> account)
	from which to add text+images into your project.
	This means that you are now free to choose the name
	of the upload directory you create,
	instead of having to use the project's ID.
	(E.g., you might choose to give it the same name
	as the corresponding directory on your local machine.)
	Of course, the project's ID will still work fine
	as the name of the directory, and is in fact the default
	for the Add Text+Images button.

	<p>
	Moreover, the string you type is actually interpreted as a 'path'
	(relative to the root of the <? echo $uploads_account; ?> account),
	so it can be a directory within a directory.
	For instance, you may find it convenient to create a personal directory
	in the <? echo $uploads_account; ?> account,
	and then create your project-specific directories within it.
	(If you do this, it's recommended that you use your DP login name
	for the name of the personal directory,
	as that may be an assumed default in the future.)

	<hr width='50%'>
	<p>
	Note the new <b>Search Your Projects</b> link above. Try it out!
	
	<hr width='50%'>
	<p>
	There is a new way to upload files to the site without needing anyone to help you:
	<ol>
		<li>FTP to <? echo $uploads_host; ?> with username <? echo $uploads_account; ?> and password <? echo $uploads_password; ?>
		<li>Make a directory named what the projectID is (look in the URL of the project when you click on the title, labeled project=projectIDXXX, use entire projectIDXXX for the folder)
		<li>Upload the text and images to that folder, titled 001.txt, 001.png, 002.txt, 002.png, etc...
		<li>Click on the title of the book to view it's details.
		<li>Click on the link at the top titled "Add Images And Text From <? echo $uploads_account; ?> Account".
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
		echo "</table>";

		if ($state == PROJ_NEW || $state == PROJ_PROOF_FIRST_UNAVAILABLE)
		{
			echo "<br>\n";
			echo "<form method='get' action='add_files.php'>\n";
			echo "<input type='hidden' name='project' value='$projectid'>\n";
			if ($userP['sitemanager'] == "yes") {
				echo "Add Text From projects Folder";
				echo "<input type='hidden' name='source_dir' value=''>\n";
			} else {
				echo "Add Text+Images from $uploads_account Account";
				echo "<br>\n";
				echo "directory: ";
				echo "<input type='text' name='source_dir'>";
				echo " (defaults to $projectid )";
			}
			echo "<br>\n";
			echo "<input type='submit' value='Add'>";
			echo "<br>\n";
			echo "</form>\n";

			echo "<a href='deletefile.php?project=$projectid'>Delete All Text</a>";
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


		echo "<h3>Per-Page Info</h3>\n";
		echo_page_table( $projectid );

        	echo "</center>";
	} elseif ( $_GET['show'] == 'search_form' ) {
		echo "
		    <center>
		    Search for projects matching the following criteria:<br>
		    <form method=get action='projectmgr.php'>
			<input type='hidden' name='show' value='search'>
			<table>
			<tr>
			    <td>Title</td>
			    <td><input type='text' name='title'></td>
			</tr>
			<tr>
			    <td>Author</td>
			    <td><input type='text' name='author'></td>
			</tr>
		";
        	if ($userP['sitemanager'] == "yes")
		{
		    echo "
			<tr>
			    <td>Project Manager</td>
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
			    <td>State</td>
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
			</table>
			<input type='submit' value='Search'>
		    </form>
		    Matching [except for State] is case-insensitive and unanchored;<br>
		    so, for instance, 'jim' matches both 'Jimmy Olsen' and 'piggyjimjams'.<br>
		    <br>
		    If desired, you should be able to select<br>
		    multiple values for State (e.g., by holding down Ctrl).
		    </center>
		";
	} else {
		$show_pages_left = 0;

		echo "<center><table border=1 width=630 cellpadding=0 cellspacing=0 style='border-collapse: collapse' bordercolor=#111111>";
    		echo "<tr>";
      		echo "<td width='175' align='center' bgcolor='".$theme['color_headerbar_bg']."'><font color='".$theme['color_headerbar_font']."'><b>Title</b></font></td>";
      		echo "<td width='100' align='center' bgcolor='".$theme['color_headerbar_bg']."'><font color='".$theme['color_headerbar_font']."'><b>Author</b></font></td>";
		if ( $show_pages_left )
		{
		    echo "<td width='50' align='center' bgcolor='".$theme['color_headerbar_bg']."'><font color='".$theme['color_headerbar_font']."'><b>Left</b></font></td>";
		}
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
			if ($userP['sitemanager'] == "yes")
			{
			    if ( $_GET['project_manager' ] != '' )
			    {
				$condition .= " AND username LIKE '%{$_GET['project_manager']}%'";
			    }
			}
			else
			{
			    // The user is a project manager, not a site admin,
			    // so they can only see their own projects.
			    $condition .= " AND username='$pguser'";
			}
			if ( count($_GET['state']) > 0 )
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
			SELECT projectid, nameofwork, authorsname, checkedoutby, state, username
			FROM projects
			WHERE $condition
			ORDER BY nameofwork asc
		");
        	if ($result != "") $numrows = (mysql_num_rows($result));

		$tr_num = 0;
		foreach ($PROJECT_STATES_IN_ORDER as $proj_state_in_order)
		{
        	   $rownum = 0;
        	   while ($rownum < $numrows) {
            	     $state = mysql_result($result, $rownum, "state");
		     if ($state == $proj_state_in_order)
		     {
            		$name = mysql_result($result, $rownum, "nameofwork");
            		$author = mysql_result($result, $rownum, "authorsname");
            		$projectid = mysql_result($result, $rownum, "projectid");
            		$outby = mysql_result($result, $rownum, "checkedoutby");
	
			if ($tr_num % 2 ) {
                		$bgcolor = $theme['color_main_bg'];
                	} else {
                		$bgcolor = $theme['color_navbar_bg'];
            		}

            		echo "<tr bgcolor=$bgcolor>\n";

			// Title
			echo "<td><a href=\"projectmgr.php?project=$projectid\">$name</a></td>\n";

			// Author
			echo "<td>$author</td>\n";

			// Left
			if ( $show_pages_left )
			{
			    $projectinfo->update_avail($projectid, $state);
			    echo "<td align=\"center\">$projectinfo->availablepages</td>\n";
			}

			// Owner
			echo "<td align=\"center\">";
            		if ($show == 'site') {
                		print mysql_result($result, $rownum, "username");
            		} else if ($outby != "") {
				// Maybe we should get this info via a
				// left outer join in the big select query.
                		$tempsql = mysql_query("SELECT email FROM users WHERE username = '$outby'");
                		$outbyemail = mysql_result($tempsql, 0, "email");
                		print "<a href=mailto:$outbyemail>$outby</a>";
            		}
			echo "</td>\n";

			// Project Status
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

			// Options
			echo "<td align=center>";
			print "<a href=\"editproject.php?project=$projectid\">Edit</a>";
            		if ($state==PROJ_POST_UNAVAILABLE || $state==PROJ_POST_AVAILABLE || $state==PROJ_POST_CHECKED_OUT) print " <a href = \"$projects_url/$projectid/$projectid.zip\">D/L</A>";
            		if (($state == PROJ_POST_VERIFYING) || ($state == PROJ_POST_COMPLETE)) print " <a href=\"$projects_url/$projectid/post.zip\">D/L</A>";
            		echo "</td>\n";

			echo "</tr>\n";

			$tr_num++;
		     }
		     $rownum++;
        	   }
		}
		echo "<tr><td colspan=6 bgcolor='".$theme['color_headerbar_bg']."'>&nbsp;</td></tr></table>";
	}

echo "<br>";
theme("","footer");
?>
