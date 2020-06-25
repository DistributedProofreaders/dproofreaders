<?php
$relPath="./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'archiving.inc');

// check that caller is localhost or bail
if(!requester_is_localhost())
    die("You are not authorized to perform this request.");

header('Content-type: text/plain');

// Find projects that were posted to PG a while ago
// (that haven't been archived yet), and archive them.

$dry_run = array_get( $_GET, 'dry_run', '' );
if ($dry_run)
{
    echo "This is a dry run.\n";
}

$result = mysqli_query(DPDatabase::get_connection(), "
    SELECT *
    FROM projects
    WHERE
        modifieddate <= UNIX_TIMESTAMP() - (24 * 60 * 60) * 100
        AND archived = '0'
        AND state = '".PROJ_SUBMIT_PG_POSTED."'
    ORDER BY modifieddate
") or die(DPDatabase::log_error());

echo "Archiving page-tables for ", mysqli_num_rows($result), " projects...\n";

while ( $project = mysqli_fetch_object($result) )
{
    archive_project($project, $dry_run);
}

echo "archive_projects.php executed.";

// vim: sw=4 ts=4 expandtab
