<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'maybe_mail.inc');
include_once($relPath.'bookpages.inc');

function numpages($projectid) {
        $level0rows = mysql_query("SELECT COUNT(fileid) FROM $projectid");
        if ($level0rows != "") { return (mysql_result($level0rows,0,0)); } else return 0;
}

function numavailpages($projectid, $roundavailstate) {
        $level0rows = mysql_query("SELECT COUNT(fileid) FROM $projectid WHERE state = '$roundavailstate'");
        if ($level0rows != "") { return (mysql_result($level0rows,0,0)); } else return 0;
}

function verifynew($project) {

    $projectid = $project['projectid'];

    $errors = project_pre_release_check( $project );

    if ( $errors )
    {
	maybe_mail_project_manager( $project, 
            "Some errors have been found:\n".
            "$errors\n".
            "Please correct the errors and put the project back into Waiting for Release."
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
        return 0;
    }

    if ($new_state == PROJ_PROOF_FIRST_BAD_PROJECT)
    {
        // nothing else to do
        return 0;
    }

    maybe_mail_project_manager( $project,
	"This project has just become available for first-round proofing." );

    $result = mysql_query("UPDATE $projectid SET state = '".AVAIL_FIRST."' WHERE state = '".UNAVAIL_FIRST."'");

    //Update the total and available pages in the HEAP table
    update_total_pages($projectid, 1);

    echo "        Prepared to release project = $projectid\n";
    return numpages($projectid);
}




function autorelease($pagesleft) {
        $todaysdate = time();
        $pagesneeded = $GLOBALS['pagesneeded'] - $pagesleft;
        $noneng_pagesneeded = $GLOBALS['noneng_pagesneeded'];
        $eng_pagesneeded = $GLOBALS['eng_pagesneeded'];
        $beginners_pagesneeded = $GLOBALS['beginners_pagesneeded'];
        $easy_projects = $GLOBALS['easy_projects'];


	echo "<pre>\n";
	echo "Starting autorelease\n";

        // Release of Different types of Projects from various logical queues
        // NB The arguments for these calls can come out of a table LATER (stage two)...
        // For now, let's get it out and working, even with hardwired arguments

	error_reporting(E_ALL);

	// You can easily imagine this data coming from a DB table.
	$categories = array(
		array(
			// Beginners Projects
			// Always have a certain number of pages available in round 1
			'projtype_desc' => "Beginners Only",
			'projtype_sql'  => "username = 'BEGIN'",
			'count2ndRnd'   => "",
			'track_by'      => "page",
			'target'        => $beginners_pagesneeded,
		),
		array(
			// Easy English Projects
			// Always have a certain number of projects in first round
			'projtype_desc' => "EASY English",
			'projtype_sql'  => "genre LIKE 'EASY%' && language LIKE 'English%'",
			'count2ndRnd'   => "",
			'track_by'      => "project",
			'target'        => $easy_projects,
		),
		array(
			// Non-English Projects
			// Always have a certain number of pages spread over the two rounds
			'projtype_desc' => "Non English",
			'projtype_sql'  => "language NOT LIKE 'English%'",
			'count2ndRnd'   => "yes",
			'track_by'      => "page",
			'target'        => $noneng_pagesneeded,
		),
		array(
			// English Projects
			// Always have a certain number of pages spread over the two rounds
			'projtype_desc' => "English",
			'projtype_sql'  => "language LIKE 'English%'",
			'count2ndRnd'   => "yes",
			'track_by'      => "page",
			'target'        => $eng_pagesneeded,
		),
		array(
			// Extra Projects
			// Make sure we have a total of at least X pages spread out over
			// the two rounds; if there's any room left, just release projects
			// from the front of the queue until we have enough pages out.
			'projtype_desc' => "Extra", // Total?
			'projtype_sql'  => "1",
			'count2ndRnd'   => "yes",
			'track_by'      => "page",
			'target'        => $GLOBALS['pagesneeded'],
		),
	);

	foreach ($categories as $cat)
	{
		$projtype_desc = $cat['projtype_desc'];
		$projtype_sql  = $cat['projtype_sql'];
		$count2ndRnd   = $cat['count2ndRnd'];
		$track_by      = $cat['track_by'];
		$target        = $cat['target'];

		// Release available projects of a given type (defined by $projtype_sql) until a target number of
		// PROJECTS or PAGES (depending on $track_by) of that type is available for proofing

		// $track_by            what units do we measure by, "project" or "page"?
		// $projtype_desc       human readable string describing this type of project
		// $projtype_sql        portion of WHERE clause that defines this type of project
		// $target              total number of units of this type we want available for proofing
		// $numReleased            total number of units of this type already released before we start
		// $count2ndRnd         if "yes", and tracking by "page", consider contribution towards target of second round

		echo "\n";
		echo "Considering $projtype_desc projects...\n";

		if ($count2ndRnd == 'yes')
		{
			$which_rounds_desc = "both rounds";
			$which_rounds_sql = "(state = '".PROJ_PROOF_FIRST_AVAILABLE."' OR state = '".PROJ_PROOF_SECOND_AVAILABLE."')";
		}
		else
		{
			$which_rounds_desc = "first round";
			$which_rounds_sql = "state = '".PROJ_PROOF_FIRST_AVAILABLE."'";
		}

		if ($track_by == 'project')
		{
		    $units_desc = 'projects';
		}
		else if ($track_by == 'page')
		{
		    $units_desc = 'unproofed pages';
		}

		echo "\n";
		echo "    For projects matching: $projtype_sql\n";
		echo "    look for: $units_desc in $which_rounds_desc\n";
		echo "    target:    $target\n";

		if ($track_by == 'project')
		{
			$rel_qry = mysql_query("SELECT COUNT(projectid) FROM projects WHERE $which_rounds_sql AND $projtype_sql ORDER BY modifieddate ASC");
			$numReleased = mysql_result($rel_qry,0);
		}
		else if ($track_by == 'page')
		{
			$rel_qry = mysql_query("SELECT projectid, state FROM projects WHERE $which_rounds_sql AND $projtype_sql ORDER BY modifieddate ASC");
			$numProjects = mysql_num_rows($rel_qry);
			$numReleased = 0;
			$i = 0;
			while ($i < $numProjects) {
				$proj = mysql_result($rel_qry,$i,0);
				if ($count2ndRnd == 'yes')
				{
					if (mysql_result($rel_qry,$i,1) == PROJ_PROOF_FIRST_AVAILABLE) {
						$numReleased = $numReleased + numpages($proj) + numavailpages($proj, AVAIL_FIRST);
					} else {
						$numReleased = $numReleased + numavailpages($proj, AVAIL_SECOND);
					}
				}
				else
				{
					$numReleased = $numReleased + numavailpages($proj, AVAIL_FIRST);
				}
				$i++;
			}
		}

		echo "    found:     $numReleased\n";

		// if there's enough out there already, don't bother
		if ($numReleased >= $target)
		{
			$surplus = $numReleased - $target;
			echo "    surplus:   $surplus\n";
			echo "\n";
			echo "    No room for releases in this category.\n";
			continue;
		}
		else
		{
			$shortfall = $target;
			echo "    shortfall: $shortfall\n";
			echo "\n";
		}
		

		// set of candidates for release
		$waiting_res = mysql_query("
			SELECT *
			FROM projects
			WHERE state = '".PROJ_PROOF_FIRST_WAITING_FOR_RELEASE."' AND $projtype_sql
			ORDER BY modifieddate ASC
		");
		if (mysql_num_rows($waiting_res) == 0)
		{
			echo "    Could have released some projects in this category, but none were waiting!\n";
			// email someone?
			continue;
		}

		// Keep releasing these projects (SP) until:
		//      we meet our target for them ($numReleased >= $target),
		// or   we run out of SP ready to release
		// or   subject to the "no doubling up of authors" rule
		// NB No other restrictions; specifically, $pagesneeded cannot influence SPs;
		// this is so a huge book ariving from another queue won't stop the release of needed SPs

		$newpagesreleased = 0;
		$totalnewreleased = 0;
		$num_projects_released = 0;

		while ( $project = mysql_fetch_assoc($waiting_res) )
		{
			echo "    considering project \"{$project['nameofwork']}\"...\n";

			// check the date
			if ($project['modifieddate'] == $todaysdate) {
				// since projects are sorted by date, if we reach here we have already
				// considered and rejected all remaining candidates under the author rule
				// and can stop checking
				echo "        modifieddate is now.\n";
				break;
			}

			// if we haven't already earlier today rejected (under the "no doubling up of authors" rule),
			// this candidate for early release, consider it now

			// "no doubling up of authors"

			// corrected to include books already marked for release

			$author_q = "SELECT projectid FROM projects WHERE
				(state = '".PROJ_PROOF_FIRST_AVAILABLE."'
					OR
				state = '".PROJ_PROOF_FIRST_VERIFY."') AND authorsname = '{$project['authorsname']}'";

			if (mysql_num_rows(mysql_query($author_q)) > 0)
			{
				// fails the author restriction,
				// push this back two day's worth (in terms of order, not delay!) in the queue
				echo "        Its author, {$project['authorsname']}, already has a book in first round.\n";
				$temp = mysql_query("UPDATE projects SET modifieddate = modifieddate + 172800 WHERE projectid = '{$project['projectid']}'");
				continue;
			}

			// OK to release
			// release the project for proofing, and keep track of
			// its contribution to reducing our total page shortfall
			$newpagesreleased = verifynew($project);
			$totalnewreleased = $totalnewreleased + (2 * $newpagesreleased);
			$num_projects_released++;

			// update our progress towards the target,
			// depending on whether we are tracking by project or page
			if ($track_by == "project")
			{
				$numReleased = $num_projects_released;
			}
			else
			{
				$numReleased = $numReleased + $newpagesreleased;
				if ($count2ndRnd == "yes") $numReleased = $numReleased + $newpagesreleased;
			}
			if ( $numReleased >= $target )
			{
				// We have now reached or exceeded our target,
				// so don't consider any more projects in this category.
				break;
			}
		}

		echo "    Released <b>$num_projects_released</b> $projtype_desc projects.\n";

		$pagesneeded = $pagesneeded - $totalnewreleased;
	}

        echo "</pre>\n";
}
?>
