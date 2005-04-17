<?PHP
include_once($relPath.'http_headers.inc');
include_once($relPath.'v_site.inc');
include_once($relPath.'c_pages.inc');
include_once($relPath.'button_defs.inc');
include_once($relPath.'slim_header.inc');
include_once('textarea.inc');
include_once('page_misc.inc');

// get cookie
$npage = getPageCookie();

// get page text
$npage['text']=isset($inCheck)?$correct_text:nPage_getText($npage);

if ($npage['spcheck']==2)
  {
    $text_file= $npage['project'].substr($npage['image'],0,-4).".txt";
    $npage['spcheck']=0;
    setTempPageCookie($npage);
    $npage['text'] = implode( '', file("$aspell_temp_dir/$text_file") );
  }


slim_header("Text Frame",TRUE,FALSE);
?>
</head>
<body onload="top.initializeStuff(0)">
<center>
<form name="editform" method="post" action="processtext.php" target="proofframe">
<script language="JavaScript" type="text/javascript">
	<!--
	function resize(factor)
	{
	if (factor == -1)
	{
		parent.imageframe.document.scanimage.width=1000;
	}
	else
	{
		currentsize=parent.imageframe.document.scanimage.width;
		newsize=currentsize*factor;
		parent.imageframe.document.scanimage.width=newsize;
	}
	}
	//-->
</script>
<?
  echo_proofing_textarea( $npage['text'], FALSE );
?>
<BR>
<?
	echo_hidden_fields($npage);

	echo_button(SAVE_AS_IN_PROGRESS,'s');
	if (!isset($editone))
	{
		echo_button(SAVE_AS_DONE_AND_PROOF_NEXT,'s');
	}
	echo_button(SAVE_AS_DONE_AND_QUIT,'s');
	echo_button(QUIT,'s');

	echo "<br>\n";

	echo_button(CHANGE_LAYOUT,'s');
	echo_button(RETURN_PAGE,'s');

	if ( ReportBadPage_button_should_be_shown($npage,$pguser) )
	{
		echo_button(REPORT_BAD_PAGE,'s');
	}

	echo "<br>\n";

	echo_button(SPELL_CHECK,'s');

	echo "<br>\n";

	echo_page_info($npage);

  echo "&nbsp;";
  $comments_url = project_comments_url($npage);
  echo _("View:")." <a href=\"$comments_url\" title=\"". _("View Project Comments in New Window")."\" target=\"viewcomments\">"._("Project Comments")."</a> ";
  $image_url = page_image_url($npage);
  echo "| <a href=\"$image_url\" title=\"". _("View Image in New Window")."\" target=\"lg_image\">"._("Image")."</a> ";
?>
<br>
<? echo _("Image Resize:"); ?>
<input title="<? echo _("Zoom Out 25%"); ?>" type="button" value="-25%"  onclick="resize(0.75)">
<input title="<? echo _("Zoom In 25%"); ?>" type="button" value="+25%" onclick="resize(1.25)">
<input title="<? echo _("Zoom to Original Size"); ?>" type="button" value="Original" onclick="resize(-1)">

</form>
</center>
</body>
</html>
