<?
    // $Id$

include_once($relPath.'v_site.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'RoundDescriptor.inc');
include_once($relPath.'bookpages.inc');
include_once($relPath.'gettext_setup.inc');
include_once($relPath.'page_ops.inc');
include_once($relPath.'f_move_post.inc');

function project_transition( $projectid, $new_state, $extras = array() )
// Move the given project to the given state,
// and perform any attendant processing.
// If there are any problems, return a string containing an error message.
// Otherwise, return an empty string.
//
// This function produces no output except for debugging messages.
//
// This function should encapsulate the entire transition graph for project
// states. That is, it should be the only code that modifies the 'state'
// column of the 'projects' table.
// It should perhaps also be the only place that modifies these columns:
//     modifieddate checkedoutby postproofer postcomments
{
	global $testing;
	global $site_supports_metadata;

	// error_reporting(E_ALL);

	$res = mysql_query("
		SELECT *
		FROM projects
		WHERE projectid = '$projectid'
	");
	if (mysql_num_rows($res) == 0)
	{
	    $error = "$projectid: "._("no such project");
	    return $error;
	}

	$project = mysql_fetch_array($res);
	$current_state = $project['state'];

	$transition_str = "$projectid: "._("transition from")." $current_state "._("to")." $new_state";
	if ($testing)
	{
		$indent = '    ';
		if (FALSE)
		{
			// The trace output will be going into a <pre> element,
			// or to a plain-text document.
			$eol = "\n";
		}
		else
		{
			// The trace output will be going to an HTML doc,
			// but not into a <pre> element.
			$eol = "<br>\n";
		}

		// echo "{$indent}$transition_str{$eol}";
	}

	// -------------------------------------------------------------------------

	if ( $new_state == $current_state )
	{
		// The transition succeeds trivially.
		// No attendant processing.
		if ($testing)
		{
			echo "{$indent}$transition_str: "._("succeeds trivially")."{$eol}";
		}
		return '';
	}

	// -------------------------------------------------------------------------

	// A project can be deleted when it's in any state.
	if ( $new_state == PROJ_DELETE )
	{
		project_drop_pages( $projectid );

		$result = mysql_query("
			DELETE FROM projects WHERE projectid = '$projectid'
		");

		global $projects_dir;
		exec("rm -rf $projects_dir/$projectid");

		if ($testing)
		{
			echo "{$indent}$transition_str: "._("success")."{$eol}";
		}

		return '';
	}

	// -------------------------------------------------------------------------

	$transition_graph = array(
		PROJ_NEW => array(
			PROJ_P1_UNAVAILABLE
			),

		PROJ_POST_FIRST_UNAVAILABLE => array(
			PROJ_POST_FIRST_AVAILABLE,
			PROJ_POST_FIRST_CHECKED_OUT,
			),
		PROJ_POST_FIRST_AVAILABLE => array(
			PROJ_POST_FIRST_UNAVAILABLE,
			PROJ_POST_FIRST_CHECKED_OUT,
			),
		PROJ_POST_FIRST_CHECKED_OUT => array(
			PROJ_POST_FIRST_UNAVAILABLE,
			PROJ_POST_FIRST_AVAILABLE,
			PROJ_POST_SECOND_AVAILABLE,
			PROJ_POST_SECOND_CHECKED_OUT,
			PROJ_POST_COMPLETE,
			PROJ_SUBMIT_PG_POSTED,
			),
		PROJ_POST_SECOND_AVAILABLE => array(
			PROJ_POST_SECOND_CHECKED_OUT,
			PROJ_POST_COMPLETE,
			PROJ_SUBMIT_PG_POSTED,
			),
		PROJ_POST_SECOND_CHECKED_OUT => array(
			PROJ_POST_SECOND_AVAILABLE,
			PROJ_POST_COMPLETE,
			PROJ_SUBMIT_PG_POSTED,
			),
		PROJ_POST_COMPLETE => array(
			PROJ_SUBMIT_PG_POSTED,
			),

		PROJ_SUBMIT_PG_POSTED => array(
			PROJ_CORRECT_AVAILABLE,
			),

		PROJ_CORRECT_AVAILABLE => array(
			PROJ_CORRECT_CHECKED_OUT,
			),
		PROJ_CORRECT_CHECKED_OUT => array(
			PROJ_CORRECT_AVAILABLE,
			PROJ_SUBMIT_PG_POSTED,
			),

	);

	if ( $site_supports_metadata )
	{
		$transition_graph[ PROJ_NEW_FILE_UPLOADED ] = array(
			PROJ_NEW_METADATA_FIRST
			);
		// Yes, you'd need more than just that.
		// This is just a start, or a reminder, I guess.
	}

	for ( $rn = 1; $rn <= MAX_NUM_PAGE_EDITING_ROUNDS; $rn++ )
	{
		$prd = get_PRD_for_round($rn);

		$transition_graph[ $prd->project_bad_state ] = array(
			$prd->project_unavailable_state,
			$prd->project_available_state,
			$prd->project_complete_state,
			);
		$transition_graph[ $prd->project_unavailable_state ] = array(
			$prd->project_waiting_state,
			);
		$transition_graph[ $prd->project_waiting_state ] = array(
			$prd->project_bad_state,
			$prd->project_unavailable_state,
			$prd->project_available_state,
			);
		$transition_graph[ $prd->project_available_state ] = array(
			$prd->project_bad_state,
			$prd->project_unavailable_state,
			$prd->project_complete_state,
			);

		if ( $rn < MAX_NUM_PAGE_EDITING_ROUNDS )
		{
			$next_prd = get_PRD_for_round($rn+1);
			$transition_graph[ $prd->project_complete_state ] = array(
				$next_prd->project_unavailable_state,
				$next_prd->project_waiting_state,
				);
		}
		else
		{
			$transition_graph[ $prd->project_complete_state ] = array(
				PROJ_POST_FIRST_CHECKED_OUT,
				PROJ_POST_FIRST_AVAILABLE,
				);
		}
	}

	if ( ! in_array( $new_state, $transition_graph[$current_state] ) )
	{
		// Requested transition is illegal.
		$error = "$transition_str "._("is illegal");
		return $error;
	}

	// -------------------------------------------------------------------------

	$now = time();

	$settings = "state='$new_state'";

	$prd = get_PRD_for_project_state($new_state);
	if ( !is_null($prd) )
	{
		if ( $new_state == $prd->project_waiting_state )
		{
			$errors = project_pre_release_check( $project );
			if ($errors)
			{
				$error = "$transition_str: "._("disallowed because of project problems").":\n<pre>\n$errors</pre>";
				return $error;
			}
			$settings .= ", modifieddate='$now'";
			// The time when projects enter the 'waiting for release' state
			// is the overall ordering criterion for the "release queue".
		}
		else if ( $current_state != $prd->project_bad_state &&
			  $new_state     == $prd->project_available_state )
		{
			$settings .= ", modifieddate='$now'";
			// The time when projects enter the 'available' state
			// is the ordering criterion for "Sort by Days"
			// in the list of available projects.
			// (But if the project is just coming back from the
			// 'bad' state, that shouldn't affect the sort order.)
		}
		else
		{
			// Don't update modifieddate.
		}
	}

	else if ( $current_state == PROJ_POST_FIRST_CHECKED_OUT &&
		in_array( $new_state, array(PROJ_POST_FIRST_UNAVAILABLE,PROJ_POST_FIRST_AVAILABLE) ) )
	{
		$settings .= ", modifieddate='$now'";
		$settings .= ", checkedoutby=''";
	}
	else if ( $new_state == PROJ_POST_FIRST_CHECKED_OUT )
	{
		if ( $extras['checkedoutby'] == '' )
		{
			return "$transition_str: \$extras['checkedoutby'] is unset";
		}
		$settings .= ", modifieddate='$now'";
		$settings .= ", checkedoutby='{$extras['checkedoutby']}'";
	}
	else if ( $current_state == PROJ_POST_FIRST_CHECKED_OUT &&
		$new_state == PROJ_POST_SECOND_AVAILABLE )
	{
		global $postcomments;
		$postcomments = htmlspecialchars($postcomments, ENT_QUOTES);
		$settings .= ", modifieddate='$now'";
		$settings .= ", postproofer='{$project['checkedoutby']}'";
		$settings .= ", postcomments='{$postcomments}'";
	}
	else if ( ( $current_state == PROJ_POST_FIRST_CHECKED_OUT ||
		    $current_state == PROJ_POST_SECOND_AVAILABLE )
		&&
		$new_state == PROJ_POST_SECOND_CHECKED_OUT )
	{
		if ( $extras['checkedoutby'] == '' )
		{
			return "$transition_str: \$extras['checkedoutby'] is unset";
		}
		$settings .= ", modifieddate='$now'";
		$settings .= ", postproofer='{$project['checkedoutby']}'";
		$settings .= ", checkedoutby='{$extras['checkedoutby']}'";
	}
	else if ( $current_state == PROJ_POST_SECOND_CHECKED_OUT &&
		$new_state == PROJ_POST_SECOND_AVAILABLE )
	{
		$settings .= ", modifieddate='$now'";
		$settings .= ", checkedoutby='{$project['postproofer']}'";
	}
	else if ( $new_state == PROJ_SUBMIT_PG_POSTED )
	{
		$settings .= ", modifieddate='$now'";

		// if no postproofer recorded, project never went through PPV
		// so whoever has it checked out must be cleared to post directly
		// and must have been the PPer
		if ($project['postproofer'] == '' ) {
			$settings .= ", postproofer='{$project['checkedoutby']}'";
		} else {
			// if checkedoutby != postproofer, then checkedoutby must be PPVer...
			// this assumes a project is only posted once!
			// otherwise, maybe also test that ppverifier is null?
			if ($project['postproofer'] != $project['checkedoutby']) {
				$settings .= ", ppverifier='{$project['checkedoutby']}'";
			}
		}

		// Populate final_page_count column on projects table with
		// total number of pages in project when it was posted, so we have a 
		// permanent record of number of pages in the project; needed for
		// PP, PM, PPV stats by page

		// Delete from the HEAP table all page counts, storing count in final_page_count
		// This next query can be used BOTH to determine existence of a row for the
		// project in the HEAP table, and read the total if there is one

		$result = mysql_query("SELECT total_pages FROM page_counts WHERE projectid = '$projectid'");
		$pgCount = mysql_result($result, 0, "total_pages");
		if (!empty($pgCount)) {
			mysql_query ("DELETE FROM page_counts WHERE projectid = '$projectid'");
		} else {
			// Try to determine number of pages directly from projectID table
			$result = mysql_query("SELECT count(*) as total_pages FROM ".$projectID." WHERE 1");
			$pgCount = mysql_result($result, 0, "total_pages");			
		}

		if (!empty($pgCount)) {
			$settings .= ", final_page_count=$pgCount";
		} // ELSE automail an error report to dbreq?

	}
      else if ( $current_state == PROJ_SUBMIT_PG_POSTED &&
		$new_state == PROJ_CORRECT_AVAILABLE)
	{
		if ( $extras['correctedby'] == '' )
		{
			return "$transition_str: \$extras['correctedby'] is unset";
		}
		$settings .= ", modifieddate='$now'";
		$settings .= ", correctedby='{$extras['correctedby']}'";
	}
	else if ( $current_state == PROJ_CORRECT_AVAILABLE &&
		$new_state == PROJ_CORRECT_CHECKED_OUT )
	{
		if ( $extras['checkedoutby'] == '' )
		{
			return "$transition_str: \$extras['checkedoutby'] is unset";
		}
		$settings .= ", modifieddate='$now'";
		$settings .= ", checkedoutby='{$extras['checkedoutby']}'";
	}
	else if ( $current_state == PROJ_CORRECT_CHECKED_OUT &&
		$new_state == PROJ_CORRECT_AVAILABLE )
	{
		$settings .= ", modifieddate='$now'";
		$settings .= ", checkedoutby=''";
	}

	if ($testing)
	{
		echo "{$indent}settings: $settings{$eol}";
	}

	$res = mysql_query("
		UPDATE projects SET $settings WHERE projectid = '$projectid'
	");
	if (!$res)
	{
		return "$transition_str: mysql error: " . mysql_error();
	}

	// ---------------------------------------------------------------------
	// Okay, so the update to the 'projects' table succeeded.
	// Now make any collateral changes to other tables.
	// (This needs to be expanded.)

	if ( $current_state == PROJ_P1_WAITING_FOR_RELEASE &&
		$new_state == PROJ_P1_AVAILABLE )
	{
		// Releasing the project.
		// Update the corresponding projectID* table.
		Pages_prepForRound( $projectid, 1 );

		// Update the page_counts table.
		project_update_page_counts( $projectid );
	}


	// move the project topic in synch with the project's progress
	move_project_thread( $projectid, get_forum_id_for_project_state($new_state) );


	// (All the stuff under PROJ_SUBMIT_PG_POSTED, except for the assignment
	// to $settings, should probably be moved here.)

	// ---------------------------------------------------------------------

	if ($testing)
	{
		echo "{$indent}$transition_str: success{$eol}";
	}
	return '';
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function project_pre_release_check( $project )
// Check whether the project seems ready to be released.
// If it is, return an empty string.
// If it has problems, return a detailed error-message.
{
	global $projects_dir;

	$errors = '';

	$projectid  = $project['projectid'];
	$nameofwork = $project['nameofwork'];
	$author     = $project['authorsname'];
	$language   = $project['language'];
	$clearance  = $project['clearance'];

	if ($nameofwork == '') $errors .= "  "._("The 'Title' field is blank.")."\n";
	if ($author == '')     $errors .= "  "._("The 'Author' field is blank.")."\n";
	if ($language == '')   $errors .= "  "._("The 'Language' field is blank.")."\n";
	if ($clearance == '')  $errors .= "  "._("The 'Clearance' field is blank.")."\n";

	if ($errors) $errors .= "\n";

    if($readBIGtable)
    {
        if(!isset($pages) || $pages->projectid != $project)
            $pages = new Pages($projectid);
        if($pages->TotalPageCount == 0) $errors .= "  "._("There are no pages.")."\n";
        foreach($pages->Pages() AS $pg)
        {
            if (!file_exists($pg->ImageFilePath())) {
                $errors .= "  $pg->pageCode: "._("image file is missing")."\n";
            } else if (filesize($pg->ImageFilePath()) < 100) {
                $errors .= "  $pg->pageCode: "._("image file is small and probably bad")."\n";
            }

            if (!file_exists($pg->TextFilePath())) {
                $errors .= "  $pg->pageCode: "._("master text file is missing.")."\n";
            } else if (filesize($pg->TextFilePath()) == 0) {
                $errors .= "  $pg->pageCode: "._("master text file is empty.")."\n";
            }
            if(
        }
    }
    else
    {
        $res = mysql_query("SELECT image,LENGTH(master_text) as master_text_length FROM $projectid ORDER BY image ASC");

        if (!$res)
        {
            $errors .= "  "._("There is no page-table.")."\n";
            return $errors;
        }


        if (mysql_num_rows($res) == 0) $errors .= "  "._("There are no pages.")."\n";

        while ( $page = mysql_fetch_assoc($res) )
        {
            $filename = $page['image'];
            $filepath = "$projects_dir/$projectid/$filename";

            if (!file_exists($filepath)) {
                $errors .= "  $filename: "._("image file is missing")."\n";
            } else if (filesize($filepath) < 100) {
                $errors .= "  $filename: "._("image file is small and probably bad")."\n";
            }

            if ($page['master_text_length'] == 0) {
                $errors .= "  $filename: "._("text is empty")."\n";
            }
        }
    }

	return $errors;
}

?>
