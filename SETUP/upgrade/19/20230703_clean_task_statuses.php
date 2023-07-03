<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------
// The following task status numbers will be set to 1 => "New":
//   3 => "Duplicate",
//   4 => "Fixed",
//   5 => "Invalid",
//   6 => "Later",
//   7 => "None",
//   8 => "Out of Date",
//   9 => "Postponed",
//   10 => "Rejected",
//   11 => "Remind",
//   12 => "Won't Fix",
//   13 => "Works for Me",
//   17 => "Implemented",
//

echo "Updating rarely used task statuses to 1 = 'New' ...\n";
$sql = "
    UPDATE tasks
        SET task_status = 1
        WHERE (task_status >= 3 AND task_status <= 13) OR task_status = 17
";

echo "$sql\n";

mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));

// ------------------------------------------------------------

echo "\nDone!\n";
