<?
//clear cookie if one is already set
$relPath='./../pinc/';
include($relPath.'cookie.inc');
include($relPath.'metarefresh.inc');
$cookieC=new userCookie();
$cookieSet=$cookieC->deleteCookie();

metarefresh(0, "../default.php", "Logout Complete",
     "<A HREF=\"../default.php\">Return to DP Home Page.</A>");
?>
