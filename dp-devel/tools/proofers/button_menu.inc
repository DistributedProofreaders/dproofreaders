<select 
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
type="text" value="<? echo $zmSize; ?>" name="zmSize" ID="zmSize" size="3" title="Input Zoom %"><a href="#" onclick="showIZ()"><img 
src="gfx/bt10.png" border="0" alt="Set Image Zoom Percent" title="Set Image Zoom Percent"></a>&nbsp;<a href="#" onclick="doRV()"><img 
src="gfx/bt8.png" border="0" alt="Revert to Original Document" title="Revert to Original Document"></a>&nbsp;<a href="#" onclick="udoRV()"><img 
src="gfx/bt7.png" border="0" alt="Undo Revert" title="Undo Revert"></a>&nbsp;<a href="#" onclick="showNW()"><img 
src="gfx/bt9.png" border="0" alt="Show All Text" title="Show All Text"></a>&nbsp;<a href="#" onclick="alert('Would show project comments in a popup.')"><img 
src="gfx/bt12.png" border="0" alt="View Project Comments" title="View Project Comments"></a>&nbsp;<a href="#" onclick="cFCE()"><img 
src="gfx/bt6.png" border="0" alt="Check for Common Errors" title="Check for Common Errors"></a>&nbsp;<INPUT 
TYPE="image" accesskey="6" name="button4" ID="button4" title="Change Interface Layout" src="gfx/bt<?PHP if ($orient==1) {echo "4";} else {echo "5";} ?>.png">&nbsp;<INPUT 
TYPE="image" accesskey="7" name="button1" ID="button1" title="Save" onclick="return(confirm('Are you sure you want to \r\n\r\nSave the Current Document?'));" src="gfx/bt3.png">&nbsp;<?PHP 
if(!isset($editone)) {echo "<INPUT \r\nTYPE=\"image\" accesskey=\"8\" name=\"button2\" ID=\"button2\" title=\"Save and Proof Next Page\" onclick=\"return(confirm('Are you sure you want to ".'\r\n\r\n'."Save and Proof Next Page?'));\" src=\"gfx/bt2.png\">".'&nbsp;';}
?><a href="#" onclick="confirmClose()"><img 
src="gfx/bt1.png" accesskey="9" border="0" alt="Quit Without Saving" title="Quit Without Saving"></a>&nbsp;<a href="help.html" target="helpNewWin"><img 
src="gfx/bt11.png" border="" alt="Help" TITLE="Help"></a>