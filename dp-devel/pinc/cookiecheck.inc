<?PHP
include($relPath.'cookie.inc');
$cookieC=new userCookie();
$cookieSet=$cookieC->checkCookie();
if ($cookieSet)
     {$cookieID=$cookieC->userID;}
else {
     // Remember the current REQUEST_URI, so that we can send the user there
     // after the login process.
     $url=$relPath."../accounts/signin.php?destination={$_SERVER['REQUEST_URI']}";
     $body="<A HREF=\"$url\">Please sign in.</A>";
     metarefresh(0,$url,'Please Login',$body);
     exit;
     }
?>
