<?
$relPath="./../pinc/";
include($relPath.'v_site.inc');
include($relPath.'connect.inc');
include($relPath.'project_states.inc');
$db_Connection=new dbConnect();

//this module sets projects as archived which have been posted more than 3 days
//this limits the number of projects that the stats code must look at

$n_days_ago = 7;

$old_date = time() - ($n_days_ago * 24 * 60 * 60);

$result = mysql_query ("
    UPDATE `projects`
    SET archived = '1'
    WHERE
        modifieddate <= $old_date
        AND archived = '0'
        AND state = '".PROJ_SUBMIT_PG_POSTED."';
");

echo "archive_projects.php executed.";
?>
