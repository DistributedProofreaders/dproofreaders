<A 
HREF="#" accesskey="," onfocus="dSI('left')"></A><A 
HREF="#" accesskey="." onfocus="dSI('right')"></A><A 
HREF="#" accesskey="'" onfocus="dSI('up')"></A><A 
HREF="#" accesskey="/" onfocus="dSI('down')"></A><select 
name="fntFace" ID="fntFace" title="Change Font Face" onChange="chFFace(this.selectedIndex)"><option 
value="0">Font Face</option><option 
value="1">Courier</option><option 
value="2">Times</option><option 
value="3">Arial</option><option 
value="4">Lucida</option><option 
value="5">Monospaced</option></select>&nbsp;<select 
name="fntSize" ID="fntSize" title="Change Font Size" onChange="chFSize(this.value)"><option 
value="0">Size</option><option 
value="8">8pt</option><option 
value="9">9pt</option><option 
value="10">10pt</option><option 
value="11">11pt</option><option 
value="12">12pt</option><option 
value="13">13pt</option><option 
value="14">14pt</option><option 
value="15">15pt</option><option 
value="16">16pt</option><option 
value="18">18pt</option><option 
value="20">20pt</option></select>&nbsp;<input 
type="text" value="<? echo $zmSize; ?>" name="zmSize" ID="zmSize" size="3" title="Input Zoom %"><INPUT 
TYPE="image" name="button5" ID="button5" title="Set Image Zoom Percent" onclick="return(showIZ())" src="gfx/bt10.png">&nbsp;<a 
href="#" onclick="doRV()"><img 
src="gfx/bt8.png" border="0" alt="Revert to Original Document" title="Revert to Original Document"></a>&nbsp;<a href="#" onclick="udoRV()"><img 
src="gfx/bt7.png" border="0" alt="Undo Revert" title="Undo Revert"></a>&nbsp;<a href="#" onclick="showNW()"><img 
src="gfx/bt9.png" border="0" alt="Show All Text" title="Show All Text"></a>&nbsp;<a 
href="projects.php?project=<?PHP echo "$project&amp;prooflevel=$prooflevel"; ?>&amp;proofing=1" TARGET="viewcomments"><img 
src="gfx/bt12.png" border="0" alt="Project Comments" title="Project Comments"></a>&nbsp;<a href="#" onclick="cFCE()"><img 
src="gfx/bt6.png" border="0" alt="Check for Common Errors" title="Check for Common Errors"></a>&nbsp;<INPUT 
TYPE="image" accesskey="6" name="button4" ID="button4" title="Save and Change Interface Layout" onclick="return(confirm('Are you sure you want to \r\nChange Layout\r\n and \r\nSave the Current Document?'));" src="gfx/bt<?PHP if ($orient=="vert") {echo "5";} else {echo "4";} ?>.png">&nbsp;<INPUT 
TYPE="image" accesskey="7" name="button1" ID="button1" title="Save" src="gfx/bt3.png">&nbsp;<?PHP 
if(!isset($editone)) {echo "<INPUT \r\nTYPE=\"image\" accesskey=\"8\" name=\"button2\" ID=\"button2\" title=\"Save and Proof Next Page\" src=\"gfx/bt2.png\">".'&nbsp;';}
?><INPUT 
TYPE="image" accesskey="9" name="button3" ID="button3" title="Quit without Saving" onclick="return(confirm('Are you sure you want to \r\n\r\nQuit Without Saving\r\nthe Current Document?'));" src="gfx/bt1.png">&nbsp;<a 
href="../../faq/prooffacehelp.html" accesskey="1" target="helpNewWin"><img 
src="gfx/bt11.png" border="" alt="Help" TITLE="Help"></a>