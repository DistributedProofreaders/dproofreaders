<?PHP
$relPath="./../pinc/";
include($relPath.'v_site.inc');
include($relPath.'connect.inc');
include($relPath.'user.inc');
$userC=new db_udb();
$noLogin="Username or password is incorrect.<BR>If you feel you have received this message in error, please try to <A HREF=\"$reset_password_url\">reset</A> your password. If this fails, contact the <A HREF=\"mailto:$site_manager_email_addr\">site manager</A>.<BR><A HREF=\"signin.php\">Back</A> to the sign in page.";
$htmlStart="<HTML><HEAD><TITLE>Login</TITLE>";
$htmlMid="</HEAD><BODY>";
$htmlEnd="</BODY></HTML>";
$noLogin=$htmlStart.$htmlMid.$noLogin.$htmlEnd;
extract($_POST);

if (ereg("[^A-Za-z0-9@_. ^]", $userNM) || ereg("[^A-Za-z0-9@_. ^]", $userPW) || strlen($userNM) > 25 || strlen($userPW) > 32)
{
    echo "Your username or password has invalid characters in it.  Please hit back & try again.  If you have any questions, feel free to contact the <a href='mailto:$site_manager_email_addr'>site manager</a>.";
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
          $htmlStart.="<META HTTP-EQUIV=\"refresh\" CONTENT=\"1 ;URL=../tools/";
          $htmlMid=".php\">".$htmlMid;
// isn't this the same as the manager field in users?
//        $result = mysql_query("SELECT value FROM usersettings WHERE username = '$username' AND setting = 'manager'");
// needs to be included in user.inc, if not....
          if ($userC->manager=='yes')
          {echo $htmlStart."project_manager/projectmgr".$htmlMid.$htmlEnd;}
          else {echo $htmlStart."proofers/proof_per".$htmlMid.$htmlEnd;}
          }
          else die ($noLogin);
     }
     else die ($noLogin);
}
else die ($noLogin);
?>
