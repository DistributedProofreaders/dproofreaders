<html><head><TITLE></TITLE></head><body><center><form method="post" action="processtext.php" target="_top">
<script language="JavaScript">
<!--
  javascript:window.history.forward(1);

function resize(newsize)
{
  parent.imageframe.document.scanimage.width=newsize;
}
//-->
</script>
<?
    if ($orient == "vert") {?>
<textarea name="text_data" COLS="65" ROWS="30" WRAP="no"><?  } else {?>
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
$dbQuery="SELECT $wText FROM $project WHERE fileid = '$fileid'";
    $result = mysql_query($dbQuery);
    $myrow = mysql_fetch_row($result);
    $text_data = mysql_result($result, 0, $wText);
    if ($prooflevel != 0) {
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
?>
<INPUT TYPE="Submit" accesskey="7" VALUE="Save" name="button1"> <?PHP 
if (!isset($editone)){echo "<INPUT TYPE=\"Submit\" accesskey=\"8\" VALUE=\"Save and Do Another\" name=\"button2\"> ";}
?><INPUT TYPE="Submit" accesskey="9" VALUE="Quit" name="button3"><BR>
<?
    echo "View: <a href=\"viewprojectcomments.php?project=$project\" target=\"comments\">Project Comments</a> ";
    echo "<a href=\"../../projects/$project/$imagefile\" target=\"lg_image\">Image</a>";

    if ($prooflevel != 0) {
        $result = mysql_query("SELECT round1_user FROM $project WHERE image = '$imagefile'");
        $username = mysql_result($result, 0, "round1_user");
        $result = mysql_query("SELECT user_id FROM phpbb_users WHERE username = '$username'");
        $user_id = mysql_result($result, 0, "user_id");
        echo " Proofed by: <a href=\"../../phpBB2/privmsg.php?mode=post&u=".$user_id."\" target=\"comments\">".$username."</a>";
    }
?>
 Image Resize: <input type="button" value="50%" onclick="resize(690)"><input type="button" value="100%" onclick="resize(1000)"><input type="button" value="200%" onclick="resize(2000)"></center>

</form>

</body></html>