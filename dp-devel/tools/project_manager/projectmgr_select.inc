<?
include_once($relPath.'misc.inc');

$transition_options_ = array(
	PROJ_NEW =>
		array(
			PROJ_PROOF_FIRST_UNAVAILABLE),

	PROJ_NEW_FILE_UPLOADED =>
		array(
			PROJ_NEW_METADATA_FIRST),

	PROJ_PROOF_FIRST_UNAVAILABLE =>
		array(
			PROJ_PROOF_FIRST_WAITING_FOR_RELEASE),

	PROJ_PROOF_FIRST_WAITING_FOR_RELEASE =>
		array(
			PROJ_PROOF_FIRST_UNAVAILABLE,
			PROJ_PROOF_FIRST_AVAILABLE),

	PROJ_PROOF_FIRST_BAD_PROJECT =>
		array(
			PROJ_PROOF_FIRST_UNAVAILABLE,
			'automodify'),

	PROJ_PROOF_FIRST_AVAILABLE =>
		array(
			PROJ_PROOF_FIRST_UNAVAILABLE,
			'automodify'),

	PROJ_PROOF_SECOND_UNAVAILABLE =>
		array(
			PROJ_PROOF_SECOND_WAITING_FOR_RELEASE),

	PROJ_PROOF_SECOND_WAITING_FOR_RELEASE =>
		array(
			PROJ_PROOF_SECOND_UNAVAILABLE,
			PROJ_PROOF_SECOND_AVAILABLE),

	PROJ_PROOF_SECOND_BAD_PROJECT =>
		array(
			PROJ_PROOF_SECOND_UNAVAILABLE,
			'automodify'),

	PROJ_PROOF_SECOND_AVAILABLE =>
		array(
			PROJ_PROOF_SECOND_UNAVAILABLE,
			'automodify'),

	PROJ_POST_FIRST_UNAVAILABLE =>
		array(
			PROJ_POST_FIRST_AVAILABLE,
			PROJ_POST_FIRST_CHECKED_OUT),

	PROJ_POST_FIRST_AVAILABLE =>
		array(
			PROJ_POST_FIRST_UNAVAILABLE,
			PROJ_POST_FIRST_CHECKED_OUT),

	PROJ_POST_FIRST_CHECKED_OUT =>
		array(
			PROJ_POST_FIRST_UNAVAILABLE,
			PROJ_POST_FIRST_AVAILABLE,
			PROJ_POST_SECOND_CHECKED_OUT,
			PROJ_POST_COMPLETE,
			PROJ_SUBMIT_PG_POSTED),

	PROJ_POST_SECOND_CHECKED_OUT =>
		array(
			PROJ_POST_COMPLETE,
			PROJ_SUBMIT_PG_POSTED),

	PROJ_POST_SECOND_AVAILABLE =>
		array(
			PROJ_POST_COMPLETE,
			PROJ_SUBMIT_PG_POSTED),

	PROJ_POST_COMPLETE =>
		array(
			PROJ_SUBMIT_PG_POSTED),
);

function getSelect($project_state)
{
	global $transition_options_;
	$next_states = array_get( $transition_options_, $project_state, array() );

	// PROJ_DELETE is implicitly allowed from every state.
	$next_states[] = PROJ_DELETE;

	echo_project_state_option( $project_state, 1 );
	foreach ($next_states as $next_state)
	{
		echo_project_state_option( $next_state, 0 );
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
