<?PHP
class userCookie
{
     var $cookieDomain="";

function userCookie()
{$this->cookieDomain=(substr_count($_SERVER['SERVER_NAME'], ".")) > 0? $_SERVER['SERVER_NAME'] : "";}

function checkCookie()
     {
     if (@$_COOKIE['pguser'])
          {
          // can only come from a cookie, forged or otherwise
          $this->userID = $_COOKIE['pguser'];
          return 1;
          }
     else {return 0;}
     }

function setUserPrefs($userID)
{
# first line of fields in SELECT was:
# u_lang, email_updates, u_plist, u_top10, u_neigh,
          $db_query="SELECT 
u_lang, emailupdates, u_plist,
i_prefs, i_res, i_type, i_layout, i_toolbar, 
i_statusbar, i_newwin, v_fnts, v_fntf, v_zoom, 
v_tframe, v_tlines, v_tchars, v_tscroll, v_twrap, h_fnts, 
h_fntf, h_zoom, h_tframe, h_tlines, h_tchars, h_tscroll, h_twrap, 
id, manager, postprocessor 
FROM users WHERE username='$userID'";
          $db_result=mysql_query($db_query);
          $uData=mysql_fetch_assoc($db_result);
          $userPrefs=implode("|",$uData)."|0";
          $cookieName=md5($userID."_prefs");
          setcookie("$cookieName",$userPrefs,time() + 86400,"/",$this->cookieDomain,0);
}

function setCookie($userID)
     {
          setcookie("pguser",$userID,time()+86400,"/",$this->cookieDomain,0);
          $this->setUserPrefs($userID);
     }

function setTempPrefs($tempPrefs,$userID)
{
          $userPrefs=implode("|",$tempPrefs);
          $cookieName=md5($userID."_prefs");
          setcookie("$cookieName",$userPrefs,time() + 86400,"/",$this->cookieDomain,0);
}

function deleteOldPrefs($userID)
{setcookie($userID."_prefs","",time() - 86400,"/",$this->cookieDomain,0);}

function getUserPrefs($userID)
{
$cookieName=md5($userID."_prefs");
@$cPrefs=explode("|",$_COOKIE[$cookieName]);
$uPrefs=array();
$i=0;
@$uPrefs['u_lang']=$cPrefs[$i++];
@$uPrefs['email_updates']=$cPrefs[$i++];
@$uPrefs['u_plist']=$cPrefs[$i++];
@$uPrefs['u_top10']=$cPrefs[$i++];
@$uPrefs['u_neigh']=$cPrefs[$i++];
@$uPrefs['i_prefs']=$cPrefs[$i++];
@$uPrefs['i_res']=$cPrefs[$i++];
@$uPrefs['i_type']=$cPrefs[$i++];
@$uPrefs['i_layout']=$cPrefs[$i++];
@$uPrefs['i_toolbar']=$cPrefs[$i++];
@$uPrefs['i_statusbar']=$cPrefs[$i++];
@$uPrefs['i_newwin']=$cPrefs[$i++];
@$uPrefs['v_fnts']=$cPrefs[$i++];
@$uPrefs['v_fntf']=$cPrefs[$i++];
@$uPrefs['v_zoom']=$cPrefs[$i++];
@$uPrefs['v_tframe']=$cPrefs[$i++];
@$uPrefs['v_tlines']=$cPrefs[$i++];
@$uPrefs['v_tchars']=$cPrefs[$i++];
@$uPrefs['v_tscroll']=$cPrefs[$i++];
@$uPrefs['v_twrap']=$cPrefs[$i++];
@$uPrefs['h_fnts']=$cPrefs[$i++];
@$uPrefs['h_fntf']=$cPrefs[$i++];
@$uPrefs['h_zoom']=$cPrefs[$i++];
@$uPrefs['h_tframe']=$cPrefs[$i++];
@$uPrefs['h_tlines']=$cPrefs[$i++];
@$uPrefs['h_tchars']=$cPrefs[$i++];
@$uPrefs['h_tscroll']=$cPrefs[$i++];
@$uPrefs['h_twrap']=$cPrefs[$i++];
@$uPrefs['user_id']=$cPrefs[$i++];
@$uPrefs['manager']=$cPrefs[$i++];
@$uPrefs['postprocessor']=$cPrefs[$i++];

@$uPrefs['prefschanged']=$cPrefs[$i++];
return $uPrefs;
}
function deleteCookie()
     {
     setcookie("pguser","",time() - 86400,"/",$this->cookieDomain,0);
     }
}
?>
