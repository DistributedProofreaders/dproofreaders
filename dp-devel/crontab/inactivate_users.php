<?
$relPath="./../pinc/";
include($relPath.'v_site.inc');

//this module sets users inactive who have not logged into the site in 6 months

    $old_date = time() - 15768000; // 6 months ago.

    $result = mysql_query ("UPDATE `users` SET active = 'no' WHERE last_login < $old_date AND active ='yes'");
  
?>
