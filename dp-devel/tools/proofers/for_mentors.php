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
include_once($relPath.'f_dpsql.inc');
// for PRIVACY_* constants
include_once($relPath.'prefs_options.inc');
// for page marginalia
include_once($relPath.'theme.inc');
// for PROJ_ declarations
include_once($relPath.'project_states.inc');
// for $users_ELR_page_tallyboard
include_once($relPath.'page_tally.inc');

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
    if (!isset($_GET['round_id']))
    {
      # If they're coming to this page from a MENTOR book in F2, 
      # referrer should contain &expected_state=F2.proj_avail.
      # Otherwise, default to the ELR round, P2.
      $round_id = (strstr($_SERVER['HTTP_REFERER'],"F2.proj_avail")) ? 'F2' : 'P2';
    }
    else
    {
      $round_id = $_GET['round_id'];
    }

    echo "<h2>" . _("Pages available to Mentors in round $round_id") . "</h2>";
    echo "<br>" . _("Oldest project listed first.") . "<br>";
    echo "<p>Show projects from: ";
    if ($round_id == 'P2')
    {
      echo "<b>P2</b> <a href='$code_url/tools/proofers/for_mentors.php?round_id=F2'>F2</a>";
    }
    else
    {
      echo "<a href='$code_url/tools/proofers/for_mentors.php?round_id=P2'>P2</a> <b>F2</b>";
    }
    echo "</p>.";
    $mentored_round_id = substr_replace($round_id,'1',-1);
    $result = mysql_query(project_sql($round_id));
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
?>
