<?
include($relPath.'v_site.inc');
include($relPath.'button_defs.inc');

// get bad page code
      $result = mysql_query("SELECT b_code FROM {$npage['project']} WHERE image = '{$npage['image']}' AND fileid='{$npage['fileid']}'");
      $b_code = mysql_result($result, 0, "b_code");
      ?>
<A
HREF="#" accesskey="," onfocus="scrollImage('left')" onclick="scrollImage('left')"></A><A
HREF="#" accesskey="." onfocus="scrollImage('right')" onclick="scrollImage('right')"></A><A
HREF="#" accesskey="'" onfocus="scrollImage('up')" onclick="scrollImage('up')"></A><A
HREF="#" accesskey="/" onfocus="scrollImage('down')" onclick="scrollImage('down')"></A><select
name="fntFace" ID="fntFace"  class="dropsmall" title="Change Font Face" onChange="top.menuframe.chFFace(this.selectedIndex)"><?PHP
  $fs=$userP['i_layout']==1? $userP['v_fntf']:$userP['h_fntf'];
  $f_s= array('Font Face','Courier','Times','Arial','Lucida','Monospaced');
    for ($i=0;$i<6;$i++)
    {$optP="<option \r\n value=\"$i\"";
    if ($i==$fs){$optP.=" selected";}
  $optP.=">$f_s[$i]</option>";
  echo $optP;}
?></select><select
name="fntSize" ID="fntSize"  class="dropsmall" title="Change Font Size" onChange="top.menuframe.chFSize(this.selectedIndex)"><?PHP
  $ff=$userP['i_layout']==1? $userP['v_fnts']:$userP['h_fnts'];
  $f_f= array('Size','8pt','9pt','10pt','11pt','12pt','13pt','14pt','15pt','16pt','18pt','20pt');
  for ($i=0;$i<12;$i++)
    {$optP="<option \r\n value=\"$i\"";
  if ($i==$ff){$optP.=" selected";}
  $optP.=">$f_f[$i]</option>";
  echo $optP;}
?></select>&nbsp;<input
type="text" value="<?PHP
  if ($userP['i_layout']==1)
    {echo $userP['v_zoom'];}
  else{echo $userP['h_zoom'];}
?>" name="zmSize" ID="zmSize" class="boxnormal"  align="bottom" size="3" title="Input Zoom %"><?
echo_button(SET_IMAGE_ZOOM,'a');
?>&nbsp;<a href="#"
onclick="top.proofframe.imageframe.document.scanimage.src='<? echo $projects_url ?>/'+document.editform.projectname.value+'/'+document.editform.imagefile.value"><img
src="gfx/bt6.png" align="top" alt="Refresh Image" title="Refresh Image" border="0" width="26" height="26"></a>&nbsp;<?
echo_button(REVERT_TO_ORIGINAL,'a');
?>&nbsp;<?PHP
  if ($npage['revert']==1 || $npage['saved']==1)
  {
    echo_button(REVERT_TO_LAST_SAVE,'a');
  }
  else
  {
    echo "<img src=\"gfx/bt7_n.png\" align=\"top\" alt=\"Revert to Last Save Disabled\" title=\"Revert to Last Save Disabled\" border=\"0\" height=\"26\" width=\"26\">";
  }
?>&nbsp;<a href="#" onclick="top.menuframe.showNW()"><img
src="gfx/bt9.png" width="26" height="26" border="0" align="top" alt="Show All Text" title="Show All Text"></a>&nbsp;<a
href="projects.php?project=<?PHP echo "{$npage['project']}&amp;proofstate={$npage['proofstate']}"; ?>&amp;proofing=1" TARGET="viewcomments"><img
src="gfx/bt12.png" width="26" height="26" border="0" align="top" alt="Project Comments" title="Project Comments"></a>&nbsp;<?
echo_button(SPELL_CHECK,'a');
?><?PHP
/*
temp disabled
&nbsp;<?
echo_button(RUN_COMMON_ERRORS_CHECK,'a');
?>
*/
?>&nbsp;<?
echo_button(CHANGE_LAYOUT,'a');
?>&nbsp;<?
echo_button(RETURN_PAGE,'a');
?>&nbsp;<?
if ((($npage['proofstate'] == PROJ_PROOF_FIRST_AVAILABLE) && ($totalpages >= 50)) || ($b_code > 0)) { echo_button(REPORT_BAD_PAGE,'a'); echo "&nbsp;"; }
?><?
echo_button(SAVE_AS_IN_PROGRESS,'a');
?>&nbsp;<?PHP
  if($npage['editone']==0)
  {
    echo_button(SAVE_AS_DONE_AND_PROOF_NEXT,'a'); echo '&nbsp;';
  }
?><?
echo_button(SAVE_AS_DONE_AND_QUIT,'a');
?>&nbsp;<?
echo_button(QUIT,'a');
?>
