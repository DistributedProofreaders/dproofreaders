<?PHP
include_once($relPath.'dpsession.inc');

class db_udb
{

function checkSafe($uInfo,$chktype)
     {
     /*
       insert regular expression check to validate/security check
       user name (1) and password (2) form data
     */
     return 1;
      }

function checkUID($userID)
      {
//      	echo "USER ID: ".$userID & " - " & $_POST['userNM'];
      	if (strcmp($userID,$_POST['userNM'])==0 && $this->checkSafe($userID,1))
          {return 1;}
      else {return 0;}
      }

function checkUPW($userPW)
     {if (strcmp($userPW,$_POST['userPW'])==0 && $this->checkSafe($userPW,2))
          {return 1;}
      else {return 0;}
     }

function checkQuery($db_result)
     {if (!$db_result)
          {$this->error='Invalid Query! '. mysql_error();return 0;}
     else {return 1;}
     }

function checkResult($db_result)
     {if (mysql_num_rows($db_result)==0)
          {$this->error=_("User Name (ID) not found!");return 0;}
     else {return 1;}
     }

function checkLogin($userID,$userPW)
     {
     $db_conn=new dbconnect();
     if (!$db_conn)
          {$this->error=$db_conn->error;return 0;}
     $db_lk=$db_conn->db_lk;
     $chkID=$this->checkUID($userID);
     $chkPW=$this->checkUPW($userPW);
     if ($chkID && $chkPW)
          {
          //check login in phpbb_users
          $dbQuery="SELECT * FROM phpbb_users WHERE username='$userID' AND user_password='".MD5($userPW)."'";
          $db_result=mysql_query($dbQuery,$db_lk);
          if (!$this->checkQuery($db_result)) {return 0;}
          if (!$this->checkResult($db_result)) {return 0;}
          $userID = mysql_result($db_result,0,'username');
	  dpsession_begin( $userID );
          return 1;
          }
     else {$this->error='Invalid Login'; return 0;}
     }

function getQueryResult($userID)
     {
     $db_conn=new dbconnect();
     if (!$db_conn)
          {$this->error=$db_conn->error;return 0;}
     $db_lk=$db_conn->db_lk;
     // now get the info from users database
     $dbQuery="SELECT * FROM users WHERE username='$userID'";
     $db_result=mysql_query($dbQuery,$db_lk);
     if (!$this->checkQuery($db_result)) {return 0;}
     if (!$this->checkResult($db_result)) {return 0;}
     $uData=mysql_fetch_assoc($db_result);
          while (list($key,$val) = each($uData))
               {$this->$key = $val;}
     // update date stamp in users
     $today=time();
     $dbQuery="UPDATE users SET last_login = '$today' WHERE username = '$userID'";
     $db_result=mysql_query($dbQuery,$db_lk);
     if (!$this->checkQuery($db_result)) {return 0;}
     return 1;
     }

function getUserPrefs($userID)
     {
     // query database for user
     $db_Qresult=$this->getQueryResult($userID);
     if (!$db_Qresult)
          {return 0;}
          else {return 1;};
     }

function addUser()
     {
     // do strcmp(POST[var], $var)==0 && checkSafe($var,$chkType) on variables
     // if all true, put data into users & phpbb_users
     }
}
?>
