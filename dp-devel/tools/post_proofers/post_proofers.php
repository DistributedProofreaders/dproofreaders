<?
if ($_COOKIE['pguser']) {
    // can only come from a cookie, forged or otherwise
    $good_login = 1;
    $pguser = $_COOKIE['pguser'];
}

if ($good_login != 1) {
    echo "<p><META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=../../accounts/signin.php\">"; 
} else {
?>

<html><title>Post Processing</title> 

<body>
<table border="1" width="630" cellpadding="0" cellspacing="0" style="border-collapse: collapse;" bordercolor="#111111">
<tr><td width="126" align="center" bgcolor="#cccccc"><a href="http://texts01.archive.org/dp/phpBB2/index.php">Forums</a></td>
<td width="126" align="center" bgcolor="#cccccc"><?

    include '../../connect.php';

    $result = mysql_query("SELECT manager FROM users WHERE username = '$pguser'");
    $manager = mysql_result($result, 0, "manager");

    if ($manager == "yes") {
        echo "<a href = \"../project_manager/projectmgr.php\">Manage Books</a>";
    } else echo "&nbsp;";

?></td>
<td width="126" align="center" bgcolor="#cccccc">&nbsp;</td>
<td width=126 bgcolor =CCCCCC align=center><a href ="../proofers/proof_per.php">Proofread Books</a></td>
<td width=126 bgcolor ="CCCCCC" align=center><a href ="../logout.php">Logout</a></td></tr></table>

<table border="1" width="630" cellpadding="0" cellspacing="0" style="border-collapse: collapse;" bordercolor="#111111">
<tr><td colspan=5 bgcolor="CCCCCC">

<P>This is the post processing section. The books listed below have already gone through two rounds of proofreading on this site and they now need to be massaged into a final e-text. Once you have checked out and downloaded a book it will remain checked out to you until you check it back in. <b>The Completed Post-Processing feature is currently disabled until it gets fully developed. E-mail the project manager with the completed project for now.</b>

<p>Here is a <a href="http://texts01.archive.org/dp/faq/post_proof.html"><b>Post Proof FAQ</b></a> that details all of the 
steps that we normally take to post proof an etext. There is a <a href=http://texts01.archive.org/dp/phpBB2/viewforum.php?f=3>forum</a> to post all your questions. If no books are listed that means no work is currently available, please check back later!
<p>
<b>First Time here?</b>  Juliet Sutherland is our Post Processing Coordinator. Please read the FAQ, select an easy work to get 
started on (usually fiction with a low page count is a good starter book) and write <a href = 
"mailto:juliet.sutherland@verizon.net"> Juliet</a> with any questions/comments.<p></td></tr>
<tr><td colspan = 5 align=center bgcolor=999999><B>My Checked Out Post-Processing Books</B></td></tr>
<tr><td width=205 align="center" bgcolor="CCCCCC"><b>Title</b></td>
    <td width="100" align="center" bgcolor="CCCCCC"><b>Author</b></td>
    <td width="50" align="center" bgcolor="CCCCCC"><b>Pages</b></td>
    <td width="75" align="center" bgcolor="CCCCCC"><b>Manager</b></td>
    <td width="200" align="center" bgcolor="CCCCCC"><b>Book Options</b></td>
</tr>

<?

    $rows = mysql_query("SELECT projectid, nameofwork, authorsname, username, scannercredit FROM projects WHERE checkedoutby = '$pguser' AND state=25");

    $rownum = 0;
    $numrow = mysql_numrows($rows);
    while ($rownum < $numrow) {
        $projectid = mysql_result($rows, $rownum, "projectid");
        $nameofwork = mysql_result($rows, $rownum, "nameofwork");
        $authorsname = mysql_result($rows, $rownum, "authorsname");
        $username = mysql_result($rows, $rownum, "username");

        // get number of pages in project
        $pages = mysql_query("SELECT fileid FROM $projectid WHERE prooflevel = '3'");
        $totalpages = (mysql_num_rows($pages));

        $users = mysql_query("SELECT email, real_name FROM users WHERE username = '$username'");
        $managersemail = mysql_result($users, 0, "email");
        $real_name = mysql_result($users, 0, "real_name");

        //alternate colors for each project
        if ($rownum % 2 ) {
            $bgcolor = "\"#CCCCCC\"";
        } else {
            $bgcolor = "\"#999999\"";
        }
 
        echo "<tr><td bgcolor = $bgcolor>$nameofwork</td><td bgcolor = $bgcolor>$authorsname</td>";
        echo "<td bgcolor = $bgcolor align=center>$totalpages</td><td bgcolor = $bgcolor><a href=\"mailto:$managersemail\">$username</a></td>";
        echo "<td bgcolor = $bgcolor><form name=\"$projectid\" method=\"get\" action=\"changestate.php\">";
        echo "<input type=\"hidden\" name=\"project\" value=\"$projectid\">\n";
?>
        <select name="state" onchange="this.form.submit()">
        <option selected>Select...</option>
        <option value="0">Download Zipped Text</option>
        <option value="1">Download Zipped Images</option>
        <option value="2">View Images Online</option>
        <option value="3">View Project Comments</option>
        <option value="20">Return to Available</option>
        </select></form></td>
<?
        echo "</tr>\n";
        //increment row number for background color change
        $rownum++;
    }
?>
<tr><td colspan = 5 align=center bgcolor=999999><B>Available Post-Processing Books</B></td></tr>
<tr><td width=205 align="center" bgcolor="CCCCCC"><b>Title</b></td>
    <td width="100" align="center" bgcolor="CCCCCC"><b>Author</b></td>
    <td width="50" align="center" bgcolor="CCCCCC"><b>Pages</b></td>
    <td width="75" align="center" bgcolor="CCCCCC"><b>Manager</b></td>
    <td width="200" align="center" bgcolor="CCCCCC"><b>Book Options</b></td>
</tr>
<?

    // list projects which are available for post proofing
    $rows = mysql_query("SELECT username, projectid, nameofwork, authorsname FROM projects WHERE state=20");
      
    $rownum = 0;
    $numrow = mysql_numrows($rows);

    while ($rownum < $numrow) {
        $projectid = mysql_result($rows, $rownum, "projectid");
        $nameofwork = mysql_result($rows, $rownum, "nameofwork");
        $authorsname = mysql_result($rows, $rownum, "authorsname");
        $username = mysql_result($rows, $rownum, "username");

        // get number of pages in project
        $pages = mysql_query("SELECT fileid FROM $projectid WHERE prooflevel = '3'");
        $totalpages = (mysql_num_rows($pages));

        $users = mysql_query("SELECT email, real_name FROM users WHERE username = '$username'");
        $managersemail = mysql_result($users, 0, "email");

        //alternate colors for each project
        if ($rownum % 2 ) {
            $bgcolor = "\"#CCCCCC\"";
        } else {
            $bgcolor = "\"#999999\"";
        }

        echo "<tr><td bgcolor = $bgcolor>$nameofwork</td><td bgcolor = $bgcolor>$authorsname</td>";
        echo "<td bgcolor = $bgcolor align=center>$totalpages</td><td bgcolor = $bgcolor><a href=\"mailto:$managersemail\">$username</a></td>";
        echo "<td bgcolor = $bgcolor><form name=\"$projectid\" method=\"get\" action=\"changestate.php\">";
        echo "<input type=\"hidden\" name=\"project\" value=\"$projectid\">\n";
?>
        <select name="state" onchange="this.form.submit()">
        <option>Select...</option>
        <option value="0">Download Zipped Text</option>
        <option value="1">Download Zipped Images</option>
        <option value="2">View Images Online</option>
        <option value="25">Check Out Book</option>
        </select></form></td>
<?
        echo "</tr>\n";
        //increment row number for background color change
        $rownum++;
    }
?>
<tr><td bgcolor=#999999 colspan=5>&nbsp;</td></tr></table>
<table border=1 cellpadding=0 cellspacing=0 style="border-collapse: collapse" bordercolor=#111111 width=630>
<td width=126 bgcolor ="CCCCCC" align=center><a href ="../../phpBB2/index.php">Forums</a></td>
<td width=126 bgcolor =CCCCCC align=center><?

    if ($manager == "yes") {
        echo "<a href = \"../project_manager/projectmgr.php\">Manage Books</a>";
    } else echo "&nbsp;";

?></td>
<td width=126 bgcolor =CCCCCC align=center>&nbsp;</td>
<td width=126 bgcolor =CCCCCC align=center><a href ="../proofers/proof_per.php">Proofread Books</a></td>
<td width=126 bgcolor ="CCCCCC" align=center><a href ="../logout.php">Logout</a></td>
</body></html>
<?
}
?>
