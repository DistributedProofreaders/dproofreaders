<?
$relPath="./../../pinc/";
include($relPath.'dp_main.inc');
include_once($relPath.'page_states.inc');
    $project = $_GET['project'];
    $fileid = $_GET['fileid'];
    $state = $_GET['state'];

    if ($state == UNAVAIL_FIRST) {
        $result = mysql_query("SELECT master_text FROM $project WHERE fileid = '$fileid'"); 
        $data = mysql_result($result, 0, "master_text");
    } else if ($state == SAVE_FIRST) {
        $result = mysql_query("SELECT round1_text FROM $project WHERE fileid = '$fileid'"); 
        $data = mysql_result($result, 0, "round1_text");
    } else if ($state == SAVE_SECOND) {
        $result = mysql_query("SELECT round2_text FROM $project WHERE fileid = '$fileid'"); 
        $data = mysql_result($result, 0, "round2_text");
    } else $data = "ERROR: Incorrect state parameter = ".$state." passed to script downloadproofed.php";

    header('Content-type: text/plain; charset=UTF-8');
    // SENDING PAGE-TEXT TO USER
    // It's a text/plain document, so no encoding is necessary.
    echo $data;

?> 
