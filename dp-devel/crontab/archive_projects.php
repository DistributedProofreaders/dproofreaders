<?
$relPath="./../pinc/";
include($relPath.'v_site.inc');
include($relPath.'f_dpsql.inc');
include($relPath.'connect.inc');
include($relPath.'project_states.inc');
$db_Connection=new dbConnect();

header('Content-type: text/plain');

//this module sets projects as archived which have been posted more than 7 days
//this limits the number of projects that the stats code must look at

$n_days_ago = 7;

$old_date = time() - ($n_days_ago * 24 * 60 * 60);

$result = dpsql_query("
    SELECT projectid, FROM_UNIXTIME(modifieddate), nameofwork
    FROM projects
    WHERE
        modifieddate <= $old_date
        AND archived = '0'
        AND state = '".PROJ_SUBMIT_PG_POSTED."'
");
echo "Archiving page-tables for ", mysql_num_rows($result), " projects...\n";
while ( list($projectid, $mod_time, $nameofwork) = mysql_fetch_row($result) )
{
    echo "$projectid  $mod_time  \"$nameofwork\"\n";
    dpsql_query("
        UPDATE projects
        SET archived = '1'
        WHERE projectid='$projectid'
    ");
}


echo "archive_projects.php executed.";
?>
