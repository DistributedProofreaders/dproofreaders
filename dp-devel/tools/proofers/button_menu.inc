<?
include_once($relPath.'v_site.inc');
include_once($relPath.'prefs_options.inc');
include_once($relPath.'button_defs.inc');
include_once('page_misc.inc');

?>
<script language="javascript">
function disableEnterKey() { 
	if (window.event.keyCode == 13) window.event.keyCode = 0;
}
</script>
<A
HREF="#" accesskey="," onfocus="scrollImage('left')" onclick="scrollImage('left')"></A><A
HREF="#" accesskey="." onfocus="scrollImage('right')" onclick="scrollImage('right')"></A><A
HREF="#" accesskey="'" onfocus="scrollImage('up')" onclick="scrollImage('up')"></A><A
HREF="#" accesskey="/" onfocus="scrollImage('down')" onclick="scrollImage('down')"></A><select
name="fntFace" ID="fntFace"  class="dropsmall" title="Change Font Face" onChange="top.chFFace(this.selectedIndex)"><?PHP
  $ff=$userP['i_layout']==1? $userP['v_fntf']:$userP['h_fntf'];
//  $f_s= array('Font Face','Courier','Times','Arial','Lucida','Monospaced','DPCustomMono2');
  $ff_len = count($f_f);
  for ($i=0;$i<$ff_len;$i++)
    {$optP="<option \r\n value=\"$i\"";
  if ($i==$ff)
    {$optP.=" selected";}
  $optP.=">$f_f[$i]</option>";
  echo $optP;}
?></select><select
name="fntSize" ID="fntSize"  class="dropsmall" title="Change Font Size" onChange="top.chFSize(this.selectedIndex)"><?PHP
  $fs=$userP['i_layout']==1? $userP['v_fnts']:$userP['h_fnts'];
//  $f_f= array('Size','8pt','9pt','10pt','11pt','12pt','13pt','14pt','15pt','16pt','18pt','20pt');
  $fs_len = count($f_s);
  for ($i=0;$i<$fs_len;$i++)
    {$optP="<option \r\n value=\"$i\"";
  if ($i==$fs)
    {$optP.=" selected";}
  $optP.=">$f_s[$i]</option>";
  echo $optP;}
?></select>
<input
type="text" value="<?PHP
  if ($userP['i_layout']==1)
    {echo $userP['v_zoom'];}
  else{echo $userP['h_zoom'];}
?>" name="zmSize" ID="zmSize" class="boxnormal"  align="bottom" size="3" title="Input Zoom %" onKeyPress="disableEnterKey()"><?

echo_button(SET_IMAGE_ZOOM,'a');
echo "\n";
//comment out zoom actual button for time being
//echo_button(SET_IMAGE_ACTUAL,'a');

?>
<a href="#"
onclick="top.proofframe.imageframe.document.scanimage.src='<? echo $projects_url ?>/'+document.editform.projectname.value+'/'+document.editform.imagefile.value"><?  echo_img(REFRESH_IMAGE,'a'); ?></a>
<?

echo_button(REVERT_TO_ORIGINAL,'a');
echo "\n";

  if ($npage['revert']==1 || $npage['saved']==1)
  {
    echo_button(REVERT_TO_LAST_SAVE,'a');
  }
  else
  {
    echo_img(REVERT_TO_LAST_SAVE_DISABLED,'a');
  }

?>
<a href="#" onclick="top.showNW()"><? echo_img(SHOW_ALL_TEXT,'a'); ?></a>
<a
href="<? echo project_comments_url($npage); ?>" TARGET="viewcomments"><? echo_img(PROJECT_COMMENTS,'a'); ?></a>
<?

echo_button(SPELL_CHECK,'a');
/*
temp disabled
echo "\n";
echo_button(RUN_COMMON_ERRORS_CHECK,'a');
*/
echo "\n";
echo_button(CHANGE_LAYOUT,'a');
echo "\n";
echo_button(RETURN_PAGE,'a');
echo "\n";

if (ReportBadPage_button_should_be_shown($npage,$pguser))
{
    echo_button(REPORT_BAD_PAGE,'a');
    echo "\n";
}

echo_button(SAVE_AS_IN_PROGRESS,'a');
echo "\n";

  if($npage['editone']==0)
  {
    echo_button(SAVE_AS_DONE_AND_PROOF_NEXT,'a');
    echo "\n";
  }

echo_button(SAVE_AS_DONE_AND_QUIT,'a');
echo "\n";
echo_button(QUIT,'a');
?>
