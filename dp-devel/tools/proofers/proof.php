<?
$relPath="./../../pinc/";
include($relPath.'dp_main.inc');

// (User clicked on the name of a project
// in the list of projects available for proofing.)

/* $_GET register_globals:
$project, $proofstate
*/

$frameGet="?id={$project}&amp;expected_state={$proofstate}";

//load the master frameset
    include('master_frame.inc');
?>
