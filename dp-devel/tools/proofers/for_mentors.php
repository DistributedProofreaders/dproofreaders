<?
/*
    Test version of for_mentors.php.  Displays information useful to Mentors
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

function project_sql()
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
            state='".PROJ_P2_AVAILABLE."'
        ORDER BY
            modifieddate ASC" ;
}

function page_summary_sql($projectid)
{
    global $forums_url;
    global $dynstats_url;

    return "SELECT 
                CASE WHEN u.u_privacy > ".PRIVACY_PUBLIC." THEN 'Anonymous'
                ELSE CONCAT('<a href=\""
                    .$dynstats_url . "/members/mdetail.php?&id=',u.u_id,
                    '\">',u.username,'</a>')
                END AS " . _("Proofreader") . ",
                COUNT(1) AS '" . _("Pages this project") . "',
                u.pagescompleted AS '" . _("Total Pages") . "',
                DATE_FORMAT(FROM_UNIXTIME(u.date_created),'%M-%d-%y') AS Joined
            FROM $projectid  AS p
                INNER JOIN users AS u ON p.round1_user = u.username
                INNER JOIN phpbb_users AS bbu ON u.username = bbu.username
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
    // echo "<hr><h3>" . _("TEST VERSION") . "</h3>"  ;
    echo "<h2>" . _("Second Round pages available to Mentors") . "</h2>";
    echo "<br>" . _("Listed oldest project first.") . "<br>";

    $result = mysql_query(project_sql()) ;
    while ($proj =  mysql_fetch_object($result))
    {
        // Display project summary info
        echo "<br>" ;
        echo "<b>$proj->nameofwork by $proj->authorsname</b>" ;
        echo "<br>" ;
        print $dynstats_url;

        dpsql_dump_query(page_summary_sql($proj->projectid));

        echo "<br>" ;
        echo _('Which proofreader did each page...') ;

        dpsql_dump_query(page_list_sql($proj->projectid));
    }

    echo "<br><br><br><hr>\n";
    theme("","footer");
?>
