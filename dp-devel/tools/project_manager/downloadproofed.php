<?
if ($_COOKIE['pguser']) {
    // can only come from a cookie, forged or otherwise
    $good_login = 1;
    $pguser = $_COOKIE['pguser'];
}

if ($good_login != 1) {
    echo "<p><META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=../../accounts/signin.php\">"; 
} else {
    $project = $_GET['project'];
    $fileid = $_GET['fileid'];
    $state = $_GET['state'];

    include '../../connect.php';
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
}
?> 
