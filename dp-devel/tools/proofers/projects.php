<?
if ($_COOKIE['pguser']) {
    // can only come from a cookie, forged or otherwise
    $good_login = 1;
    $pguser = $_COOKIE['pguser'];
}

if ($good_login != 1) {
    echo "<html><head><META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=../../accounts/signin.php\"></head><body></body></html>"; 
} else {

    $project = $_GET['project'];
    $prooflevel = $_GET['prooflevel'];

    //connect to database
    include '../../connect.php';

    $result = mysql_query("SELECT nameofwork, authorsname, comments, username FROM projects WHERE projectid = '$project'");
    $nameofwork = mysql_result($result, 0, "nameofwork");
    $authorsname = mysql_result($result, 0, "authorsname");
    $comments = mysql_result($result, 0, "comments");
    $username = mysql_result($result, 0, "username");

    $users = mysql_query("SELECT email FROM users WHERE username = '$username'");
    $email = mysql_result($users, 0, "email");
?>

<html><head><title> Project Comments</title></head><body>
<table border=1 width=630><tr>
<td width=126 bgcolor="CCCCCC" align=center><a href ="proof_per.php">Back</a></td>
<td width=126 bgcolor="CCCCCC" align=center><a href ="../../phpBB2/index.php">Forums</a></td>
<?
echo "<td width=126 bgcolor=CCCCCC align=center><a href=\"proof.php?project=".$project."&prooflevel=".$prooflevel."&orient=vert\">Vertical Proofing</a></td>";
echo "<td width=126 bgcolor=CCCCCC align=center><a href=\"proof.php?project=".$project."&prooflevel=".$prooflevel."\">Horizontal Proofing</a></td>";
?>
<td width=126 bgcolor="CCCCCC" align=center><a href ="../logout.php">Logout</a></td>
</tr></table><br>
Check out <a href="http://texts01.archive.org/dp/faq/document.html">Document Guidelines 1.20</a> for detailed project formatting comments. Instructions in Project Comments below take precedence over the guidelines.
<P><table border=1 width=630>
<tr><td bgcolor="CCCCCC" align=center><h3><b>

<?
    if ($prooflevel == 0) {
        echo "First Round Project</b></h3></td><td bgcolor = \"CCCCCC\"><b>This is a First-Round project, these files are output from the OCR software and have not been looked at.</b></td></tr>";
    } else {
        echo "Second Round Project</b></h3></td><td bgcolor = \"CCCCCC\"><b>These are files that have already been proofed once, but now need to be examined <B>closely</B> for small errors that may have been missed. See <A HREF=\"http://www.promo.net/pg/vol/proof.html#What_kinds\" target = \" \">this page</A> for examples.</b></td>";
    }

    echo "<tr><td bgcolor=\"CCCCCC\" align=center><b>Name of Work</b></td>";
    echo "<td>$nameofwork</td></tr>";
    echo "<tr><td bgcolor=\"CCCCCC\" align=center><b>Author</b></td>";
    echo "<td>$authorsname</td></tr>";
    echo "<tr><td bgcolor=\"CCCCCC\" align=center><b>Project Manager</b></td>";
    echo "<td><A HREF=\"mailto: $email\">$username</A></td></tr>";

    echo "<tr><td bgcolor = \"CCCCCC\"><h3>Project Comments</h3></td><td>$comments</td></tr></table>"; 
}
?>
<br><table border=1 width=630><tr>
<td width=126 bgcolor="CCCCCC" align=center><a href ="proof_per.php">Back</a></td>
<td width=126 bgcolor="CCCCCC" align=center><a href ="../../phpBB2/index.php">Forums</a></td>
<?
echo "<td width=126 bgcolor=CCCCCC align=center><a href=\"proof.php?project=".$project."&prooflevel=".$prooflevel."&orient=vert\">Vertical Proofing</a></td>";
echo "<td width=126 bgcolor=CCCCCC align=center><a href=\"proof.php?project=".$project."&prooflevel=".$prooflevel."\">Horizontal Proofing</a></td>";
?>
<td width=126 bgcolor="CCCCCC" align=center><a href ="../logout.php">Logout</a></td>
</tr></table><br></body></html>
