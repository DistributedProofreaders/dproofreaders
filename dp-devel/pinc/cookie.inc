<?PHP
class userCookie
{
     var $cookieDomain="";

function checkCookie()
     {
     if (@$_COOKIE['pguser'])
          {
          // can only come from a cookie, forged or otherwise
          $this->good_login = 1;
          $this->userID = $_COOKIE['pguser'];
          return 1;
          }
     else {return 0;}
     }

function setCookie($userID)
     {
          setcookie("pguser",$userID,time()+86400,"/",$this->cookieDomain,0);
     }

function deleteCookie()
     {
     setcookie("pguser","",time() - 86400,"/",$this->cookieDomain,0);
     }
}
?>