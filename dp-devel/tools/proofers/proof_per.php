<?
$relPath="./../../pinc/";
include($relPath.'dp_main.inc');
include($relPath.'bookpages.inc');
include($relPath.'showavailablebooks.inc');

// $userP['prefschanged'] will be set ==1 if they have changed prefs while in proofing mode
// should offer link to save changes and|or restore defaults

    echo "\n<html><head>";
    echo "\n<title>Personal Page for $pguser</title>";
      if ($userP['i_newwin']==1)
        {include($relPath.'js_newwin.inc');}
    echo "</head><body>";
    echo "\n <table border=0 cellspacing=0 width=630>";
    echo "\n <tr><td width=1 bgcolor='#CCCCCC'>&nbsp;</td>";
    echo "\n <td bgcolor='#CCCCCC' align=left colspan=2><font size='+1'>Welcome</font><font color='#0000FF' font size='+1'> $pguser</font></td></tr>";
    echo "\n <tr><td width=1 bgcolor='#CCCCCC'>&nbsp;</td><td>";
    echo "\n <b>Site Stats:</b><br>";
    include("../../stats/hourly.txt");

    //get total pages completed
    $pagessql = "SELECT pagescompleted FROM users WHERE username = '$pguser' LIMIT 1";
    $pages = mysql_query($pagessql);
    $totalpages = mysql_result($pages, 0, "pagescompleted");

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

    //get total number of users
    $users = mysql_query("SELECT count(*) AS numusers FROM users WHERE pagescompleted >=1");
    $totalusers = (mysql_result($users,0,"numusers"));
    echo "\n<br>Total users who completed at least 1 page: <b> $totalusers</b>";

    echo"\n <p><b>Your Stats:<br></b>";
    echo "\nYour Total Pages:<font color=\"#0000FF\"><b> $totalpages</b></font><br>";
    echo "\nYour Current Rank: <font color=\"#0000FF\"><b> $currentrank</b></font><br>";

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

echo "User Preferences: <b>";
if ($userP['prefschanged']==1)
{echo "<a href=\"../../userprefs.php\">Save current changes</a>";}
else {
if ($userP['i_prefs']==1)
{echo "<a href=\"../../userprefs.php\">View</a>";}
else {echo "<a href=\"../../userprefs.php\">Set user preferences</a>";}
}
echo "</b>";

//Following top ten/your neighbor board provided by David Bridson, modified for looks by Charles Franks, and updated by Curtis Weyant
if ( $userP['u_top10'] || $userP['u_neigh'] ) {
?>


<p><b>Top 10 Proofers:</b>
<blockquote>

<TABLE border=1 cellspacing=0 cellpadding=0>

<?
    $pagessql = "SELECT username,pagescompleted FROM users ORDER BY pagescompleted DESC";
    $pages = mysql_query($pagessql);

    $numrows = mysql_num_rows($pages);
    $i = 0;

    while ($i < $numrows) {
        $username = mysql_result($pages, $i, "username");
        $pagescompleted = mysql_result($pages, $i, "pagescompleted");

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
    $show_neighbors = $userP['u_neigh'];

    $i = 0;
    while ($i < $numrows) {
        // If ranking is in top ten or is current user or immediate
        // neighbour, print line in table
        if(($rankings[$i] < 11 && $userP['u_top10']) || (($i >= $userindex - $show_neighbors) && ($i <= $userindex + $show_neighbors) && $userP['u_neigh']) && $printedblankline) {
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

            // If the user has 0 pages done, or they are in the top 10, don't
            // print the neighbor's table (just break!)
            $display_neighbors = true;
            if ( $totalpages == 0 ) {
               $display_neighbors = false;
               break;
            }
            echo "<TR><TD colspan=3><BR></TD></TR><TR><TD colspan=3><b>Your Neighborhood:<b><BR></TD></TR>\n";
            $printedblankline = TRUE;
        }
    }
?>

</TABLE>
</blockquote>
<? } ?>

<p><font color='#FF0000'><B>If having troubles setting users prefs, <a href="../logout.php">Logout</a> and sign in again.</B></font>
<P><b>See whats new in the <a href = "../../phpBB2/index.php">Forums</a></b>
<?

    // If Post Processor give link to post processing page.
    $postprocessor = $userP['postprocessor'];
    $postprocessorpages = $totalpages;
    if ($postprocessor == "yes" || $postprocessorpages >= 400) {
//if ($postprocessor == "yes"){

?>

<P><b>Post Processing:</b><br>
You can help in the post processing phase of Distributed Proofreaders! After going through two rounds of proofreading, the books need to be massaged into a final e-text and you can help <a href ="../post_proofers/post_proofers.php">here</A>!<P>
<?
        $rows = mysql_query("SELECT count(projectid) AS numprojects FROM projects WHERE state=61");
        $postprojects = (mysql_result($rows,0,"numprojects"));

        echo "Currently there are <b> $postprojects </b> projects waiting";

        $rows = mysql_query("SELECT count(projectid) AS yourprojects FROM projects WHERE checkedoutby = '$pguser' and state=65");
        $postprojects = (mysql_result($rows,0,yourprojects));

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
<p><p>
Want to help out the site by providing material for us to proof? Check <a href="http://texts01.archive.org/dp/faq/scan/submitting.htm">here</a> to find out how!
<p></td><td width = "1" bgcolor = "CCCCCC">&nbsp</td></tr><tr>
<td width = "1" bgcolor = "CCCCCC" colspan ="4">&nbsp</td></tr></table><p><p>
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

<?
if ($userP['u_plist'] == 1 || $userP['u_plist'] == 3) {
echo "<table border=1 width=630>";
echo "<tr><td bgcolor='#CCCCCC' colspan=2><h3>Current First - Round Projects</h3></td>";
echo "<td bgcolor='#CCCCCC' colspan=5> These files are output from the OCR software and have not been looked at.</td></tr>";

    //Select all projects in the list for round 1
    $result = mysql_query("SELECT * FROM projects WHERE state = 2 or state = 8 ORDER BY modifieddate asc, nameofwork asc");
    showavailablebooks($result,2,$userP,0);
echo "</table>";
}
?>

<?
if ($userP['u_plist'] == 2 || $userP['u_plist'] == 3) {
echo "\n<table border=1 width=630>";
echo "<br><tr><td bgcolor='CCCCCC' colspan=2><h3>Current Second - Round Projects </h3></td>";
if ($totalpages < 50) {
echo "<td bgcolor='#cccccc' colspan=5>Second round projects are unavailable until you have proofed more than 50 first round pages.  After 50 pages of first round proofing the second round projects will be unlocked for you.";
echo "</td></tr></table>\n<p>";
} else {
echo "<td bgcolor='CCCCCC' colspan=5>These are files that have already been proofed once, but now need to be examined <B>closely</B> for small errors that may have been missed.";
echo "See <A HREF='http://www.promo.net/pg/vol/proof.html#What_kinds' target='_new'>this page</A> for examples.";
echo "</td></tr>";
    //Select all projects in the list for round 2 
    $result = mysql_query("SELECT * FROM projects WHERE state = 12 OR state = 18 ORDER BY nameofwork ASC");
    showavailablebooks($result,12,$userP,0);
    echo "</table>\n<p>";
}
} 
?>

<table border=1 cellpadding=0 cellspacing=0 style='border-collapse: collapse' bordercolor='#111111' width=630>
<tr>
<td width=126 bgcolor ="CCCCCC" align=center><a href ="../../phpBB2/index.php">Forums</a></td>
<?
    // If Project Manager give link back to project manager page.

    $manager = $userP['manager'];
    if ($manager == "yes") {
        echo "<td width=126 bgcolor='#CCCCCC' align=center><a href = \"../project_manager/projectmgr.php\">Manage Projects</a></td>\n";
    } else {
        echo "<td width=126 bgcolor='#CCCCCC' align=center>&nbsp;</td>\n";
    }

?>
<td width=126 bgcolor='CCCCCC' align=center>&nbsp;</td>
<?

    if ($postprocessor == "yes" || $postprocessorpages >= 400) {
  //  if ($postprocessor == "yes") {
        echo "<td width=126 bgcolor='#CCCCCC' align=center><a href =\"../post_proofers/post_proofers.php\">Post-Processing</a></td>";
    } else {
        echo "<td width=126 bgcolor='#CCCCCC' align=center>&nbsp;</td>\n";
    }

?>

<td width=126 bgcolor='#CCCCCC' align=center><a href ="../logout.php">Logout</a></td>
</tr></table></body>
</html>
