<html><head><TITLE>Proofing Page</TITLE>
<SCRIPT LANGUAGE="JavaScript" SRC="dpproof.js" TYPE="text/javascript"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">
<!-- 
<?PHP
echo "newFntS=$fntS;\r\n";
echo "newFntF=$fntF;\r\n";
echo "newZmSize=$zmSize;\r\n";

?>
//  -->
</SCRIPT></head><BODY 
BGCOLOR="#CCCCCC" TEXT="#000000" LINK="#000000" VLINK="#000000" 
ALINK="#000000" TOPMARGIN="0" onLoad="doBU()"><CENTER><FORM NAME="editform" ID="editform" 
METHOD="POST" ACTION="processtext.php" TARGET="_top">
<?PHP
// set values for hidden form fields
$sHidFS="<INPUT \nTYPE=\"hidden\" VALUE=\"";
$sHidFM="\" NAME=\"";
$sHidFM2="\" ID=\"";
$sHidFE="\">";
echo $sHidFS.$imagefile.$sHidFM.'imagefile'.$sHidFM2.'imagefile'.$sHidFE;
echo $sHidFS.$fileid.$sHidFM.'fileid'.$sHidFM2.'fileid'.$sHidFE;
echo $sHidFS.$prooflevel.$sHidFM.'prooflevel'.$sHidFM2.'prooflevel'.$sHidFE;
echo $sHidFS.$project.$sHidFM.'projectname'.$sHidFM2.'projectname'.$sHidFE;
echo $sHidFS.$orient.$sHidFM.'orient'.$sHidFM2.'orient'.$sHidFE;
echo $sHidFS.$lang.$sHidFM.'lang'.$sHidFM2.'lang'.$sHidFE;
echo $sHidFS.$js.$sHidFM.'js'.$sHidFM2.'js'.$sHidFE;
echo $sHidFS.$fntF.$sHidFM.'fntF'.$sHidFM2.'fntF'.$sHidFE;
echo $sHidFS.$fntS.$sHidFM.'fntS'.$sHidFM2.'fntS'.$sHidFE;
//echo $sHidFS.$zmSize.$sHidFM.'zmSize'.$sHidFM2.'zmSize'.$sHidFE;
echo $sHidFS.$sTags.$sHidFM.'sTags'.$sHidFM2.'sTags'.$sHidFE;
if (isset($editone)) { echo $sHidFS.$editone.$sHidFM.'editone'.$sHidFM2.'editone'.$sHidFE;}
?>
<TABLE 
CELLPADDING="0" CELLSPACING="0">
<TR><TD COLSPAN="5" ALIGN="center" VALIGN="bottom">
<?PHP
include('button_menu.inc');
?>
</TD></TR><TR>
<TD COLSPAN="5" ALIGN="center" VALIGN="top"><textarea 
name="text_data" ID="text_data" COLS="70" ROWS="<?PHP
if ($orient=='vert') {if ($lang==1) {echo "38";}else {echo "36";}}
else {echo "6";}?>" onSelect="getCurSel()" 
onClick="getCurCaret()" onKeyup="getCurCaret()" WRAP="off">
<?PHP
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
?></TEXTAREA><BR><?PHP 
//display page number
echo "<FONT SIZE=\"-1\"><B>Page Number: $pageNum";
    if ($prooflevel != 0) {
        $result = mysql_query("SELECT round1_user FROM $project WHERE image = '$imagefile'");
        $username = mysql_result($result, 0, "round1_user");
        $result = mysql_query("SELECT user_id FROM phpbb_users WHERE username = '$username'");
        $user_id = mysql_result($result, 0, "user_id");   
        echo " - Proofed by: <a href=\"../../phpBB2/privmsg.php?mode=post&u=".$user_id."\" target=\"comments\">".$username."</a>"; 
    }
echo "</B></FONT>";
// include tag bar
if ($sTags)
{

echo "<BR>";
include('ptags.inc');

// need to use $lang to determine non-ascii display
// right now, it is on(2)/off(1), but could be lang specific
if ($lang==2)
{echo '<BR>';
include('pnonascii.inc');}
else {
//space holder for more langs
}

//this is to finish off any lang
echo " | <A \nHREF=\"#\" onclick=\"mNA()\">More</A></FONT>";
}
?>
</TD></TR></TABLE></FORM></CENTER></BODY></HTML>