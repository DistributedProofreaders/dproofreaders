<?
$relPath="./../../pinc/";
include($relPath.'dp_main.inc');
/* $_GET $project, $prooflevel, $proofing */

    $result = mysql_query("SELECT nameofwork, authorsname, comments, username FROM projects WHERE projectid = '$project'");
    $nameofwork = mysql_result($result, 0, "nameofwork");
    $authorsname = mysql_result($result, 0, "authorsname");
    $comments = mysql_result($result, 0, "comments");
    $username = mysql_result($result, 0, "username");
if (isset($proofing))
{   $phpuser = mysql_query("SELECT user_id FROM phpbb_users WHERE username = '$pguser'");
   $user_id = mysql_result($phpuser, 0, "user_id");
}
if (isset($prooflevel)){
   if ($prooflevel==0)
   {$wTime="round1_time";
    $wState=8;}
   else {$wTime="round2_time";
         $wState=18;}
$proofdate=mysql_query("SELECT $wTime FROM $project WHERE state='$wState' ORDER BY $wTime DESC LIMIT 1");
  if (mysql_num_rows($proofdate)!=0)
     {$lastproofed=date("l, F jS, Y \a\\t g:i:sA",mysql_result($proofdate,0,$wTime))."&nbsp;&nbsp;&nbsp; (Current Time: ".date("g:i:sA",time()).")";}
  else {$lastproofed="Project has not been proofread in this round.";}
}
include($relPath.'doctype.inc');
echo "$docType\r\n<HTML><HEAD><TITLE> Project Comments</TITLE>";
if (!isset($proofing))
{
?>
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">
<!-- 
function newProofWin(winURL)
{
newWidth=600;newHeight=450;
iCan=(window.ScriptEngine) ? (ScriptEngine().indexOf("InScript") != -1) : false;
if (!iCan)
{sw=screen.width;
if (sw)
{newWidth=screen.width-20;
newHeight=((newWidth-40) * 75)/100;}
newFeatures="'toolbars=0,location=0,directories=0;status=0;menubar=0,scrollbars=1,resizable=1,width="+newWidth+",height="+newHeight+",top=0,left=5'";
nwWin=window.open(winURL,"prooferWin",newFeatures);}
else {alert('This interface does not currently support the iCab browser.\r\nPlease use the standard proofing interface.');}}
// -->
</SCRIPT>
<?PHP
}
?>
</HEAD><BODY>
<?PHP
if (!isset($proofing))
{include('./projects_menu.inc');
?>

<br>
Check out <a href="http://texts01.archive.org/dp/faq/document.html">Document Guidelines 1.20</a> for detailed project formatting comments. <BR>Instructions in Project Comments below take precedence over the guidelines.

<P><table border=1 width=630><tr>
<tr><td bgcolor="CCCCCC" align=center><h3><b>

<?
    if ($prooflevel == 0) {
        echo "First Round Project</b></h3></td><td bgcolor = \"CCCCCC\"><b>This is a First-Round project, these files are output from the OCR software and have not been looked at.</b></td></tr>";
    } else {
        echo "Second Round Project</b></h3></td><td bgcolor = \"CCCCCC\"><b>These are files that have already been proofed once, but now need to be examined <B>closely</B> for small errors that may have been missed. See <A HREF=\"http://www.promo.net/pg/vol/proof.html#What_kinds\" target = \" \">this page</A> for examples.</b></td>";
    }
}
else {
?>
<table border=1 width=630>
<?PHP
}

    echo "<tr><td bgcolor=\"CCCCCC\" align=\"center\"><b>Name of Work</b></td>";
    echo "<td>$nameofwork</td></tr>";
    echo "<tr><td bgcolor=\"CCCCCC\" align=\"center\"><b>Author</b></td>";
    echo "<td>$authorsname</td></tr>";
    echo "<tr><td bgcolor=\"CCCCCC\" align=\"center\"><b>Project Manager</b></td>";
    echo "<td>$username</td></tr>";
if (isset($prooflevel))
{    echo "<tr><td bgcolor=\"CCCCCC\" align=\"center\"><b>Last Proofread</b></td>";
    echo "<td>$lastproofed</td></tr>";}
if (!isset($proofing))
  {  echo "<tr><td bgcolor = \"CCCCCC\" align=center><h3>Project Comments</h3></td><td>$comments</td></tr></table>";}
else {echo "<tr><td bgcolor=\"CCCCCC\" align=center><b>Forum</b></td>";
      echo "<td><a href=\"project_topic.php?project=$project&amp;user_id=$user_id&amp;action=c\">Discuss this Project in the Forum</td></tr>";
      echo "<tr><td bgcolor = \"CCCCCC\" colspan=\"2\" align=\"center\"><h3>Project Comments</h3></td></tr><tr><td colspan=\"2\">$comments</td></tr></table>";
      echo"<p><p><b> This information has been opened in a separate browser window, feel free to leave it open for reference or close it.</b>";
     }
echo "<BR>";
if (!isset($proofing))
{include('./projects_menu.inc');}
?>
</BODY></HTML>
