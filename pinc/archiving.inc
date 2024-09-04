<?php
include_once($relPath.'Stopwatch.inc');
include_once($relPath.'Project.inc'); // does_project_page_table_exist()

use Symfony\Component\Process\Process;

// include udb_user.php to place $archive_db_name in this file's scope.
global $archive_projects_dir, $archive_db_name;
include($relPath.'udb_user.php'); // $archive_db_name

/**
 * Archive a project
 *
 * This will:
 * - Move the project's page-table to the archive database.
 * - Move the project's directory out of $projects_dir &
 *   write out project's metadata to a JSON file.
 *   (for later off-site migration).
 * - Mark the project as having been archived.
 *
 * @param Project $project
 *   the Project to archive
 * @param bool $dry_run
 *   if true, do not actually archive, but rather echo information about what would happen
 */
function archive_project(Project $project, bool $dry_run = false)
{
    global $archive_projects_dir, $archive_db_name;

    if (!is_dir($archive_projects_dir)) {
        throw new RuntimeException(sprintf(_("Error: archive directory %s does not exist."), $archive_projects_dir));
    }

    $projectid = $project->projectid;

    $mod_time_str = date('Y-m-d H:i:s', $project->modifieddate);
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
            $process = new Process(["rm", "-rf", $smooth_dir]);
            $process->run();
            if (!$process->isSuccessful()) {
                throw new RuntimeException(sprintf(_("Unable to delete %s"), $smooth_dir));
            }
        }
    }

    $project_dir = $project->dir;
    $new_dir = "$archive_projects_dir/$projectid";
    if (file_exists($project_dir)) {
        if ($dry_run) {
            echo "    Write project metadata.\n";
        } else {
            $project->generate_metadata_json();
        }

        if ($dry_run) {
            echo "    Move $project_dir to $new_dir.\n";
        } else {
            // Remove uncompressed versions of whole-project texts, leaving zips.
            foreach (glob("$project_dir/projectID*.txt") as $filename) {
                if (!unlink($filename)) {
                    throw new RuntimeException(sprintf(_("Unable to delete %s"), $filename));
                }
            }

            if (!rename($project_dir, $new_dir)) {
                throw new RuntimeException(sprintf(_('Unable to move %1$s to %2$s', $project_dir, $new_dir)));
            }
        }
    } else {
        echo "    Warning: $project_dir does not exist.\n";
    }

    archive_ancillary_data_for_project_etc($project, '        ', $dry_run);

    if ($dry_run) {
        echo "    Mark project as archived.\n";
    } else {
        $sql = sprintf(
            "
            UPDATE projects
            SET archived = '1'
            WHERE projectid = '%s'
            ",
            DPDatabase::escape($projectid)
        );
        DPDatabase::query($sql);

        $project->log_project_event('[archiver]', 'archive');
    }
}

// -----------------------------------------------------------------------------

/**
 * Archive the ancillary info relating to $project
 * or to any project that was merged into it (and then deleted).
 *
 * @param Project $project
 *   the project to archive
 * @param string $indent
 *   text to place at the beginning of output lines
 * @param bool $dry_run
 *   if true, do not actually archive, but rather echo information about what would happen
 */
function archive_ancillary_data_for_project_etc(Project $project, string $indent, bool $dry_run)
{
    archive_ancillary_data_for_project($project, $indent, $dry_run);

    // Check for any deleted projects that were merged into this project,
    // and archive their ancillary info too.
    $sql = sprintf(
        "
        SELECT projectid
        FROM projects
        WHERE state = '%s'
            AND deletion_reason = '%s'
        ORDER BY modifieddate
        ",
        DPDatabase::escape(PROJ_DELETE),
        DPDatabase::escape("merged into $project->projectid")
    );
    $res2 = DPDatabase::query($sql);
    while ([$projectid] = mysqli_fetch_row($res2)) {
        $project2 = new Project($projectid);
        archive_ancillary_data_for_project($project2, $indent, $dry_run);
    }
}

/**
 * Archive the ancillary info relating to $project
 *
 * @param Project $project
 *   the project to archive
 * @param string $indent
 *   text to place at the beginning of output lines
 * @param bool $dry_run
 *   if true, do not actually archive, but rather echo information about what would happen
 */
function archive_ancillary_data_for_project(Project $project, string $indent, bool $dry_run)
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

/**
 * "Move" (copy and delete) rows pertaining to the given project
 * from $table_name in the main db to $table_name in the archive db.
 *
 * Assumes that the table has a column named 'projectid'.
 *
 * @param string $projectid
 *   the project ID
 * @param string $table_name
 *   name of the database table to modify
 * @param string $indent
 *   text to place at the beginning of output lines
 * @param bool $dry_run
 *   if true, do not actually archive, but rather echo information about what would happen
 */
function move_project_rows_to_archive_db(string $projectid, string $table_name, string $indent, bool $dry_run)
{
    global $archive_db_name;

    echo $indent, "$projectid $table_name: ";

    if ($dry_run) {
        echo "move rows to $archive_db_name.$table_name\n";
        return;
    }

    $watch = new Stopwatch();
    $watch->start();

    $sql = sprintf(
        "
        INSERT INTO $archive_db_name.$table_name
        SELECT *
        FROM $table_name
        WHERE projectid = '%s'
        ",
        DPDatabase::escape($projectid)
    );
    DPDatabase::query($sql);
    $n_copied = DPDatabase::affected_rows();
    echo sprintf("%4d rows copied", $n_copied);

    if ($n_copied == 0) {
        echo ".\n";
        return;
    }

    $sql = sprintf(
        "
        DELETE 
        FROM $table_name
        WHERE projectid = '%s'
        ",
        DPDatabase::escape($projectid)
    );
    DPDatabase::query($sql);
    $n_deleted = DPDatabase::affected_rows();
    if ($n_deleted == $n_copied) {
        echo " and deleted";
    } else {
        echo "BUT $n_deleted DELETED! ABORTING\n";
        exit;
    }

    $t_elapsed = $watch->read();
    if (0) { /** @phpstan-ignore-line */
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

$archival_recovery_multiplier = 1;
// If you're running the script when the site is down (e.g., when upgrading),
// you probably don't need to insert sleeps, so you can eliminate them
// by setting this variable to zero.
