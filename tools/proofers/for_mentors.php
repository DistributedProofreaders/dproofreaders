<?php
/*
     Displays information useful to Mentors.
    (i.e. those who are second-round proofreading projects with difficulty = "BEGINNER")

    ************************************
*/
$relPath = '../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'dpsql.inc');          // for dpsql_dump_query
include_once($relPath.'prefs_options.inc');  // for PRIVACY_* constants
include_once($relPath.'theme.inc');          // for page marginalia
include_once($relPath.'project_states.inc'); // for PROJ_ declarations
include_once($relPath.'TallyBoard.inc');     // for TallyBoard
include_once($relPath.'Project.inc');

require_login();

// Display page header.
$title = _("For Mentors");
output_header($title);

echo "<h1>$title</h1>";

// ---------------------------------------------------------------

// Decide which mentoring-round we're dealing with.

$mentoring_round = get_round_param($_GET, 'round_id', null, true);
if (!$mentoring_round) {
    // Consider the page they came from.
    $referer = @$_SERVER['HTTP_REFERER'];

    // If they're coming to this page from a MENTORS ONLY book in X2,
    // referrer should contain &expected_state=X2.proj_avail.
    foreach (Rounds::get_all() as $round) {
        if (strpos($referer, $round->project_available_state)) {
            $mentoring_round = $round;
            break;
        }
    }

    if (!isset($mentoring_round)) {
        // Just take the first.
        foreach (Rounds::get_all() as $round) {
            if ($round->is_a_mentor_round()) {
                $mentoring_round = $round;
                break;
            }
        }
        if (!isset($mentoring_round)) {
            die("There are no mentoring rounds!");
        }
    }
}

if (!$mentoring_round->is_a_mentor_round()) {
    die("$mentoring_round->id is not a mentoring round!");
}

// ---------------------------------------------------------------

// Are there other mentoring rounds? If so, provide mentoring links for them.
$other_mentoring_rounds = [];
foreach (Rounds::get_all() as $round) {
    if ($round->is_a_mentor_round() && $round->id != $mentoring_round->id) {
        $other_mentoring_rounds[] = $round;
    }
}
if (count($other_mentoring_rounds) > 0) {
    echo "<p>" . _('Show this page for:');

    $links = [];
    foreach ($other_mentoring_rounds as $other_round) {
        $url = "$code_url/tools/proofers/for_mentors.php?round_id={$other_round->id}";
        $links[] = "<a href='$url'>{$other_round->id}</a>";
    }
    echo implode(" | ", $links);
    echo "</p>";
}

// ---------------------------------------------------------------

if (!user_can_work_on_beginner_pages_in_round($mentoring_round)) {
    echo "<p class='warning'>";
    echo sprintf(
        _("You do not have access to 'Mentors Only' projects in %s."),
        $mentoring_round->id
    );
    echo "</p>\n";
    exit;
}

// ---------------------------------------------------------------

$mentored_round = $mentoring_round->mentee_round;

// output a table of contents with links to anchors on this page
echo "<p>";
echo sprintf(_("Projects with pages available to Mentors in round %s."), "<b>$mentoring_round->id</b>");
echo " ";
echo sprintf(_("Please check the Saved column in the 'Which proofreader did each page...' section for each project listed below, to make sure you first work on the project with the oldest pages saved in %s."), "<b>$mentored_round->id</b>");
echo "</p>";

$projects_available = get_beginner_projects_in_state($mentoring_round->project_available_state, $mentored_round->id);
if ($projects_available) {
    echo "<ol>";
    foreach ($projects_available as $proj_obj) {
        echo "<li><a href='#$proj_obj->projectid'>";
        echo output_project_label($proj_obj->nameofwork, $proj_obj->authorsname, $proj_obj->t_left_mentee_round);
        echo "</a></li>";
    }
    echo "</ol>";
} else {
    echo "<p><i>" . _("No projects available") . "</i></p>";
}

// output a listing of projects in this mentoring round that are in a waiting state
echo "<p>";
echo sprintf(_("Projects for Mentors, waiting to be released into round %s."), "<b>$mentoring_round->id</b>");
echo " ";
echo _("Oldest project listed first.");
echo "</p>";

$projects_waiting = get_beginner_projects_in_state($mentoring_round->project_waiting_state, $mentored_round->id);
if ($projects_waiting) {
    echo "<ol>";
    foreach ($projects_waiting as $proj_obj) {
        $project = new Project($proj_obj->projectid);
        echo "<li>";
        echo output_project_label($proj_obj->nameofwork, $proj_obj->authorsname, $proj_obj->t_left_mentee_round);
        if (in_array($mentoring_round->project_waiting_state, $project->get_hold_states())) {
            // TRANSLATORS: string indicates that the project is "on hold"
            echo " <b>[" . _("On hold") . "]</b>";
        }
        echo "</li>";
    }
    echo "</ol>";
} else {
    echo "<p><i>" . _("No projects waiting") . "</i></p>";
}

// output details about each available project
foreach ($projects_available as $proj_obj) {
    output_project_details($mentored_round, $proj_obj->projectid, $proj_obj->nameofwork, $proj_obj->authorsname);
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function output_project_label($nameofwork, $authorsname, $date = null)
{
    // TRANSLATORS: format is <title> by <author>.
    echo sprintf(_("%1\$s by %2\$s"), $nameofwork, $authorsname);
    if (!is_null($date)) {
        $date = date("Y-m-d H:i T", $date);
        echo " ($date)";
    }
}

/**
 * For each mentorable project (in this round), show a summary (one line per mentee)
 * and then a listing (one line per page).
 */
function output_project_details($mentored_round, $projectid, $nameofwork, $authorsname)
{
    global $code_url;

    echo "<hr>";

    // Display project summary info
    $proj_url = "$code_url/project.php?id=$projectid";
    echo "<p id='$projectid' class='bold'>";
    output_project_label("<a href='$proj_url'>" . html_safe($nameofwork) . "</a>", html_safe($authorsname));
    echo "</p>" ;

    dpsql_dump_query(page_summary_sql($mentored_round, $projectid));

    echo "<p>" ;
    echo _('Which proofreader did each page...') ;
    echo "</p>";

    dpsql_dump_query(page_list_sql($mentored_round, $projectid));
}

// -------------------------------------------------------------------

function get_beginner_projects_in_state($state, $mentored_round)
{
    $mentored_round_detail = $mentored_round . '.proj_done';

    $sql = sprintf(
        "
        SELECT
            projects.projectid,
            projects.nameofwork,
            projects.authorsname,
            MAX(project_events.timestamp) AS t_left_mentee_round
        FROM projects LEFT OUTER JOIN project_events USING (projectid)
        WHERE
            difficulty = 'BEGINNER'
            AND project_events.event_type = 'transition'
            AND project_events.details2 = '%s'
            AND projects.state = '%s'
        GROUP BY projects.projectid
        ORDER BY
            project_events.timestamp ASC
        ",
        DPDatabase::escape($mentored_round_detail),
        DPDatabase::escape($state)
    );
    $result = DPDatabase::query($sql);
    $projects = [];
    while ($proj_obj = mysqli_fetch_object($result)) {
        $projects[] = $proj_obj;
    }
    return $projects;
}

// -------------------------------------------------------------------

function page_summary_sql($mentored_round, $projectid)
{
    global $code_url;

    $round_tallyboard = new TallyBoard($mentored_round->id, 'U');

    [$joined_with_user_page_tallies, $user_page_tally_column] =
            $round_tallyboard->get_sql_joinery_for_current_tallies('u.u_id');


    $mdetail_a_href = "<a href=\"{$code_url}/stats/members/mdetail.php?&id=";
    $proofreader = DPDatabase::escape(_("Proofreader"));
    $pages_this_project = DPDatabase::escape(_("Pages this project"));
    // TRANSLATORS: %s is a round ID
    $total_pages = DPDatabase::escape(sprintf(_("Total %s Pages"), $mentored_round->id));
    $joined = DPDatabase::escape(_("Joined"));
    return "
        SELECT
            CASE WHEN u.u_privacy = " . PRIVACY_ANONYMOUS . " THEN u.username
            ELSE CONCAT('$mdetail_a_href', u.u_id, '\">', u.username,'</a>')
            END AS '$proofreader',
            COUNT(1) AS '$pages_this_project',
            $user_page_tally_column AS '$total_pages',
            DATE_FORMAT(FROM_UNIXTIME(u.date_created),'%Y %b %d %H:%i') AS '$joined'
        FROM $projectid  AS p
            INNER JOIN users AS u ON p.{$mentored_round->user_column_name} = u.username
            $joined_with_user_page_tallies
        GROUP BY p.{$mentored_round->user_column_name}
    ";
}

// -------------------------------------------------------------------

function page_list_sql($mentored_round, $projectid)
{
    // copied from pinc/LPage.inc:
    $order = "
        (
            SELECT MIN({$mentored_round->time_column_name})
            FROM $projectid
            WHERE {$mentored_round->user_column_name}
              = p.{$mentored_round->user_column_name}
        ),
        {$mentored_round->user_column_name},
        image
    ";

    $page = DPDatabase::escape(_("Page"));
    $saved = DPDatabase::escape(_("Saved"));
    $proofreader = DPDatabase::escape(_("Proofreader"));
    $wc_events = DPDatabase::escape(_("WordCheck Events"));

    return "
        SELECT
            p.fileid AS '$page',
            DATE_FORMAT(FROM_UNIXTIME(p.{$mentored_round->time_column_name}),'%Y %b %d %H:%i') AS '$saved',
            p.{$mentored_round->user_column_name} AS '$proofreader',
            (SELECT count(*)
             FROM wordcheck_events
             WHERE projectid = '$projectid'
                AND round_id = '$mentored_round->id'
                AND username = p.{$mentored_round->user_column_name}
                AND SUBSTRING_INDEX(image, '.', 1)=p.fileid
            ) AS '$wc_events'
        FROM $projectid AS p
            INNER JOIN users AS u ON p.{$mentored_round->user_column_name} = u.username
        ORDER BY $order
    ";
}
