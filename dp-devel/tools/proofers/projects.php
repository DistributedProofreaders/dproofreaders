<?
$relPath="./../../pinc/";
include($relPath.'v_site.inc');
include($relPath.'dp_main.inc');
include($relPath.'projectinfo.inc');

function notify($project, $proofstate, $pguser) {
    echo "<tr><td bgcolor=\"CCCCCC\" align=center><b>Book Completed:</b></td><td colspan=4><a href=\"posted_notice.php?project=$project&proofstate=$proofstate\">";
    $temp = mysql_query("SELECT * FROM usersettings WHERE username = '".$pguser."' AND setting = 'posted_notice' AND value = '".$project."'");
    if (mysql_num_rows($temp) == 0) {
        echo "Yes, I would like to be notified when this has been posted to Project Gutenberg.";
    } else echo "No, I would like to not be notified when this has been posted to Project Gutenberg.";
    echo "</a></td></tr>";
}

function recentlyproofed($project, $proofstate, $pguser,$userP,$wlist) {

    echo "<tr><td colspan=5 bgcolor=CCCCCC align=center><h3>";
        if ($wlist==0)
          {echo "My Recently Completed";}
        else
          {echo "My Recently Proofread";}
    echo "</h3></td>";
    $recentNum=5;

    $sql = "SELECT image, fileid, state, ";
    $whichTime = $proofstate==PROJ_PROOF_FIRST_AVAILABLE ? "round1_time" : "round2_time";
    $sql.=$whichTime." FROM $project WHERE ";
    if ($proofstate==PROJ_PROOF_FIRST_AVAILABLE) {$sql.="round1_user";} else {$sql.="round2_user";}
    $sql.="='$pguser' AND "; 
    if ($proofstate==PROJ_PROOF_FIRST_AVAILABLE) 
      {
        if ($wlist==0)
          {$sql.="state ='".SAVE_FIRST."'";}
        else
          {$sql.="(state ='".TEMP_FIRST."' OR state ='".OUT_FIRST."')";}
      }
    else
     {
        if ($wlist==0)
          {$sql.="state ='".SAVE_SECOND."'";}
        else
          {$sql.="(state ='".TEMP_SECOND."' OR state ='".OUT_SECOND."')";}
      } 
    $sql.=" ORDER BY ".$whichTime." DESC LIMIT $recentNum"; 
    $result = mysql_query($sql);
    $rownum = 0;
    $numrows = mysql_num_rows($result);

    while (($rownum < $recentNum) && ($rownum < $numrows)) {
        $imagefile = mysql_result($result, $rownum, "image");
        $fileid = mysql_result($result, $rownum, "fileid");
        $timestamp = mysql_result($result, $rownum, $whichTime);
        $pagestate = mysql_result($result, $rownum, "state");
        $newproject = "project=$project";
        $newfileid="&amp;fileid=$fileid";
        $newimagefile = '&amp;imagefile='.$imagefile;
        $newproofstate = '&amp;proofstate='.$proofstate;
        $newpagestate = '&amp;pagestate='.$pagestate;
        $saved="&amp;saved=1";
        $editone="&amp;editone=1";
        if (($rownum % 5) ==0) {echo "</tr><tr>";}
        $eURL="proof_frame.php?".$newproject.$newfileid.$newimagefile.$newproofstate.$newpagestate.$saved.$editone;
        echo "<TD ALIGN=\"center\">";
        echo "<A HREF=\"$eURL\" target=\"proofframe\">";
        echo date("M d", $timestamp).": ".$imagefile."</a></td>\r\n";
        $rownum++;
    }

    while (($rownum % 5) !=0) {echo "<td>&nbsp;</td>"; $rownum++;}
    echo "</tr>";
}


$projectinfo = new projectinfo();
if ($proofstate==PROJ_PROOF_FIRST_AVAILABLE) {
  $projectinfo->update_avail($project, PROJ_PROOF_FIRST_AVAILABLE);
} else $projectinfo->update_avail($project,PROJ_PROOF_SECOND_AVAILABLE);

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
   if ($proofstate==PROJ_PROOF_FIRST_AVAILABLE)
   {$wTime="round1_time";
    $wState=SAVE_FIRST;}
   else {$wTime="round2_time";
         $wState=SAVE_SECOND;}
$proofdate=mysql_query("SELECT $wTime FROM $project WHERE state='$wState' ORDER BY $wTime DESC LIMIT 1");
  if (mysql_num_rows($proofdate)!=0)
     {$lastproofed=date("l, F jS, Y \a\\t g:i:sA",mysql_result($proofdate,0,$wTime))."&nbsp;&nbsp;&nbsp; (Current Time: ".date("g:i:sA",time()).")";}
  else {$lastproofed="Project has not been proofread in this round.";}
}
include($relPath.'doctype.inc');
echo "$docType\r\n<HTML><HEAD><TITLE> Project Comments</TITLE>";
if (!isset($proofing) && $userP['i_newwin']==1)
{include($relPath.'js_newwin.inc');}
echo "</HEAD><BODY>";
if (!isset($proofing)) {
    include('./projects_menu.inc');
?>
<br><table border=1 width=630><tr><td bgcolor="CCCCCC" align=center><h3><b>

<?
    if ($proofstate==PROJ_PROOF_FIRST_AVAILABLE) {
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

    if (!isset($proofing))
      {
        recentlyproofed($project, $proofstate, $pguser,$userP,0);
        recentlyproofed($project, $proofstate, $pguser,$userP,1);
      }
    echo "<tr><td bgcolor=\"CCCCCC\" colspan=5 align=center><h3>Project Comments</h3></td></tr><tr><td colspan=5>";
    echo "Follow the <a href=\"$siteurl/faq/document.html\">Document Guidelines 2.00</a> for detailed project formatting directions.";
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
