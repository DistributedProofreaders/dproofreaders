<?PHP
include_once($relPath.'cookiehash.php');
class userCookie
{
     var $cookieDomain="";

function userCookie()
{$this->cookieDomain=(substr_count($_SERVER['SERVER_NAME'], ".")) > 0? $_SERVER['SERVER_NAME'] : "";}

function deleteCookie()
{
  setcookie("pguser","",time() - 86400,"/",$this->cookieDomain,0);
}

function checkCookieH()
{
  if (!isset($_COOKIE['pguser'])) {return false;}
  $ccheck=explode("|",$_COOKIE['pguser']);
  $isChecked=checkCookieHash($ccheck[0],$ccheck[1]);
  if ($isChecked)
    {return true;}
  else
    {return false;}
}

function checkCookie()
{
  if (isset($_COOKIE['pguser']) && $this->checkCookieH())
  {
    // can only come from a cookie, forged or otherwise
    $ccheck=explode("|",$_COOKIE['pguser']);
    $this->userID = $ccheck[0];
    return 1;
  }
  else {return 0;}
}

function setUserPrefs($userID)
{
  if ($this->checkCookieH() || $this->ignoreHash)
  {
          $db_users="SELECT u_id, u_profile, u_lang, email_updates, 
u_plist, u_top10, u_neigh, u_align, i_prefs, i_theme,
id, manager, postprocessor, sitemanager, team_1, team_2, team_3 
FROM users WHERE username='$userID'";
          $db_usersres=mysql_query($db_users);
          $u_usersData=mysql_fetch_assoc($db_usersres);
          $db_query="SELECT 
profilename, i_res, i_type, i_layout, i_toolbar, 
i_statusbar, i_newwin, v_fnts, v_fntf, v_zoom, 
v_tframe, v_tlines, v_tchars, v_tscroll, v_twrap, h_fnts, 
h_fntf, h_zoom, h_tframe, h_tlines, h_tchars, h_tscroll, h_twrap 
FROM user_profiles WHERE u_ref='{$u_usersData['u_id']}' AND id='{$u_usersData['u_profile']}'";
          $db_result=mysql_query($db_query);
          $uData=mysql_fetch_assoc($db_result);
          $userPrefs=implode("|",$u_usersData)."|".implode("|",$uData)."|0";
          $cookieName=md5($userID."_prefs");
          setcookie("$cookieName",$userPrefs,time() + 86400,"/",$this->cookieDomain,0);
    $this->ignoreHash=0;
  }
  else 
  {
    $this->deleteCookie();
    echo "Database access error 1634: Preference Write Error.";
  }
}

function setCookie($userID)
{
  $cookieVal=$userID.'|'.getCookieHash($userID);
  setcookie("pguser","$cookieVal",time()+86400,"/",$this->cookieDomain,0);
  $this->ignoreHash=1;
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
     if ($this->checkCookieH())
     {
$cookieName=md5($userID."_prefs");
@$cPrefs=explode("|",$_COOKIE[$cookieName]);
$uPrefs=array();
$i=0;
// from users
@$uPrefs['u_id']=$cPrefs[$i++];
@$uPrefs['u_profile']=$cPrefs[$i++];
@$uPrefs['u_lang']=$cPrefs[$i++];
@$uPrefs['email_updates']=$cPrefs[$i++];
@$uPrefs['u_plist']=$cPrefs[$i++];
@$uPrefs['u_top10']=$cPrefs[$i++];
@$uPrefs['u_neigh']=$cPrefs[$i++];
@$uPrefs['u_align']=$cPrefs[$i++];
@$uPrefs['i_prefs']=$cPrefs[$i++];
@$uPrefs['i_theme']=$cPrefs[$i++];
@$uPrefs['user_id']=$cPrefs[$i++];
@$uPrefs['manager']=$cPrefs[$i++];
@$uPrefs['postprocessor']=$cPrefs[$i++];
@$uPrefs['sitemanager']=$cPrefs[$i++];
@$uPrefs['team_1']=$cPrefs[$i++];
@$uPrefs['team_2']=$cPrefs[$i++];
@$uPrefs['team_3']=$cPrefs[$i++];

// from user_profiles
@$uPrefs['profilename']=$cPrefs[$i++];
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

@$uPrefs['prefschanged']=$cPrefs[$i++];
return $uPrefs;
     }
else {return false;}
}

}
?>