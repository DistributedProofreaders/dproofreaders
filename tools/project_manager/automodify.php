<?php
// This script is actually 4 scripts in one file:
//   - Cleanup Files: Removes duplicates and checks in missing pages after 3 hours
//   - Promote Level: If a project is ready to be promoted, it sends it to round 2
//   - Complete Project: If a project has completed round 2, it sends it to post-processing or assign to the project manager
//   - Release Projects: If there are not enough projects available to end users, it will release projects waiting to be released
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'project_trans.inc');
include_once($relPath.'DPage.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'Project.inc'); // project_get_auto_PPer
include_once($relPath.'misc.inc'); // requester_is_localhost(), html_safe()
include_once('autorelease.inc');

$one_project = get_projectID_param($_GET, 'project', TRUE);
$refresh_url = @$_GET['return_uri'];

// The following users are authorized to run this script:
// 1) localhost (eg: run from crontab) - can operate on all projects
// 2) SA and PFs - can operates on all projects
// 3) PMs - can operate only on their own projects
if(!requester_is_localhost()) {
    require_login();

    if ( !user_is_a_sitemanager() && !user_is_proj_facilitator() ) 
    {
        if ($one_project)
        {
            $project = new Project($one_project);
            if(!$project->can_be_managed_by_user($pguser))
                die('You are not authorized to invoke this script.');
        }
        else
        {
            die('You are not authorized to invoke this script.');
        }
    }
}

if (!isset($refresh_url)) $refresh_url = 'projectmgr.php';

$trace = FALSE;

// -----------------------------------------------------------------------------

function pages_indicate_bad_project( $project, $round )
// Do the states of the project's pages (in the given round)
// indicate that the project is bad?
{
    global $trace;

    // (Note duplication of code with LPage::markAsBad in LPage.inc)

    // If it has no bad pages, it's good.
    //
    $n_bad_pages = $project->get_num_pages_in_state($round->page_bad_state);
    if ($trace) echo "n_bad_pages = $n_bad_pages\n";
    //
    if ($n_bad_pages == 0) return FALSE;


    // If it has at least 10 bad pages,
    // reported by at least 3 different users, it's bad.
    //
    $n_unique_reporters = $project->get_num_pages_in_state($round->page_bad_state, "DISTINCT(b_user)");
    if ($trace) echo "n_unique_reporters = $n_unique_reporters\n";
    //
    if ($n_bad_pages >= 10 && $n_unique_reporters >= 3) return TRUE;


    // Otherwise, it's good.
    //
    return FALSE;
}

// -----------------------------------------------------------------------------

$have_echoed_blurb_for_this_project = 0;

function ensure_project_blurb( $project )
{
    global $have_echoed_blurb_for_this_project;

    if ( !$have_echoed_blurb_for_this_project )
    {
        echo "\n";
        echo "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX\n";
        echo "projectid  = {$project->projectid}\n";
        echo "nameofwork = \"" . html_safe($project->nameofwork) . "\"\n";
        echo "state      = {$project->state}\n";
        echo "\n";
        $have_echoed_blurb_for_this_project = 1;
    }
}

// -----------------------------------------------------------------------------

echo "<pre>\n";

if ($one_project) {
    $verbose = $GLOBALS['testing'];
    $condition = sprintf("projectid = '%s'", DPDatabase::escape($one_project));

    // log tracetimes
    $tracetime = time();
    $sql = sprintf("
        INSERT INTO job_logs (filename, tracetime, event, comments)
        VALUES ('automodify.php', %d, 'BEGIN', 'running for single proj %s')",
        $tracetime,
        DPDatabase::escape($one_project));
    DPDatabase::query($sql);



} else {
    $verbose = 1;

    $condition = "0";
    for ( $rn = 1; $rn <= MAX_NUM_PAGE_EDITING_ROUNDS; $rn++ )
    {
        $round = get_Round_for_round_number($rn);
        $condition .= sprintf("
            OR state = '%s'
            OR state = '%s'
            OR state = '%s'",
        DPDatabase::escape($round->project_available_state),
        DPDatabase::escape($round->project_complete_state),
        DPDatabase::escape($round->project_bad_state));
    }

    // log tracetimes
    $tracetime = time();
    $sql = sprintf("
        INSERT INTO job_logs (filename, tracetime, event, comments)
        VALUES ('automodify.php', %d, 'BEGIN', 'running for all eligible projects')",
        $tracetime);
    DPDatabase::query($sql);


}
$sql = "
    SELECT projectid
    FROM projects
    WHERE $condition
    ORDER BY projectid";
$allprojects = DPDatabase::query($sql);
// The "ORDER BY" clause isn't essential,
// it's just there to ensure consistency of order when testing.

while ( list($projectid) = mysqli_fetch_row($allprojects) ) {
    $have_echoed_blurb_for_this_project = 0;

    $project = new Project($projectid);
    $state = $project->state;
    $nameofwork = $project->nameofwork;

    if ($trace)
    {
        ensure_project_blurb( $project );
    }

    // Decide which round the project is in
    $round = get_Round_for_project_state($state);
    if ( is_null($round) )
    {
        echo "    automodify.php: unexpected state $state for project $projectid\n";
        continue;
    }

    //Bad Page Error Check
    {
        if ( ($state == $round->project_available_state) || ($state == $round->project_bad_state) )
        {
            if ( pages_indicate_bad_project( $project, $round ) )
            {
                // This project's pages indicate that it's bad.
                // If it isn't marked as such, make it so.
                if ($trace) echo "project looks bad.\n";
                $appropriate_state = $round->project_bad_state;
            }
            else
            {
                // Pages don't indicate that the project is bad.
                // (Although it could be bad for some other reason. Hmmm.)
                if ($trace) echo "project looks okay.\n";
                $appropriate_state = $round->project_available_state;
            }

            if ($state != $appropriate_state)
            {
                if ($verbose)
                {
                    ensure_project_blurb( $project );
                    echo "    Re badness, changing state to $appropriate_state\n";
                }
                if ($trace) echo "changing its state to $appropriate_state\n";
                $error_msg = project_transition( $projectid, $appropriate_state, PT_AUTO );
                if ($error_msg)
                {
                    echo "$error_msg\n";
                }
                $state = $appropriate_state;
            }
        }
    }

    if (
        ($one_project) ||
        (($state == $round->project_available_state) &&
           ($project->get_num_pages_in_state($round->page_avail_state) == 0))
    )
    {

        // Reclaim MIA pages

        if ($verbose)
        {
            ensure_project_blurb( $project );
            echo "    Reclaiming any MIA pages\n";
        }

        $n_hours_to_wait = 4;
        $max_reclaimable_time = time() - $n_hours_to_wait * 60 * 60;

        $sql = sprintf("
            SELECT image
            FROM $projectid
            WHERE state IN ('%s','%s')
                AND $round->time_column_name <= %d
            ORDER BY image ASC",
            $round->page_out_state,
            $round->page_temp_state,
            $max_reclaimable_time);
        try {
            $res = DPDatabase::query($sql);
        } catch(DBQueryError $error) {
            echo $error->getMessage() . "\n";
            echo "Skipping further processing of this project.\n";
            continue;
        }

        $n_reclaimable_pages = mysqli_num_rows($res);
        if ($verbose) echo "        reclaiming $n_reclaimable_pages pages\n";

        while ( list($image) = mysqli_fetch_row($res) )
        {
            Page_reclaim( $projectid, $image, $round, '[automodify.php]' );
        }


        // Decide whether the project is finished its current round.
        if ( $state == $round->project_available_state )
        {
            $num_done_pages  = $project->get_num_pages_in_state($round->page_save_state);
            $num_total_pages = $project->get_num_pages($projectid);

            if ($num_done_pages != $num_total_pages)
            {
                if ($verbose) echo "    Only $num_done_pages of $num_total_pages pages are in '$round->page_save_state'.\n";
                continue;
            }

            if ($verbose) echo "    All $num_total_pages pages are in '$round->page_save_state'.\n";

            if ( project_has_a_hold_in_state($projectid, $state) )
            {
                if ($verbose)
                {
                    echo "    Normally, this project would now advance to {$round->project_complete_state},\n";
                    echo "    but it has a hold in $state, so it stays where it is.\n";
                }
                continue;
            }

            $state = $round->project_complete_state;
            if ($verbose) echo "    Advancing \"" . html_safe($nameofwork) . "\" to $state\n";

            $error_msg = project_transition( $projectid, $state, PT_AUTO );
            if ($error_msg)
            {
                echo "$error_msg\n";
                continue;
            }
        }
    }

    if ($state == $round->project_complete_state)
    {
        // The project is ready to exit this round.

        if ($round->round_number < MAX_NUM_PAGE_EDITING_ROUNDS)
        {
            // It goes to the next round.
            $next_round = get_Round_for_round_number( 1 + $round->round_number );
            $new_state = $next_round->project_waiting_state;
        }
        elseif ($round->round_number == MAX_NUM_PAGE_EDITING_ROUNDS)
        {
            // It goes into post-processing.
            if ( is_null(project_get_auto_PPer($projectid)) )
            {
                $new_state = PROJ_POST_FIRST_AVAILABLE;
            }
            else
            {
                $new_state = PROJ_POST_FIRST_CHECKED_OUT;
            }
        }
        else
        {
            die("round_number is {$round->round_number}???\n");
        }

        if ($verbose)
        {
            ensure_project_blurb( $project );
            echo "    Promoting \"" . html_safe($nameofwork) . "\" to $new_state\n";
        }

        $error_msg = project_transition( $projectid, $new_state, PT_AUTO );
        if ($error_msg)
        {
            echo "$error_msg\n";
        }
    }
}

if ($trace) echo "\n";

if ($verbose)
{
    echo "\n";
    echo "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX\n";
    echo "\n";
}

echo "</pre>\n";

if (!$one_project)
{
    // log tracetimes
    $tracetimea = time();
    $tooktime = $tracetimea - $tracetime;
    $sql = sprintf("
        INSERT INTO job_logs (filename, tracetime, event, comments)
        VALUES ('automodify.php', %d, 'MIDDLE', 'pre autorelease, %d seconds so far')",
        $tracetimea,
        $tooktime);
    DPDatabase::query($sql);

    autorelease();

    // log tracetimes
    $tracetimea = time();
    $tooktime = $tracetimea - $tracetime;
    $sql = sprintf("
        INSERT INTO job_logs (filename, tracetime, event, comments)
        VALUES ('automodify.php', %d, 'END', 'post autorelease, started at %d, took %d seconds')",
        $tracetimea,
        $tracetime,
        $tooktime);
    DPDatabase::query($sql);

}
else
{

    // log tracetimes
    $tracetimea = time();
    $tooktime = $tracetimea - $tracetime;
    $sql = sprintf("
        INSERT INTO job_logs (filename, tracetime, event, comments)
        VALUES ('automodify.php', %d, 'END', 'end single, started at %d, took %d seconds')",
        $tracetimea,
        $tracetime,
        $tooktime);
    DPDatabase::query($sql);


    echo "<META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=$refresh_url\">";
}

// vim: sw=4 ts=4 expandtab
