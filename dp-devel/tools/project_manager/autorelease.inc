<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'maybe_mail.inc');
include_once($relPath.'bookpages.inc');
// include_once($relPath.'f_dpsql.inc');

function attempt_to_release($project)
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
        $new_state = PROJ_PROOF_FIRST_BAD_PROJECT;
    }
    else
    {
        $new_state = PROJ_PROOF_FIRST_AVAILABLE;
    }


    $error_msg = project_transition( $projectid, $new_state );
    if ($error_msg)
    {
        echo "$error_msg\n";
        return FALSE;
    }

    if ($new_state == PROJ_PROOF_FIRST_BAD_PROJECT)
    {
        // nothing else to do
        return FALSE;
    }

    maybe_mail_project_manager( $project,
	      "This project has just become available for first-round proofreading.",
	      "DP Proofreading Started");

    return TRUE;
}




function autorelease()
{
    $todaysdate = time();

    echo "<pre>\n";
    echo "Starting autorelease\n";

    // First, get the set of all authors with works in first round
    {
		    $first_round_authors = array();
		    $author_res =
			    mysql_query("
				    SELECT authorsname
				    FROM projects
				    WHERE state = '".PROJ_PROOF_FIRST_AVAILABLE."'
				       OR state = '".PROJ_PROOF_FIRST_VERIFY."'
				    ORDER BY authorsname
			    ") or die(mysql_error());
        while ( $author_row = mysql_fetch_assoc($author_res) )
        {
            $author = $author_row['authorsname'];
            $first_round_authors[$author]++;
        }
		    echo "\n";
		    echo "    List of authors in first round:\n";
		    foreach ( $first_round_authors as $author => $one )
		    {
            echo "        $author\n";
        }
    }

    // Next, get the set of all PMs with works in first round
    {
        $first_round_pms = array();
        $pm_res =
          mysql_query("
				    SELECT username
				    FROM projects
				    WHERE state = '".PROJ_PROOF_FIRST_AVAILABLE."'
				        OR state = '".PROJ_PROOF_FIRST_VERIFY."'
				    ORDER BY username
          ") or die(mysql_error());
        while ( $pm_row = mysql_fetch_assoc($pm_res) )
        {
            $pm = $pm_row['username'];
            $first_round_pms[$pm]++;
        }
        echo "\n";
        echo "    List of pms in first round:\n";
        foreach ( $first_round_pms as $pm => $one )
        {
            echo "        $pm\n";
        }
    }



    // Keep track of which PMs get a book released in this particular execution of the script

    {
		$fresh_released_pms = array();

	}


    // -------------------------------------------------------------------------

    AP_setup();

    // -------------------------------------------------------------------------

    // Release of Different types of Projects from various logical queues

    error_reporting(E_ALL);

    $q_res = mysql_query("
        SELECT *
        FROM queue_defns
        WHERE enabled
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
        // $release_criterion   logical expression using projects1|2, pages1|2

        echo "\n";
        echo "Considering $q_name projects...\n";
        echo "\n";
        echo "    For projects matching: $project_selector\n";
        echo "    release if: $release_criterion\n";

        // Does the $release_criterion allow us to release any projects?
        if (! AP_evaluate_criteria( $project_selector, $release_criterion ) )
        {
            echo "    The release criterion fails, so no projects released in this category.\n";
            continue;
        }

        // We are allowed to release projects!

        // set of candidates for release
        $waiting_res = mysql_query("
            SELECT *
            FROM projects
            WHERE state = '".PROJ_PROOF_FIRST_WAITING_FOR_RELEASE."' AND ($project_selector)
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
        // NB No other restrictions; specifically, $GLOBALS['pagesneeded'] cannot influence SPs;
        // this is so a huge book ariving from another queue won't stop the release of needed SPs

        $num_projects_released = 0;

        while ( $project = mysql_fetch_assoc($waiting_res) )
        {
            echo "    considering project \"{$project['nameofwork']}\"...\n";

            $authorsname = $project['authorsname'];
            $pm = $project['username'];
            $is_special = ( substr($project['comments'], 0, strlen('SPECIAL:')) == 'SPECIAL:' );

            if ($is_special)
            {
                echo "        It's special, so it's exempt from release restrictions.\n";
            }
            else
            {
                // If an author is Anonymous, Unknown, or Various, we should allow 3 books by that 'Author'
                // Otherwise we only allow one book by the author to be available in first round

                // TODO: Add a flag to some queues that all have identical authors that allows
                // a queue-defined limit of identical authors - eg some of the periodicals that have short
                // issues

                if ((($authorsname == 'Anonymous') && ($first_round_authors[$authorsname] > 3)) ||
                    (($authorsname == 'Unknown') && ($first_round_authors[$authorsname] > 3)) ||
                    (($authorsname == 'Various') && ($first_round_authors[$authorsname] > 3)) ||
                    (array_key_exists($authorsname, $first_round_authors) && ($authorsname <> 'Anonymous') && ($authorsname <> 'Unknown') && ($authorsname <> 'Various')))
                {
                    echo "        Its author, $authorsname, already has a project(s) in first round.\n";
                    continue;
                }

                // If a PM has more than 10 books already in round 1, their books should be skipped

                if ((array_key_exists($pm, $first_round_pms)) && ($first_round_pms[$pm] > 10))
                {
                    echo "        The PM, $pm, already has $first_round_pms[$pm] projects in first round.\n";
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
            if (!attempt_to_release($project))
            {
                // Something went wrong. Error messages have been output.
                continue;
            }

            echo "        Project released!\n";


		  // special authors (anon, etc) have to have their counts incremented
		  if (array_key_exists($authorsname, $first_round_authors)) {
                $first_round_authors[$authorsname] = $first_round_authors[$authorsname] + 1;
		  } else {
                $first_round_authors[$authorsname] = 1;
		  }


		  // keep track of which PMs have had a book released this execution of the script
		  $fresh_released_pms[$pm] = 1;


            $num_projects_released++;

            // Update active_page_counts
            // (The release of this project will affect the release-ability
            // of other projects in this category, and possibly of projects
            // in other categories.)
            AP_add_project( $project['projectid'] );

            if (! AP_evaluate_criteria( $project_selector, $release_criterion ) )
            {
                echo "    The release criterion now fails, so no further projects released in this category.\n";
                break;
            }
        }

        echo "    Released <b>$num_projects_released</b> $q_name projects.\n";
    }

    echo "</pre>\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function AP_setup()
// Set up temporary table active_page_counts,
// containing a row for each project in round 1 or 2.
// fields:
//     projectid
//     pages1 = number of pages yet to receive 1st proofreading
//     pages2 = number of pages yet to receive 2nd proofreading
// Note that, by definition, pages1 <= pages2, since
// a page can only receive 2nd proofreading after 1st proofreading.
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
            pages1     INT(4),
            pages2     INT(4)
        )
    ") or die(mysql_error());

    // Get a list of active projects.
    $projects_res =
        mysql_query("
        SELECT projectid
        FROM projects
        WHERE (state = '".PROJ_PROOF_FIRST_AVAILABLE."' OR state = '".PROJ_PROOF_SECOND_AVAILABLE."')
		") or die(mysql_error());

    // Run through them and fill up the table.
    while( $project = mysql_fetch_assoc($projects_res) )
    {
        AP_add_project( $project['projectid'] );
    }
}

function AP_add_project( $projectid )
{
    mysql_query("
        INSERT INTO active_page_counts
        SELECT
            '$projectid',
        SUM(
				    state = '".BAD_FIRST."' OR
            state = '".AVAIL_FIRST."' OR
            state = '".OUT_FIRST."' OR
            state = '".TEMP_FIRST."'
        ) as pages1,
        SUM(
            state != '".SAVE_SECOND."'
        ) as pages2
        FROM $projectid
    ") or die(mysql_error());
}

function AP_evaluate_criteria( $project_selector, $release_criterion )
{
    // Get the criterion-evaluation environment
    // (values for projects1|2, pages1|2)
    $res = mysql_query("
        SELECT
        SUM(projects.state='".PROJ_PROOF_FIRST_AVAILABLE."') as projects1,
        SUM(projects.state='".PROJ_PROOF_SECOND_AVAILABLE."') as projects2,
        SUM(active_page_counts.pages1) as pages1,
        SUM(active_page_counts.pages2) as pages2
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

?>
