<?
$relPath="./../../pinc/";
include($relPath.'cookiecheck.inc');
include($relPath.'connect.inc');
$dbC=new dbConnect();

    $project = $_GET['project'];
    $prooflevel = $_GET['prooflevel'];

    $result = mysql_query("SELECT nameofwork, authorsname, comments, username FROM projects WHERE projectid = '$project'");
    $nameofwork = mysql_result($result, 0, "nameofwork");
    $authorsname = mysql_result($result, 0, "authorsname");
    $comments = mysql_result($result, 0, "comments");
    $username = mysql_result($result, 0, "username");

    $users = mysql_query("SELECT email FROM users WHERE username = '$username'");
    $email = mysql_result($users, 0, "email");
include($relPath.'doctype.inc');
echo "<HTML><HEAD><TITLE> Project Comments</TITLE>$docType";
?>
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">
<!-- 
function newProofWin(winURL)
{
sw=screen.width
if (sw)
{newWidth=screen.width-20;
newHeight=((newWidth-40) * 75)/100;
}
else {newWidth=600;newHeight=450;}
newFeatures="'toolbars=0,location=0,directories=0;status=0;menubar=0,scrollbars=1,resizable=1,width="+newWidth+",height="+newHeight+",top=0,left=5'";
nwWin=window.open(winURL,"prooferWin",newFeatures);}
// -->
</SCRIPT></HEAD><BODY>
<?PHP
include('./projects_menu.inc');
?>
<br>
Check out <a href="http://texts01.archive.org/dp/faq/document.html">Document Guidelines 1.20</a> for detailed project formatting comments. <BR>Instructions in Project Comments below take precedence over the guidelines.
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
echo "<BR>";
include('./projects_menu.inc');
?>
</BODY></HTML>
