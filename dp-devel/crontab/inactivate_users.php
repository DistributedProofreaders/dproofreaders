<?
$relPath="./../pinc/";
include($relPath.'dp_main.inc');

//this module sets users inactive who have not logged into the site in 6 months

    $old_date = time() - 15768000; // 6 months ago.

    $result = mysql_query ("UPDATE `users` SET active = 'no' WHERE last_login < $old_date AND active ='yes'");
    $numrows = mysql_num_rows($result);

    echo "inactivate_users.php set $numrows users who have not logged in for 6 months as inactive";
?>
