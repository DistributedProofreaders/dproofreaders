<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'Project.inc');

header('Content-type: text/plain');

// ------------------------------------------------------------

// We've changed the t_latest_home_visit field to only track PP/PPV visits.
// Delete rows from the user_project_info table that ONLY have a value for this
// field and only if the row is for a non-PP/PPVer of the project. This
// deletes entries that were only added by users who opened the project page
// but didn't otherwise interact with the project (proofing a page, subscribing
// to events, etc).

echo "Pruning entries in user_project_info\n";
$sql = "
    SELECT projectid
    FROM projects
    ORDER BY projectid
";

$result = mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));

while ([$projectid] = mysqli_fetch_row($result)) {
    $project = new Project($projectid);
    $sql = sprintf("
        SELECT username
        FROM user_project_info
        WHERE projectid = '%s'
            AND t_latest_page_event = 0
            AND iste_round_available = 0
            AND iste_round_complete = 0
            AND iste_pp_enter = 0
            AND iste_sr_available = 0
            AND iste_sr_complete = 0
            AND iste_ppv_enter = 0
            AND iste_posted = 0
            AND iste_sr_reported = 0
        ORDER BY username
    ", DPDatabase::escape($projectid));

    $upi_result = mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));

    $upi_users_to_delete = [];
    $upi_users_to_retain = [];
    while ([$username] = mysqli_fetch_row($upi_result)) {
        if ($username != $project->PPer and $username != $project->PPVer) {
            $upi_users_to_delete[] = $username;
        } else {
            $upi_users_to_retain[] = $username;
        }
    }
    $upi_rows_delete = count($upi_users_to_delete);
    $upi_rows_retain = count($upi_users_to_retain);

    // skip the table if there are no rows to delete
    if ($upi_rows_delete == 0) {
        continue;
    }

    // delete the row -- because ($projectid, $username) is the primary key
    // we can delete it just based on those two fields alone
    $sql = sprintf(
        "
        DELETE FROM user_project_info
        WHERE projectid = '%s'
            AND username in (%s)
    ",
        DPDatabase::escape($projectid),
        surround_and_join(array_map("DPDatabase::escape", $upi_users_to_delete), "'", "'", ",")
    );

    mysqli_query(DPDatabase::get_connection(), $sql) or die(mysqli_error(DPDatabase::get_connection()));

    echo "$projectid: rows with non-zero t_latest_home_visit to delete = $upi_rows_delete; to retain = $upi_rows_retain\n";
}

// ------------------------------------------------------------

echo "\nDone!\n";
