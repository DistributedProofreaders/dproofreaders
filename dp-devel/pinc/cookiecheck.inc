<?PHP
include($relPath.'cookie.inc');
$cookieC=new userCookie();
$cookieSet=$cookieC->checkCookie();
if ($cookieSet)
     {$cookieID=$cookieC->userID;}
else {
     $url=$relPath."../accounts/signin.php";
     $body="<A HREF=\"".$relPath."../accounts/signin.php\">Please sign in.</A>";
     metarefresh(0,$url,'Please Login',$body);
     exit;
     }
?>