<?
    // $Id$

include_once($relPath.'v_site.inc');
include_once($relPath.'gettext_setup.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'ProjectTransition.inc');

function project_transition( $projectid, $new_state, $extras = array() )
// Move the given project to the given state,
// and perform any attendant processing.
// If there are any problems, return a string containing an error message.
// Otherwise, return an empty string.
//
// This function produces no output except for debugging messages.
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

	$error = $transition->do_state_change( $project, $extras );
	if ( $error == '' )
	{
		// success
		if ($testing)
		{
			echo "{$indent}$transition_str: success{$eol}";
		}
	}
	else
	{
		// failure
		// Prepend $transition_str to the error message.
		$error = "$transition_str: $error";
	}
	return $error;
}

?>
