<html><head><TITLE>Proofing Page</TITLE>
<SCRIPT LANGUAGE="JavaScript" SRC="dpproof.js" TYPE="text/javascript"></SCRIPT>
</HEAD><BODY 
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
echo $sHidFS.$proofstate.$sHidFM.'proofstate'.$sHidFM2.'proofstate'.$sHidFE;
echo $sHidFS.$project.$sHidFM.'projectname'.$sHidFM2.'projectname'.$sHidFE;
echo $sHidFS.$lang.$sHidFM.'lang'.$sHidFM2.'lang'.$sHidFE;
if (isset($editone)) { echo $sHidFS.$editone.$sHidFM.'editone'.$sHidFM2.'editone'.$sHidFE;}
if (isset($saved)) { echo $sHidFS.$saved.$sHidFM.'saved'.$sHidFM2.'saved'.$sHidFE;}
?>
<TABLE 
CELLPADDING="0" CELLSPACING="0">
<TR><TD COLSPAN="5" ALIGN="center" VALIGN="bottom">
<?PHP
include('button_menu.inc');
?>
</TD></TR><TR>
<TD COLSPAN="5" ALIGN="center" VALIGN="top"><?PHP
if ($userP['i_layout']==1)
{$tcols=$userP['v_tchars'];$trows=$userP['v_tlines'];$twrap=$userP['v_twrap'];}
else {$tcols=$userP['h_tchars'];$trows=$userP['h_tlines'];$twrap=$userP['h_twrap'];}
$twrap=$twrap==1? '': " wrap=\"off\"";
echo "<textarea name=\"text_data\" COLS=\"$tcols\" ROWS=\"$trows\" onSelect=\"getCurSel()\" 
onClick=\"getCurCaret()\" onKeyup=\"getCurCaret()\"$twrap>";
// depends on prooflevel
if ($proofstate<9)
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
    if ($proofstate > 9 || isset($saved)) {
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
    if ($proofstate > 9) {
        $result = mysql_query("SELECT round1_user FROM $project WHERE image = '$imagefile' AND fileid='$fileid'");
        $username = mysql_result($result, 0, "round1_user");
        $result = mysql_query("SELECT user_id FROM phpbb_users WHERE username = '$username'");
        $user_id = mysql_result($result, 0, "user_id");   
        echo " - Proofed by: <a href=\"../../phpBB2/privmsg.php?mode=post&u=".$user_id."\" target=\"comments\">".$username."</a>"; 
    }
echo "</B></FONT>";
// include tag bar
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
echo " | <a \r\nhref=\"#\" onclick=\"mNA()\" title=\"Extended Latin-1 Character Set\">More</A>";
//Greek transliterator
echo " | <a \r\nhref=\"#\" onclick=\"mGR()\" title=\"Greek-to-ASCII Transliteration\">Greek</A></FONT>";
?>
</TD></TR></TABLE></FORM></CENTER></BODY></HTML>