<?
include_once($relPath.'misc.inc');
include_once($relPath.'v_site.inc');
include_once($relPath.'maybe_mail.inc');
include_once($relPath.'stages.inc');
// include_once($relPath.'f_dpsql.inc');

function autorelease()
{
    echo "<pre>\n";
    echo "Starting autorelease\n";

    for ($rn = 1; $rn <= MAX_NUM_PAGE_EDITING_ROUNDS; $rn++ )
    {
        $round = get_Round_for_round_number($rn);
        autorelease_for_round($round);
    }

    echo "</pre>\n";
}

function attempt_to_release( $round, $project )
{
    $projectid = $project['projectid'];

    $errors = project_pre_release_check( $project );

    if ( $errors )
    {
        maybe_mail_project_manager( $project, 
            "Some errors have been found:\n".
            "$errors\n".
            "Please correct the errors and put the project back into Waiting for Release.",
            "DP Errors Before Release"
        );
        $new_state = $round->project_bad_state;
    }
    else
    {
        $new_state = $round->project_available_state;
    }


    $error_msg = project_transition( $projectid, $new_state );
    if ($error_msg)
    {
        echo "$error_msg\n";
        return FALSE;
    }

    if ($new_state == $round->project_bad_state)
    {
        // nothing else to do
        return FALSE;
    }

    maybe_mail_project_manager( $project,
        "This project has just become available for work in round {$round->id}.",
        "DP Proofreading Started");

    return TRUE;
}




function autorelease_for_round( $round )
{
    echo "\n";
    echo "Starting autorelease for round {$round->id}...\n";

    $q_res = mysql_query("
        SELECT *
        FROM queue_defns
        WHERE round_number={$round->round_number}
        ORDER BY ordering
    ") or die(mysql_error());

    if ( mysql_num_rows($q_res) == 0 )
    {
        echo "\n";
        echo "There are no queue definitions for this round (enabled or not)!\n";
        echo "We interpret this as meaning that projects should not\n";
        echo "wait here, but automatically become available.\n";

        // set of candidates for release
        $waiting_res = mysql_query("
            SELECT *
            FROM projects
            WHERE state = '{$round->project_waiting_state}'
            ORDER BY modifieddate ASC
        ");

        $n = mysql_num_rows($waiting_res);
        echo "\n";
        echo "There are $n projects waiting for release in this round.\n";

        while ( $project = mysql_fetch_assoc($waiting_res) )
        {
            echo "\n";
            echo "    considering project \"{$project['nameofwork']}\"...\n";

            if (!attempt_to_release($round, $project))
            {
                // Something went wrong. Error messages have been output.
                continue;
            }

            echo "        Project released!\n";
        }

        echo "\n";
        echo "Finished round {$round->id}\n";
        echo "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";
        return;
    }

    // -------------------------------------------------------------------------

    // Some queue definitions do exist for this round.

    // First, get the set of all authors with works in this round
    {
        $this_round_authors = array();
        $author_res =
            mysql_query("
                SELECT authorsname
                FROM projects
                WHERE state = '{$round->project_available_state}'
                ORDER BY authorsname
            ") or die(mysql_error());
        while ( $author_row = mysql_fetch_assoc($author_res) )
        {
            $author = $author_row['authorsname'];
            $this_round_authors[$author]++;
        }
        echo "\n";
        echo "    List of authors in this round:\n";
        foreach ( $this_round_authors as $author => $one )
        {
            echo "        $author\n";
        }
    }

    // Next, get the set of all PMs with works in this round
    {
        $this_round_pms = array();
        $pm_res =
            mysql_query("
                SELECT username
                FROM projects
                WHERE state = '{$round->project_available_state}'
                ORDER BY username
            ") or die(mysql_error());
        while ( $pm_row = mysql_fetch_assoc($pm_res) )
        {
            $pm = $pm_row['username'];
            $this_round_pms[$pm]++;
        }
        echo "\n";
        echo "    List of pms in this round:\n";
        foreach ( $this_round_pms as $pm => $one )
        {
            echo "        $pm\n";
        }
    }



    // Keep track of which PMs get a book released in this particular execution of the script

    {
        $fresh_released_pms = array();

    }


    // -------------------------------------------------------------------------

    AP_setup($round);

    // -------------------------------------------------------------------------

    // Release of Different types of Projects from various logical queues

    error_reporting(E_ALL);

    $q_res = mysql_query("
        SELECT *
        FROM queue_defns
        WHERE enabled AND round_number={$round->round_number}
        ORDER BY ordering
    ") or die(mysql_error());

    while ( $qd = mysql_fetch_assoc($q_res) )
    {
        $q_name            = $qd['name'];
        $project_selector  = $qd['project_selector'];
        $release_criterion = $qd['release_criterion'];

        // Release available projects of a given type (defined by $project_selector)
        // as long as the $release_criterion is true for projects of that type.

        // $q_name       human readable string describing this type of project
        // $project_selector        portion of WHERE clause that defines this type of project
        // $release_criterion   logical expression involving 'projects' and 'pages':
        //                      the number of projects of this type in this round,
        //                      and their total number of unproofed pages.

        echo "\n";
        echo "Considering $q_name projects...\n";
        echo "\n";
        echo "    For projects matching: $project_selector\n";
        echo "    release if: $release_criterion\n";

        // Does the $release_criterion allow us to release any projects?
        if (! AP_evaluate_criteria( $round, $project_selector, $release_criterion ) )
        {
            echo "    The release criterion fails, so no projects released in this category.\n";
            continue;
        }

        // We are allowed to release projects!

        // set of candidates for release
        $waiting_res = mysql_query("
            SELECT *
            FROM projects
            WHERE state = '{$round->project_waiting_state}' AND ($project_selector)
            ORDER BY modifieddate ASC
        ");

        if (mysql_num_rows($waiting_res) == 0)
        {
            echo "    Could have released some projects in this category, but none were waiting!\n";
            // email someone?
            continue;
        }

        // Keep releasing these projects (SP) until:
        //      the $release_criterion fails
        // or   we run out of SP ready to release
        // or   subject to the "no doubling up of authors" rule

        $num_projects_released = 0;

        while ( $project = mysql_fetch_assoc($waiting_res) )
        {
            echo "    considering project \"{$project['nameofwork']}\"...\n";

            $authorsname = $project['authorsname'];
            $pm = $project['username'];
            $is_special = startswith($project['comments'], 'SPECIAL:');

            if ($is_special)
            {
                echo "        It's special, so it's exempt from release restrictions.\n";
            }
            else
            {
                // If an author is Anonymous, Unknown, or Various, we should allow 3 books by that 'Author'
                // Otherwise we only allow one book by the author to be available in this round

                // TODO: Add a flag to some queues that all have identical authors that allows
                // a queue-defined limit of identical authors - eg some of the periodicals that have short
                // issues

                if ((($authorsname == 'Anonymous') && ($this_round_authors[$authorsname] > 3)) ||
                    (($authorsname == 'Unknown') && ($this_round_authors[$authorsname] > 3)) ||
                    (($authorsname == 'Various') && ($this_round_authors[$authorsname] > 3)) ||
                    (array_key_exists($authorsname, $this_round_authors) && ($authorsname <> 'Anonymous') && ($authorsname <> 'Unknown') && ($authorsname <> 'Various')))
                {
                    echo "        Its author, $authorsname, already has a project(s) in this round.\n";
                    continue;
                }

                // If a PM has more than 10 books already in this round, their books should be skipped

                if ((array_key_exists($pm, $this_round_pms)) && ($this_round_pms[$pm] > 10))
                {
                    echo "        The PM, $pm, already has $this_round_pms[$pm] projects in this round.\n";
                    continue;
                }

                // If a PM has had a book released in this execution of the script, pass over it until next time

                if (array_key_exists($pm, $fresh_released_pms))
                {
                    echo "        The PM, $pm, already has had a project released during this execution of the release script.\n";
                    continue;
                }
            }


            // OK to release
            // release the project for proofreading, and keep track of
            // its contribution to reducing our total page shortfall
            if (!attempt_to_release($round, $project))
            {
                // Something went wrong. Error messages have been output.
                continue;
            }

            echo "        Project released!\n";


            // special authors (anon, etc) have to have their counts incremented
            if (array_key_exists($authorsname, $this_round_authors)) {
                $this_round_authors[$authorsname] = $this_round_authors[$authorsname] + 1;
            } else {
                $this_round_authors[$authorsname] = 1;
            }


            // keep track of which PMs have had a book released this execution of the script
            $fresh_released_pms[$pm] = 1;


            $num_projects_released++;

            // Update active_page_counts
            // (The release of this project will affect the release-ability
            // of other projects in this category, and possibly of projects
            // in other categories.)
            AP_add_project( $round, $project['projectid'] );

            if (! AP_evaluate_criteria( $round, $project_selector, $release_criterion ) )
            {
                echo "    The release criterion now fails, so no further projects released in this category.\n";
                break;
            }
        }

        echo "    Released <b>$num_projects_released</b> $q_name projects.\n";
    }

    AP_teardown($round);

    echo "\n";
    echo "Finished round {$round->id}\n";
    echo "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function AP_setup( $round )
// Set up temporary table active_page_counts,
// containing a row for each project in this round.
// fields:
//     projectid
//     pages = number of pages yet to receive proofreading in this round
//
// This table is similar to page_counts,
// but contains slightly different information.
// Maybe they could be merged.
{
    // If we had one table with all page info,
    // we could set up this table with a single
    // CREATE TABLE ... SELECT ...
    // statement.
    // Instead, we have to loop over the active projects.

    // Create the table.
    mysql_query("
        CREATE TEMPORARY TABLE active_page_counts
        (
            projectid  TEXT NOT NULL,
            pages      INT(4),
        )
    ") or die(mysql_error());

    // Get a list of active projects.
    $projects_res =
        mysql_query("
        SELECT projectid
        FROM projects
        WHERE state = '{$round->project_available_state}'
    ") or die(mysql_error());

    // Run through them and fill up the table.
    while( $project = mysql_fetch_assoc($projects_res) )
    {
        AP_add_project( $round, $project['projectid'] );
    }
}

function AP_teardown( $round )
{
    mysql_query("
        DROP TABLE active_page_counts
    ") or die(mysql_error());
}

function AP_add_project( $round, $projectid )
{
    mysql_query("
        INSERT INTO active_page_counts
        SELECT
            '$projectid',
            SUM( state != '{$round->page_save_state}' ) as pages
        FROM $projectid
    ") or die(mysql_error());
}

function AP_evaluate_criteria( $round, $project_selector, $release_criterion )
{
    // Get the criterion-evaluation environment
    // (values for 'projects', 'pages')
    $res = mysql_query("
        SELECT
            SUM(projects.state='{$round->project_available_state}') as projects,
            SUM(active_page_counts.pages) as pages
        FROM projects NATURAL JOIN active_page_counts
        WHERE $project_selector
    ") or die(mysql_error());
    $env = mysql_fetch_assoc($res);
    // print_r($env);

    return evaluate_expression( $release_criterion, $env );
}

function evaluate_expression( $expression, $env )
{
    $expr = $expression;
    foreach( $env as $id => $value )
    {
        if (is_null($value)) $value = 0;
        $expr = str_replace( $id, $value, $expr );
    }
    // should check $expr for traps
    $result = eval( "return ($expr);" );

    if (TRUE)
    {
        echo "\n";
        echo "    Evaluate '$expression' wrt {";
        foreach( $env as $id => $value )
        {
            echo " $id => $value,";
        }
        echo "} = '$expr' = $result\n";
    }

    return $result;
}

// vim: sw=4 ts=4 expandtab
?>
