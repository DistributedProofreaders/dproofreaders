<?PHP
include_once($relPath.'project_states.inc');
include_once($relPath.'project_states_text.inc');

function projectStateRound($state)
  {
  $wProc='NONE';
    if ($state==PROJ_NEW)
      {$wProc=='NEW';}
//  for the pre-processing
//    else if ($state==)
//      {$wProc=='PR';}
    else if ($state==PROJ_PROOF_FIRST_UNAVAILABLE || $state==PROJ_PROOF_FIRST_WAITING_FOR_RELEASE || $state==PROJ_PROOF_FIRST_BAD_PROJECT || 
      $state==PROJ_PROOF_FIRST_AVAILABLE || $state==PROJ_PROOF_FIRST_VERIFY || $state==PROJ_PROOF_FIRST_COMPLETE)
      {$wProc=='FIRST';}
    else if ($state==PROJ_PROOF_SECOND_UNAVAILABLE || $state==PROJ_PROOF_SECOND_WAITING_FOR_RELEASE || $state==PROJ_PROOF_SECOND_BAD_PROJECT || 
      $state==PROJ_PROOF_SECOND_AVAILABLE || $state==PROJ_PROOF_SECOND_VERIFY || $state==PROJ_PROOF_SECOND_COMPLETE)
      {$wProc=='SECOND';}
    else if ($state==PROJ_POST_UNAVAILABLE || $state==PROJ_POST_AVAILABLE || $state==PROJ_POST_CHECKED_OUT || $state==PROJ_POST_VERIFY || 
      $state==PROJ_POST_VERIFYING || $state==PROJ_POST_COMPLETE)
      {$wProc=='PP';}
    else if ($state==PROJ_SUBMIT_PG_UNAVAILABLE || $state==PROJ_SUBMIT_PG_AVAILABLE || $state==PROJ_SUBMIT_PG_POSTING || $state==PROJ_SUBMIT_PG_POSTED)
      {$wProc=='GB';}
    else if ($state==PROJ_COMPLETE)
      {$wProc=='COMPLETE';}
  return $wProc;
  }

function project_states_text($state)
  {
  switch($state)
    {
    case PROJ_NEW:
      return PROJ_NEW_TEXT;
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
    case PROJ_POST_UNAVAILABLE:
      return PROJ_POST_UNAVAILABLE_TEXT;
    case PROJ_POST_AVAILABLE:
      return PROJ_POST_AVAILABLE_TEXT;
    case PROJ_POST_CHECKED_OUT:
      return PROJ_POST_CHECKED_OUT_TEXT;
    case PROJ_POST_VERIFY:
      return PROJ_POST_VERIFY_TEXT;
    case PROJ_POST_VERIFYING:
      return PROJ_POST_VERIFYING_TEXT;
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
    case PROJ_DELETE:
      return PROJ_DELETE_TEXT;
    case PROJ_COMPLETE:
      return PROJ_COMPLETE_TEXT;
    }
  return "";
  }
?>