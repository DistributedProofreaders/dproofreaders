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

    include '../../connect.php';

    $result = mysql_query("SELECT text_data FROM $project WHERE fileid = '$fileid'"); 

    $data = mysql_result($result, 0, "text_data");

    echo"<pre>$data</pre>";
}
?> 
