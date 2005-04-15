<?
$relPath="./../../pinc/";
include($relPath.'dp_main.inc');

// (User clicked on "Start Proofreading" link or
// one of the links in "Done" or "In Progress" trays.)

/* $_GET register_globals:
$project, $proofstate
*/

$frameGet="?" . $_SERVER['QUERY_STRING'];

//load the master frameset
    include('master_frame.inc');
?>
