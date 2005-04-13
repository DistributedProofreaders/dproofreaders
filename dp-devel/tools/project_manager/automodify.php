<?
// This script is actually 4 scripts in one file:
//   - Cleanup Files: Removes duplicates and checks in missing pages after 3 hours
//   - Promote Level: If a project is ready to be promoted, it sends it to round 2
//   - Complete Project: If a project has completed round 2, it sends it to post-processing or assign to the project manager
//   - Release Projects: If there are not enough projects available to end users, it will release projects waiting to be released
$relPath="./../../pinc/";

include($relPath.'connect.inc');
$db_Connection=new dbConnect();

include_once($relPath.'stages.inc');
include($relPath.'projectinfo.inc');
include($relPath.'project_trans.inc');
include_once($relPath.'bookpages.inc');
include_once($relPath.'page_ops.inc');

include('autorelease.php');
include('sendtopost.php');

$trace = FALSE;

// -----------------------------------------------------------------------------

function pages_indicate_bad_project( $projectid, $round )
// Do the states of the project's pages (in the given round)
// indicate that the project is bad?
{
    global $trace;

    // If it has no bad pages, it's good.
    //
    $n_bad_pages = mysql_result(mysql_query("
        SELECT COUNT(*) FROM $projectid WHERE state = '$round->page_bad_state'
        "),0);
    if ($trace) echo "n_bad_pages = $n_bad_pages\n";
    //
    if ($n_bad_pages == 0) return FALSE;


    // If it has at least 10 bad pages,
    // reported by at least 3 different users, it's bad.
    //
    $n_unique_reporters = mysql_result(mysql_query("
        SELECT COUNT(DISTINCT(b_user)) FROM $projectid WHERE state='$round->page_bad_state'
        "),0);
    if ($trace) echo "n_unique_reporters = $n_unique_reporters\n";
    //
    if ($n_bad_pages >= 10 && $n_unique_reporters >= 3) return TRUE;


    // In round 2, if it has any bad pages
    // and no available pages, it's bad.
    //
    if ($round->round_number == 2)
    {
        $n_avail_pages = mysql_result(mysql_query("
            SELECT COUNT(*) FROM $projectid WHERE state = '$round->page_avail_state'
            "),0);
        if ($trace) echo "n_avail_pages = $n_avail_pages\n";
        if ($n_avail_pages == 0) return TRUE;
    }

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
        echo "projectid  = {$project['projectid']}\n";
        echo "nameofwork = \"{$project['nameofwork']}\"\n";
        echo "state      = {$project['state']}\n";
        echo "\n";
        $have_echoed_blurb_for_this_project = 1;
    }
}

// -----------------------------------------------------------------------------

echo "<pre>\n";

$one_project = isset($_GET['project'])?$_GET['project']:0;

if ($one_project) {
    $verbose = 0;
    $condition = "projectid = '$one_project'";

    // log tracetimes
    $tracetime = time();
    mysql_query("INSERT INTO job_logs (filename, tracetime, event, comments)
               VALUES ('automodify.php', $tracetime, 'BEGIN', 'running for single proj $one_project')");



} else {
    $verbose = 1;

    $condition = "0";
    for ( $rn = 1; $rn <= MAX_NUM_PAGE_EDITING_ROUNDS; $rn++ )
    {
        $round = get_Round_for_round_number($rn);
        $condition .= "
            OR state = '{$round->project_available_state}'
            OR state = '{$round->project_complete_state}'
            OR state = '{$round->project_bad_state}'
        ";
    }

    // log tracetimes
    $tracetime = time();
    mysql_query("INSERT INTO job_logs (filename, tracetime, event, comments)
               VALUES ('automodify.php', $tracetime, 'BEGIN', 'running for all eligible projects')");


}
$allprojects = mysql_query("
    SELECT projectid, state, username, nameofwork
    FROM projects
    WHERE $condition
");

while ( $project = mysql_fetch_assoc($allprojects) ) {
    $have_echoed_blurb_for_this_project = 0;

    $projectid  = $project["projectid"];
    $state      = $project["state"];
    $username   = $project["username"];
    $nameofwork = $project["nameofwork"];

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
            if ( pages_indicate_bad_project( $projectid, $round ) )
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
                if ($trace) echo "changing its state to $appropriate_state\n";
                $error_msg = project_transition( $projectid, $appropriate_state );
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
           (Project_getNumPagesInState($projectid, $round->page_avail_state) == 0))
    )
    {

        if ($verbose)
        {
            ensure_project_blurb( $project );
            echo "    Reclaiming any MIA pages\n";
        }

        // Check in MIA pages

        $outtable = mysql_query("SELECT * FROM $projectid WHERE state = '$round->page_out_state' ORDER BY image ASC");
        if ($outtable != "") { $numoutrows = (mysql_num_rows($outtable)); } else $numoutrows = 0;

        if ($verbose) echo "        examining $numoutrows pages in '$round->page_out_state'\n";

        $n_reclaimed = 0;
        $page_num = 0;
        $dietime = time() - 14400; // 4 Hour TTL

        while ($page_num < $numoutrows) {

            $fileid = mysql_result($outtable, $page_num, "fileid");
            $timestamp = mysql_result($outtable, $page_num, $round->time_column_name);

            if ($timestamp == "") $timestamp = $dietime;

            if ($timestamp <= $dietime) {
                Page_reclaim( $projectid, $fileid, $round->round_number );
                $n_reclaimed++;
            }
            $page_num++;
        }

        if ($verbose) echo "            $n_reclaimed pages reclaimed\n";


        // Check in MIA temp pages

        $temptable = mysql_query("SELECT * FROM $projectid WHERE state = '$round->page_temp_state' ORDER BY image ASC");
        if ($temptable != "") { $numtemprows = (mysql_num_rows($temptable)); } else $numtemprows = 0;

        if ($verbose) echo "        examining $numtemprows pages in '$round->page_temp_state'\n";

        $n_reclaimed = 0;
        $page_num2 = 0;

        while ($page_num2 < $numtemprows) {

            $fileid = mysql_result($temptable, $page_num2, "fileid");
            $timestamp = mysql_result($temptable, $page_num2, $round->time_column_name);

            if ($timestamp == "") $timestamp = $dietime;

            if ($timestamp <= $dietime) {
                Page_reclaim( $projectid, $fileid, $round->round_number );
                $n_reclaimed++;
            }
            $page_num2++;
        }

        if ($verbose) echo "            $n_reclaimed pages reclaimed\n";


        // Decide whether the project is finished its current round.
        if ( $state == $round->project_available_state )
        {
            $num_done_pages  = Project_getNumPagesInState($projectid, $round->page_save_state);
            $num_total_pages = Project_getNumPages($projectid);

            if ($num_done_pages == $num_total_pages)
            {
                if ($verbose) echo "    All $num_total_pages pages are in '$round->page_save_state'.\n";
                $state = $round->project_complete_state;
            }
            else
            {
                if ($verbose) echo "    Only $num_done_pages of $num_total_pages pages are in '$round->page_save_state'.\n";
                $state = $round->project_available_state;
            }
        }

        project_update_page_counts( $projectid );

        if ($verbose)
        {
            ensure_project_blurb( $project );
            echo "    Advancing \"$nameofwork\" to $state\n";
        }
        $error_msg = project_transition( $projectid, $state );
        if ($error_msg)
        {
            echo "$error_msg\n";
            continue;
        }
    }


    // Promote Level
    if ($state == $round->project_complete_state
        && $round->round_number < MAX_NUM_PAGE_EDITING_ROUNDS)
    {
        $next_round = get_Round_for_round_number( 1 + $round->round_number );

        project_update_page_counts( $projectid );

        if ( hold_project_between_rounds( $project ) )
        {
            $next_round_state = $next_round->project_unavailable_state;
        }
        else
        {
            $next_round_state = $next_round->project_waiting_state;
        }

        if ($verbose)
        {
            ensure_project_blurb( $project );
            echo "    Promoting \"$nameofwork\" to $next_round_state\n";
        }

        $error_msg = project_transition( $projectid, $next_round_state );
        if ($error_msg)
        {
            echo "$error_msg\n";
            continue;
        }

        Pages_prepForRound( $projectid, $next_round->round_number );

        if ( $next_round_state == $next_round->project_unavailable_state )
        {
            maybe_mail_project_manager(
                $project,
                "This project is being held between rounds $round->round_number and $next_round->round_number.",
                "DP Project Held Between Rounds"); 
        }
    }

    // Completed Level
    if ($state == $round->project_complete_state
        && $round->round_number == MAX_NUM_PAGE_EDITING_ROUNDS)
    {
        sendtopost($projectid, $username);
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
    mysql_query("INSERT INTO job_logs (filename, tracetime, event, comments)
               VALUES ('automodify.php', $tracetimea, 'MIDDLE', 'pre autorelease, $tooktime seconds so far')");

    autorelease();

    // log tracetimes
    $tracetimea = time();
    $tooktime = $tracetimea - $tracetime;
    mysql_query("INSERT INTO job_logs (filename, tracetime, event, comments)
               VALUES ('automodify.php', $tracetimea, 'END', 'post autorelease, started at $tracetime, took $tooktime seconds')");

}
else
{

    // log tracetimes
    $tracetimea = time();
    $tooktime = $tracetimea - $tracetime;
    mysql_query("INSERT INTO job_logs (filename, tracetime, event, comments)
               VALUES ('automodify.php', $tracetimea, 'END', 'end single, started at $tracetime, took $tooktime seconds')");


    echo "<META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=projectmgr.php\">";
}

// -----------------------------------------------------------------------------

function hold_project_between_rounds( $project )
{
    return FALSE;
    // return ( $project['nameofwork'] == 'Copyright Renewals 1950' );

    // If holding between rounds becomes popular, we'll obviously
    // want a more flexible way to answer this question.
}

?>
