<?PHP
include_once($relPath.'v_site.inc');
include_once($relPath.'doctype.inc');
include_once($relPath.'c_pages.inc');
include_once($relPath.'button_defs.inc');
include_once($relPath.'prefs_options.inc');
$tpage=new processpage();
$lang=isset($lang)?$lang:1;

// get cookie
$npage=$tpage->getPageCookie();

// set page
$tpage->setPageState($npage['pagestate'],$npage['project'],$npage['fileid'],$npage['image'],$npage['proofstate']);

// get page text
$npage['text']=isset($inCheck)?$correct_text:$tpage->getText($npage['pagestate'],$npage['saved'],$npage['revert']);

// get total pages available for proofing
$result = mysql_query("SELECT total_pages FROM page_counts WHERE projectid = '".$npage['project']."'");
$total_pages = mysql_result($result, 0, "total_pages");

// get bad page code
      $result = mysql_query("SELECT b_code FROM {$npage['project']} WHERE image = '{$npage['image']}' AND fileid='{$npage['fileid']}'");
      $b_code = mysql_result($result, 0, "b_code");

if ($npage['spcheck']==2)
  {
    $text_file= $npage['project'].substr($npage['image'],0,-4).".txt";
    $npage['spcheck']=0;
    $tpage->setTempPageCookie($npage);
    $npage['text'] = implode( '', file("$aspell_temp_dir/$text_file") );
  }
echo $docType."\r\n";
?>
<html>
<head><TITLE>Text Frame</TITLE></head>
<body onload="top.menuframe.ldAll(0)">
<center>
<form name="editform" method="post" action="processtext.php" target="proofframe">
<script language="JavaScript" type="text/javascript">
	<!--
	function resize(newsize)
	{
		parent.imageframe.document.scanimage.width=newsize;
	}
	//-->
</script>
<?
// display text area
  // choose horizontal or vertical settings
  if ($userP['i_layout']==1)
  {
    $tcols = $userP['v_tchars'];
    $trows = $userP['v_tlines'];
    $twrap = $userP['v_twrap'];
    $tfonti = $userP['v_fntf'];
    $tfont = $f_f[$tfonti];
  }
  else
  {
    $tcols = $userP['h_tchars'];
    $trows = $userP['h_tlines'];
    $twrap = $userP['h_twrap'];
    $tfonti = $userP['h_fntf'];
    $tfont = $f_f[$tfonti];
  }
  // choose general settings
  $twrap = $twrap==1? '': " wrap=\"off\"";
  // write the textarea
  echo "<textarea name=\"text_data\" COLS=\"$tcols\" ROWS=\"$trows\" $twrap";
  if ($tfont != '')
  {
    echo ' style="font-family: ' . $tfont . '"';
  }
  echo ">";
  // SENDING PAGE-TEXT TO USER
  // We're sending it in an HTML document, so encode special characters.
  echo htmlspecialchars($npage['text'],ENT_NOQUOTES);
?></textarea>
<BR>
<?
// set values for hidden form fields
  $sHidFS="<input type=\"hidden\" value=\"";
  $sHidFM="\" name=\"";
  $sHidFM2="\" id=\"";
  $sHidFE="\">\n";
  echo $sHidFS.$npage['image'].$sHidFM.'imagefile'.$sHidFM2.'imagefile'.$sHidFE;
  echo $sHidFS.$npage['fileid'].$sHidFM.'fileid'.$sHidFM2.'fileid'.$sHidFE;
  echo $sHidFS.$npage['proofstate'].$sHidFM.'proofstate'.$sHidFM2.'proofstate'.$sHidFE;
  echo $sHidFS.$npage['pagestate'].$sHidFM.'pagestate'.$sHidFM2.'pagestate'.$sHidFE;
  echo $sHidFS.$npage['project'].$sHidFM.'projectname'.$sHidFM2.'projectname'.$sHidFE;
  echo $sHidFS.$lang.$sHidFM.'lang'.$sHidFM2.'lang'.$sHidFE;

	echo_button(SAVE_AS_IN_PROGRESS,'s');
	if (!isset($editone))
	{
		echo_button(SAVE_AS_DONE_AND_PROOF_NEXT,'s');
	}
	echo_button(SAVE_AS_DONE_AND_QUIT,'s');
	echo_button(QUIT,'s');

	echo "<BR>\n";

	echo_button(CHANGE_LAYOUT,'s');
	echo_button(RETURN_PAGE,'s');
	if ((($npage['proofstate'] == PROJ_PROOF_FIRST_AVAILABLE) && ($totalpages >= 50)) || ($b_code > 0))
	{
		echo_button(REPORT_BAD_PAGE,'s');
	}

	echo "<BR>\n";

	echo_button(SPELL_CHECK,'s');

	echo "<br>\n";

//display page number
  $pageNum=substr($npage['image'],0,-4);
  echo "<font size=\"-1\"><B>Page Number: $pageNum/$total_pages";
    if ($tpage->getProjectRound($npage['proofstate']) == 2)
    {
      $result = mysql_query("SELECT round1_user FROM {$npage['project']} WHERE image = '{$npage['image']}' AND fileid='{$npage['fileid']}'");
      $username = mysql_result($result, 0, "round1_user");
      $result = mysql_query("SELECT user_id FROM phpbb_users WHERE username = '$username'");
      $user_id = mysql_result($result, 0, "user_id");
      echo " - Proofed by: <a href=\"$forums_url/privmsg.php?mode=post&u=".$user_id."\" target=\"comments\">".$username."</a>";
    }
  echo "</b></font>\n";
  echo "<BR>\n";
  echo "View: <a href=\"projects.php?project={$npage['project']}&amp;proofstate={$npage['proofstate']}&amp;proofing=1\" target=\"viewcomments\">Project Comments</a> ";
  echo "| <a href=\"$projects_url/{$npage['project']}/{$npage['image']}\" target=\"lg_image\">Image</a> ";
?>
<br>
Image Resize:
<input type="button" value="50%" onclick="resize(690)">
<input type="button" value="100%" onclick="resize(1000)">
<input type="button" value="200%" onclick="resize(2000)">

</form>
</center>
</body>
</html>
