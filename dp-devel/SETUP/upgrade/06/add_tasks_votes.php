<?php
$relPath='./pinc/';
include($relPath.'connect.inc');
$db_Connection=new dbConnect();

//----------------------------------------------------------------------------------------------------------------------
$sql = 'CREATE TABLE `tasks_votes` ('
        . ' `id` INT NOT NULL AUTO_INCREMENT, '
        . ' `task_id` MEDIUMINT(9) NOT NULL, '
        . ' `u_id` INT(10) NOT NULL, '
        . ' `vote_os` TINYINT(1) NOT NULL, '
        . ' `vote_browser` TINYINT(1) NOT NULL,'
        . ' INDEX (`task_id`, `u_id`),'
        . ' UNIQUE (`id`)'
        . ' )';
        
$result = mysql_query($sql) or die(mysql_error());
echo "<center><p>Addition of tasks_votes table complete!";
?>