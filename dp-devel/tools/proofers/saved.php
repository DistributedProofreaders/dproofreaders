<?
$relPath="./../../pinc/";
include($relPath.'dp_main.inc');
$project = gtog('project');
$imagefile = gtog('imagefile');
$js=gtog('js');
$orient=gtog('orient');

$project="project=$project";
$imagefile="&imagefile=$imagefile";
$js=isset($js)? "&js=$js":'';
$orient=isset($orient)? "&orient=$orient":'';
$frame1=$project.$imagefile.$js.$orient;
include($relPath."doctype.inc");
echo "<HTML><HEAD><TITLE>$imagefile</TITLE>";
echo "<META HTTP-EQUIV=\"refresh\" CONTENT=\"2 ;URL=imageframe.php?$frame1\">"; 
echo "</HEAD><BODY>";
echo "File has been saved.  Reloading image file in 2 seconds.";
echo "</BODY></HTML>";