<?
$relPath="./../../pinc/";
include($relPath.'dp_main.inc');
include($relPath.'projectinfo.inc');

function notify($project, $proofstate, $pguser) {
    echo "<tr><td bgcolor=\"CCCCCC\" align=center><b>Book Completed:</b></td><td colspan=4><a href=\"posted_notice.php?project=$project&proofstate=$proofstate\">";
    $temp = mysql_query("SELECT * FROM usersettings WHERE username = '".$pguser."' AND setting = 'posted_notice' AND value = '".$project."'");
    if (mysql_num_rows($temp) == 0) {
        echo "Notify me";
    } else echo "Do not notify me";
    echo "</a></td></tr>";
}

function recentlyproofed($project, $proofstate, $pguser) {

    echo "<tr><td colspan=5 bgcolor=CCCCCC align=center><h3>My Recently Proofread</h3></td>";
    $recentNum=5;

    $sql = "SELECT image, fileid, ";
    $whichTime=$proofstate <9? "round1_time" : "round2_time";
    $sql.=$whichTime." FROM $project WHERE ";
    if ($proofstate <9) {$sql.="round1_user";} else {$sql.="round2_user";}
    $sql.="='$pguser' AND (state ='"; 
    if ($proofstate <9) {$sql.="9' OR state = '8";} else {$sql.="19' OR state = '18";} 
    $sql.="') ORDER BY ".$whichTime." DESC LIMIT $recentNum"; 
    $result = mysql_query($sql);

    $rownum = 0;
    $numrows = mysql_num_rows($result);

    while (($rownum < $recentNum) && ($rownum < $numrows)) {
        $imagefile = mysql_result($result, $rownum, "image");
        $fileid = mysql_result($result, $rownum, "fileid");
        $timestamp = mysql_result($result, $rownum, $whichTime);
        $newproject = "project=$project";
        $newfileid="&amp;fileid=$fileid";
        $newimagefile = '&amp;imagefile='.$imagefile;
        $newproofstate = '&amp;proofstate='.$proofstate;
        $saved="&amp;saved=1";
        $editone="&amp;editone=1";
        if (($rownum % 5) ==0) {echo "</tr><tr>";}
        $eURL="proof.php?".$newproject.$newfileid.$newimagefile.$newproofstate.$saved.$editone;
        echo "<TD ALIGN=\"center\">";
        if ($userP['i_newwin']==0) {echo "<A HREF=\"$eURL\">";}
        else {echo "<A HREF=\"#\" onclick=\"newProofWin('$eURL')\">";}
        echo date("M d", $timestamp).": ".$imagefile."</a></td>\r\n";
        $rownum++;
    }

    while (($rownum % 5) !=0) {echo "<td>&nbsp;</td>"; $rownum++;}
    echo "</tr>";
}





$projectinfo = new projectinfo();
if ($proofstate < 9) {
  $projectinfo->update_avail($project, 2);
} else $projectinfo->update_avail($project,12);

/* $_GET $project, $proofstate, $proofing */

    $result = mysql_query("SELECT nameofwork, authorsname, comments, username, topic_id FROM projects WHERE projectid = '$project'");
    $nameofwork = mysql_result($result, 0, "nameofwork");
    $authorsname = mysql_result($result, 0, "authorsname");
    $comments = mysql_result($result, 0, "comments");
    $username = mysql_result($result, 0, "username");
    $topic_id = mysql_result($result, 0, "topic_id");
    $phpuser = mysql_query("SELECT user_id FROM phpbb_users WHERE username = '$pguser'");
    $user_id = mysql_result($phpuser, 0, "user_id");


if (isset($proofstate)){
   if ($proofstate < 9)
   {$wTime="round1_time";
    $wState=9;}
   else {$wTime="round2_time";
         $wState=19;}
$proofdate=mysql_query("SELECT $wTime FROM $project WHERE state='$wState' ORDER BY $wTime DESC LIMIT 1");
  if (mysql_num_rows($proofdate)!=0)
     {$lastproofed=date("l, F jS, Y \a\\t g:i:sA",mysql_result($proofdate,0,$wTime))."&nbsp;&nbsp;&nbsp; (Current Time: ".date("g:i:sA",time()).")";}
  else {$lastproofed="Project has not been proofread in this round.";}
}
include($relPath.'doctype.inc');
echo "$docType\r\n<HTML><HEAD><TITLE> Project Comments</TITLE>";
if (!isset($proofing) && $userP['i_newwin']==1)
{
?>
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">
<!-- 
function newProofWin(winURL)
{
<?PHP 
$i_r= array('640x480','800x600','1024x768','1152x864','1280x1024','1600x1200');
$wSize=explode("x",$i_r[$userP['i_res']*1]);
echo "newFeatures='toolbar={$userP['i_toolbar']},status={$userP['i_statusbar']},location=0,directories=0,menubar=0,scrollbars=1,resizable=1,width=".($wSize[0]-20).",height=".($wSize[1]-30).",top=0,left=5';\r\n";
?>
nwWin=window.open(winURL,"prooferWin",newFeatures);}
// -->
</SCRIPT>
<?PHP
}
?>
</HEAD><BODY>
<?PHP
if (!isset($proofing)) {
    include('./projects_menu.inc');
    ?>

<br><table border=1 width=630><tr><td bgcolor="CCCCCC" align=center><h3><b>

<?
    if ($proofstate < 9) {
        echo "First Round Project</b></h3></td><td bgcolor = \"CCCCCC\" colspan=4>";
        echo "<b>This is a First-Round project, these files are output from the OCR software and have not been looked at.</b></td></tr>";
    } else {
        echo "Second Round Project</b></h3></td><td bgcolor = \"CCCCCC\" colspan=4>";
        echo "<b>These are files that have already been proofed once, but now need to be examined <B>closely</B> for small errors that may have been missed. See <A HREF=\"http://www.promo.net/pg/vol/proof.html#What_kinds\" target=\" \">this page</A> for examples.</b></td>";
    }
} else {
?>
<table border=1 width=630>
<?PHP
}

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

    echo "<tr><td bgcolor=\"CCCCCC\" align=center><b>Forum</b></td><td colspan=4><a href=\"project_topic.php?project=$project\">";

    if ($topic_id == "") {
        echo "Start a discussion about this project";
    } else {
        echo "Discuss this project";
    }
    echo "</a></td></tr>";

    notify($project, $proofstate, $pguser);

    recentlyproofed($project, $proofstate, $pguser);

    echo "<tr><td bgcolor=\"CCCCCC\" colspan=5 align=center><h3>Project Comments</h3></td></tr><tr><td colspan=5>";
    echo "Follow the <a href=\"http://texts01.archive.org/dp/faq/document.html\">Document Guidelines 1.22</a> for detailed project formatting directions.";
    echo "Instructions below take precedence over the guidelines:<P>";
    echo "$comments</td></tr></table>";
    echo "<BR>";

    if (!isset($proofing)) {
        include('./projects_menu.inc');
    } else {
        echo"<p><p><b> This information has been opened in a separate browser window, feel free to leave it open for reference or close it.</b>";
    }
?>
</BODY></HTML>
