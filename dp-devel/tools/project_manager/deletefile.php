<?
$relPath="./../../pinc/";
include($relPath.'dp_main.inc');

    $project = $_GET['project'];
    $fileid = $_GET['fileid'];
    
    $sql = mysql_query("SELECT username FROM projects WHERE projectid = '$project' LIMIT 1");
    $username = mysql_result($sql, 0, "username");

    $sql = mysql_query("SELECT sitemanager FROM users WHERE username = '$pguser' LIMIT 1");
    $sitemanager = mysql_result($sql, 0, "sitemanager");

    if (($sitemanager != 'yes') && ($pguser != $username)) {
        echo "<P>You are not allowed to change the state on this project. If this message is an error, contact the <a href=\"charlz@lvcablemodem.com\">site manager</a>.";
        echo "<P>Back to <a href=\"projectmgr.php\">project manager</a> page.";
    } else {
        if ($fileid == '') {
           $sql = "DELETE FROM $project WHERE 1";
        } else $sql = "DELETE FROM $project WHERE fileid = '$fileid'";
        mysql_query($sql);

        echo "<html><head><META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=projectmgr.php?project=$project\"></head><body></body></html>"; 
    }
?>
