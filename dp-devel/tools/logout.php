<?
//clear cookie if one is already set
$relPath='./../pinc/';
//include($relPath.'cookie.inc');
include($relPath.'metarefresh.inc');
include($relPath.'dp_main.inc');
include_once($relPath.'v_site.inc');

if ($use_cookies) {
	$cookieC=new userCookie();
	$cookieSet=$cookieC->deleteCookie();
} else {
	session_unset();
	session_destroy();
}

metarefresh(0, "../default.php", "Logout Complete",
     "<A HREF=\"../default.php\">Return to DP Home Page.</A>");
?>
