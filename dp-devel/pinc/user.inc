<?PHP
include_once($relPath.'cookie.inc');
include_once($relPath.'session.inc');

class db_udb
{
     var $cookieDomain="";

function db_udb()
{
    $server_name = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : "";
    $this->cookieDomain = substr_count($server_name, ".") > 0 ? $server_name : "";
}

function checkSafe($uInfo,$chktype)
{
     if ($chktype == 1) {

    $username_max_len = 25;
    // This is the length of the 'username' field in the 'users' table.

    if ( $username == '' )
    {
	$this->error = _("You did not supply a User Name (ID).");
        return 0;
    }

    if ( strlen($username) > $username_max_len )
    {
	$this->error = _("Your User Name is too long.<br>(The maximum is")." $username_max_len "._("characters").".)";
        return 0;
    }

    if ( ereg( "[^-a-zA-Z0-9@._ ]", $username) )
    {
	$this->error = _("Your User Name contains invalid characters.<br>(Valid characters are:")." a-z A-Z 0-9 @ - . _ "._("space").")";
        return 0;
    }

    // In order to prevent one class of impersonations,
    // any space character in $username must have a non-space on either side.
    // Thus, no leading or trailing spaces, and no adjacent spaces.

    if ( trim($username) != $username )
    {
	$this->error = _("Your User Name has leading or trailing whitespace, which is not allowed.");
        return 0;
    }

    if ( ereg( "  ", $username ) )
    {
	$this->error = _("Your User Name contains adjacent space characters, which is not allowed.");
        return 0;
    }
     }
     /*
       insert regular expression check to validate/security check
       user name (1) and password (2) form data
     */
     return 1;
}

function checkUID($userID)
      {if (strcmp($userID,$_POST['userNM'])==0 && $this->checkSafe($userID,1))
          {return 1;}
      else {return 0;}
      }

function checkUPW($userPW)
     {if (strcmp($userPW,$_POST['userPW'])==0 && $this->checkSafe($userPW,2))
          {return 1;}
     else {$this->error=_("Invalid Sign In"); return 0;}
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
	  startSession($userID);
          return 1;
          }
     else {return 0;}
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
