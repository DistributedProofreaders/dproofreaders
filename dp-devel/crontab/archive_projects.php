<?
$relPath="./../pinc/";
include($relPath.'v_site.inc');
include($relPath.'f_dpsql.inc');
include($relPath.'connect.inc');
include($relPath.'project_states.inc');
$db_Connection=new dbConnect();

header('Content-type: text/plain');

// Find projects that were posted to PG a while ago
// (that haven't been archived yet), and:
// -- move the project's page-table to the archive database,
// -- mark the project as having been archived.

$n_days_ago = 7;

$old_date = time() - ($n_days_ago * 24 * 60 * 60);

$result = mysql_query("
    SELECT projectid, FROM_UNIXTIME(modifieddate), nameofwork
    FROM projects
    WHERE
        modifieddate <= $old_date
        AND archived = '0'
        AND state = '".PROJ_SUBMIT_PG_POSTED."'
") or die(mysql_error());

echo "Archiving page-tables for ", mysql_num_rows($result), " projects...\n";

while ( list($projectid, $mod_time, $nameofwork) = mysql_fetch_row($result) )
{
    echo "$projectid  $mod_time  \"$nameofwork\"\n";

    mysql_query("
        ALTER TABLE $projectid
        RENAME AS dp_archive.$projectid
    ") or die(mysql_error());

    mysql_query("
        UPDATE projects
        SET archived = '1'
        WHERE projectid='$projectid'
    ") or die(mysql_error());
}


echo "archive_projects.php executed.";
?>
