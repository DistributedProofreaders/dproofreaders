<?
$relPath="./../../pinc/";
include($relPath.'dp_main.inc');
    $project = $_GET['project'];
    $fileid = $_GET['fileid'];
    $state = $_GET['state'];

    if ($state == 0) {
        $result = mysql_query("SELECT master_text FROM $project WHERE fileid = '$fileid'"); 
        $data = mysql_result($result, 0, "master_text");
    } else if ($state == 9) {
        $result = mysql_query("SELECT round1_text FROM $project WHERE fileid = '$fileid'"); 
        $data = mysql_result($result, 0, "round1_text");
    } else if ($state == 19) {
        $result = mysql_query("SELECT round2_text FROM $project WHERE fileid = '$fileid'"); 
        $data = mysql_result($result, 0, "round2_text");
    } else $data = "ERROR: Incorrect state parameter = ".$state." passed to script downloadproofed.php";

    echo"<pre>$data</pre>";
?> 
