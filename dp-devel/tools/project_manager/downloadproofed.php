<?
$relPath="./../../pinc/";
include($relPath.'dp_main.inc');
include_once($relPath.'page_states.inc');
    $project = $_GET['project'];
    $fileid = $_GET['fileid'];
    $round_num = $_GET['round_num'];

    if ($round_num == 0) {
        $result = mysql_query("SELECT master_text FROM $project WHERE fileid = '$fileid'"); 
        $data = mysql_result($result, 0, "master_text");
    } else if ($round_num == 1) {
        $result = mysql_query("SELECT round1_text FROM $project WHERE fileid = '$fileid'"); 
        $data = mysql_result($result, 0, "round1_text");
    } else if ($round_num == 2) {
        $result = mysql_query("SELECT round2_text FROM $project WHERE fileid = '$fileid'"); 
        $data = mysql_result($result, 0, "round2_text");
    } else $data = "ERROR: Incorrect round_num parameter = ".$round_num." passed to script downloadproofed.php";

    header("Content-type: text/plain; charset=$charset");
    // SENDING PAGE-TEXT TO USER
    // It's a text/plain document, so no encoding is necessary.
    echo $data;

?> 
