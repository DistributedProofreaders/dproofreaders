<?PHP
include_once($relPath.'project_states.inc');
include_once($relPath.'misc.inc');

function get_phase_containing_project_state($state)
{
    global $project_state_phase_;
    return array_get( $project_state_phase_, $state, 'NONE' );
}

function project_states_text($state)
{
    global $project_state_label_;
    return array_get( $project_state_label_, $state, '' );
}

?>
