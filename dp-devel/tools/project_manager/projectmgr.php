<?
if($_COOKIE['pguser']) {
    // can only come from a cookie, forged or otherwise
    $good_login = 1;
    $pguser = $_COOKIE['pguser'];
}

if ($good_login != 1) {
    echo "<p><META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=../../accounts/signin.php\">"; 
} else {

    ///connect to database
    include '../../connect.php';
    include 'pm_globals.php';

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

            update_globals($project, $state);

            //link bar at top of page

            echo "<table border=1 width=630 cellpadding=0 cellspacing=0 style=\"border-collapse: collapse\" bordercolor=#111111><tr>";
            echo "<td width=126 align=center bgcolor = \"CCCCCC\"><a href=\"projectmgr.php\">Back</a></td>";
            echo "<td width=126 bgcolor = \"CCCCCC\" align=center><a href = \"editproject.php\">Create Project</a></td>";
            echo "<td width=126 bgcolor = \"CCCCCC\" align=center><a href =\"../proofers/proof_per.php\">Proofread Project</a></td>";
            echo "<td width=126 bgcolor = \"CCCCCC\" align=center><a href =\"../post_proofers/post_proofers.php\">Post-Processing</a></td>";
            echo "<td width=126 bgcolor = \"CCCCCC\" align=center><a href = \"../logout.php\">Logout</a></td>";
            echo "</tr></table><BR><BR>";

            echo "<table border = \"1\">";
            printf("<tr><td colspan = \"4\"><B><font size=+1>Project Name: $name</font></B></td></tr><tr><td bgcolor=\"CCCCCC\"><b>Author:</b></td><td>$author</td>");
            printf("<td bgcolor=\"CCCCCC\"><b>Total Number of Master Pages:</b></td><td>$total_pages</td></tr><tr><td bgcolor=\"CCCCCC\"><b>Language:</b></td><td>$language</td>");
            printf("<td bgcolor=\"CCCCCC\"><b>Pages Remaining to be Proofed:</b></td><td>$availablepages</td></tr>");
            echo "</table>";

            if ($state == 0) {
                echo "<h3>Master Files</h3>";

                //Print each row
                echo "<table border=1>\n";

                echo "</tr>\n";
                echo "<tr bgcolor=\"CCCCCC\"><td width = \"4\">Index</td><td>Image</td><td>Master Text</td><td>Date Uploaded</td><td>Delete</td></tr>\n";
                $counter = 1; // for index.. need to make adjustable
                $rownum = 0;

                while ($rownum < $total_rows) {
                    $imagename = mysql_result($level0rows, $rownum, "image");
                    $date = mysql_result($level0rows, $rownum, "round1_time");
                    $fileid = mysql_result($level0rows, $rownum, "fileid");

                    $bgcolor = "#FFFFFF";

                    $date_txt = date("M j h:i A", $date);
                    printf("<tr><td>$counter</td><td bgcolor = $bgcolor><a href=displayimage.php?project=$project&imagefile=$imagename>$imagename</a></td><td><a href=downloadproofed.php?project=$project&fileid=$fileid&state=0>View</a></td><td>$date_txt</td><td><a href=deletefile.php?project=$project&fileid=$fileid>Delete</a></td></tr>\n");

                    $counter++;
                    $rownum++;
                }
                echo "</table>";
            } else if ($state < 10) {
                echo "<h3>First-Round Files:</h3>";

                //Print each row
                echo "<table border=1>\n";

                echo "</tr>\n";
                echo "<tr bgcolor=\"CCCCCC\"><td width = \"4\">Index</td><td>Image</td><td>Round 1 Text</td><td>Date Uploaded</td><td>Proofed By</td><td>Master Text</td><td>Delete</td></tr>\n";
                $counter = 1; // for index.. need to make adjustable
                $rownum = 0;

                while ($rownum < $done1_pages) {
                    $imagename = mysql_result($done1_rows, $rownum, "image");
                    $date = mysql_result($done1_rows, $rownum, "round1_time");
                    $name = mysql_result($done1_rows, $rownum, "round1_user");
                    $fileid = mysql_result($done1_rows, $rownum, "fileid");

                    $bgcolor = "#FFFFFF";

                    $users = mysql_query("SELECT real_name, email FROM users WHERE username = '$name'");
                    if (mysql_num_rows($users) == 0) {
                        $real_name = $name;
			$email = "";
                    } else {
                        $email = mysql_result($users, 0, "email");
                        $real_name = mysql_result($users, 0, "real_name");
                    }
                    $date_txt = date("M j h:i A" , $date);

                    printf("<tr><td>$counter</td><td bgcolor = $bgcolor><a href=displayimage.php?project=$project&imagefile=$imagename>$imagename</a></td><td><a href=downloadproofed.php?project=$project&fileid=$fileid&state=9>View</a></td><td>$date_txt</td><td><a href = mailto:$email>$real_name</td><td><a href=downloadproofed.php?project=$project&fileid=$fileid&state=0>View</a></td><td><a href=checkin.php?project=$project&fileid=$fileid&state=9>Delete</a></td></tr>\n");

                    $counter++;
                    $rownum++;
                }
                echo "</table>";
            } else {

                //print out all level 3 proofed files associated with the project

                echo "<h3>Second-Round Files:</h3>";

                //Print each row
                $lastfilename = "0";

                echo "<table border=1>\n";

                echo "<tr bgcolor=\"CCCCCC\"><td width=4>Index</td><td>Image</td><td>Round 2 Text</td><td>Date Uploaded</td><td>Round 2 Proofed By</td><td>Round 1 Text</td><td>Round 1 Proofed By</td>";
                if ($state < 20) echo "<td>Delete</td>";
                echo "</tr>\n";

                $counter = 1;
                $rownum = 0;

                while ($rownum < $done2_pages) {
                    $imagename = mysql_result($done2_rows, $rownum, "image");
                    $date = mysql_result($done2_rows, $rownum, "round2_time");
                    $round2_user = mysql_result($done2_rows, $rownum, "round2_user");
                    $round1_user = mysql_result($done2_rows, $rownum, "round1_user");
                    $fileid = mysql_result($done2_rows, $rownum, "fileid");

                    $users = mysql_query("SELECT real_name, email FROM users WHERE username = '$round2_user'");
                    if (mysql_num_rows($users) == 0) {
                        $real_name = $round2_user;
			$email = "";
                    } else {
                        $email = mysql_result($users, 0, "email");
                        $real_name = mysql_result($users, 0, "real_name");
                    }

                    $bgcolor = "#FFFFFF";

                    $users = mysql_query("SELECT real_name, email FROM users WHERE username = '$round1_user'");
                    if (mysql_num_rows($users) == 0) {
                        $oldreal_name = $round1_user;
                        $oldemail = "";
                    } else {
                        $oldemail = mysql_result($users, 0, "email");
                        $oldreal_name = mysql_result($users, 0, "real_name");
                    }
                    $date_txt = date("M j h:i A", $date);

                    printf("<tr><td>$counter</td><td bgcolor = $bgcolor><a href=displayimage.php?project=$project&imagefile=$imagename>$imagename</a></td><td><a href=downloadproofed.php?project=$project&fileid=$fileid&state=19>View</a></td><td>$date_txt</td><td><a href = mailto:$email>$real_name</a></td>");
                    printf("<td><a href=downloadproofed.php?project=$project&fileid=$fileid&state=9>View</a></td><td><a href=mailto:$oldemail>$oldreal_name</A></td>");
                    if ($state < 20) { printf("<td><a href=checkin.php?project=$project&fileid=$fileid&state=19>Delete</a></td>"); }
                    printf("</tr>\n");

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
<B>Project Manager Notice:</B> Please do not put in the "standard comments" in the projects. Place the following instead if you have nothing additional:<p>
No additional comments, review the <a href="http://texts01.archive.org/dp/faq/document.html">Document Guidelines</a> for formatting.

<P>Major changes have been made to the flow process of projects. Normal flow of a project has you only create a project with files, put into "Waiting for Release" status and then doing the post-processing yourself or just posting the finished product. Your default settings is to have the projects be assigned to you, if you want them to go to post-processing instead automatically, e-mail Charles Aldarondo. If you have any questions about the new process, e-mail Charles Aldarondo <a HREF="mailto:Charles@Aldarondo.net">Charles@Aldarondo.net</A>.
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
            $result = mysql_query("SELECT projectid, nameofwork, authorsname, checkedoutby, state, username FROM projects WHERE state != 30 ORDER BY state asc, nameofwork asc");
        } else if ($show == 'all') {
            $result = mysql_query("SELECT projectid, nameofwork, authorsname, checkedoutby, state FROM projects WHERE username = '$pguser' ORDER BY state asc, nameofwork asc");
        } else $result = mysql_query("SELECT projectid, nameofwork, authorsname, checkedoutby, state FROM projects WHERE state != 30 AND username = '$pguser' ORDER BY state asc, nameofwork asc");
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

            update_avail_globals($project, $state);

            print "<tr bgcolor=$bgcolor><td><a href=\"projectmgr.php?project=$projectid\">$name</a></td><td>$author</td><td align=\"center\">$availablepages</td><td align=\"center\">";
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
                } else if ($id == 42) {
                    print "<option value=$id>$s_name\n";
                } else if ($state == 0) {
                   if (($id == 1) || ($id == 30)) {
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
                } else if ($state == 10) {
                    if ($id == 18) {
                        print "<option value=$id>$s_name\n";
                    }
                } else if ($state == 12) {
                    if (($id == 10) || ($id == 18)) {
                        print "<option value=$id>$s_name\n";
                    }
                } else if ($state == 20) {
                    if ($id == 25) {
                        print "<option value=$id>$s_name\n";
                    }
                } else if ($state == 25) {
                    if (($id == 20) || ($id == 29) || ($id == 30)) {
                        print "<option value=$id>$s_name\n";
                    }
                } else if ($state == 29) {
                    if ($id == 30) {
                        print "<option value=$id>$s_name\n";
                    }
                }

                $staterow++;
            }
            echo "</select></form></td><td align=center>";

            print "<a href=\"editproject.php?project=$projectid\">Edit</a>";
            if (($state == 20) || ($state == 25)) print " <a href = \"../../projects/$projectid/$projectid.zip\">D/L</A>";
            if ($state == 29) print " <a href=\"../../projects/$projectid/post.zip\">D/L</A>";
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
}
?>
