<?PHP
include_once($relPath.'project_states.inc');
include_once($relPath.'project_states_text.inc');

function projectStateRound($state)
  {
  $wProc='NONE';
    if ($state==NEW_PROJECT)
      {$wProc=='NEW';}
//  for the pre-processing
//    else if ($state==)
//      {$wProc=='PR';}
    else if ($state==UNAVAIL_PI_FIRST || $state==WAITING_PI_FIRST || $state==BAD_PI_FIRST || 
      $state==AVAIL_PI_FIRST || $state==VERIFY_PI_FIRST || $state==COMPLETE_PI_FIRST)
      {$wProc=='FIRST';}
    else if ($state==UNAVAIL_PI_SECOND || $state==WAITING_PI_SECOND || $state==BAD_PI_SECOND || 
      $state==AVAIL_PI_SECOND || $state==VERIFY_PI_SECOND || $state==COMPLETE_PI_SECOND)
      {$wProc=='SECOND';}
    else if ($state==UNAVAIL_PP || $state==AVAIL_PP || $state==IN_PP || $state==VERIFY_PP || 
      $state==VERIFYING_PP || $state==COMPLETE_PP)
      {$wProc=='PP';}
    else if ($state==UNAVAIL_GB || $state==AVAIL_GB || $state==POSTING_GB || $state==POSTED_GB)
      {$wProc=='GB';}
    else if ($state==COMPLETED_PROJECT)
      {$wProc=='COMPLETE';}
  return $wProc;
  }

function project_states_text($state)
  {
  switch($state)
    {
    case NEW_PROJECT:
      return NEW_PROJECT_TEXT;
    case UNAVAIL_PI_FIRST:
      return UNAVAIL_PI_FIRST_TEXT;
    case UNAVAIL_PI_SECOND:
      return UNAVAIL_PI_SECOND_TEXT;
    case WAITING_PI_FIRST:
      return WAITING_PI_FIRST_TEXT;
    case WAITING_PI_SECOND:
      return WAITING_PI_SECOND_TEXT;
    case BAD_PI_FIRST:
      return BAD_PI_FIRST_TEXT;
    case BAD_PI_SECOND:
      return BAD_PI_SECOND_TEXT;
    case AVAIL_PI_FIRST:
      return AVAIL_PI_FIRST_TEXT;
    case AVAIL_PI_SECOND:
      return AVAIL_PI_SECOND_TEXT;
    case VERIFY_PI_FIRST:
      return VERIFY_PI_FIRST_TEXT;
    case VERIFY_PI_SECOND:
      return VERIFY_PI_SECOND_TEXT;
    case COMPLETE_PI_FIRST:
      return COMPLETE_PI_FIRST_TEXT;
    case COMPLETE_PI_SECOND:
      return COMPLETE_PI_SECOND_TEXT;
    case UNAVAIL_PP:
      return UNAVAIL_PP_TEXT;
    case AVAIL_PP:
      return AVAIL_PP_TEXT;
    case IN_PP:
      return IN_PP_TEXT;
    case VERIFY_PP:
      return VERIFY_PP_TEXT;
    case VERIFYING_PP:
      return VERIFYING_PP_TEXT;
    case COMPLETE_PP:
      return COMPLETE_PP_TEXT;
    case UNAVAIL_GB:
      return UNAVAIL_GB_TEXT;
    case AVAIL_GB:
      return AVAIL_GB_TEXT;
    case POSTING_GB:
      return POSTING_GB_TEXT;
    case POSTED_GB:
      return POSTED_GB_TEXT;
    case DELETE_PROJECT:
      return DELETE_PROJECT_TEXT;
    case COMPLETED_PROJECT:
      return COMPLETED_PROJECT_TEXT;
    }
  return "";
  }
?>