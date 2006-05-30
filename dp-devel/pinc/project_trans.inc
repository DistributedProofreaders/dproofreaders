<?
    // $Id$

include_once($relPath.'v_site.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'gettext_setup.inc');
include_once($relPath.'DPage.inc');
include_once($relPath.'f_move_post.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'ProjectTransition.inc');

function project_transition( $projectid, $new_state, $extras = array() )
// Move the given project to the given state,
// and perform any attendant processing.
// If there are any problems, return a string containing an error message.
// Otherwise, return an empty string.
//
// This function produces no output except for debugging messages.
//
// This function should be the only code that modifies the 'state'
// column of the 'projects' table.
// It should perhaps also be the only place that modifies these columns:
//     modifieddate checkedoutby postproofer postcomments
{
	global $testing;
	global $site_supports_metadata;

	// error_reporting(E_ALL);

	$project = new Project($projectid);

	$current_state = $project->state;

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

	$transition = get_transition( $current_state, $new_state );
	if ( is_null($transition) )
	{
		// Requested transition is illegal.
		$error = "$transition_str "._("is illegal");
		return $error;
	}

	// -------------------------------------------------------------------------

	$now = time();

	$settings = "state='$new_state'";
	if ( !empty($transition->settings_template) )
	{
		// echo "transition->settings_template = $transition->settings_template\n";

		// expand template
		$patterns = array(); $replacements = array();

		$braced_pattern = '/{[^}]*}/';
		$r = preg_match_all( $braced_pattern, $transition->settings_template, $matches );
		if ( $r === FALSE )
		{
			return "$transition_str: preg_match_all return FALSE";
		}
		foreach( $matches[0] as $braced_thing )
		{
			switch ( $braced_thing )
			{
				case '{TIMESTAMP}':
				   	$replacement = time();
					break;

				case '{E:checkedoutby}':
					$replacement = @$extras['checkedoutby'];
					if ( $replacement == '' )
					{
						return "$transition_str: \$extras['checkedoutby'] is unset";
					}
					break;

				case '{E:correctedby}':
					$replacement = @$extras['correctedby'];
					if ( $replacement == '' )
					{
						return "$transition_str: \$extras['correctedby'] is unset";
					}
					break;

				case '{G:postcomments}':
					global $postcomments;
					$replacement = $postcomments;
					break;

				default:
					return "$transition_str: in settings_template, bad braced_thing $braced_thing";
					break;
			}
			$patterns[] = "/$braced_thing/";
			$replacements[] = $replacement;
		}

		$settings_addition = preg_replace(
		       	$patterns, $replacements, $transition->settings_template );

		$settings .= ", $settings_addition";
	}

	$round = get_Round_for_project_state($new_state);
	if ( !is_null($round) )
	{
		if ( $new_state == $round->project_waiting_state
		  || $new_state == $round->project_available_state )
		{
			$errors = project_pre_release_check( get_object_vars($project), $round );
			if ($errors)
			{
				$error = "$transition_str: "._("disallowed because of project problems").":\n<pre>\n$errors</pre>";
				// At this point, the corresponding code in changestate.php
				// would transit the project to the round's bad state.
				// It's probably sufficient to just leave it in its current state.
				return $error;
			}
		}
	}

	else if ( $new_state == PROJ_DELETE )
	{
		global $projects_dir;
		exec("rm -rf $projects_dir/$projectid");

		project_drop_pages( $projectid );

		if ( $current_state == PROJ_NEW )
		{
			$result = mysql_query("
				DELETE FROM projects WHERE projectid = '$projectid'
			");

			if ($testing)
			{
				echo "{$indent}$transition_str: "._("success")."{$eol}";
			}

			return '';
		}
		else
		{
			// It's somewhat problematic to delete the entry from 'projects' table.
			// Instead, keep the entry, but change its state to 'deleted'.
		}
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
	}


	// move the project topic in synch with the project's progress
	move_project_thread( $projectid, get_forum_id_for_project_state($new_state) );


	// ---------------------------------------------------------------------

	if ($testing)
	{
		echo "{$indent}$transition_str: success{$eol}";
	}
	return '';
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function project_pre_release_check( $project, $round )
// Check whether the project seems ready to be released into the round.
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

	$res = mysql_query("
		SELECT image, LENGTH($round->prevtext_column_name) AS input_text_length
		FROM $projectid
		ORDER BY image ASC
	");

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

		/*
		An empty page-text is no longer an indication that something went wrong.
		if ($page['input_text_length'] == 0) {
			$errors .= "  $filename: "._("text is empty")."\n";
		}
		*/
	}

	return $errors;
}

?>
