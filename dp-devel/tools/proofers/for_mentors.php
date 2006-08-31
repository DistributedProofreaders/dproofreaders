<?
/*
     Displays information useful to Mentors.
    (i.e. those who are second-round proofreading projects with difficulty = "BEGINNER")

    ************************************
*/
$relPath='../..//pinc/';
// to establish logon
include_once($relPath.'dp_main.inc');
// for dpsql_dump_query
include_once($relPath.'dpsql.inc');
// for PRIVACY_* constants
include_once($relPath.'prefs_options.inc');
// for page marginalia
include_once($relPath.'theme.inc');
// for PROJ_ declarations
include_once($relPath.'project_states.inc');
// for TallyBoard
include_once($relPath.'TallyBoard.inc');

function project_sql($round_id)
{
    return
        "SELECT
            projectid,
            nameofwork,
            authorsname
        FROM
            projects
        WHERE
            difficulty = 'BEGINNER'
        AND
            state='".constant("PROJ_{$round_id}_AVAILABLE")."'
        ORDER BY
            modifieddate ASC" ;
}

function page_summary_sql($projectid)
{
    global $forums_url,$code_url,$mentored_round_id;

    $round_tallyboard = new TallyBoard($mentored_round_id, 'U' );

    list($joined_with_user_page_tallies,$user_page_tally_column) =
            $round_tallyboard->get_sql_joinery_for_current_tallies('u.u_id');

    return "SELECT
                CASE WHEN u.u_privacy = ".PRIVACY_ANONYMOUS." THEN 'Anonymous'
                ELSE CONCAT('<a href=\""
                    .$code_url . "/stats/members/mdetail.php?&id=',u.u_id,
                    '\">',u.username,'</a>')
                END AS " . _("Proofreader") . ",
                COUNT(1) AS '" . _("Pages this project") . "',
                $user_page_tally_column AS '" . sprintf(_("Total %s Pages"),$mentored_round_id) . "',
                DATE_FORMAT(FROM_UNIXTIME(u.date_created),'%M-%d-%y') AS Joined
            FROM $projectid  AS p
                INNER JOIN users AS u ON p.round1_user = u.username
                INNER JOIN phpbb_users AS bbu ON u.username = bbu.username
                $joined_with_user_page_tallies
            GROUP BY p.round1_user" ;
}

function page_list_sql($projectid)
{
    return "
    SELECT
        p.fileid AS '" . _('Page') . "',
        CASE WHEN u.u_privacy=".PRIVACY_ANONYMOUS." THEN 'Anonymous'
        ELSE p.round1_user
        END AS " . _('Proofreader') . "
    FROM $projectid AS p
        INNER JOIN users AS u ON p.round1_user = u.username
    ORDER BY fileid " ;
}


// Collect the data.

// Project selection. ****************************************************************

// Collect the projects to report.
// Hold the result in an array
// and release the database locks.


    // Page header. **********************************************************************

    // Display page header.

    theme(_("For Mentors"), "header");

    // ---------------------------------------------------------------

    $round_id = @$_GET['round_id'];
    if ( $round_id != '' )
    {
        $mentoring_round = get_Round_for_round_id($round_id);
    }
    else
    {
        // Consider the page they came from.
        $referer = $_SERVER['HTTP_REFERER'];

        // If they're coming to this page from a MENTORS ONLY book in X2, 
        // referrer should contain &expected_state=X2.proj_avail.
        foreach ( $Round_for_round_id_ as $round )
        {
            if ( strpos($referer, $round->project_available_state) )
            {
                $mentoring_round = $round;
                break;
            }
        }

        if ( !isset($mentoring_round) )
        {
            // Just take the first.
            foreach ( $Round_for_round_id_ as $round )
            {
                if ( $round->is_a_mentor_round() )
                {
                    $mentoring_round = $round;
                    break;
                }
            }
            if ( !isset($mentoring_round) )
            {
                die("There are no mentoring rounds!");
            }
        }
    }

    if ( !$mentoring_round->is_a_mentor_round() )
    {
        die("$mentoring_round->id is not a mentoring round!");
    }

    // ---------------------------------------------------------------

    // Are there other mentoring rounds? If so, provide mentoring links for them.
    $other_mentoring_rounds = array();
    foreach ( $Round_for_round_id_ as $round )
    {
        if ( $round->is_a_mentor_round() && $round->id != $mentoring_round->id )
        {
            $other_mentoring_rounds[] = $round;
        }
    }
    if ( count($other_mentoring_rounds) > 0 )
    {
        echo "<p>(" . _('Show this page for:');

        foreach( $other_mentoring_rounds as $other_round )
        {
            $url = "$code_url/tools/proofers/for_mentors.php?round_id={$other_round->id}";
            echo " <a href='$url'>{$other_round->id}</a>";
        }
        echo ")</p>";
    }

    // ---------------------------------------------------------------

    if ( !user_can_work_on_beginner_pages_in_round($mentoring_round) )
    {
        echo sprintf(
                _("You do not have access to 'Mentors Only' projects in %s."),
                $mentoring_round->id
            );
        echo "\n";
        theme("","footer");
        exit;
    }

    // ---------------------------------------------------------------

    echo "<h2>" . sprintf(_("Pages available to Mentors in round %s"), $mentoring_round->id) . "</h2>";
    echo "<br>" . _("Oldest project listed first.") . "<br>";

    $mentored_round_id = $mentoring_round->mentee_round->id;
    $result = mysql_query(project_sql($mentoring_round->id));
    while ($proj =  mysql_fetch_object($result))
    {
        // Display project summary info
        echo "<br>" ;
        echo "<b>$proj->nameofwork by $proj->authorsname</b>" ;
        echo "<br>" ;

        dpsql_dump_query(page_summary_sql($proj->projectid));

        echo "<br>" ;
        echo _('Which proofreader did each page...') ;

        dpsql_dump_query(page_list_sql($proj->projectid));
    }

    echo "<br><br><br><hr>\n";
    theme("","footer");

// vim: sw=4 ts=4 expandtab
?>
