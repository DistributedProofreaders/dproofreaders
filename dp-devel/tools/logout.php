<?
//clear cookie if one is already set


setcookie("pguser","",time() - 86400,"/",$_SERVER['SERVER_NAME'],0);
     echo "<p><META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=../default.php\">"; 
?>
