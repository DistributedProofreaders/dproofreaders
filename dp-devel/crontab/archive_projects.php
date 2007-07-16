<?
$relPath="./../pinc/";
include($relPath.'misc.inc');
include($relPath.'connect.inc');
include($relPath.'project_states.inc');
include_once($relPath.'archiving.inc');
$db_Connection=new dbConnect();

header('Content-type: text/plain');

// Find projects that were posted to PG a while ago
// (that haven't been archived yet), and archive them.

$dry_run = array_get( $_GET, 'dry_run', '' );
if ($dry_run)
{
    echo "This is a dry run.\n";
}

$result = mysql_query("
    SELECT *
    FROM projects
    WHERE
        modifieddate <= UNIX_TIMESTAMP() - (24 * 60 * 60) * IF( INSTR(nameofwork,'{P3 Qual}'), 28, 7 )
        AND archived = '0'
        AND state = '".PROJ_SUBMIT_PG_POSTED."'
    ORDER BY modifieddate
") or die(mysql_error());

echo "Archiving page-tables for ", mysql_num_rows($result), " projects...\n";

while ( $project = mysql_fetch_object($result) )
{
    archive_project($project, $dry_run);
}

echo "archive_projects.php executed.";

// vim: sw=4 ts=4 expandtab
?>
