<html><head><TITLE></TITLE></head><body><center><form method="post" action="processtext.php" target="_top">
<script language="JavaScript">
<!--
function resize(newsize)
{
  parent.imageframe.document.scanimage.width=newsize;
}
//-->
</script>
<?
    if ($orient == "vert") {?>
<textarea name="text_data" COLS="65" ROWS="42" WRAP="no"><?  } else {?>
<textarea name="text_data" COLS="65" ROWS="6" WRAP="no"><?  }

// depends on prooflevel
if ($prooflevel==0)
{ if (isset($saved))
  {$wText="round1_text";}
  else {$wText="master_text";}
}
else {
  if (isset($saved))
  {$wText="round2_text";}
  else {$wText="round1_text";}
}
$dbQuery="SELECT $wText FROM $project WHERE fileid = '$fileid' AND image='$imagefile'";
    $result = mysql_query($dbQuery);
    $myrow = mysql_fetch_row($result);
    $text_data = mysql_result($result, 0, $wText);
    if ($prooflevel != 0 || isset($saved)) {
        $text_data = strip_tags($text_data, '<i>&');
    } else {
        $search = array("'>'i", "'<'i");
        $replace = array("*", "*");
        $text_data = preg_replace($search, $replace, $text_data);
    }
    echo $text_data;
?></textarea>
<BR>
<?
    echo "<INPUT \r\nTYPE=\"hidden\" VALUE=\"$imagefile\" name=\"imagefile\">";
    echo "<INPUT \r\nTYPE=\"hidden\" VALUE=\"$fileid\" name=\"fileid\">";
    echo "<INPUT \r\nTYPE=\"hidden\" VALUE=\"$prooflevel\" name=\"prooflevel\">";
    echo "<INPUT \r\nTYPE=\"hidden\" VALUE=\"$project\" name=\"projectname\">";
    echo "<INPUT \r\nTYPE=\"hidden\" VALUE=\"$lang\" name=\"lang\">";
    echo "<INPUT \r\nTYPE=\"hidden\" VALUE=\"$js\" name=\"js\">";
    echo "<INPUT \r\nTYPE=\"hidden\" VALUE=\"$orient\" name=\"orient\">";
if (isset($editone)) {    echo "<INPUT \r\nTYPE=\"hidden\" VALUE=\"$editone\" name=\"editone\">";}
if (isset($saved)) {    echo "<INPUT \r\nTYPE=\"hidden\" VALUE=\"$saved\" name=\"saved\">";}
?>
<INPUT TYPE="submit" accesskey="6" onclick="return(confirm('Are you sure you want to \r\nChange Layout\r\n and \r\nSave the Current Document?'));" name="button4" ID="button4" title="Save and Change Interface Layout" VALUE="Switch to <?PHP if ($orient=="vert") {echo "Horizontal";} else {echo "Vertical";} ?>"> 
<INPUT TYPE="Submit" accesskey="7" VALUE="Save" name="button1" title="Save"> <?PHP 
if (!isset($editone)){echo "<INPUT TYPE=\"Submit\" accesskey=\"8\" VALUE=\"Save and Do Another\" name=\"button2\" title=\"Save and Proof Next Page\"> ";}
?><INPUT TYPE="Submit" accesskey="5" VALUE="Save and Quit" name="button5" title="Save and Quit">
<INPUT TYPE="Submit" accesskey="9" onclick="return(confirm('Are you sure you want to \r\n\r\nQuit?'));" VALUE="Quit" name="button3" title="Quit without Saving"><BR>
<?
//display page number
echo "<B>Page Number: $pageNum ";
    if ($prooflevel != 0) {
        $result = mysql_query("SELECT round1_user FROM $project WHERE image = '$imagefile' AND fileid='$fileid'");
        $username = mysql_result($result, 0, "round1_user");
        $result = mysql_query("SELECT user_id FROM phpbb_users WHERE username = '$username'");
        $user_id = mysql_result($result, 0, "user_id");   
        echo " - Proofed by: <a href=\"../../phpBB2/privmsg.php?mode=post&u=".$user_id."\" target=\"comments\">".$username."</a>"; 
    }
echo "</B><BR>";
    echo "View: <a href=\"projects.php?project=$project&amp;prooflevel=$prooflevel&amp;proofing=1\" target=\"viewcomments\">Project Comments</a> ";
    echo "<a href=\"../../projects/$project/$imagefile\" target=\"lg_image\">Image</a>";

?>
 Image Resize: <input type="button" value="50%" onclick="resize(690)"><input type="button" value="100%" onclick="resize(1000)"><input type="button" value="200%" onclick="resize(2000)"></center>

</form>

</body></html>