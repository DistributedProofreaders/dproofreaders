<?
$relPath="./../../pinc/";
include($relPath.'dp_main.inc');

    $project = $_GET['project'];
    $fileid = $_GET['fileid'];
    $pagestate = $_GET['state'];

    $sql = mysql_query("SELECT state, username FROM projects WHERE projectid = '$project'");
    $projstate = mysql_result($sql, 0, "state");
    $username = mysql_result($sql, 0, "username");

    $sql = mysql_query("SELECT sitemanager FROM users WHERE username = '$pguser'");
    $sitemanager = mysql_result($sql, 0, "sitemanager");

    if (($sitemanager != 'yes') && ($pguser != $username)) {
        echo "<P>You are not allowed to change the state on this project. If this message is an error, contact the <a href=\"charlz@lvcablemodem.com\">site manager</a>.";
        echo "<P>Back to <a href=\"projectmgr.php\">project manager</a> page.";
    } else if (($projstate < 10) && ($pagestate == 9)) {
        $result = mysql_query("UPDATE $project SET round1_text = '', round1_user = '', round1_time = '', state = 2 WHERE fileid = '$fileid'");
        echo "<html><head><META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=projectmgr.php?project=$project\"></head><body></body></html>";

    } else if (($state >= 10) && ($state < 20) && ($pagestate == 19)) {
        $result = mysql_query("UPDATE $project SET round2_text = '', round2_user = '', round2_time = '', state = 12 WHERE fileid = '$fileid'");
        echo "<html><head><META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=projectmgr.php?project=$project\"></head><body></body></html>";

    } else {
        print "File can not be checked back in due to the project not currently being available or available in a different state. Go <a href=\"projectmgr.php?project=$project\">back</a>.";
    }
?>

