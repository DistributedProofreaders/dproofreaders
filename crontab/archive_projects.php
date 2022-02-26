<?php
$relPath = "./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'archiving.inc');

require_localhost_request();

header('Content-type: text/plain');

// Find projects that were posted to PG a while ago
// (that haven't been archived yet), and archive them.

$DAYS_TO_RETAIN = 100;

$dry_run = array_get($_GET, 'dry_run', '');
if ($dry_run) {
    echo "This is a dry run.\n";
}

$sql = sprintf("
    SELECT *
    FROM projects
    WHERE
        modifieddate <= UNIX_TIMESTAMP() - (24 * 60 * 60) * $DAYS_TO_RETAIN
        AND archived = '0'
        AND state = '%s'
    ORDER BY modifieddate
", DPDatabase::escape(PROJ_SUBMIT_PG_POSTED));
$result = DPDatabase::query($sql);

echo "Archiving page-tables for ", mysqli_num_rows($result), " projects...\n";

while ($project_data = mysqli_fetch_assoc($result)) {
    $project = new Project($project_data);
    archive_project($project, $dry_run);
}

echo "archive_projects.php executed.\n";
