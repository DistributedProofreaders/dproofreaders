<?
$relPath="./../../pinc/";
include($relPath.'dp_main.inc');
/* $_GET $project, $imagefile, $js, $orient */

$project="project=$project";
$imagefile="&imagefile=$imagefile";
$js=isset($js)? "&js=$js":'';
$orient=isset($orient)? "&orient=$orient":'';
$frame1=$project.$imagefile.$js.$orient;
$body="File has been saved.  Reloading image file in 2 seconds.";
$url="imageframe.php?$frame1";
metarefresh(2,$url,$imagefile,$body);
?>