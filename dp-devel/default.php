<?
$etext_limit = 10;
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Distributed Proofreaders</title>
</head>

<body>

<table border=0><tr>
<td>
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
<td bgcolor=#cccccc></td><td><br>This project is currently a private effort to support <a href="http://www.gutenberg.net">Project Gutenberg</a>
and is not an 'official' Project Gutenberg site.
<br>As such, if you have any questions or comments regarding this site
please <a href="mailto:charlz@lvcablemodem.com">E-mail Charles Franks</a> not the folks
at PG.
<br><br></td><tr>

<td bgcolor=#CCCCCC></td><td bgcolor=CCCCCC align=left><font size="+1">Site concept:</font></td><tr>
<td bgcolor=#CCCCCC></td><td>
<br>This site provides a web-based method of easing the proofreading work associated with the creation of Project Gutenberg E-Texts.
By breaking the work into individual pages many proofreaders can be working on the same book at the same time.
This significantly speeds up the proofreading/E-Text creation process.
<P> When a proofer elects to proofread a page for a particular project, the text and image
file are displayed on a single webpage. This allows the text file to be easily
reviewed and compared to the image file, thus assisting the proofreading of the text file.
The edited text file is then submitted back to the site via the same webpage that it was edited on.
<p>Once all pages for a particular book have been processed the Project Manager joins the pieces, properly formats them into a PG E-Text and
submits it to the PG archive.
<p></td><tr>

<td bgcolor=CCCCCC></td><td bgcolor=CCCCCC align=left><font size="+1">Update: Saturday November 16th</font></td><tr>
<td bgcolor=CCCCCC></td><td>
I have finally had a chance to update the finance page.. we have received almost $675 in donations! The site is now actually $127.47 in <b> the black.</b> Big Thanks!! to everyone who donated.
<p>I am still buried in email and scanning.  Charles F.
<p>Want to help support this site????  We can always use $$ to buy books, software, hardware etc. By clicking on the 'Beg Button' below you can donate with your credit card or PayPal account via PayPal!!<br>
You can view the financial statement for this site <a href="finance.html">here.</a>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="charlz@lvcablemodem.com">
<input type="image" src="http://images.paypal.com/images/x-click-butcc-donate.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
</form><p></td><tr>

<td bgcolor=CCCCCC><td align=left><b>Hourly Page Count:&nbsp&nbsp</b> <? include("stats/hourly.txt"); ?>
<br>Our goal this month is <b>34,000</b> pages which means <b>1,133</b> pages per day.<p></td><tr>

<?
    include 'connect.php';

    $numdays = 1;

    //Changing this will change how many days will show at the top of the main page
    $daterange=$numdays * 86400;

    //Get dates
    $datethru = mktime();
    $datefrom = $datethru - $daterange;
    $datefromformat = date("l jS",$datefrom)." of ".date("F",$datefrom);
    $datethruformat = date("l jS",$datethru)." of ".date("F",$datethru);
    $sqldatefrom = date("Ymd",$datefrom);
    $sqldatethru = date("Ymd",$datethru);

    //Put a few items into variables for handy usage later
    $blankline = "\n<td width = 1 bgcolor=CCCCCC>&nbsp</td><td>";
    $tailmessage[4]="have posted to the PG archive.\n\n<p>\n";
    $tailmessage[3]="has finished going through the site and moves up to Silver.\n\n<p>\n";
    $tailmessage[2]="have been released for proofing and get Bronze Stars.\n\n<p>\n";

    print "<td width = 1 bgcolor=CCCCCC>&nbsp</td>
    <td bgcolor= CCCCCC align = left><font size=\"+1\">
    Site Activity from ".$datefromformat." to ".$datethruformat."</font></td><tr>";
    print $blankline."<p>\n";

    $result = mysql_query("SELECT nameofwork, authorsname FROM projects WHERE state = '30' and modifieddate >= '$sqldatefrom' and modifieddate <= '$sqldatethru' ORDER BY modifieddate DESC");

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

    $result = mysql_query("SELECT nameofwork, authorsname FROM projects WHERE state >= 19 and state <= 29 AND modifieddate >= '$sqldatefrom' and modifieddate <= '$sqldatethru' ORDER BY modifieddate desc");

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
<br><font size = "+1">Proofreaders:</font></td><tr>
<td bgcolor=CCCCCC></td><td>
<font size="+1"><a href="accounts/addproofer.php">Create</a></font> an account to get started! You can also read the <a href="faq/ProoferFAQ.html">Frequently Asked Questions (FAQ)</a> to get an
 idea of what we do here at this site.
<p><u><font size=+1>Already have an account?</font></u>
<br><font size="+1"><a href="accounts/signin.php">Sign In</a></font> and start proofing!!
<br><br></td><tr>

<td bgcolor=CCCCCC></td><td bgcolor=CCCCCC align=left><font size="+1">Daily Statistics</font></td><tr>
<td bgcolor=CCCCCC><td align=center><img SRC="stats/stats.png" width=600 height=500 ALT="stats.png"><p></td><tr>
<td bgcolor=CCCCCC><td align=center><img SRC="stats/target.png" width=600 height=500 ALT="target.png"><p></td><tr>
<td bgcolor=CCCCCC><td align=center>Note: if you can't see the stats image please first click the Refresh/Reload button on your browser and if the image still does not appear please <a href="mailto
:charlz@lvcablemodem.com">E-mail me</a> and tell me the make and version of your browser as well as what operating system you are using.</td><tr>
<td bgcolor=CCCCCC><td align=center><a href="previous_stats.html">Previous Months Statistics</a></td><tr>

<td bgcolor="#cccccc">&nbsp;</td>
<td bgcolor="#cccccc"><font size="+1">How are we doing so far?</font></td>
</table>

<? 
//Gold E-texts
$result = mysql_query("SELECT projectid FROM projects WHERE state=30"); 
$goldresult = mysql_query("SELECT nameofwork, authorsname, ziplink, txtlink, htmllink, modifieddate, postednum FROM projects WHERE state=30 ORDER BY modifieddate DESC LIMIT $etext_limit");
echo "<img src='web_graphics/gold_star.jpg' border='0' height='38' width='40' alt='Gold_Star.jpg'> = <font face='Verdana' size='4'>".mysql_num_rows($result)." Complete.</font>  The book has been processed through this site and posted to the Project Gutenberg archive.<br>";
$numofetexts = 1;
while ($row = mysql_fetch_array($goldresult)) {
$links="";
if (trim($row['ziplink']) <> "") $links=$links."<a href='".$row['ziplink']."'>zip version</a>, ";
if (trim($row['txtlink']) <> "") $links=$links."<a href='".$row['txtlink']."'>text version</a>, ";
if (trim($row['htmllink']) <> "") $links=$links."<a href='".$row['htmllink']."'>html version</a>";
$moddate = $row['modifieddate'];
$dateyear = substr($moddate, 0, 4);
$datemonth = substr($moddate, 4, 2);
$dateday = substr($moddate, 6, 2);
$datecomplete = $dateyear."-".$datemonth."-".$dateday;
$unixsec = strtotime($datecomplete);
$moddate = date("l, F jS, Y",$unixsec);
if ($numofetexts == $etext_limit) {
echo "<font face='Verdana' size='1' color='#444444'><b>$numofetexts) \"".$row['nameofwork']."\"</b></font><font face='Verdana' size='1'>, ".$row['authorsname']."<br>".$row['postednum']." pages; $moddate<br>$links<br>--<a href='list_etexts.php?x=g'>See more e-texts</a></font><br><br>";
} else {
echo "<font face='Verdana' size='1' color='#444444'><b>$numofetexts) \"".$row['nameofwork']."\"</b></font><font face='Verdana' size='1'>, ".$row['authorsname']."<br>".$row['postednum']." pages; $moddate<br>$links<br><br>";
}
$numofetexts++;
}


//Silver E-texts
$result = mysql_query("SELECT projectid FROM projects WHERE state>=19 AND state<=29"); 
$goldresult = mysql_query("SELECT nameofwork, authorsname, modifieddate, postednum FROM projects WHERE state>=19 AND state<=29 ORDER BY modifieddate DESC LIMIT $etext_limit");
echo "<img src='web_graphics/silver_star.jpg' border='0' height='38' width='40' alt='Silver_Star.jpg'> = <font size='+1'>".mysql_num_rows($result)." In Progress.</font>  The book has been processed through this site but not yet posted to the Project Gutenberg archive (going through final proofing/assembly).<br>";
$numofetexts = 1;
while ($row = mysql_fetch_array($goldresult)) {
$moddate = $row['modifieddate'];
$dateyear = substr($moddate, 0, 4);
$datemonth = substr($moddate, 4, 2);
$dateday = substr($moddate, 6, 2);
$datecomplete = $dateyear."-".$datemonth."-".$dateday;
$unixsec = strtotime($datecomplete);
$moddate = date("l, F jS, Y",$unixsec);
if ($numofetexts == $etext_limit) {
echo "<font face='Verdana' size='1' color='#444444'><b>$numofetexts) \"".$row['nameofwork']."\"</b></font><font face='Verdana' size='1'>, ".$row['authorsname']."<br>".$row['postednum']." pages; $moddate<br>--<a href='list_etexts.php?x=s'>See more e-texts</a></font><br><br>";
} else {
echo "<font face='Verdana' size='1' color='#444444'><b>$numofetexts) \"".$row['nameofwork']."\"</b></font><font face='Verdana' size='1'>, ".$row['authorsname']."<br>".$row['postednum']." pages; $moddate<br><br>";
}
$numofetexts++;
}

//Bronze E-texts
$result = mysql_query("SELECT projectid FROM projects WHERE state=2 OR state=12 OR state=8 OR state=18"); 
$goldresult = mysql_query("SELECT nameofwork, authorsname, modifieddate, postednum FROM projects WHERE state=2 OR state=12 OR state=8 OR state=18 ORDER BY modifieddate DESC LIMIT $etext_limit");
echo "<img src='web_graphics/bronze_star.jpg' border='0' height='38' width='40' alt='Bronze_Star.jpg'> = <font size='+1'>".mysql_num_rows($result)." Now Proofing.</font>  The book is currently being processed through this site, sign in and start helping!!!! <br>";
$numofetexts = 1;
while ($row = mysql_fetch_array($goldresult)) {
$moddate = $row['modifieddate'];
$dateyear = substr($moddate, 0, 4);
$datemonth = substr($moddate, 4, 2);
$dateday = substr($moddate, 6, 2);
$datecomplete = $dateyear."-".$datemonth."-".$dateday;
$unixsec = strtotime($datecomplete);
$moddate = date("l, F jS, Y",$unixsec);
if ($numofetexts == $etext_limit) {
echo "<font face='Verdana' size='1' color='#444444'><b>$numofetexts) \"".$row['nameofwork']."\"</b></font><font face='Verdana' size='1'>, ".$row['authorsname']."<br>".$row['postednum']." pages; $moddate<br>--<a href='list_etexts.php?x=b'>See more e-texts</a></font><br><br>";
} else {
echo "<font face='Verdana' size='1' color='#444444'><b>$numofetexts) \"".$row['nameofwork']."\"</b></font><font face='Verdana' size='1'>, ".$row['authorsname']."<br>".$row['postednum']." pages; $moddate<br><br>";
}
$numofetexts++;
}
?>

</body>
</html>
