<?
$relPath="./../../pinc/";
include($relPath.'dp_main.inc');
    $imagefile = ptog('imagefile');
    $fileid = ptog('fileid');
    $prooflevel = ptog('prooflevel');
    $button1 = ptog('button1');
    $button2 = ptog('button2');
    $button3 = ptog('button3');
    $button4 = ptog('button4');
    $project = ptog('projectname');
    $text_data = ptog('text_data');
    $text_data = strip_tags($text_data, '<i>');
    $orient = ptog('orient');
    $lang=ptog('lang');
    $js = ptog('js');
    $button1_x = ptog('button1_x');
    $button2_x = ptog('button2_x');
    $button3_x = ptog('button3_x');
    $button4_x = ptog('button4_x');

if ($js==1)
{
$fntF=ptog('fntF');
$fntS=ptog('fntS');
$sTags=ptog('sTags');
$zmSize=ptog('zmSize');
$fntF=isset($fntF)? $fntF:'0';
$fntS=isset($fntS)? $fntS:'0';
$sTags=isset($sTags)? $sTags:'1';
$zmSize=isset($zmSize)? $zmSize:'100';
//echo $fntF;
$prefTags="&fntF=$fntF&fntS=$fntS&sTags=$sTags&zmSize=$zmSize";
}

function addUserCount($project,$prooflevel,$fileid,$pguser)
{
$sql = "SELECT state FROM $project WHERE fileid='$fileid'";
$result=dquery($sql);
$rows=nrows($result);
  if ($rows !=0)
  {
  $curState=dresult($result,0,'state');
    if (($prooflevel==0 && $curState==5) || ($prooflevel==2 && $curState==15))
    {
    // add to user page count
    $sql = "UPDATE users SET pagescompleted = pagescompleted+1 WHERE username = '$pguser'";
    $result = mysql_query($sql);
    }
  }
}

function isProjectDone($project,$prooflevel)
{
$sql = "SELECT * FROM $project WHERE state='";
$sql.=$prooflevel==0? "2'":"12'";
$sql.=" OR state='";
$sql.=$prooflevel==0? "5'":"15'";
$sql.=" LIMIT 1";
$result = dquery($sql);
$rows=nrows($result);
  if ($rows == 0)
  {
  if ($prooflevel == 0) { $newstate = 8; } else { $newstate = 18; }
  $result = dquery("UPDATE projects SET state = '$newstate' WHERE projectid = '$project'");
  }
}

function savePage($project,$prooflevel,$fileid,$text_data,$pguser)
{
$timestamp = time();
$dbQuery="UPDATE $project SET state='";
  if ($prooflevel==2)
  {$dbQuery.="18', round2_text='$text_data', round2_time='$timestamp', round2_user='$pguser'";}
  else {$dbQuery.="8', round1_text='$text_data', round1_time='$timestamp', round1_user='$pguser'";}
$dbQuery.=" WHERE fileid='$fileid'";
$result = dquery($dbQuery);
}

function isOpenProject($project,$prooflevel)
{
$result = dquery("SELECT state FROM projects WHERE projectid = '$project'");
$curState=dresult($result,0,'state');
  if (($prooflevel ==0 && $curState < 9) || ($prooflevel ==2 && $curState < 19))
  {return 1;}
  else {return 0;}
}

// see if project is still in an open state for proofing level
$isOpen=isOpenProject($project,$prooflevel);
if (!$isOpen)
{
  if ($js==0)
  {include($relPath.'doctype.inc');
  echo "$docType\r\n<HTML><HEAD><TITLE>Project Round Complete</TITLE>";
  echo "<META HTTP-EQUIV=\"refresh\" CONTENT=\"4 ;URL=proof_per.php\" TARGET=\"_top\"></HEAD><BODY>";
  echo "No more files available for proofing for this round of the project.<BR> You will be taken back to the project page in 4 seconds.";
  echo "</BODY></HTML>";
  exit;}
  else {
  echo "$docType\r\n<HTML><HEAD><TITLE>Project Round Complete</TITLE></HEAD><BODY>";
  echo "No more files available for proofing in this round of the project.<BR>";
  echo "Please <A HREF=\"#\" onclick=\"window.close()\">click here</A> to close the proofing window.";
  echo "</BODY></HTML>";
  exit;}
}
// see which button they pressed
// buttons which save
if (($button1 != "") || ($button2 != "") || isset($button1_x) || isset($button2_x))
{
addUserCount($project,$prooflevel,$fileid,$pguser);
savePage($project,$prooflevel,$fileid,$text_data,$pguser);
isProjectDone($project,$prooflevel);
} // end save page


// save and restore image to edit view
if ((@$button1 != "") || isset($button1_x) || isset($button4_x) || (@$button4 !=""))
{
$project = 'project='.$project;
$fileid = '&fileid='.$fileid;
$imagefile = '&imagefile='.$imagefile;
$prooflevel = '&prooflevel='.$prooflevel;
$js='&js='.$js;
$lang='&lang='.$lang;
$saved='&saved=1';
  if ($button4 != "" || isset($button4_x))
  {
  $orient=$orient=='vert'? $orient='hrzn':$orient='vert';
  } // end change layout button 4
$orient = '&orient='.$orient;
$frame1 = 'proof.php?'.$project.$fileid.$imagefile.$prooflevel.$orient.$lang.$js.$saved;
  if ($js==1) {$frame1=$frame1.$prefTags;}
echo "<HTML><HEAD><META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=$frame1\"></HEAD><BODY>"; 

} // end save and continue same page button 1 & button 4

// save and do another send back to proof.php for a new page
if ($button2 != "" || isset($button2_x))
{
$project = 'project='.$project;
$prooflevel = '&prooflevel='.$prooflevel;
$js='&js='.$js;
$lang='&lang='.$lang;
$orient = '&orient='.$orient;
$frame1 = 'proof.php?'.$project.$prooflevel.$orient.$lang.$js;
  if ($js==1) {$frame1=$frame1.$prefTags;}
echo "<HTML><HEAD><META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=$frame1\" TARGET=\"_top\"></HEAD><BODY>"; 
} // end save and do another button 2

// if quit without saving send back to projects page
if ($button3 != "")
{
$dbQuery="UPDATE $project SET state='";
$dbQuery.=$prooflevel==2?"12":"2";
$dbQuery.="WHERE fileid = '$fileid'";
$result = mysql_query($dbQuery);
echo "<HTML><HEAD><META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=proof_per.php\" TARGET=\"_top\"></HEAD><BODY>"; 
} // end button 3 quit
?>
<script language="JavaScript">
<!--
  javascript:window.history.forward(1);
//-->
</script>
</BODY></HTML>
