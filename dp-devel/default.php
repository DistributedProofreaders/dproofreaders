<?php
$relPath="./pinc/";
include($relPath.'connect.inc');
$db_Connection=new dbConnect();
include($relPath.'showstartexts.inc');
$etext_limit = 10;
?>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="SHORTCUT ICON" href="/dp/favicon.ico">
<title>Distributed Proofreaders</title>
</head>
<body>
<table border=0><tr><td>
<form action="accounts/login.php" method="post"><table border=1>
<tr><td bgcolor=#CCCCCC><strong>Username</strong></td><td><input type="text" name="userNM" size=10 maxsize=50></td></tr>
<tr><td bgcolor=#CCCCCC><strong>Password</strong></td><td><input type="password" name="userPW" size=10 maxsize=50></td></tr>
<tr><td colspan=2 align=center><a href="accounts/addproofer.php">New User?</a> <input type="submit" value="Auth me"></td></tr>
</table></form>
</td>
<td width=640 align="center" bgcolor=#FFFFFF><img SRC="web_graphics/title2.gif" BORDER=0 height=120 width=640 ALT="title2.gif"></td>
</tr></table>
<br><br>

<table border=0 cellspacing=0>
<tr><td width=1 bgcolor=#cccccc>&nbsp;</td><td bgcolor=#cccccc align="left"><font size=+1>About this site:</font></td><tr>
<td bgcolor=#cccccc></td><td><br>This project is currently a sub-group of <a href="http://www.gutenberg.net">Project Gutenberg</a>
and is not the official Project Gutenberg site.
<br>As such, if you have any questions or comments regarding this site please <a href="mailto:dphelp@texts01.archive.org">E-mail DP-help</a> and not the folks at Project Gutenberg.
<br><br></td><tr>

<td bgcolor=#CCCCCC></td><td bgcolor=CCCCCC align=left><font size="+1">Site concept:</font></td><tr>
<td bgcolor=#CCCCCC></td><td>
<p>This site provides a web-based method of easing the proofreading work associated with the creation of Project Gutenberg e-books. By breaking the work into individual pages many proofreaders can be working on the same book at the same time. This significantly speeds up the proofreading/e-book creation process.</p>
<p>When a proofer elects to proofread a page for a particular project, the text and image
file are displayed on a single web page. This allows the text file to be easily
reviewed and compared to the image file, thus assisting the proofreading of the text file.
The edited text file is then submitted back to the site via the same web page that it was edited on.</p>
<p>Once all pages for a particular book have been processed, a post-processor joins the pieces, properly formats them into a Project Gutenberg e-book and submits it to the Project Gutenberg archive.</p>
</td><tr>

<?
$result = mysql_query("SELECT * FROM news ORDER BY uid DESC LIMIT 1");
$date_posted = date("l F jS, Y", mysql_result($result,0,"date_posted"));
$message = mysql_result($result,0,"message");
echo "<td bgcolor='#cccccc'></td><td bgcolor='#cccccc' align='left'><font size='+1'>Update: $date_posted</font></td></tr>";
echo "<td bgcolor='#cccccc'></td><td>";
echo $message;
?>

<br>You can view the financial statement for this site <a href="finance.html">here.</a>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="charlz@lvcablemodem.com">
<input type="image" src="http://images.paypal.com/images/x-click-butcc-donate.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
</form><p></td><tr>

<td bgcolor=CCCCCC><td align=left><b>Hourly Page Count:</b><br><? include("stats/hourly.txt"); ?>
<br>Our goal this month is <b>134,008</b> pages which means <b>4,786</b> pages per day.<p></td><tr>

<?
    $numdays = 1;

    //Changing this will change how many days will show at the top of the main page
    $daterange=$numdays * 86400;

    //Get dates
    $datethru = mktime();
    $datefrom = $datethru - $daterange;
    $datefromformat = date("l jS",$datefrom)." of ".date("F",$datefrom);
    $datethruformat = date("l jS",$datethru)." of ".date("F",$datethru);
// table dates
    $sqldatefrom = $datefrom;
    $sqldatethru = $datethru;

    //Put a few items into variables for handy usage later
    $blankline = "\n<td width = 1 bgcolor=CCCCCC>&nbsp</td><td>";
    $tailmessage[4]="have posted to the PG archive.\n\n<p>\n";
    $tailmessage[3]="has finished going through the site and moves up to Silver.\n\n<p>\n";
    $tailmessage[2]="have been released for proofing and get Bronze Stars.\n\n<p>\n";

    print "<td width = 1 bgcolor=CCCCCC>&nbsp</td>
    <td bgcolor= CCCCCC align = left><font size=\"+1\">
    Site Activity from ".$datefromformat." to ".$datethruformat."</font></td><tr>";
    print $blankline."<p>\n";

    $result = mysql_query("SELECT nameofwork, authorsname FROM projects WHERE state = '79' and modifieddate >= '$sqldatefrom' and modifieddate <= '$sqldatethru' ORDER BY modifieddate DESC");

    $rownum = 0;
    $numgold = mysql_num_rows($result);
    while ($rownum < $numgold) {
        $title = mysql_result($result, $rownum, "nameofwork");
        $author = mysql_result($result, $rownum, "authorsname");

        print "<i>".$title."</i>, ".$author."<br>\n";
        $rownum++;
    } //End while

    if ($rownum >0) {
        print $tailmessage[4];
    }

    $result = mysql_query("SELECT nameofwork, authorsname FROM projects WHERE state >= 60 and state < 79 AND modifieddate >= '$sqldatefrom' and modifieddate <= '$sqldatethru' ORDER BY modifieddate desc");

    $rownum = 0;
    $numsilver = mysql_num_rows($result);
    while ($rownum < $numsilver) {
        $title = mysql_result($result, $rownum, "nameofwork");
        $author = mysql_result($result, $rownum, "authorsname");

        print "<i>".$title."</i>, ".$author."<br>\n";
        $rownum++;
    } //End while

    if ($rownum >0) {
        print $tailmessage[3];
    }

    $result = mysql_query("SELECT nameofwork, authorsname FROM projects WHERE (state = 2 OR state = 12 OR state = 8 OR state = 18) AND (modifieddate >= '$sqldatefrom' AND modifieddate <= '$sqldatethru') ORDER BY modifieddate desc");

    $rownum = 0;
    $numbronze = mysql_num_rows($result);
    while ($rownum < $numbronze) {
        $title = mysql_result($result, $rownum, "nameofwork");
        $author = mysql_result($result, $rownum, "authorsname");

        print "<i>".$title."</i>, ".$author."<br>\n";
        $rownum++;
    } //End while

    if ($rownum >0) {
        print $tailmessage[2];
    }
?>
</tr><tr>

<td width = 1 bgcolor=CCCCCC>&nbsp</td><td bgcolor=CCCCCC align=left><font size = "+1">Get Started!</font><td><tr>
<td width = 1 bgcolor=CCCCCC>&nbsp</td><td>
<br><u><font size = "+1">Proofreaders:</font></u>
<br><font size="+1"><a href="accounts/addproofer.php">Create</a></font> an account to get started! You can also read the <a href="faq/ProoferFAQ.html">Frequently Asked Questions (FAQ)</a> to get an
 idea of what we do here at this site.
<p><u><font size=+1>Already have an account?</font></u>
<br><font size="+1"><a href="accounts/signin.php">Sign In</a></font> and start proofing!!
<br><br></td><tr>

<td bgcolor=CCCCCC></td><td bgcolor=CCCCCC align=left><font size="+1">Daily Statistics</font></td><tr>
<td bgcolor=CCCCCC><td align=center><a href="stats/stats.png"><img SRC="stats/stats.png" width=300 height=250 ALT="stats.png"></a>&nbsp;<a href="stats/target.png"><img SRC="stats/target.png" width=300 height=250 ALT="target.png"></a><p></td><tr>
<td bgcolor=CCCCCC><td align=center>Note: if you can't see the stats image please first click the Refresh/Reload button on your browser and if the image still does not appear please <a href="mailto:dphelp@texts01.archive.org">E-mail DP-help</a> and tell us the make and version of your browser as well as what operating system you are using.</td><tr>
<td bgcolor=CCCCCC><td align=center><a href="previous_stats.html">Previous Months Statistics</a></td><tr>

<td bgcolor="#cccccc">&nbsp;</td>
<td bgcolor="#cccccc"><font size="+1">How are we doing so far?</font></td>
</table>

<? 
//Gold E-texts
showstartexts($etext_limit,'gold');
//Silver E-texts
showstartexts($etext_limit,'silver');
//Bronze E-texts
showstartexts($etext_limit,'bronze');
?>

</body>
</html>
