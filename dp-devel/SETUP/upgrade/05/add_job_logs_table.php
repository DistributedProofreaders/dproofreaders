<?php
$relPath='./pinc/';
include($relPath.'connect.inc');
$db_Connection=new dbConnect();

//----------------------------------------------------------------------------------------------------------------------

$result = mysql_query("CREATE TABLE `job_logs` (
`filename` VARCHAR( 40 ) DEFAULT '0' NOT NULL ,
`tracetime` INT( 12 ) UNSIGNED DEFAULT '0' NOT NULL ,
`event` VARCHAR( 20 ) DEFAULT '0' NOT NULL ,
`comments` VARCHAR( 255 ) 
) TYPE = MYISAM") ;



echo "<center>";
echo "<p>Addition of 'job_logs' table complete!";
?>
