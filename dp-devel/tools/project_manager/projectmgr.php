<?
$relPath="./../../pinc/";
include($relPath.'dp_main.inc');
include($relPath.'projectinfo.inc');
$projectinfo = new projectinfo();

    echo "<title>Project Managers Page</title>";

    $project = $_GET['project'];
    $show = $_GET['show'];

    $users = mysql_query("SELECT manager, sitemanager FROM users WHERE username = '$pguser'");
    $manager = mysql_result($users, 0, "manager");
    $sitemanager = mysql_result($users, 0, "sitemanager");

    if ($manager == 'no') {
        echo "<P>You are not listed as a project manager. Please contact the <a href=\"mailto: charlz@lvcablemodem.com\">site manager</A> about resolving this problem.";
        echo "<P>Back to <A HREF=\"../../default.php\">home page</A>";
    } else if ($project) {
        //if they have selected a project print out the detailed info for that project

        $result = mysql_query("SELECT nameofwork, authorsname, language, username, state FROM projects WHERE projectid = '$project'");
        $manager = mysql_result($result, 0, "username");
        $state = mysql_result($result, 0, "state");
        $name = mysql_result($result, 0, "nameofwork");
        $author = mysql_result($result, 0, "authorsname");
        $language = mysql_result($result, 0, "language");

        if (($manager != $pguser) && ($sitemanager != 'yes')) {
            echo "<P>You are not listed as a project manager for this project. Please contact the <a href=\"mailto: charlz@lvcablemodem.com\">site manager</A> about resolving this problem.";
            echo "<P>Back to <A HREF=\"projectmgr.php\">manager home page</A>";
        } else {

            $projectinfo->update($project, $state);

            //link bar at top of page

            echo "<table border=1 width=630 cellpadding=0 cellspacing=0 style=\"border-collapse: collapse\" bordercolor=#111111><tr>";
            echo "<td width=126 align=center bgcolor = \"CCCCCC\"><a href=\"projectmgr.php\">Back</a></td>";
            echo "<td width=126 bgcolor = \"CCCCCC\" align=center><a href = \"editproject.php\">Create Project</a></td>";
            echo "<td width=126 bgcolor = \"CCCCCC\" align=center><a href =\"../proofers/proof_per.php\">Proofread Project</a></td>";
            echo "<td width=126 bgcolor = \"CCCCCC\" align=center><a href =\"../post_proofers/post_proofers.php\">Post-Processing</a></td>";
            echo "<td width=126 bgcolor = \"CCCCCC\" align=center><a href = \"../logout.php\">Logout</a></td>";
            echo "</tr></table><BR><BR>";

            echo "<table border = \"1\">";
            printf("<tr><td colspan = \"4\"><b><font size=+1>Project Name: $name</font></b> ($project)</td></tr><tr><td bgcolor=\"CCCCCC\"><b>Author:</b></td><td>$author</td>");
            printf("<td bgcolor=\"CCCCCC\"><b>Total Number of Master Pages:</b></td><td>$projectinfo->total_pages</td></tr><tr><td bgcolor=\"CCCCCC\"><b>Language:</b></td><td>$language</td>");
            printf("<td bgcolor=\"CCCCCC\"><b>Pages Remaining to be Proofed:</b></td><td>$projectinfo->availablepages</td></tr>");

            if ($state == 0) {
                printf("<tr><td bgcolor=\"CCCCCC\" colspan=2><a href=\"add_files.php?project=$project\">");
                if ($sitemanager == 'yes') {
                   printf("Add All Text From projects Folder");
                } else echo "Add All Text/Images From dpscans Account";
                echo "</a><td bgcolor=\"CCCCCC\" colspan=2><a href=\"deletefile.php?project=$project\">Delete All Text</a></td></tr></table>";

                echo "<h3>Master Files</h3>";

                //Print each row
                echo "<table border=1>\n";

                echo "</tr>\n";
                echo "<tr bgcolor=\"CCCCCC\"><td width = \"4\">Index</td><td>Image</td><td>Size</td><td>Master Text</td><td>Size</td><td>Date Uploaded</td><td>Delete</td><td>Bad Page</td></tr>\n";
                $counter = 1; // for index.. need to make adjustable
                $rownum = 0;

                $path = "../../projects/".$project."/";

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

                    $date_txt = date("M j h:i A", $date);
                    printf("<tr><td>$counter</td><td bgcolor><a href=displayimage.php?project=$project&imagefile=$imagename>$imagename</a></td><td = $bgcolor>$imagesize<td><a href=downloadproofed.php?project=$project&fileid=$fileid&state=0>View</a></td><td>".strlen($master_text)."</td><td>$date_txt</td><td><a href=deletefile.php?project=$project&fileid=$fileid>Delete</a></td><td>");

		    if (($page_state == BAD_FIRST) || ($page_state == BAD_SECOND)) {
		       printf("<center><a href='badpage.php?projectid=$project&fileid=$fileid'>X</a></center></td></tr>\n"); 
		    } else { 	                
		       printf("&nbsp;</td></tr>\n"); 
		    } 

                    $counter++;
                    $rownum++;
                }
                echo "</table>";
            } else if ($state < 10) {
                echo "</table><h3>First-Round Files:</h3>";

                //Print each row
                echo "<table border=1>\n";

                echo "</tr>\n";
                echo "<tr bgcolor=\"CCCCCC\"><td width = \"4\">Index</td><td>Image</td><td>Round 1 Text</td><td>Date Uploaded</td><td>Proofed By</td><td>Master Text</td><td>Delete</td><td>Bad Page</td></tr>\n";
                $counter = 1; // for index.. need to make adjustable
                $rownum = 0;

                while ($rownum < $projectinfo->done1_pages) {
                    $imagename = mysql_result($projectinfo->done1_rows, $rownum, "image");
                    $date = mysql_result($projectinfo->done1_rows, $rownum, "round1_time");
                    $name = mysql_result($projectinfo->done1_rows, $rownum, "round1_user");
                    $fileid = mysql_result($projectinfo->done1_rows, $rownum, "fileid");
		    $page_state = mysql_result($projectinfo->done1_rows, $rownum, "state");

                    $bgcolor = "#FFFFFF";

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
                    $date_txt = date("M j h:i A" , $date);

                    printf("<tr><td>$counter</td><td bgcolor = $bgcolor><a href=displayimage.php?project=$project&imagefile=$imagename>$imagename</a></td><td><a href=downloadproofed.php?project=$project&fileid=$fileid&state=9>View</a></td><td>$date_txt</td><td><a href = mailto:$email>");
                    if ($sitemanager == "yes") { printf("$real_name"); } else printf("$name");
                    printf("</a> ($pagescompleted)</td><td><a href=downloadproofed.php?project=$project&fileid=$fileid&state=0>View</a></td><td><a href=checkin.php?project=$project&fileid=$fileid&state=9>Delete</a></td><td>");

		    if (($page_state == BAD_FIRST) || ($page_state == BAD_SECOND)) {
		       printf("<center><a href='badpage.php?projectid=$project&fileid=$fileid'>X</a></center></td></tr>\n"); 
		    } else { 	                
		       printf("&nbsp;</td></tr>\n"); 
		    } 

                    $counter++;
                    $rownum++;
                }
                echo "</table>";
            } else {

                //print out all level 3 proofed files associated with the project

                echo "</table><h3>Second-Round Files:</h3>";

                //Print each row
                $lastfilename = "0";

                echo "<table border=1>\n";

                echo "<tr bgcolor=\"CCCCCC\"><td width=4>Index</td><td>Image</td><td>Round 2 Text</td><td>Date Uploaded</td><td>Round 2 Proofed By</td><td>Round 1 Text</td><td>Round 1 Proofed By</td><td>Master Text</td>";
                if ($state < 20) echo "<td>Delete</td>";
                echo "<td>Bad Page</td></tr>\n";

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

                    $bgcolor = "#FFFFFF";

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
                    $date_txt = date("M j h:i A", $date);

                    printf("<tr><td>$counter</td><td bgcolor = $bgcolor><a href=displayimage.php?project=$project&imagefile=$imagename>$imagename</a></td><td><a href=downloadproofed.php?project=$project&fileid=$fileid&state=19>View</a></td><td>$date_txt</td><td><a href = mailto:$email>");
                    if ($sitemanager == "yes") { printf("$real_name"); } else printf("$round2_user");
                    printf("</a> ($pagescompleted)</td><td><a href=downloadproofed.php?project=$project&fileid=$fileid&state=9>View</a></td><td><a href=mailto:$oldemail>");
                    if ($sitemanager == "yes") { printf("$oldreal_name"); } else printf("$round1_user");
                    printf("</a> ($oldpagescompleted)</td><td><a href=downloadproofed.php?project=$project&fileid=$fileid&state=0>View</a></td>");
                    if ($state < 20) { printf("<td><a href=checkin.php?project=$project&fileid=$fileid&state=19>Delete</a></td>"); }
		    if (($page_state == BAD_FIRST) || ($page_state == BAD_SECOND)) {
		        printf("<td><center><a href='badpage.php?projectid=$project&fileid=$fileid'>X</a></center></td></tr>\n"); 
		    } else { 	                
		        printf("<td>&nbsp;</td></tr>\n"); 
		    } 

                    $counter++;
                    $lastfilename = $imagename;
                    $rownum++;
                }
                echo "</table>";
            }

            //link bar at bottom of detailed project page

            echo "<BR><BR><table border=1 width=630 cellpadding=0 cellspacing=0 style=\"border-collapse: collapse\" bordercolor=#111111><tr>";
            echo "<td width=126 align=center bgcolor = \"CCCCCC\"><a href=\"projectmgr.php\">Back</a></td>";
            echo "<td width=126 bgcolor = \"CCCCCC\" align=center><a href = \"editproject.php\">Create Project</a></td>";
            echo "<td width=126 bgcolor = \"CCCCCC\" align=center><a href =\"../proofers/proof_per.php\">Proofread Project</a></td>";
            echo "<td width=126 bgcolor = \"CCCCCC\" align=center><a href =\"../post_proofers/post_proofers.php\">Post-Processing</a></td>";
            echo "<td width=126 bgcolor = \"CCCCCC\" align=center><a href = \"../logout.php\">Logout</a></td>";
            echo "</tr></table>";
        }
    } else {
        // Listing of Projects
?>
<body>

<div align="left">
<table border=1 width=630 cellpadding=0 cellspacing=0 style="border-collapse: collapse" bordercolor=#111111><tr>
<td width=126 align=center bgcolor ="CCCCCC"><a href ="projectmgr.php?show=all">Show All Projects</a></td>
<td width=126 bgcolor ="CCCCCC" align=center><a href ="editproject.php">Create Project</a></td>
<td width=126 bgcolor ="CCCCCC" align=center><a href ="../proofers/proof_per.php">Proofread Project</a></td>
<td width=126 bgcolor ="CCCCCC" align=center><a href ="../post_proofers/post_proofers.php">Post-Processing</a></td>
<td width=126 bgcolor ="CCCCCC" align=center><a href ="../logout.php">Logout</a></td></tr>
<?
if ($sitemanager == "yes") {
    if ($show == "site") {?>
<tr><td colspan=2 align=center bgcolor ="CCCCCC"><a href ="projectmgr.php">Project Manager</a></td>
<?  } else { ?>
<tr><td colspan=2 align=center bgcolor ="CCCCCC"><a href ="projectmgr.php?show=site">Site Manager</a></td>
<?  } ?>
<td colspan=3 align=center bgcolor ="CCCCCC"><a href ="../../phpBB2/index.php">Forums</a></td>
<?
} else { ?>
<tr><td colspan=6 align=center bgcolor ="CCCCCC"><a href ="../../phpBB2/index.php">Forums</a></td>
<?
}
?>
</tr></table>
<table border=1 width=630 cellpadding=0 cellspacing=0 style="border-collapse: collapse" bordercolor=#111111>
<tr><td colspan=6 bgcolor=999999>&nbsp;</td></tr><tr><td colspan=6>
<B>Project Manager Notice:</B>
<P>There is a new way to upload files to the site without needing anyone to help you:
<OL>
<LI>FTP to texts01.archive.org with username dpscans and password image$
<LI>Make a directory named what the projectID is (look in the URL of the project when you click on the title, labeled project=projectIDXXX, use entire projectIDXXX for the folder)
<LI>Upload the text and images to that folder, titled 001.txt, 001.png, 002.txt, 002.png, etc...
<LI>Click on the title of the book to view it's details.
<LI>Click on the link at the top titled "Add Images And Text From dpscans Account".
<LI>It will load them into the database, look over the list of pages before setting it to "Waiting to be Released".
</OL>

<P>Recent bug fixes include being able to put any characters in the project comments box and preventing books from the same author to be available in the First Round (meaning if you have 4 volumes of a series, you can put them into Waiting to be Released and they will be released as each passes through the First Round).

<P>If you want the projects to go to post-processing automatically, e-mail Charles Aldarondo <a HREF="mailto:Charles@Aldarondo.net">Charles@Aldarondo.net</A>.
</td></tr>
    <tr>
      <td width="175" align="center" bgcolor="#C0C0C0"><b>Title</b></td>
      <td width="100" align="center" bgcolor="#C0C0C0"><b>Author</b></td>
      <td width="50" align="center" bgcolor="#C0C0C0"><b>Left</b></td>
      <td width="75" align="center" bgcolor="#C0C0C0"><b>
<?
        if ($show == 'site') {
            echo "PM";
        } else echo "Owner";
?>
</b></td>
      <td width="180" align="center" bgcolor="#C0C0C0"><b>Project Status</b></td>
      <td width="50" align="center" bgcolor="#C0C0C0"><b>Options</b></td>
    </tr>

<?
        $numrows = 0;
        if (($show == 'site') && ($sitemanager === 'yes')) {
            $result = mysql_query("SELECT projectid, nameofwork, authorsname, checkedoutby, state, username FROM projects WHERE state != 79 ORDER BY state asc, nameofwork asc");
        } else if ($show == 'all') {
            $result = mysql_query("SELECT projectid, nameofwork, authorsname, checkedoutby, state FROM projects WHERE username = '$pguser' ORDER BY state asc, nameofwork asc");
        } else $result = mysql_query("SELECT projectid, nameofwork, authorsname, checkedoutby, state FROM projects WHERE state != 79 AND username = '$pguser' ORDER BY state asc, nameofwork asc");
        if ($result != "") $numrows = (mysql_num_rows($result));

        $numstates = 0;
        $state_names = mysql_query("SELECT name, id FROM states ORDER BY id asc");
        if ($state_names != "") $numstates = mysql_num_rows($state_names);

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

            //alternate colors for each project
            if ($rownum % 2 ) {
                $bgcolor = "\"#CCCCCC\"";
            } else {
                $bgcolor = "\"#999999\"";
            }

            $projectinfo->update_avail($projectid, $state);

            print "<tr bgcolor=$bgcolor><td><a href=\"projectmgr.php?project=$projectid\">$name</a></td><td>$author</td><td align=\"center\">$projectinfo->availablepages</td><td align=\"center\">";
            if ($show == 'site') {
                print mysql_result($result, $rownum, "username");
            } else if ($outby != "") {
                print "<a href=mailto:$outbyemail>$outby</a>";
            }

            $staterow = 0;
            print "</td><td valign=center><form name=\"$projectid\" method=get action=\"changestate.php\"><input type=hidden name=project value=\"$projectid\"><select name=state onchange=\"this.form.submit()\">";
            while ($staterow < $numstates) {
                $id = mysql_result($state_names, $staterow, "id");
                $s_name = mysql_result($state_names, $staterow, "name");
                if ($id == $state) {
                    print "<option value=$id selected>$s_name\n";
                } else if ($id == 99) {
                    print "<option value=$id>$s_name\n";
                } else if ($state == 0) {
                   if (($id == 1) || ($id == 79)) {
                        print "<option value=$id>$s_name\n";
                    }
                } else if ($state == 1) {
                    if (($id == 0) || ($id == 8)) {
                        print "<option value=$id>$s_name\n";
                    }
                } else if ($state == 2) {
                    if (($id == 0) || ($id == 8)) {
                        print "<option value=$id>$s_name\n";
                    }
                } else if ($state == 8) {
                   if ($id == 0) {
                        print "<option value=$id>$s_name\n";
                    }
                } else if ($state == 10) {
                    if ($id == 18) {
                        print "<option value=$id>$s_name\n";
                    }
                } else if ($state == 12) {
                    if (($id == 10) || ($id == 18)) {
                        print "<option value=$id>$s_name\n";
                    }
		} else if ($state == 31) {
		    if (($id == 0) || ($id == 8)) {
			print "<option value=$id>$s_name\n";
		    }
		} else if ($state == 41) {
		    if (($id == 10) || ($id == 18)) {
			print "<option value=$id>$s_name\n";
		    }
                } else if ($state == 60) {
                    if (($id == 61) || ($id == 65)) {
                        print "<option value=$id>$s_name\n";
                    }
                } else if ($state == 61) {
                    if (($id == 60) || ($id == 65)) {
                        print "<option value=$id>$s_name\n";
                    }
                } else if ($state == 65) {
                    if (($id == 60) || ($id == 61) || ($id == 68) || ($id == 69) || ($id == 79)) {
                        print "<option value=$id>$s_name\n";
                    }
                } else if ($state == 68) {
                    if (($id == 69) || ($id == 79)) {
                        print "<option value=$id>$s_name\n";
                    }
                } else if ($state == 69) {
                    if (($id == 79) || ($id == 70) || ($id == 71) || ($id == 75)) {
                        print "<option value=$id>$s_name\n";
                    }
                } else if ($state == 70) {
                    if (($id == 71) || ($id == 75)) {
                        print "<option value=$id>$s_name\n";
                    }
                } else if ($state == 71) {
                    if (($id == 70) || ($id == 75) || ($id == 79)) {
                        print "<option value=$id>$s_name\n";
                    }
                } else if ($state == 75) {
                    if (($id == 70) || ($id == 71) || ($id == 79)) {
                        print "<option value=$id>$s_name\n";
                    }
                }

                $staterow++;
            }
            echo "</select></form></td><td align=center>";

            print "<a href=\"editproject.php?project=$projectid\">Edit</a>";
            if (($state >= 60) && ($state <= 65)) print " <a href = \"../../projects/$projectid/$projectid.zip\">D/L</A>";
            if (($state == 68) || ($state == 69)) print " <a href=\"../../projects/$projectid/post.zip\">D/L</A>";
            echo "</td></tr>\n";
            //increment row number for background color change
            $rownum++;
        }
?>
<tr><td colspan=6 bgcolor=999999>&nbsp;</td></tr></table>
<table width=630 border=1 cellpadding=0 cellspacing=0 style="border-collapse: collapse" bordercolor=#111111><tr>
<td width=126 align=center bgcolor ="CCCCCC"><a href ="projectmgr.php?show=all">Show All Projects</a></td>
<td width=126 bgcolor ="CCCCCC" align=center><a href ="editproject.php">Create Project</a></td>
<td width=126 bgcolor ="CCCCCC" align=center><a href ="../proofers/proof_per.php">Proofread Project</a></td>
<td width=126 bgcolor ="CCCCCC" align=center><a href ="../post_proofers/post_proofers.php">Post-Processing</a></td>
<td width=126 bgcolor ="CCCCCC" align=center><a href ="../logout.php">Logout</a></td>
<?
if ($sitemanager == "yes") {
    if ($show == "site") {?>
<tr><td colspan=2 align=center bgcolor ="CCCCCC"><a href ="projectmgr.php">Project Manager</a></td>
<?  } else { ?>
<tr><td colspan=2 align=center bgcolor ="CCCCCC"><a href ="projectmgr.php?show=site">Site Manager</a></td>
<?  } ?>
<td colspan=3 align=center bgcolor ="CCCCCC"><a href ="../../phpBB2/index.php">Forums</a></td>
<?
} else { ?>
<tr><td colspan=6 align=center bgcolor ="CCCCCC"><a href ="../../phpBB2/index.php">Forums</a></td>
<?
}
?>
</tr></table></div>
</body>
<?
    }
?>
