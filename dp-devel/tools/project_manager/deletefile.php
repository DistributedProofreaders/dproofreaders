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
    $file = $_GET['file'];

    //connect to database
    include '../../connect.php';

    $sql = mysql_query("SELECT username FROM projects WHERE projectid = '$project' LIMIT 1");
    $username = mysql_result($sql, 0, "username");

    $sql = mysql_query("SELECT sitemanager FROM users WHERE username = '$pguser' LIMIT 1");
    $sitemanager = mysql_result($sql, 0, "sitemanager");

    if (($sitemanager != 'yes') && ($pguser != $username)) {
        echo "<P>You are not allowed to change the state on this project. If this message is an error, contact the <a href=\"charlz@lvcablemodem.com\">site manager</a>.";
        echo "<P>Back to <a href=\"projectmgr.php\">project manager</a> page.";
    } else {
        $sql = "DELETE from $project WHERE Image_Filename = '$file'";

        $result = mysql_query($sql);
        exec("perl projects\myrmdir.pl projects\\$project\\$file"); 

        echo "File ",$file," has been deleted.";
        echo "You will be taken back to the project master files page in 1 second.";
        echo "<p><META HTTP-EQUIV=\"refresh\" CONTENT=\"1 ;URL=projectmgr.php?project=$project\">"; 
    }
}
?>
