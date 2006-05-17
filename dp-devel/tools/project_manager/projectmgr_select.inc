<?
include_once($relPath.'misc.inc');
include_once($relPath.'v_site.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'ProjectTransition.inc');


function getSelect($project)
{
	global $pguser;

	echo_project_state_option( $project->state, 1 );

	$transitions = get_valid_transitions( $project, $pguser );
	foreach ( $transitions as $transition )
	{
		echo_project_state_option( $transition->next_state, 0 );
	}
}

function echo_project_state_option($project_state,$selected)
{
	echo "<option value='$project_state'";
	if ($selected) echo " SELECTED";
	echo ">";
	if ($project_state == 'automodify')
	{
		echo 'automodify';
	}
	else
	{
		echo project_states_text($project_state);
	}
	echo "</option>\n";
}
?>
