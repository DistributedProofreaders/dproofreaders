<?
$relPath="./../pinc/";
include($relPath.'misc.inc');
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

$dry_run = array_get( $_GET, 'dry_run', '' );
if ($dry_run)
{
    echo "This is a dry run.\n";
}

$n_days_ago = 7;

$old_date = time() - ($n_days_ago * 24 * 60 * 60);

$result = mysql_query("
    SELECT projectid, FROM_UNIXTIME(modifieddate), nameofwork
    FROM projects
    WHERE
        modifieddate <= $old_date
        AND archived = '0'
        AND state = '".PROJ_SUBMIT_PG_POSTED."'
    ORDER BY modifieddate
") or die(mysql_error());

echo "Archiving page-tables for ", mysql_num_rows($result), " projects...\n";

while ( list($projectid, $mod_time, $nameofwork) = mysql_fetch_row($result) )
{
    echo "$projectid  $mod_time  \"$nameofwork\"\n";

    if ($dry_run)
    {
        echo "    Move table $projectid to dp_archive.\n";
    }
    else
    {
        mysql_query("
            ALTER TABLE $projectid
            RENAME AS dp_archive.$projectid
        ") or die(mysql_error());
    }

    if ($dry_run)
    {
        echo "    Mark project as archived.\n";
    }
    else
    {
        mysql_query("
            UPDATE projects
            SET archived = '1'
            WHERE projectid='$projectid'
        ") or die(mysql_error());
    }
}


echo "archive_projects.php executed.";

// vim: sw=4 ts=4 expandtab
?>
