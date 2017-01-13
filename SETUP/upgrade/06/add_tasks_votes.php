<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');

echo "Creating 'tasks_votes' table...\n";
$sql = 'CREATE TABLE `tasks_votes` ('
        . ' `id` INT NOT NULL AUTO_INCREMENT, '
        . ' `task_id` MEDIUMINT(9) NOT NULL, '
        . ' `u_id` INT(10) NOT NULL, '
        . ' `vote_os` TINYINT(1) NOT NULL, '
        . ' `vote_browser` TINYINT(1) NOT NULL,'
        . ' INDEX (`task_id`, `u_id`),'
        . ' UNIQUE (`id`)'
        . ' )';
        
$result = mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));

echo "\nDone!\n";
?>
