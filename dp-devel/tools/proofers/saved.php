<?
$relPath="./../../pinc/";
include($relPath.'dp_main.inc');
/* $_GET $project, $imagefile, $js, $orient */

$project="project=$project";
$imagefile="&imagefile=$imagefile";
$frame1=$project.$imagefile;
$body="File has been saved.  Reloading image file in 2 seconds.";
$url="imageframe.php?$frame1";
metarefresh(2,$url,$imagefile,$body);
?>