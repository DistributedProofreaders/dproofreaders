<?
$relPath="./../../pinc/";
include($relPath.'dp_main.inc');



?>
<table border=1 cellpadding=0 cellspacing=0 style='border-collapse: collapse' 
bordercolor='#111111' width=630>
<tr>
<td width=126 bgcolor ="#CCCCCC" align=center><a href ="../../phpBB2/index.php">Forums</a></td>
<?
    // If Project Manager give link back to project manager page.

    $manager = $userP['manager'];
    if ($manager == "yes") {
        echo "<td width=126 bgcolor='#CCCCCC' align=center><a href = \"../project_manager/projectmgr.php\">Manage 
Projects</a></td>\n";
    } else {
        echo "<td width=126 bgcolor='#CCCCCC' align=center>&nbsp;</td>\n";
    }

?>
<td width=126 bgcolor='#CCCCCC' align=center>&nbsp;</td>
<?
    $postprocessor = $userP['postprocessor'];
    if ($postprocessor == "yes" || $postprocessorpages >= 400) {
  //  if ($postprocessor == "yes") {
        echo "<td width=126 bgcolor='#CCCCCC' align=center><a href 
=\"../post_proofers/post_proofers.php\">Post-Processing</a></td>";
    } else {
        echo "<td width=126 bgcolor='#CCCCCC' align=center>&nbsp;</td>\n";
    }
?>

<td width=126 bgcolor='#CCCCCC' align=center><a href ="../logout.php">Logout</a></td>
</tr></table>
<?

/* $_GET $project, $proofstate, $proofing */

    $result = mysql_query("SELECT nameofwork, authorsname, comments, username, topic_id, postcomments FROM projects WHERE 
projectid = '$project'");
    $nameofwork = mysql_result($result, 0, "nameofwork");
    $authorsname = mysql_result($result, 0, "authorsname");
    $comments = mysql_result($result, 0, "comments");
    $username = mysql_result($result, 0, "username");
    $topic_id = mysql_result($result, 0, "topic_id");
    $phpuser = mysql_query("SELECT user_id FROM phpbb_users WHERE username = '$pguser'");
    $user_id = mysql_result($phpuser, 0, "user_id");
    $postcomments = mysql_result($result, 0, "postcomments");


include($relPath.'doctype.inc');
echo "$docType\r\n<HTML><HEAD><TITLE> Project Comments</TITLE>";

include($relPath.'js_newwin.inc');
echo "</HEAD><BODY>";
?>

<table border=1 width=630>
<?PHP


    echo "<tr><td bgcolor=\"CCCCCC\" align=\"center\"><b>Title</b></td>";
    echo "<td colspan=4>$nameofwork</td></tr>";
    echo "<tr><td bgcolor=\"CCCCCC\" align=\"center\"><b>Author</b></td>";
    echo "<td colspan=4>$authorsname</td></tr>";
    echo "<tr><td bgcolor=\"CCCCCC\" align=\"center\"><b>Project Manager</b></td>";
    echo "<td colspan=4>$username</td></tr>";
    if (isset($proofstate)) {
        echo "<tr><td bgcolor=\"CCCCCC\" align=\"center\"><b>Last Proofread</b></td>";
        echo "<td colspan=4>$lastproofed</td></tr>";
    }

    echo "<tr><td bgcolor=\"CCCCCC\" align=center><b>Forum</b></td><td colspan=4><a 
href=\"../proofers/project_topic.php?project=$project\">";

    if ($topic_id == "") {
        echo "Start a discussion about this project";
    } else {
        echo "Discuss this project";
    }
    echo "</a></td></tr>";
    echo "<tr><td bgcolor=\"CCCCCC\" colspan=5 align=center><h3>Project Comments</h3></td></tr><tr><td colspan=5>";
    echo "Follow the <a href=\"http://texts01.archive.org/dp/faq/document.html\">Document Guidelines 2.00</a> for detailed project formatting directions.";
    echo "Instructions below take precedence over the guidelines:<P>";
    echo "$comments</td></tr>";
    echo "<tr><td bgcolor=\"CCCCCC\" colspan=5 align=center><h3>Post Processor Comments</h3></td></tr><tr><td colspan=5>";
    echo "$postcomments</td></tr>";
    echo "</table>";
    echo "<BR>";


?>
</BODY></HTML>
