<?PHP
include_once($relPath.'project_states.inc');

function get_phase_containing_project_state($state)
{
    global $project_state_phase_;
    if (isset($project_state_phase_[$state]))
    {
      return $project_state_phase_[$state];
    }
    else
    {
      return 'NONE';
    }
}

function project_states_text($state)
  {
    global $project_state_label_;
    if (isset($project_state_label_[$state]))
    {
      return $project_state_label_[$state];
    }
    else
    {
      return "";
    }
  }
?>
