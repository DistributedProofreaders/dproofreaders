<?PHP
include_once($relPath.'project_states.inc');
include_once($relPath.'project_states_text.inc');

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
            $wProc = 'FIRST' ;
            break ;

        case PROJ_PROOF_SECOND_UNAVAILABLE :
        case PROJ_PROOF_SECOND_WAITING_FOR_RELEASE :
        case PROJ_PROOF_SECOND_BAD_PROJECT :
        case PROJ_PROOF_SECOND_AVAILABLE :
        case PROJ_PROOF_SECOND_VERIFY :
        case PROJ_PROOF_SECOND_COMPLETE :
            $wProc = 'SECOND' ;
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

// hmmm...this is php after all - why isn't this an associative array?
// The syntax to use it would be roughly the same, and it would be more readable.
// And it would save you from creating a whole second set of manifest constants.
// Which aren't even easy to find. How would you ever catch a misspelled constant?
// (Since there is no compile step to validate it for you.)

// But then I don't know php very well - must be some reason...

function project_states_text($state)
  {
  switch($state)
    {
    case PROJ_NEW:
      return PROJ_NEW_TEXT;
    case PROJ_NEW_WAITING_APPROVAL:
      return PROJ_NEW_WAITING_APPROVAL_TEXT;
    case PROJ_NEW_UNAPPROVED:
      return PROJ_NEW_UNAPPROVED_TEXT;
    case PROJ_NEW_APPROVED;
      return PROJ_NEW_APPROVED_TEXT;
    case PROJ_NEW_FILE_UPLOADED:
      return PROJ_NEW_FILE_UPLOADED_TEXT;
    case PROJ_NEW_METADATA_FIRST:
      return PROJ_NEW_METADATA_FIRST_TEXT;
    case PROJ_NEW_METADATA_BAD:
      return PROJ_NEW_METADATA_BAD_TEXT;
    case PROJ_NEW_METADATA_SECOND:
      return PROJ_NEW_METADATA_SECOND_TEXT;
    case PROJ_NEW_PREPROCESSING:
      return PROJ_NEW_PREPROCESSING_TEXT;
    case PROJ_NEW_PENDING_PM:
      return PROJ_NEW_PENDING_PM_TEXT;
    case PROJ_PROOF_FIRST_UNAVAILABLE:
      return PROJ_PROOF_FIRST_UNAVAILABLE_TEXT;
    case PROJ_PROOF_SECOND_UNAVAILABLE:
      return PROJ_PROOF_SECOND_UNAVAILABLE_TEXT;
    case PROJ_PROOF_FIRST_WAITING_FOR_RELEASE:
      return PROJ_PROOF_FIRST_WAITING_FOR_RELEASE_TEXT;
    case PROJ_PROOF_SECOND_WAITING_FOR_RELEASE:
      return PROJ_PROOF_SECOND_WAITING_FOR_RELEASE_TEXT;
    case PROJ_PROOF_FIRST_BAD_PROJECT:
      return PROJ_PROOF_FIRST_BAD_PROJECT_TEXT;
    case PROJ_PROOF_SECOND_BAD_PROJECT:
      return PROJ_PROOF_SECOND_BAD_PROJECT_TEXT;
    case PROJ_PROOF_FIRST_AVAILABLE:
      return PROJ_PROOF_FIRST_AVAILABLE_TEXT;
    case PROJ_PROOF_SECOND_AVAILABLE:
      return PROJ_PROOF_SECOND_AVAILABLE_TEXT;
    case PROJ_PROOF_FIRST_VERIFY:
      return PROJ_PROOF_FIRST_VERIFY_TEXT;
    case PROJ_PROOF_SECOND_VERIFY:
      return PROJ_PROOF_SECOND_VERIFY_TEXT;
    case PROJ_PROOF_FIRST_COMPLETE:
      return PROJ_PROOF_FIRST_COMPLETE_TEXT;
    case PROJ_PROOF_SECOND_COMPLETE:
      return PROJ_PROOF_SECOND_COMPLETE_TEXT;
    case PROJ_POST_FIRST_UNAVAILABLE:
      return PROJ_POST_FIRST_UNAVAILABLE_TEXT;
    case PROJ_POST_FIRST_AVAILABLE:
      return PROJ_POST_FIRST_AVAILABLE_TEXT;
    case PROJ_POST_FIRST_CHECKED_OUT:
      return PROJ_POST_FIRST_CHECKED_OUT_TEXT;
    case PROJ_POST_SECOND_AVAILABLE:
      return PROJ_POST_SECOND_AVAILABLE_TEXT;
    case PROJ_POST_SECOND_CHECKED_OUT:
      return PROJ_POST_SECOND_CHECKED_OUT_TEXT;
    case PROJ_POST_COMPLETE:
      return PROJ_POST_COMPLETE_TEXT;
    case PROJ_SUBMIT_PG_UNAVAILABLE:
      return PROJ_SUBMIT_PG_UNAVAILABLE_TEXT;
    case PROJ_SUBMIT_PG_AVAILABLE:
      return PROJ_SUBMIT_PG_AVAILABLE_TEXT;
    case PROJ_SUBMIT_PG_POSTING:
      return PROJ_SUBMIT_PG_POSTING_TEXT;
    case PROJ_SUBMIT_PG_POSTED:
      return PROJ_SUBMIT_PG_POSTED_TEXT;
    case PROJ_CORRECT_AVAILABLE:
	return PROJ_CORRECT_AVAILABLE_TEXT;
    case PROJ_CORRECT_CHECKED_OUT: 
	return PROJ_CORRECT_CHECKED_OUT_TEXT;
    case PROJ_DELETE:
      return PROJ_DELETE_TEXT;
    case PROJ_COMPLETE:
      return PROJ_COMPLETE_TEXT;
    }
  return "";
  }
?>
