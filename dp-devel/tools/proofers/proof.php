<?
$relPath="./../../pinc/";
include($relPath.'dp_main.inc');

/* $_GET from IN PROGRESS, DONE
$project, $fileid, $imagefile, $proofstate, $pagestate, $editone
*/

/* $_GET from Start Proofing
$project, $proofstate
*/

$frameGet="?project={$project}&amp;proofstate={$proofstate}";

//load the master frameset
    include('master_frame.inc');
?>
