<?
$relPath="./../../pinc/";
include($relPath.'dp_main.inc');

/* $_GET from recently done
$project, $fileid, $imagefile, $proofstate, $pagestate, $editone
*/

/* $_GET from start proofing
$project, $proofstate
*/

$frameGet="?project={$project}&amp;proofstate={$proofstate}";

//load the master frameset
    include('master_frame.inc');
?>
