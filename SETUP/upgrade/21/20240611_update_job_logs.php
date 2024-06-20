<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');

// ------------------------------------------------------------

echo "Altering job_logs...\n";

$sql = "
    ALTER TABLE `job_logs`
        MODIFY COLUMN `filename` varchar(40) NOT NULL default '',
        MODIFY COLUMN `event` varchar(20) NOT NULL default '',
        ADD COLUMN `succeeded` tinyint default null,
        ADD INDEX timestamp (tracetime, succeeded)
";

mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));

// ------------------------------------------------------------

echo "\nDone!\n";
