<?PHP
$relPath="./../pinc/";
include($relPath.'v_site.inc');
include($relPath.'connect.inc');
include($relPath.'user.inc');
include($relPath.'metarefresh.inc');
include($relPath.'theme.inc');
$userC=new db_udb();
$noLogin="Username or password is incorrect.<BR>If you feel you have received this message in error, please try to <A HREF=\"$reset_password_url\">reset</A> your password. If this fails, contact the <A HREF=\"mailto:$site_manager_email_addr\">site manager</A>.<BR><A HREF=\"signin.php\">Back</A> to the sign in page.";
$htmlStart="<HTML><HEAD><TITLE>Login</TITLE>";
$htmlMid="</HEAD><BODY>";
$htmlEnd="</BODY></HTML>";
$noLogin=$htmlStart.$htmlMid.$noLogin.$htmlEnd;
extract($_POST);

if (ereg("[^1-zA-Z0-1@. \s\-]", $userNM) || strlen($userNM) > 25) {
	theme("Invalid Username", "header");
	echo "<b><center>Your username has invalid characters in it or is too long.  Please hit back & try again.<br>";
	echo "If you believe this is in error please contact <a href='mailto:$general_help_email_addr'>$general_help_email_addr</a>.</center></b>";
	theme("", "footer");
	exit();
} elseif (ereg("[^1-zA-Z0-1@. \s\-]", $userPW) || strlen($userPW) > 32) {
	theme("Invalid Password", "header");
	echo "<b><center>Your password has invalid characters in it or is too long.  Please hit back & try again.<br>";
	echo "If you believe this is in error please contact <a href='mailto:$general_help_email_addr'>$general_help_email_addr</a>.</center></b>";
	theme("", "footer");
	exit();
}

if (!empty($userNM) && !empty($userPW))
{
// $userNM = str_replace("\'", "''", $userNM);

$uC=$userC->checkLogin($userNM,$userPW);
     if ($uC)
     {
     $uP=$userC->getUserPrefs($userNM);
          if ($uP)
          {
          // send them to the correct page
// isn't this the same as the manager field in users?
//        $result = mysql_query("SELECT value FROM usersettings WHERE username = '$username' AND setting = 'manager'");
// needs to be included in user.inc, if not....
          if ($userC->manager=='yes')
          {
              $url = "../tools/project_manager/projectmgr.php";
          }
          else
          {
              $url = "../tools/proofers/proof_per.php";
          }
          metarefresh(1,$url,"Login","");
          }
          else die ($noLogin);
     }
     else die ($noLogin);
}
else die ($noLogin);
?>
