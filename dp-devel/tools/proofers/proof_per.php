<?
$relPath="./../../pinc/";
include($relPath.'dp_main.inc');
    echo "<html><head>";
    echo "<title>Personal Page for $pguser</title></head><body>";
    echo" <table border = \"0\" cellspacing = \"0\" width = \"630\">";
    echo" <td width = \"1\" bgcolor = \"CCCCCC\">&nbsp</td><td bgcolor = \"CCCCCC\" align = left colspan =\"3\"><font size=+1>Welcome</font><font color=\"#0000FF\" font size = +1> $pguser</font></td><td width = \"1\" bgcolor = \"CCCCCC\" colspan =\"1\">&nbsp</td><tr>";
    echo" <td width = \"1\" bgcolor = \"CCCCCC\">&nbsp</td><td align =\"center\"><b>This is your Personal Page!</b></td>";
    echo" <td width = \"1\" bgcolor = \"CCCCCC\">&nbsp</td><td>";

    echo" <b>Site Stats:</b><br>";
    include("stats/hourly.txt");  
  echo" <p><b>Your Stats:<br></b>";

    //get total pages completed
    $pagessql = "SELECT pagescompleted FROM users WHERE username = '$pguser' LIMIT 1";
    $pages = mysql_query($pagessql);
    $totalpages = mysql_result($pages, 0, "pagescompleted");
    echo "Your Total Pages:<font color=\"#0000FF\"><b> $totalpages</b></font><br>";

    //get rank

    if ($totalpages >= 0) {
        $yourrank = 1;
    }
    if ($totalpages >= 26) {
        $yourrank = 2;
    }
    if ($totalpages >= 151) {
        $yourrank = 3;
    }
    if ($totalpages >= 401) {
        $yourrank = 4;
    }
    if ($totalpages >= 1001) {
        $yourrank = 5;
    }
    if ($totalpages >= 2001) {
        $yourrank = 6;
    }
    if ($totalpages >= 5001) {
        $yourrank = 7;
    }
    if ($totalpages >= 10001) {
        $yourrank = 8;
    }

    $ranksql = "SELECT rankname FROM ranks WHERE rankid = '$yourrank' LIMIT 1";
    $rankresult = mysql_query($ranksql);
    $currentrank = mysql_result($rankresult, 0, "rankname");

    echo "Your Current Rank: <font color=\"#0000FF\"><b> $currentrank</b></font><br>";

    //get total number of users
    $users = mysql_query("SELECT manager FROM users WHERE pagescompleted >=1");
    $totalusers = (mysql_num_rows($users));

    $pagessql = "SELECT username, pagescompleted FROM users ORDER BY pagescompleted DESC";
    $pages = mysql_query($pagessql);

    $numrows = mysql_num_rows($pages);
    $yourrank = 1;

    while ($yourrank <= $numrows) {
        if (mysql_result($pages, $yourrank-1, "username") == $pguser) {
            $totalpages = mysql_result($pages, $yourrank-1, "pagescompleted");
            break;
        }
        $yourrank++;
    }

    if ($yourrank - 1 == $totalusers) {
        $yourrank = $totalusers;
    }
    if ($yourrank > $totalusers) {
        $yourrank = 1;
    }

    echo "Total users who completed at least 1 page:<font color=\"#0000FF\"><b> $totalusers</b></font><br><P>";

//Following top ten/your neighbor board provided by David Bridson, modified for looks by Charles Franks
?>

<b>Top 10 Proofers:</b>
<blockquote>

<TABLE border=1 cellspacing=0 cellpadding=0>

<?
    $pagessql = "SELECT username, Pagescompleted FROM users ORDER BY pagescompleted DESC";
    $pages = mysql_query($pagessql);

    $numrows = mysql_num_rows($pages);
    $i = 0;

    while ($i < $numrows) {
        $username = mysql_result($pages, $i, "username");
        $pagescompleted = mysql_result($pages, $i, "Pagescompleted");

        if ($username == $pguser) {
            $userindex = $i;
        }

        if ($i != 0) {
            if ($pagescompleted == $lastcompleted) {
                // If Current User has completed same number of pages as
                // previous, assign same ranking

                $rankings[$i] = $rankings[$i - 1];
            } else {
                // If Current User has completed fewer pages than
                // previous, assign next ranking

                $rankings[$i] = $i+1;
            }
        } else {
            // Top user has ranking one
            $rankings[$i] = 1;
        }

        $i++;
        $lastcompleted = $pagescompleted;
    }

    $printedblankline = FALSE; // Note - this may be PHP4 only
    $show_neighbors = isset($_GET['show_neighbors']) ? $_GET['show_neighbors'] : 5;

    $i = 0;
    while ($i < $numrows) {
        // If ranking is in top ten or is current user or immediate
        // neighbour, print line in table
        if(($rankings[$i] < 11) || (($i >= $userindex - $show_neighbors) && ($i <= $userindex + $show_neighbors))) {
            echo "<TR";
            if ($userindex == $i) {
                // Highlight current user (#C0C0C0 is grey)
                echo " BGCOLOR=\"#C0C0C0\"";
            }

            echo "><TD>$rankings[$i]";

            // If user's ranking is the same as the previous or next
            // user's, it should have an "=" suffix

            if ((($i > 0) && ($rankings[$i]==$rankings[$i-1])) ||
                (($i < $totalusers-1) && ($rankings[$i]==$rankings[$i+1]))) {
                echo "=";
            }

            echo "</TD><TD>".mysql_result($pages, $i, "username")."</TD><TD>".mysql_result($pages, $i, "Pagescompleted")."</TD></TR>\n";
        }
        $i++;

        // Print blank line between user's neighbours & top 10 if
        // necessary

        if (($rankings[$userindex-2]>10) && ($rankings[$i]==11) && ($printedblankline == FALSE)) {
            echo "<TR><TD colspan=3><BR></TD></TR><TR><TD colspan=3><b>Your Neighborhood:<b><BR></TD></TR>\n";
            $printedblankline = TRUE;
        }
    }
?>

</TABLE>
<form method="get" action="<? print $_SERVER['PHP_SELF']; ?>">
<p>Show <select name="show_neighbors">
<?
for ( $i=1; $i <= 10; $i++ ) {
   $real_value = $i*2;
   print "  <option value=\"$i\"";
   if ( $i == $show_neighbors ) {
       print " selected=\"selected\"";
   }
   print ">$real_value</option>\n";
}
?>
</select>
neighbors <input type="submit" name="go" value="Go" /></p>
</form>
</blockquote>
<p>
<p>

<b>See whats new in the <a href = "../../phpBB2/index.php">Forums</a></b>
<?

    // If Post Processor give link to post processing page.

    $result = mysql_query("SELECT postprocessor, pagescompleted FROM users WHERE username = '$pguser'");
    $postprocessor = mysql_result($result, 0, "postprocessor");
    $postprocessorpages = mysql_result($result, 0, "pagescompleted");
    if ($postprocessor == "yes" || $postprocessorpages >= 400) {
//if ($postprocessor == "yes"){

?>

<P><b>Post Processing:</b><br>
You can help in the post processing phase of Distributed Proofreaders! After going through two rounds of proofreading, the books need to be massaged into a final e-text and you can help <a href ="../post_proofers/post_proofers.php">here</A>!<P>
<?
        $rows = mysql_query("SELECT projectid FROM projects WHERE state=20");
        $postprojects = (mysql_num_rows($rows));

        echo "Currently there are <b> $postprojects </b> projects waiting";

        $rows = mysql_query("SELECT projectid FROM projects WHERE checkedoutby = '$pguser' and state=25");
        $postprojects = (mysql_num_rows($rows));

        if ($postprojects > 0) {
            echo " and you have <b> $postprojects </b> projects checked out";
        }
        echo ".";
    }
?>
<p>Want to help support this site????  We can always use $$ to buy books, software, hardware etc. By clicking on the 'Beg Button' below you can donate with your credit card or PayPal account via PayPal!!
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="charlz@lvcablemodem.com">
<input type="image" src="http://images.paypal.com/images/x-click-butcc-donate.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
</form>
<p>
<p></td><td width = "1" bgcolor = "CCCCCC">&nbsp</td><tr>
<td width = "1" bgcolor = "CCCCCC">&nbsp</td><td width = "1" bgcolor = "CCCCCC" colspan ="2">&nbsp</td><td bgcolor = "CCCCCC">&nbsp</td><td width = "1" bgcolor = "CCCCCC" colspan ="2">&nbsp</td>
</table><p><p>

<?
    //Select all projects in the list for round 1
    $result = mysql_query("SELECT * FROM projects WHERE state = 2 or state = 8 ORDER BY modifieddate asc, nameofwork asc");
    $numrows = mysql_num_rows($result);
?>
<table border="1" width="630">
<tr>
  <td bgcolor="#CCCCCC"><h3>Random Rule</h3></td>
</tr>
<tr>
  <td>
<?
  include($relPath."randrule.php");
  print dpRandomRule();
?>
  </td>
</tr>
</table>

<p><p>

<table border=1 width=630>
<tr><td bgcolor=CCCCCC colspan=2><h3>Current First - Round Projects</h3></td>
<td bgcolor=CCCCCC colspan=4> These files are output from the OCR software and have not been looked at.</tr>
<tr><td bgcolor = "CCCCCC"><b>Name of Work</b></td><td align = "center" bgcolor = "CCCCCC"><b>Author</b></td><td bgcolor = "CCCCCC" width=70><b>Language</b></td><td bgcolor = "CCCCCC" width=60><center><b>Project Manager</b></center></td><td width = 100 align = center bgcolor = "CCCCCC"><b>Pages available for proofing</b></td><td width = 100 align = center bgcolor = "CCCCCC"><b>Total pages in the book</b></tr>
<?
    $rownum = 0;
    $rownum2 = 0;
    while ($rownum2 < $numrows) {
        $projectid = mysql_result($result, $rownum2, "projectid");

        // find out how many files are available for proofing for each project!!!!!
        $rows = mysql_query("SELECT fileid FROM $projectid WHERE state=2");
        $availablepages = mysql_num_rows($rows);

            //alternate colors for each project
            if ($rownum % 2) {
                $bgcolor = "\"#CCCCCC\"";
            } else {
                $bgcolor = "\"#999999\"";
            }

            // find out how many files the project has total!!!!!

            $rows = mysql_query("SELECT fileid FROM $projectid");
            $totalpages = (mysql_num_rows($rows));

            $nameofwork = mysql_result($result, $rownum2, "nameofwork");
            $authorsname = mysql_result($result, $rownum2, "authorsname");
            $language = mysql_result($result, $rownum2, "language");
            $username = mysql_result($result, $rownum2, "username");
            $projectid = mysql_result($result, $rownum2, "projectid");
            $state = mysql_result($result, $rownum2, "state");

            $users = mysql_query("SELECT email FROM users WHERE username = '$username'");
            $email = mysql_result($users, 0, "email");

        if (($availablepages != 0) && ($state == 2)) {
            // NOTE: Leave space before <tr> in there, needed for client.
            echo " <tr><td bgcolor = $bgcolor>$nameofwork</td><td bgcolor = $bgcolor>$authorsname</td>";
            echo "<td bgcolor = $bgcolor>$language</td><td bgcolor = $bgcolor><a href=\"mailto:$email\">$username</a></td>";
            echo "<td align=center bgcolor=$bgcolor>$availablepages</td><td align = center bgcolor = $bgcolor>$totalpages</td></tr>";
            echo "<tr><td bgcolor=$bgcolor colspan=4 align=center><a href=\"projects.php?project=$projectid&prooflevel=0\">Project Comments & Start Proofing</a></td>";
            echo "<td bgcolor=$bgcolor colspan=2 align=center><a href=\"listpages.php?project=$projectid&prooflevel=0\">My Recently Done</a></td></tr>\n";
        } else if (($availablepages == 0) || ($state == 2)) {

            // NOTE: Leave space before <tr> in there, needed for client.
            echo "<tr><td bgcolor = $bgcolor>$nameofwork</td><td bgcolor = $bgcolor>$authorsname</td>";
            echo "<td bgcolor = $bgcolor>$language</td><td bgcolor = $bgcolor><a href=\"mailto:$email\">$username</a></td>";
            echo "<td align=center bgcolor=$bgcolor>$availablepages</td><td align = center bgcolor = $bgcolor>$totalpages</td></tr>";
            echo "<td bgcolor=$bgcolor colspan=6 align=center><a href=\"listpages.php?project=$projectid&prooflevel=0\">My Recently Done</a></td></tr>\n";

        } else $rownum--;
        $rownum++;
        $rownum2++;
    }
?>

</table>
<table border=1 width=630>
<br><tr><td bgcolor="CCCCCC" colspan=2><h3>Current Second - Round Projects </h3></td>
<td bgcolor="CCCCCC" colspan=4>These are files that have already been proofed once, but now need to be examined <B>closely</B> for small errors that may have been missed.
See <A HREF="http://www.promo.net/pg/vol/proof.html#What_kinds" target = " ">this page</A> for examples.
</td></tr>
<tr><td bgcolor = "CCCCCC"><b>Name of Work</b></td><td align = "center" bgcolor = "CCCCCC"><b>Author</b></td><td bgcolor = "CCCCCC" width=70><b>Language</b></td><td bgcolor = "CCCCCC" width=60><center><b>Project Manager</b></center></td><td width = 100 align = center bgcolor = "CCCCCC"><b>Pages available for proofing</b></td><td width = 100 align = center bgcolor = "CCCCCC"><b>Total pages in the book</b></tr>
<?
    $rownum = 0;
    $bgcolor2 = "\"#999999\"";
    $rownum2 = 0;

    //Select all projects in the list for round 2 
    $result = mysql_query("SELECT * FROM projects WHERE state = 12 OR state = 18 ORDER BY nameofwork ASC");
    $numrows = mysql_num_rows($result);

    while ($rownum2 < $numrows) {
        $projectid = mysql_result($result, $rownum2, "projectid");

        // find out how many files are available for proofing for each project!!!!!
        $rows = mysql_query("SELECT fileid FROM $projectid WHERE state='12'");
        $availablepages = mysql_num_rows($rows);
   
            $rows = mysql_query("SELECT fileid FROM $projectid");
            $totalpages = (mysql_num_rows($rows));

            $nameofwork = mysql_result($result, $rownum2, "nameofwork");
            $authorsname = mysql_result($result, $rownum2, "authorsname");
            $language = mysql_result($result, $rownum2, "language");
            $username = mysql_result($result, $rownum2, "username");
            $projectid = mysql_result($result, $rownum2, "projectid");
            $state = mysql_result($result, $rownum2, "state");

            $users = mysql_query("SELECT email FROM users WHERE username = '$username' LIMIT 1");
            $email = mysql_result($users, 0, "email");

            //alternate colors for each project
            if ($rownum % 2) {
                $bgcolor = "\"#CCCCCC\"";
            } else {
                $bgcolor = "\"#999999\"";
            }

        if (($availablepages != 0) && ($state == 12)) {
            // NOTE: Leave space before <tr> in there, needed for client.
            echo "<tr><td bgcolor = $bgcolor>$nameofwork</td><td bgcolor = $bgcolor>$authorsname</td>";
            echo "<td bgcolor = $bgcolor>$language</td><td bgcolor = $bgcolor><a href=\"mailto:$email\">$username</a></td>";
            echo "<td align=center bgcolor=$bgcolor>$availablepages</td><td align = center bgcolor = $bgcolor>$totalpages</td></tr>";
            echo "<tr><td bgcolor=$bgcolor colspan=4 align=center><a href=\"projects.php?project=$projectid&prooflevel=2\">Project Comments & Start Proofing</a></td>";
            echo "<td bgcolor=$bgcolor colspan=2 align=center><a href=\"listpages.php?project=$projectid&prooflevel=2\">My Recently Done</a></td></tr>\n";
        } else if ($availablepages == 0) {

            // NOTE: Leave space before <tr> in there, needed for client.
            echo " <tr><td bgcolor = $bgcolor>$nameofwork</td><td bgcolor = $bgcolor>$authorsname</td>";
            echo "<td bgcolor = $bgcolor>$language</td><td bgcolor = $bgcolor><a href=\"mailto:$email\">$username</a></td>";
            echo "<td align=center bgcolor=$bgcolor>$availablepages</td><td align = center bgcolor = $bgcolor>$totalpages</td></tr>";
            echo "<td bgcolor=$bgcolor colspan=6 align=center><a href=\"listpages.php?project=$projectid&prooflevel=2\">My Recently Done</a></td></tr>\n";

        } else $rownum--;
        $rownum++;
        $rownum2++;
    }
    echo "</table>\n<p>";

    echo "<table border=1 cellpadding=0 cellspacing=0 style=\"border-collapse: collapse\" bordercolor=#111111 width=630>";
?>
<td width=126 bgcolor ="CCCCCC" align=center><a href ="../../phpBB2/index.php">Forums</a></td>
<?
    // If Project Manager give link back to project manager page.

    $result = mysql_query("SELECT manager FROM users WHERE username = '$pguser'");
    $manager = mysql_result($result, 0, "manager");
    if ($manager == "yes") {
        echo "<td width=126 bgcolor =CCCCCC align=center><a href = \"../project_manager/projectmgr.php\">Manage Projects</a></td>\n";
    } else {
        echo "<td width=126 bgcolor =CCCCCC align=center>&nbsp;</td>\n";
    }

?>
<td width=126 bgcolor =CCCCCC align=center>&nbsp;</td>
<?

    if ($postprocessor == "yes" || $postprocessorpages >= 400) {
  //  if ($postprocessor == "yes") {
        echo "<td width=126 bgcolor =CCCCCC align=center><a href =\"../post_proofers/post_proofers.php\">Post-Processing</a></td>";
    } else {
        echo "<td width=126 bgcolor =CCCCCC align=center>&nbsp;</td>\n";
    }

?>

<td width=126 bgcolor ="CCCCCC" align=center><a href ="../logout.php">Logout</a></td>
</tr></table></body>
</html>
