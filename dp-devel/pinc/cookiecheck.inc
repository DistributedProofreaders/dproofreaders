<?PHP
include($relPath.'cookie.inc');
$cookieC=new userCookie();
$cookieSet=$cookieC->checkCookie();
if ($cookieSet)
     {$cookieID=$cookieC->userID;}
else {
     include($relPath.'doctype.inc');
     echo $docType."\r\n";
     echo "<HTML><HEAD><TITLE>Please Login</TITLE>\r\n";
     echo "<META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=";
     echo $relPath."../accounts/signin.php\">";
     echo "</HEAD><BODY><A HREF=\"".$relPath."../accounts/signin.php\">Please sign in.</A>".
     "</BODY></HTML>";
     exit;
     }
?>