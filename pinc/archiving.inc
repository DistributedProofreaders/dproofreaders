<?php
include_once($relPath.'site_vars.php'); // $projects_dir $archive_projects_dir
include_once($relPath.'project_states.inc'); // PROJ_SUBMIT_PG_POSTED PROJ_DELETE
include_once($relPath.'Stopwatch.inc');
include_once($relPath.'Project.inc'); // does_project_page_table_exist()
// connect.inc include()s udb_user.php but only in a local scope, so we
// need to include it again to place $archive_db_name in this scope.
include($relPath.'udb_user.php'); // $archive_db_name


function archive_project($project, $dry_run)
// -- Move the project's page-table to the archive database.
// -- Move the project's directory out of $projects_dir &
//    write out project's metadata to a JSON file.
//    (for later off-site migration).
// -- Mark the project as having been archived.
{
    global $archive_projects_dir, $archive_db_name;

    if (!is_dir($archive_projects_dir)) {
        die("Error: archive directory $archive_projects_dir does not exist.\n");
    }

    $projectid = $project->projectid;

    $mod_time_str = strftime('%Y-%m-%d %H:%M:%S', $project->modifieddate);
    echo "$projectid ($mod_time_str) \"$project->nameofwork\"\n";

    if (!$project->pages_table_exists) {
        echo "    Table $projectid does not exist.\n";
    } elseif ($dry_run) {
        echo "    Move table $projectid to $archive_db_name.\n";
    } else {
        validate_projectID($projectid);
        $sql = "
            ALTER TABLE $projectid
            RENAME AS $archive_db_name.$projectid
        ";
        DPDatabase::query($sql);
    }

    $smooth_dir = "$project->dir/smooth";
    if (is_dir($smooth_dir)) {
        if ($dry_run) {
            echo "    Remove $smooth_dir directory.\n";
        } else {
            exec("rm -rf " . escapeshellarg($smooth_dir));
        }
    }

    $project_dir = $project->dir;
    $new_dir = "$archive_projects_dir/$projectid";
    if (file_exists($project_dir)) {
        if ($dry_run) {
            echo "    Move $project_dir to $new_dir.\n";
        } else {
            // Remove uncompressed versions of whole-project texts, leaving zips.
            exec("rm $project_dir/projectID*.txt");
            rename($project_dir, $new_dir) or die("Unable to move $project_dir to $new_dir");
        }
    } else {
        echo "    Warning: $project_dir does not exist.\n";
    }

    archive_ancillary_data_for_project_etc($project, '        ', $dry_run);

    generate_project_metadata_json($project, "$new_dir/metadata.json", $dry_run);

    if ($dry_run) {
        echo "    Mark project as archived.\n";
    } else {
        $sql = sprintf("
            UPDATE projects
            SET archived = '1'
            WHERE projectid = '%s'
        ", DPDatabase::escape($projectid));
        DPDatabase::query($sql);

        $project->log_project_event('[archiver]', 'archive');
    }
}

// -----------------------------------------------------------------------------

function generate_project_metadata_json($project, $filename, $dry_run)
// Write out project's metadata to a JSON file within the archive
{
    $metadata = [
        "projectid" => $project->projectid,
        "nameofwork" => $project->nameofwork,
        "authorsname" => $project->authorsname,
        "language" => $project->language,
        "genre" => $project->genre,
        "postednum" => $project->postednum,
        "image_source" => $project->image_source,
    ];

    if ($dry_run) {
        echo "    Write project metadata to $filename.\n";
    } else {
        file_put_contents($filename, json_encode($metadata,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE |
            JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK))
            or die("Error writing project metadata to $filename.");
    }
}

function archive_ancillary_data_for_project_etc($project, $indent, $dry_run)
// Archive the ancillary info relating to $project
// or to any project that was merged into it (and then deleted).
{
    archive_ancillary_data_for_project($project, $indent, $dry_run);

    // Check for any deleted projects that were merged into this project,
    // and archive their ancillary info too.
    $sql = sprintf("
        SELECT projectid, modifieddate, state
        FROM projects
        WHERE state = '%s'
        AND deletion_reason = '%s'
        ORDER BY modifieddate
    ", DPDatabase::escape(PROJ_DELETE),
        DPDatabase::escape("merged into $project->projectid"));
    $res2 = DPDatabase::query($sql);
    while ($project2 = mysqli_fetch_object($res2)) {
        archive_ancillary_data_for_project($project2, $indent, $dry_run);
    }
}

function archive_ancillary_data_for_project($project, $indent, $dry_run)
{
    $projectid = $project->projectid;

    if ($project->state != PROJ_SUBMIT_PG_POSTED && $project->state != PROJ_DELETE) {
        echo $indent, "$projectid state is '$project->state'; skipping.\n";
        global $skipped_due_to_state;
        $skipped_due_to_state[] = "$projectid $project->state";
        return;
    }

    if (does_project_page_table_exist($projectid) == false || $dry_run) {
        // project's page table doesn't exist.
        // Good, that's consistent with it having archived='1'
    } else {
        // project's page table exists (or something else weird happened)
        echo $indent, "$projectid page table exists; skipping.\n";
        global $skipped_due_to_page_table;
        $skipped_due_to_page_table[] = $projectid;
        return;
    }

    // okay, proceed
    move_project_rows_to_archive_db($projectid, 'page_events     ', $indent, $dry_run);
    move_project_rows_to_archive_db($projectid, 'wordcheck_events', $indent, $dry_run);
}

// -----------------------------------------------------------------------------

function move_project_rows_to_archive_db($projectid, $table_name, $indent, $dry_run)
// "Move" (i.e. copy and delete) rows pertaining to the given project
// from $table_name in the main db to $table_name in the archive db.
//
// (Assumes that the table has a column named 'projectid'.)
{
    global $archive_db_name;

    echo $indent, "$projectid $table_name: ";

    if ($dry_run) {
        echo "move rows to $archive_db_name.$table_name\n";
        return;
    }

    $watch = new Stopwatch();
    $watch->start();

    $sql = sprintf("
        INSERT INTO $archive_db_name.$table_name
        SELECT *
        FROM $table_name
        WHERE projectid = '%s'
    ", DPDatabase::escape($projectid));
    DPDatabase::query($sql);
    $n_copied = DPDatabase::affected_rows();
    echo sprintf("%4d rows copied", $n_copied);

    if ($n_copied == 0) {
        echo ".\n";
        return;
    }

    $sql = sprintf("
        DELETE 
        FROM $table_name
        WHERE projectid = '%s'
    ", DPDatabase::escape($projectid));
    DPDatabase::query($sql);
    $n_deleted = DPDatabase::affected_rows();
    if ($n_deleted == $n_copied) {
        echo " and deleted";
    } else {
        echo "BUT $n_deleted DELETED! ABORTING\n";
        exit;
    }

    $t_elapsed = $watch->read();
    if (0) {
        echo sprintf(" (in %.2f sec => %d rows/sec)", $t_elapsed, $n_deleted / $t_elapsed);
    }
    echo ".\n";

    // Moving a project-worth of rows can take a while,
    // so if you're running the script on a live site,
    // this sleep gives the db server some time to recover
    // in between queries.
    global $archival_recovery_multiplier;
    sleep($t_elapsed * $archival_recovery_multiplier);
}

$archival_recovery_multiplier = 6;
// If you're running the script when the site is down (e.g., when upgrading),
// you probably don't need to insert sleeps, so you can eliminate them
// by setting this variable to zero.

// -----------------------------------------------------------------------------

function archival_skip_logs_init()
{
    global $skipped_due_to_state, $skipped_due_to_page_table;

    $skipped_due_to_state = [];
    $skipped_due_to_page_table = [];
}

function archival_skip_logs_dump()
{
    global $skipped_due_to_state, $skipped_due_to_page_table;

    echo "\n";
    echo count($skipped_due_to_state), " skipped_due_to_state:\n";
    foreach ($skipped_due_to_state as $str) {
        echo "    $str\n";
    }

    echo "\n";
    echo count($skipped_due_to_page_table), " skipped_due_to_page_table:\n";
    foreach ($skipped_due_to_page_table as $str) {
        echo "    $str\n";
    }
}
