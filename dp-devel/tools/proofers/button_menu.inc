<A 
HREF="#" accesskey="," onfocus="dSI('left')"></A><A 
HREF="#" accesskey="." onfocus="dSI('right')"></A><A 
HREF="#" accesskey="'" onfocus="dSI('up')"></A><A 
HREF="#" accesskey="/" onfocus="dSI('down')"></A><select 
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
?>" name="zmSize" ID="zmSize" class="boxnormal"  align="bottom" size="3" title="Input Zoom %"><INPUT 
TYPE="image" name="button35" ID="button35" align="top" size="26" alt="Set Image Zoom Percent" title="Set Image Zoom Percent" 
onclick="return(showIZ());" src="gfx/bt10.png">&nbsp;<a href="#" 
onclick="top.proofframe.imageframe.document.scanimage.src='../../projects/'+document.editform.projectname.value+'/'+document.editform.imagefile.value"><img 
src="gfx/bt6.png" align="top" alt="Refresh Image" title="Refresh Image" border="" width="26" height="26"></a>&nbsp;<INPUT 
TYPE="image" name="button8" ID="button8" align="top" size="26" alt="Revert to Original Document" title="Revert to Original Document" 
onclick="return(confirm('Are you sure you want to \r\nrevert to the original text for this round?'));" src="gfx/bt8.png">&nbsp;<?PHP
  if ($npage['revert']==1 || $npage['saved']==1)
  {
    echo "<INPUT \r\nTYPE=\"image\" name=\"button9\" ID=\"button9\" align=\"top\" size=\"26\" alt=\"Revert to Last Save\" title=\"Revert to Last Save\" ".
    "onclick=\"return(confirm('Are you sure you want to \\r\\nrevert to your last save?'));\" src=\"gfx/bt7.png\">";
  }
  else
  {
    echo "<img src=\"gfx/bt7_n.png\" align=\"top\" alt=\"Revert to Last Save Disabled\" title=\"Revert to Last Save Disabled\" border=\"0\" height=\"26\" width=\"26\">";
  }
?>&nbsp;<a href="#" onclick="top.menuframe.showNW()"><img 
src="gfx/bt9.png" width="26" height="26" border="0" align="top" alt="Show All Text" title="Show All Text" width="26" height="26"></a>&nbsp;<a 
href="projects.php?project=<?PHP echo "{$npage['project']}&amp;proofstate={$npage['proofstate']}"; ?>&amp;proofing=1" TARGET="viewcomments"><img 
src="gfx/bt12.png" width="26" height="26" border="0" align="top" alt="Project Comments" title="Project Comments" width="26" height="26"></a>&nbsp;<INPUT 
TYPE="image" name="button10" ID="button10" align="top" size="26" alt="Run Spelling Check" title="Run Spelling Check" src="gfx/bt16.png"><?PHP 
/*
temp disabled
&nbsp;<INPUT 
TYPE="image" name="button11" ID="button11" align="top" size="26" alt="Run Common Errors Check" title="Run Common Errors Check" src="gfx/bt17.png" onclick="alert('currently disabled');return(false);">
*/
?>&nbsp;<INPUT 
TYPE="image" accesskey="6" name="button4" ID="button4" align="top" size="26" alt="Save and Change Interface Layout" title="Save and Change Interface Layout" onclick="return(confirm('Are you sure you want to \r\nChange Layout\r\n and \r\nSave the Current Document?'));" src="gfx/bt<?PHP if ($userP['i_layout']==1) {echo "5";} else {echo "4";} ?>.png">&nbsp;<INPUT 
TYPE="image" accesskey="3" name="button7" ID="button7" align="top" size="26" alt="Return Page to Current Round" title="Return Page to Current Round" onclick="return(confirm('Are you sure you want to \r\nreturn this page to the current round?'));" src="gfx/bt15.png">&nbsp;<INPUT 
TYPE="image" accesskey="4" name="button6" ID="button6" align="top" size="26" alt="Report Bad Page" title="Report Bad Page" onclick="return(confirm('Are you sure you want to \r\nreport this page as bad?'));" src="gfx/bt14.png">&nbsp;<INPUT 
TYPE="image" accesskey="7" name="button1" ID="button1" align="top" size="26" alt="Save" title="Save" src="gfx/bt3.png">&nbsp;<?PHP 
  if($npage['editone']==0)
  {
    echo "<INPUT \r\nTYPE=\"image\" accesskey=\"8\" name=\"button2\" ID=\"button2\" align=\"top\" size=\"26\" alt=\"Save and Proof Next Page\" title=\"Save and Proof Next Page\" src=\"gfx/bt2.png\">".'&nbsp;';
  }
?><INPUT 
TYPE="image" accesskey="5" name="button5" ID="button5" align="top" size="26" alt="Save and Quit" title="Save and Quit" src="gfx/bt13.png">&nbsp;<INPUT 
TYPE="image" accesskey="9" name="button3" ID="button3" align="top" size="26" alt="Quit" title="Quit" onclick="return(confirm('Are you sure you want to \r\n\r\nQuit?'));" src="gfx/bt1.png">