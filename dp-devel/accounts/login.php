<?PHP
$relPath="./../pinc/";
include($relPath.'connect.inc');
include($relPath.'user.inc');
$userC=new db_udb();
$noLogin="Username or password is incorrect.<BR>If you feel you have received this message in error, please try to <A HREF=\"http://texts01.archive.org/dp/phpBB2/profile.php?mode=sendpassword\">reset</A> your password. If this fails, contact the <A HREF=\"mailto:charlz@lvcablemodem.com\">webmaster</A>.<BR><A HREF=\"signin.php\">Back</A> to the sign in page.";
$htmlStart="<HTML><HEAD><TITLE>Login</TITLE>";
$htmlMid="</HEAD><BODY>";
$htmlEnd="</BODY></HTML>";
$noLogin=$htmlStart.$htmlMid.$noLogin.$htmlEnd;
extract($_POST);

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