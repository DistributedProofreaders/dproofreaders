<?
$username = isset($HTTP_POST_VARS['userNM']) ? $HTTP_POST_VARS['userNM'] : '';
$password = isset($HTTP_POST_VARS['userPW']) ? $HTTP_POST_VARS['userPW'] : '';
$username = str_replace("\'", "''", $username);

include '../connect.php';

$sql = "SELECT username, user_password FROM phpbb_users WHERE username = '$username'";

if (!($result = mysql_query($sql))) {
     die("Unable to make request, contact webmaster");
}

if (mysql_num_rows($result) == 1) {
    $user_password = mysql_result($result, 0, "user_password");
    if (md5($password) == $user_password) {
        //set cookie if not already set
        if (!isset($pguser)) {
            setcookie("pguser",$username,time()+86400,"/","texts01.archive.org",0);
        }

        // calculate date
        $year  = date("Y");
        $month = date("m");
        $day = date("d");
        $todaysdate = $year.$month.$day;

        //update last login date
        $updatefile = mysql_query("UPDATE users SET last_login = '$todaysdate' WHERE username = '$username'");

        $result = mysql_query("SELECT value FROM usersettings WHERE username = '$username' AND setting = 'manager'");
        if (mysql_num_rows($result) > 0) {
            $manager = mysql_result($result, 0, "value");
            if ($manager == 'yes') {    ///if have a manager account send to projectmgr.php
                echo "<p><META HTTP-EQUIV=\"refresh\" CONTENT=\"1 ;URL=../tools/project_manager/projectmgr.php\">";
            } else {
                echo "<p><META HTTP-EQUIV=\"refresh\" CONTENT=\"1 ;URL=../tools/proofers/proof_per.php\">";
            }
        } else echo "<p><META HTTP-EQUIV=\"refresh\" CONTENT=\"1 ;URL=../tools/proofers/proof_per.php\">";

    } else print("Username or password is incorrect.<br>If you feel you have recieved this message in error, please try to <a href=\"http://texts01.archive.org/dp/phpBB2/profile.php?mode=sendpassword\">reset</a> your password. If this fails, contact the <a href=\"mailto:charlz@lvcablemodem.com\">webmaster</a><br><a href=\"signin.php\">Back</a> to sign in page.");

} else die ("Username or password is incorrect.<br>If you feel you have recieved this message in error, please try to <a href=\"http://texts01.archive.org/dp/phpBB2/profile.php?mode=sendpassword\">reset</a> your password. If this fails, contact the <a href=\"mailto:charlz@lvcablemodem.com\">webmaster</a><br><a href=\"signin.php\">Back</a> to sign in page.");
?>
