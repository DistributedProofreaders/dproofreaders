<?

function get_allowed_next_project_states($state)
{
	// PROJ_DELETE is implicitly allowed from every state.

	switch ($state)
	{
		case PROJ_NEW:
			return array(
				PROJ_PROOF_FIRST_UNAVAILABLE);

		case PROJ_NEW_FILE_UPLOADED:
			return array(
				PROJ_NEW_METADATA_FIRST);

		case PROJ_PROOF_FIRST_UNAVAILABLE:
			return array(
				PROJ_PROOF_FIRST_WAITING_FOR_RELEASE);

		case PROJ_PROOF_FIRST_WAITING_FOR_RELEASE:
			return array(
				PROJ_PROOF_FIRST_UNAVAILABLE,
				PROJ_PROOF_FIRST_AVAILABLE);

		case PROJ_PROOF_FIRST_BAD_PROJECT:
		case PROJ_PROOF_FIRST_AVAILABLE:
			return array(
				PROJ_PROOF_FIRST_UNAVAILABLE,
				'automodify');

		case PROJ_PROOF_FIRST_VERIFY:
			return array(
				PROJ_PROOF_FIRST_UNAVAILABLE);

		case PROJ_PROOF_SECOND_UNAVAILABLE:
			return array(
				PROJ_PROOF_SECOND_WAITING_FOR_RELEASE);

		case PROJ_PROOF_SECOND_WAITING_FOR_RELEASE:
			return array(
				PROJ_PROOF_SECOND_UNAVAILABLE,
				PROJ_PROOF_SECOND_AVAILABLE);

		case PROJ_PROOF_SECOND_BAD_PROJECT:
		case PROJ_PROOF_SECOND_AVAILABLE:
			return array(
				PROJ_PROOF_SECOND_UNAVAILABLE,
				'automodify');

		case PROJ_POST_FIRST_UNAVAILABLE:
			return array(
				PROJ_POST_FIRST_AVAILABLE,
				PROJ_POST_FIRST_CHECKED_OUT);

		case PROJ_POST_FIRST_AVAILABLE:
			return array(
				PROJ_POST_FIRST_UNAVAILABLE,
				PROJ_POST_FIRST_CHECKED_OUT);

		case PROJ_POST_FIRST_CHECKED_OUT:
			return array(
				PROJ_POST_FIRST_UNAVAILABLE,
				PROJ_POST_FIRST_AVAILABLE,
				PROJ_POST_SECOND_CHECKED_OUT,
				PROJ_POST_COMPLETE,
				PROJ_SUBMIT_PG_POSTED);

		case PROJ_POST_SECOND_CHECKED_OUT:
		case PROJ_POST_SECOND_AVAILABLE:
			return array(
				PROJ_POST_COMPLETE,
				PROJ_SUBMIT_PG_POSTED);

		case PROJ_POST_COMPLETE:
			return array(
				PROJ_SUBMIT_PG_POSTED);

		default:
			return array();
	}
}

function getSelect($project_state)
{
	$next_states = get_allowed_next_project_states($project_state);
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
	echo "</option>";
}
?>
