<?php
$relPath='../../../pinc/';
include($relPath.'connect.inc');
$db_Connection=new dbConnect();

//----------------------------------------------------------------------------------------------------------------------
$result = mysql_query("ALTER TABLE `tasks` ADD `related_postings` MEDIUMTEXT NOT NULL") or die(mysql_error());

$empty_array_value = base64_encode(serialize(array()));
mysql_query("
    UPDATE tasks
    SET related_postings='$empty_array_value'
    WHERE related_postings=''
") or die(mysql_error());

echo "<center><p>Addition of `related_postings` field to `tasks` table complete!";
?>
