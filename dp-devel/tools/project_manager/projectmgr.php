<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'projectinfo.inc');
include_once($relPath.'page_states.inc');
$projectinfo = new projectinfo();
include_once('projectmgr_select.inc');
include_once($relPath.'f_project_states.inc');
theme("Project Managers", "header");

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
		<li>FTP to texts01.archive.org with username dpscans and password image$
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
		$project = $_GET['project'];
		$result = mysql_query("SELECT nameofwork, authorsname, language, username, state FROM projects WHERE projectid = '".$_GET['project']."'");
        	$manager = mysql_result($result, 0, "username");
        	$state = mysql_result($result, 0, "state");
        	$name = mysql_result($result, 0, "nameofwork");
        	$author = mysql_result($result, 0, "authorsname");
        	$language = mysql_result($result, 0, "language");
		
		if (($manager != $pguser) && ($userP['sitemanager'] != 'yes')) {
            		echo "<P>You are not listed as a project manager for this project. Please contact the <a href='mailto:$site_manager_email_addr'>site manager</A> about resolving this problem.";
            		echo "<P>Back to <A HREF=\"projectmgr.php\">manager home page</A>";
            		theme("","footer");
            		exit();
        	}
        	
        	$projectinfo->update($_GET['project'], $state);
	
		echo "<center><table border=1>";
		echo "<tr><td bgcolor='".$theme['color_headerbar_bg']."' colspan=4><b><font color='".$theme['color_headerbar_font']."' size=+1>Project Name: $name</font></b> <font color='".$theme['color_headerbar_font']."'>(".$_GET['project'].")</font></td></tr>";
		echo "<tr><td bgcolor='".$theme['color_navbar_bg']."'>Author:</td><td>$author</td><td bgcolor='".$theme['color_navbar_bg']."'>Total Number of Master Pages:</td><td>$projectinfo->total_pages</td></tr>";
		echo "<tr><td bgcolor='".$theme['color_navbar_bg']."'>Language:</td><td>$language</td><td bgcolor='".$theme['color_navbar_bg']."'>Pages Remaining to be Proofed:</td><td>$projectinfo->availablepages</td></tr>";
	
		if ($state == PROJ_NEW || $state == PROJ_PROOF_FIRST_UNAVAILABLE) {
			echo "<tr><td bgcolor='".$theme['color_navbar_bg']."' colspan=2><a href='add_files.php?project=".$_GET['project']."'>";
			if ($userP['sitemanager'] == "yes") {
				echo "Add All Text From projects Folder"; } else { echo "Add All Text/Images from dpscans Account";
			}
			echo "</a><td bgcolor='".$theme['color_navbar_bg']."' colspan=2><a href='deletefile.php?project=".$_GET['project']."'>Delete All Text</a></td></tr></table>";
			echo "<h3>Master Files</h3>";
			echo "<table border=1></tr>";
			echo "<tr bgcolor='".$theme['color_headerbar_bg']."'><td width=4><font color='".$theme['color_headerbar_font']."'>Index</font></td><td><font color='".$theme['color_headerbar_font']."'>Image</font></td><td><font color='".$theme['color_headerbar_font']."'>Size</font></td><td><font color='".$theme['color_headerbar_font']."'>Master Text</font></td><td><font color='".$theme['color_headerbar_font']."'>Size</font></td><td><font color='".$theme['color_headerbar_font']."'>Date Uploaded</font></td><td><font color='".$theme['color_headerbar_font']."'>Delete</font></td><td><font color='".$theme['color_headerbar_font']."'>Bad Page</font></td></tr>";
                	$counter = 1;
                	$rownum = 0;
                	$path = "$projects_dir/$project/";
			while ($rownum < $projectinfo->total_pages) {
				$imagename = mysql_result($projectinfo->total_rows, $rownum, "image");
				$date = mysql_result($projectinfo->total_rows, $rownum, "round1_time");
				$fileid = mysql_result($projectinfo->total_rows, $rownum, "fileid");
                    		$master_text = mysql_result($projectinfo->total_rows, $rownum, "master_text");
		    		$page_state = mysql_result($projectinfo->total_rows, $rownum, "state");
                    		if (file_exists($path.$imagename)) {
                       			$imagesize = filesize(realpath($path.$imagename));
                       			$bgcolor = "#FFFFFF";
                    		} else {
                       			$imagesize = 0;
                       			$bgcolor = "#FF0000";
                    		}
                    		if ($rownum % 2 ) {
                			$trcolor = $theme['color_main_bg'];
                		} else {
                			$trcolor = $theme['color_navbar_bg'];
            			}
                    		$date_txt = date("M j h:i A", $date);
                    		echo "<tr bgcolor='$trcolor'><td>$counter</td><td bgcolor='$bgcolor'><a href=displayimage.php?project=".$_GET['project']."&imagefile=$imagename>$imagename</a></td><td bgcolor=$bgcolor>$imagesize<td><a href=downloadproofed.php?project=".$_GET['project']."&fileid=$fileid&state=".UNAVAIL_FIRST.">View</a></td><td>".strlen($master_text)."</td><td>$date_txt</td><td><a href=deletefile.php?project=".$_GET['project']."&fileid=$fileid>Delete</a></td><td>";
		     		if (($page_state == BAD_FIRST) || ($page_state == BAD_SECOND)) {
		     			echo "<center><a href='badpage.php?projectid=".$_GET['project']."&fileid=$fileid'>X</a></center></td></tr>";
		     		} else {
		     			echo "&nbsp;</td></tr>";
		     		}
                    		$counter++;
                    		$rownum++;
                	}
			echo "</table>";
		} elseif ($state == PROJ_PROOF_FIRST_AVAILABLE || $state == PROJ_PROOF_FIRST_WAITING_FOR_RELEASE || $state == PROJ_PROOF_FIRST_BAD_PROJECT || $state == PROJ_PROOF_FIRST_VERIFY || $state == PROJ_PROOF_FIRST_COMPLETE) {
			echo "</table><h3>First-Round Files:</h3>";
                	echo "<table border=1></tr>";
                	echo "<tr bgcolor='".$theme['color_headerbar_bg']."'><td width=4><font color='".$theme['color_headerbar_font']."'>Index</font></td><td><font color='".$theme['color_headerbar_font']."'>Image</font></td><td><font color='".$theme['color_headerbar_font']."'>Round 1 Text</font></td><td><font color='".$theme['color_headerbar_font']."'>Date Uploaded</font></td><td><font color='".$theme['color_headerbar_font']."'>Proofed By</font></td><td><font color='".$theme['color_headerbar_font']."'>Master Text</font></td><td><font color='".$theme['color_headerbar_font']."'>Delete</font></td><td><font color='".$theme['color_headerbar_font']."'>Bad Page</font></td></tr>";
                	$counter = 1;
                	$rownum = 0;
                	while ($rownum < $projectinfo->done1_pages) {
                    		$imagename = mysql_result($projectinfo->done1_rows, $rownum, "image");
                    		$date = mysql_result($projectinfo->done1_rows, $rownum, "round1_time");
                    		$name = mysql_result($projectinfo->done1_rows, $rownum, "round1_user");
                    		$fileid = mysql_result($projectinfo->done1_rows, $rownum, "fileid");
		    		$page_state = mysql_result($projectinfo->done1_rows, $rownum, "state");
				$users = mysql_query("SELECT real_name, email, pagescompleted FROM users WHERE username = '$name'");
				if (mysql_num_rows($users) == 0) {
                        		$real_name = $name;
					$email = "";
                        		$pagescompleted = 0;
                    		} else {
                        		$email = mysql_result($users, 0, "email");
                        		$real_name = mysql_result($users, 0, "real_name");
                        		$pagescompleted = mysql_result($users, 0, "pagescompleted");
                    		}
                    		if ($rownum % 2 ) {
                			$trcolor = $theme['color_main_bg'];
                		} else {
                			$trcolor = $theme['color_navbar_bg'];
            			}
                    	$date_txt = date("M j h:i A" , $date);
                    	echo "<tr bgcolor='$trcolor'><td>$counter</td><td><a href=displayimage.php?project=".$_GET['project']."&imagefile=$imagename>$imagename</a></td><td><a href=downloadproofed.php?project=".$_GET['project']."&fileid=$fileid&state=".SAVE_FIRST.">View</a></td><td>$date_txt</td><td><a href = mailto:$email>";
                    	if ($userP['sitemanager'] == "yes") { 
                    		echo $real_name; 
                    	} else { 
                    		echo $name; 
                    	}
                    	echo "</a> ($pagescompleted)</td><td><a href=downloadproofed.php?project=".$_GET['project']."&fileid=$fileid&state=".UNAVAIL_FIRST.">View</a></td><td><a href=checkin.php?project=".$_GET['project']."&fileid=$fileid&state=".SAVE_FIRST.">Delete</a></td><td>";
			if (($page_state == BAD_FIRST) || ($page_state == BAD_SECOND)) {
		       		echo "<center><a href='badpage.php?projectid=".$_GET['project']."&fileid=$fileid'>X</a></center></td></tr>"; 
		    	} else { 	                
		       		echo "&nbsp;</td></tr>"; 
		    	} 
			$counter++;
                    	$rownum++;
                	}
			echo "</table>";
		} else {
                	echo "</table><h3>Second-Round Files:</h3>";
                	$lastfilename = "0";
                	echo "<table border=1>\n";
                	echo "<tr bgcolor='".$theme['color_headerbar_bg']."'><td width=4><font color='".$theme['color_headerbar_font']."'>Index</font></td><td><font color='".$theme['color_headerbar_font']."'>Image</font></td><td><font color='".$theme['color_headerbar_font']."'>Round 2 Text</font></td><td><font color='".$theme['color_headerbar_font']."'>Date Uploaded</font></td><td><font color='".$theme['color_headerbar_font']."'>Round 2 Proofed By</font></td><td><font color='".$theme['color_headerbar_font']."'>Round 1 Text</font></td><td><font color='".$theme['color_headerbar_font']."'>Round 1 Proofed By</font></td><td><font color='".$theme['color_headerbar_font']."'>Master Text</font></td>";
                	$inRound=projectStateRound($state);
	        	if ($inRound=='NEW' || $inRound=='PR' || $inRound='FIRST' || $inRound=='SECOND') {
	        		echo "<td><font color='".$theme['color_headerbar_font']."'>Delete</font></td>";
	        	}
        		echo "<td><font color='".$theme['color_headerbar_font']."'>Bad Page</font></td></tr>\n";
			$counter = 1;
                	$rownum = 0;
	                while ($rownum < $projectinfo->done2_pages) {
        	        	$imagename = mysql_result($projectinfo->done2_rows, $rownum, "image");
                	    	$date = mysql_result($projectinfo->done2_rows, $rownum, "round2_time");
                    		$round2_user = mysql_result($projectinfo->done2_rows, $rownum, "round2_user");
                    		$round1_user = mysql_result($projectinfo->done2_rows, $rownum, "round1_user");
                    		$fileid = mysql_result($projectinfo->done2_rows, $rownum, "fileid");
		    		$page_state = mysql_result($projectinfo->done2_rows, $rownum, "state");
                    		$users = mysql_query("SELECT real_name, email, pagescompleted FROM users WHERE username = '$round2_user'");
                    		if (mysql_num_rows($users) == 0) {
                        		$real_name = $round2_user;
					$email = "";
                        		$pagescompleted = 0;
                    		} else {
                        		$email = mysql_result($users, 0, "email");
                        		$real_name = mysql_result($users, 0, "real_name");
                        		$pagescompleted = mysql_result($users, 0, "pagescompleted");
                    		}
				$users = mysql_query("SELECT real_name, email, pagescompleted FROM users WHERE username = '$round1_user'");
                    		if (mysql_num_rows($users) == 0) {
                        		$oldreal_name = $round1_user;
                        		$oldemail = "";
                        		$oldpagescompleted = 0;
                    		} else {
                        		$oldemail = mysql_result($users, 0, "email");
                        		$oldreal_name = mysql_result($users, 0, "real_name");
                        		$oldpagescompleted = mysql_result($users, 0, "pagescompleted");
                    		}
                    		if ($rownum % 2 ) {
                			$trcolor = $theme['color_main_bg'];
                		} else {
                			$trcolor = $theme['color_navbar_bg'];
            			}
                    		$date_txt = date("M j h:i A", $date);
	  			echo "<tr bgcolor='$trcolor'><td>$counter</td><td><a href=displayimage.php?project=".$_GET['project']."&imagefile=$imagename>$imagename</a></td><td><a href=downloadproofed.php?project=".$_GET['project']."&fileid=$fileid&state=".SAVE_SECOND.">View</a></td><td>$date_txt</td><td><a href = mailto:$email>";
                    		if ($userP['sitemanager'] == "yes") { 
                    			echo $real_name; 
                    		} else { 
                    			echo $round2_user;
                    		}
                    		echo "</a> ($pagescompleted)</td><td><a href=downloadproofed.php?project=".$_GET['project']."&fileid=$fileid&state=".SAVE_FIRST.">View</a></td><td><a href=mailto:$oldemail>";
                    		if ($userP['sitemanager'] == "yes") {
                    			echo $oldreal_name; 
                    		} else { 
                    			echo $round1_user; 
                    		}
                    		echo "</a> ($oldpagescompleted)</td><td><a href=downloadproofed.php?project=".$_GET['project']."&fileid=$fileid&state=".UNAVAIL_FIRST.">View</a></td>";
		 		$roundID=projectStateRound($state);
                    		if ($roundID=='FIRST' || $roundID=='SECOND') {
                    			echo "<td><a href=checkin.php?project=".$_GET['project']."&fileid=$fileid&state=".SAVE_SECOND.">Delete</a></td>"; 
                    		} else { 
                    			echo "<td>&nbsp;</td>";
                    		}
                    		if (($page_state == BAD_FIRST) || ($page_state == BAD_SECOND)) {
		        		echo "<td><center><a href='badpage.php?projectid=".$_GET['project']."&fileid=$fileid'>X</a></center></td></tr>";
		    		} else { 	                
		        		echo "<td>&nbsp;</td></tr>";
		    		} 
				$counter++;
        			$lastfilename = $imagename;
        			$rownum++;
			}
			echo "</table>";
        	}
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
