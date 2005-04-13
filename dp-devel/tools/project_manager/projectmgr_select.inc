<?
include_once($relPath.'misc.inc');
include_once($relPath.'v_site.inc');
include_once($relPath.'stages.inc');

$transition_options_ = array(
	PROJ_NEW =>
		array(
			PROJ_P1_UNAVAILABLE),

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

if ($site_supports_metadata)
{
	$transition_options_[ PROJ_NEW_FILE_UPLOADED ] =
		array(
			PROJ_NEW_METADATA_FIRST);
}

for ($rn = 1; $rn <= MAX_NUM_PAGE_EDITING_ROUNDS; $rn++ )
{
	$round = get_Round_for_round_number($rn);

	$transition_options_[ $round->project_unavailable_state ] =
			array(
				$round->project_waiting_state);

	$transition_options_[ $round->project_waiting_state ] =
			array(
				$round->project_unavailable_state,
				$round->project_available_state);

	$transition_options_[ $round->project_bad_state ] =
			array(
				$round->project_unavailable_state,
				'automodify');

	$transition_options_[ $round->project_available_state ] =
			array(
				$round->project_unavailable_state,
				'automodify');
}

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
