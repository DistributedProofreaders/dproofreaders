<A 
HREF="#" accesskey="," onfocus="dSI('left')"></A><A 
HREF="#" accesskey="." onfocus="dSI('right')"></A><A 
HREF="#" accesskey="'" onfocus="dSI('up')"></A><A 
HREF="#" accesskey="/" onfocus="dSI('down')"></A><select 
name="fntFace" ID="fntFace" title="Change Font Face" onChange="chFFace(this.selectedIndex)"><?PHP

$fs=$userP['i_layout']==1? $userP['v_fntf']:$userP['h_fntf'];
$f_s= array('Font Face','Courier','Times','Arial','Lucida','Monospaced');
for ($i=0;$i<6;$i++)
{$optP="<option \r\n value=\"$i\"";
if ($i==$fs){$optP.=" selected";}
$optP.=">$f_s[$i]</option>";
echo $optP;}
?></select><select 
name="fntSize" ID="fntSize" title="Change Font Size" onChange="chFSize(this.selectedIndex)"><?PHP 

$ff=$userP['i_layout']==1? $userP['v_fnts']:$userP['h_fnts'];
$f_f= array('Size','8pt','9pt','10pt','11pt','12pt','13pt','14pt','15pt','16pt','18pt','20pt');
for ($i=0;$i<12;$i++)
{$optP="<option \r\n value=\"$i\"";
if ($i==$ff){$optP.=" selected";}
$optP.=">$f_f[$i]</option>";
echo $optP;}
?></select>&nbsp;<input 
type="text" value="<? 
if ($userP['i_layout']==1)
{echo $userP['v_zoom'];} else{echo $userP['h_zoom'];}?>" name="zmSize" ID="zmSize" size="3" title="Input Zoom %"><INPUT 
TYPE="image" name="button35" ID="button35" size="26" alt="Set Image Zoom Percent" title="Set Image Zoom Percent" onclick="return(showIZ())" src="gfx/bt10.png">&nbsp;<a 
href="#" onclick="doRV()"><img 
src="gfx/bt8.png" width="26" height="26" border="0" alt="Revert to Original Document" title="Revert to Original Document"></a>&nbsp;<a href="#" onclick="udoRV()"><img 
src="gfx/bt7.png" width="26" height="26" border="0" alt="Undo Revert" title="Undo Revert"></a>&nbsp;<a href="#" onclick="showNW()"><img 
src="gfx/bt9.png" width="26" height="26" border="0" alt="Show All Text" title="Show All Text"></a>&nbsp;<a 
href="projects.php?project=<?PHP echo "$project&amp;proofstate=$proofstate"; ?>&amp;proofing=1" TARGET="viewcomments"><img 
src="gfx/bt12.png" width="26" height="26" border="0" alt="Project Comments" title="Project Comments"></a>&nbsp;<a href="#" onclick="cFCE()"><img 
src="gfx/bt6.png" width="26" height="26" border="0" alt="Check for Common Errors" title="Check for Common Errors"></a>&nbsp;<INPUT 
TYPE="image" accesskey="6" name="button4" ID="button4" size="26" alt="Save and Change Interface Layout" title="Save and Change Interface Layout" onclick="return(confirm('Are you sure you want to \r\nChange Layout\r\n and \r\nSave the Current Document?'));" src="gfx/bt<?PHP if ($userP['i_layout']==1) {echo "5";} else {echo "4";} ?>.png">&nbsp;<INPUT 
TYPE="image" accesskey="3" name="button7" ID="button7" size="26" alt="Return Page to Current Round" title="Return Page to Current Round" onclick="return(confirm('Are you sure you want to \r\nreturn this page to the current round?'));" src="gfx/bt15.png">&nbsp;<INPUT 
TYPE="image" accesskey="4" name="button6" ID="button6" size="26" alt="Report Bad Page" title="Report Bad Page" onclick="return(confirm('Are you sure you want to \r\nreport this page as bad?'));" src="gfx/bt14.png">&nbsp;<INPUT 
TYPE="image" accesskey="7" name="button1" ID="button1" size="26" alt="Save" title="Save" src="gfx/bt3.png">&nbsp;<?PHP 
if(!isset($editone)) {echo "<INPUT \r\nTYPE=\"image\" accesskey=\"8\" name=\"button2\" ID=\"button2\" size=\"26\" alt=\"Save and Proof Next Page\" title=\"Save and Proof Next Page\" src=\"gfx/bt2.png\">".'&nbsp;';}
?><INPUT 
TYPE="image" accesskey="5" name="button5" ID="button5" size="26" alt="Save and Quit" title="Save and Quit" src="gfx/bt13.png">&nbsp;<INPUT 
TYPE="image" accesskey="9" name="button3" ID="button3" size="26" alt="Quit" title="Quit" onclick="return(confirm('Are you sure you want to \r\n\r\nQuit?'));" src="gfx/bt1.png">&nbsp;<a 
href="../../faq/prooffacehelp.html" accesskey="1" target="helpNewWin"><img 
src="gfx/bt11.png" width="26" height="26" border="" alt="Help" TITLE="Help"></a>