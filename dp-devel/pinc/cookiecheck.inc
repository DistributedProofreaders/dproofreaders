<?
include_once($relPath.'v_site.inc');
if ($use_cookies) {
	include($relPath.'cookie.inc');
	include_once($relPath.'cookiehash.php');
	include_once($relPath.'metarefresh.inc');

	$cookieC=new userCookie();
	$cookieSet=$cookieC->checkCookie();

	if ($cookieSet) {
		$cookieID=$cookieC->userID;
		$cookieVal=$cookieID.'|'.getCookieHash($cookieID);
		$cookieName=md5($cookieID."_prefs");
		$userPrefs = $_COOKIE[$cookieName];
		setCookie("pguser","$cookieVal",time()+86400,"/",$cookieC->cookieDomain,0);
		setCookie("$cookieName",$userPrefs,time()+86400,"/",$cookieC->cookieDomain,0);
	} else {
		// Remember the current REQUEST_URI, so that we can send the user there
		// after the login process.
		$url=$relPath."../accounts/signin.php?destination={$_SERVER['REQUEST_URI']}";
		$body="<A HREF=\"$url\">" . _("Please Sign In") . "</A>";
		$title = _("Please Sign In");
		metarefresh(0,$url,$title,$body);
		exit;
	}
}
?>
