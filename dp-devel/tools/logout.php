<?
//clear cookie if one is already set
$relPath='./../pinc/';
include($relPath.'cookie.inc');
$cookieC=new userCookie();
$cookieSet=$cookieC->deleteCookie();
include($relPath.'doctype.inc');
echo $docType."\r\n";
echo "<HTML><HEAD><TITLE>Logout Complete</TITLE>\r\n";
echo "<META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=../default.php\">";
echo "</HEAD><BODY><A HREF=\"../default.php\">Return to Home Page.</A>".
     "</BODY></HTML>";
?>
