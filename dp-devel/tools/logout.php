<?
//clear cookie if one is already set
$relPath='./../pinc/';
include($relPath.'cookie.inc');
include($relPath.'metarefresh.inc');
include($relPath.'dp_main.inc');

if (0) {
	$cookieC=new userCookie();
	$cookieSet=$cookieC->deleteCookie();
}

session_unset();
session_destroy();

metarefresh(0, "../default.php", "Logout Complete",
     "<A HREF=\"../default.php\">Return to DP Home Page.</A>");
?>
