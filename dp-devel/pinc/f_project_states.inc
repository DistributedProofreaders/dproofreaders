<?PHP
include_once($relPath.'project_states.inc');

function get_phase_containing_project_state($state)
{
    $wProc='NONE';
    switch( $state )
    {
        case PROJ_NEW :
            $wProc = 'NEW' ;
            break ;

        /*
        case ? :
            $wProc = 'PR' ;
            break ;
        */

        case PROJ_PROOF_FIRST_UNAVAILABLE :
        case PROJ_PROOF_FIRST_WAITING_FOR_RELEASE :
        case PROJ_PROOF_FIRST_BAD_PROJECT :
        case PROJ_PROOF_FIRST_AVAILABLE :
        case PROJ_PROOF_FIRST_VERIFY :
        case PROJ_PROOF_FIRST_COMPLETE :
        case PROJ_PROOF_SECOND_UNAVAILABLE :
        case PROJ_PROOF_SECOND_WAITING_FOR_RELEASE :
        case PROJ_PROOF_SECOND_BAD_PROJECT :
        case PROJ_PROOF_SECOND_AVAILABLE :
        case PROJ_PROOF_SECOND_VERIFY :
        case PROJ_PROOF_SECOND_COMPLETE :
            $wProc = 'PAGE_EDITING' ;
            break ;

        case PROJ_POST_FIRST_UNAVAILABLE :
        case PROJ_POST_FIRST_AVAILABLE :
        case PROJ_POST_FIRST_CHECKED_OUT :
        case PROJ_POST_SECOND_AVAILABLE :
        case PROJ_POST_SECOND_CHECKED_OUT :
        case PROJ_POST_COMPLETE :
            $wProc = 'PP' ;
            break ;

        case PROJ_SUBMIT_PG_UNAVAILABLE :
        case PROJ_SUBMIT_PG_AVAILABLE :
        case PROJ_SUBMIT_PG_POSTING :
        case PROJ_SUBMIT_PG_POSTED :
            $wProc = 'GB' ;
            break ;

        case PROJ_COMPLETE :
            $wProc = 'COMPLETE' ;
            break ;
    }
    return $wProc;
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
