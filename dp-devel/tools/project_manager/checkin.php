<?
if ($_COOKIE['pguser']) {
    // can only come from a cookie, forged or otherwise
    $good_login = 1;
    $pguser = $_COOKIE['pguser'];
}

if ($good_login != 1) {
    echo "<p><META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=accounts/signin.php\">"; 
} else {

    $project = $_GET['project'];
    $fileid = $_GET['fileid'];
    $prooflevel = $_GET['prooflevel'];

    ///connect to database
    include '../../connect.php';

    $sql = mysql_query("SELECT * FROM projects WHERE projectid = '$project'");
    $state = mysql_result($sql, 0, "state");
    $username = mysql_result($sql, 0, "username");

    $sql = mysql_query("SELECT sitemanager FROM users WHERE username = '$pguser'");
    $sitemanager = mysql_result($sql, 0, "sitemanager");

    if (($sitemanager != 'yes') && ($pguser != $username)) {
        echo "<P>You are not allowed to change the state on this project. If this message is an error, contact the <a href=\"charlz@lvcablemodem.com\">site manager</a>.";
        echo "<P>Back to <a href=\"projectmgr.php\">project manager</a> page.";
    } else if ((($state < 10) && ($prooflevel == 1)) ||
        (($state >= 10) && ($state < 20) && ($prooflevel == 3))) {

        $result = mysql_query("SELECT Image_Filename FROM $project WHERE fileid = '$fileid' AND prooflevel = '$prooflevel'");
        if (mysql_num_rows($result) > 0) {
            $image_name = mysql_result($result, 0, "Image_Filename");

            $result = mysql_query("DELETE FROM $project WHERE fileid = '$fileid'");

            $result = mysql_query("SELECT fileid FROM $project WHERE Image_Filename = '$image_name' AND prooflevel = '$prooflevel'");
            if (mysql_num_rows($result) == 0) {
                $oldlevel = $prooflevel - 1;
                $result = mysql_query("UPDATE $project SET checkedout = 'no' WHERE prooflevel = '$oldlevel' AND Image_Filename = '$image_name'");
            }

            if (($state == 9) || ($state == 19)) {
                $newstate = $state - 1;
                $result = mysql_query("UPDATE projects SET state = '$newstate' WHERE projectid = '$project'");
            }

            echo "File has been checked back in and is now available for proofing. You will be taken back to the project master files page in 1 second.";
            echo "<p><META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=projectmgr.php?project=$project\">";
        }
    } else {
        print "File can not be checked back in due to the project not currently being available or available in a different state. Go <a href=\"projectmgr.php?project=$project\">back</a>.";
    }
}
?>
