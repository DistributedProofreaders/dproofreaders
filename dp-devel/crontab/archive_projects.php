<?
$relPath="./../pinc/";
include($relPath.'dp_main.inc');

//this module sets projects as archived which have been posted more than 3 days
//this limits the number of projects that the stats code must look at

    $old_date = time() - 259200; // 3 days ago.

    $result = mysql_query ("UPDATE `projects` SET archived = '1'
                            WHERE modifieddate <= $old_date
                            AND archived = '0' AND state = 'proj_submit_pgposted';");

    echo "archive_projects.php executed.";
?>