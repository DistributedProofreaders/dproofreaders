<?PHP
include('cookie.inc');
$cookieC=new userCookie();
$cookieSet=$cookieC->checkCookie();
if ($cookieSet)
     {$cookieID=$cookieC->userID;}
else {
     include('doctype.inc');
     echo $docType."\r\n";
     echo "<HTML><HEAD><TITLE>Please Login</TITLE>\r\n";
     echo "<META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=";
     include('metabaseurl.inc');
     echo $baseURL."accounts/signin.php\">";
     echo "</HEAD><BODY><A HREF=\"".$baseURL."accounts/signin.php\">Please sign in.</A>".
     "</BODY></HTML>";
     exit;
     }
?>