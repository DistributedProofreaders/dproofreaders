<?PHP
include_once($relPath.'project_states.inc');
include_once($relPath.'project_states_text.inc');

function pageStateRound($state)
  {
  $wProc='NONE';
    if ($state==UNAVAIL_FIRST || $state==BAD_FIRST || $state==AVAIL_FIRST || 
      $state==OUT_FIRST || $state==TEMP_FIRST || $state==SAVE_FIRST)
      {$wProc=='FIRST';}
    else if ($state==UNAVAIL_SECOND || $state==BAD_SECOND || $state==AVAIL_SECOND || 
      $state==OUT_SECOND || $state==TEMP_SECOND || $state==SAVE_SECOND)
      {$wProc=='SECOND';}
  return $wProc;
  }
?>