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

    // Send the page as plain-text.
    // Partly because it might possibly contain malicious HTML code, but
    // mainly because the client shouldn't be interpreting it as HTML anyhow.
    header('Content-type: text/plain');
    echo $data;

?> 
