<?PHP
include($relPath.'doctype.inc');
include($relPath.'c_pages.inc');
$tpage=new processpage();
$lang=isset($lang)?$lang:1;

// get cookie
$npage=$tpage->getPageCookie();

// set page
$tpage->setPageState($npage['pagestate'],$npage['project'],$npage['fileid'],$npage['image'],$npage['proofstate']);

// get page text
$npage['text']=isset($inCheck)?$correct_text:$tpage->getText($npage['pagestate'],$npage['saved'],$npage['revert']);

echo $docType."\r\n";
?>
<html><head><TITLE>Text Frame</TITLE></head><body><center><form method="post" action="processtext.php" target="proofframe">
<script language="JavaScript">
<!--
function resize(newsize)
{
  parent.imageframe.document.scanimage.width=newsize;
}
function mNA()
{
// additional character window
winURL='morenonascii.php';
newFeatures="'toolbars=0,location=0,directories=0;status=0;menubar=0,scrollbars=1,resizable=1,width=460,height=240,top=200,left=200'";
asciiWin=window.open(winURL,"nonasciiWin",newFeatures);
asciiWin.focus();
}
function mGR()
{
// greek character window
winURL='greek2ascii.php';
newFeatures="'toolbars=0,location=0,directories=0;status=0;menubar=0,scrollbars=1,resizable=1,width=620,height=200,top=180,left=180'";
greekWin=window.open(winURL,"grasciiWin",newFeatures);
greekWin.focus();
}
//-->
</script>
<?
// display text area
    if ($userP['i_layout']==1)
    {$tcols=$userP['v_tchars'];$trows=$userP['v_tlines'];$twrap=$userP['v_twrap'];}
    else {$tcols=$userP['h_tchars'];$trows=$userP['h_tlines'];$twrap=$userP['h_twrap'];}
  $twrap=$twrap==1? '': " wrap=\"off\"";
  echo "<textarea name=\"text_data\" COLS=\"$tcols\" ROWS=\"$trows\"$twrap>";
  echo $npage['text'];
?></textarea>
<BR>
<?
// set values for hidden form fields
  $sHidFS="<input \r\ntype=\"hidden\" value=\"";
  $sHidFM="\" name=\"";
  $sHidFM2="\" id=\"";
  $sHidFE="\">";
  echo $sHidFS.$npage['image'].$sHidFM.'imagefile'.$sHidFM2.'imagefile'.$sHidFE;
  echo $sHidFS.$npage['fileid'].$sHidFM.'fileid'.$sHidFM2.'fileid'.$sHidFE;
  echo $sHidFS.$npage['proofstate'].$sHidFM.'proofstate'.$sHidFM2.'proofstate'.$sHidFE;
  echo $sHidFS.$npage['pagestate'].$sHidFM.'pagestate'.$sHidFM2.'pagestate'.$sHidFE;
  echo $sHidFS.$npage['project'].$sHidFM.'projectname'.$sHidFM2.'projectname'.$sHidFE;
  echo $sHidFS.$lang.$sHidFM.'lang'.$sHidFM2.'lang'.$sHidFE;
?>
<INPUT TYPE="Submit" accesskey="7" VALUE="Save" name="button1" title="Save"> <?PHP 
if (!isset($editone)){echo "<INPUT TYPE=\"Submit\" accesskey=\"8\" VALUE=\"Save and Do Another\" name=\"button2\" title=\"Save and Proof Next Page\"> ";}
?><INPUT TYPE="Submit" accesskey="5" VALUE="Save and Quit" name="button5" title="Save and Quit">
<INPUT TYPE="Submit" accesskey="9" onclick="return(confirm('Are you sure you want to \r\n\r\nQuit?'));" VALUE="Quit" name="button3" title="Quit without Saving"><BR>
<INPUT TYPE="submit" accesskey="6" onclick="return(confirm('Are you sure you want to \r\nChange Layout\r\n and \r\nSave the Current Document?'));" name="button4" ID="button4" title="Save and Change Interface Layout" VALUE="Switch to <?PHP if ($userP['i_layout']==1) {echo "Horizontal";} else {echo "Vertical";} ?>"> <INPUT 
TYPE="submit" accesskey="3" name="button7" ID="button7" value="Return Page to Round" onclick="return(confirm('Are you sure you want to \r\nreturn this page to the current round?'));"> <INPUT 
TYPE="submit" accesskey="4" name="button6" ID="button6" value="Report Bad Page" onclick="return(confirm('Are you sure you want to \r\nreport this page as bad?'));"><BR>
<?
//display page number
  $pageNum=substr($npage['image'],0,-4);
  echo "<font size=\"-1\"><B>Page Number: $pageNum";
    if ($tpage->getProjectRound($npage['proofstate']) == 2)
    {
      $result = mysql_query("SELECT round1_user FROM {$npage['project']} WHERE image = '{$npage['image']}' AND fileid='{$npage['fileid']}'");
      $username = mysql_result($result, 0, "round1_user");
      $result = mysql_query("SELECT user_id FROM phpbb_users WHERE username = '$username'");
      $user_id = mysql_result($result, 0, "user_id");   
      echo " - Proofed by: <a href=\"../../phpBB2/privmsg.php?mode=post&u=".$user_id."\" target=\"comments\">".$username."</a>"; 
    }
  echo "</b></font>";
  echo "<BR>";
  echo "View: <a href=\"projects.php?project={$npage['project']}&amp;proofstate={$npage['proofstate']}&amp;proofing=1\" target=\"viewcomments\">Project Comments</a> ";
  echo "Characters: ";
  echo "<a href=\"#\" onclick=\"mNA()\">Latin-1</a> ";
  echo "<a href=\"#\" onclick=\"mGR()\">Greek</a> ";
  echo "<a href=\"../../projects/{$npage['project']}/{$npage['image']}\" target=\"lg_image\">Image</a> ";
?>
<br>
 Image Resize: <input type="button" value="50%" onclick="resize(690)"><input type="button" value="100%" onclick="resize(1000)"><input type="button" value="200%" onclick="resize(2000)">
</form></center></body></html>