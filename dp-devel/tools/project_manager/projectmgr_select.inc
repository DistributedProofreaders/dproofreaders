<?
$st_id=array();
// 0 cannot be used
$st_id[ 0]=0;
$st_id[ 1]=PROJ_NEW;
$st_id[ 2]=PROJ_PROOF_FIRST_UNAVAILABLE;
$st_id[ 3]=PROJ_PROOF_FIRST_WAITING_FOR_RELEASE;
$st_id[ 4]=PROJ_PROOF_FIRST_BAD_PROJECT;
$st_id[ 5]=PROJ_PROOF_FIRST_AVAILABLE;
$st_id[ 6]=PROJ_PROOF_FIRST_VERIFY;
$st_id[ 7]=PROJ_PROOF_FIRST_COMPLETE;
$st_id[ 8]=PROJ_PROOF_SECOND_UNAVAILABLE;
$st_id[ 9]=PROJ_PROOF_SECOND_WAITING_FOR_RELEASE;
$st_id[10]=PROJ_PROOF_SECOND_BAD_PROJECT;
$st_id[11]=PROJ_PROOF_SECOND_AVAILABLE;
$st_id[12]=PROJ_PROOF_SECOND_VERIFY;
$st_id[13]=PROJ_PROOF_SECOND_COMPLETE;
$st_id[14]=PROJ_POST_UNAVAILABLE;
$st_id[15]=PROJ_POST_AVAILABLE;
$st_id[16]=PROJ_POST_CHECKED_OUT;
$st_id[17]=PROJ_POST_VERIFY;
$st_id[18]=PROJ_POST_VERIFYING;
$st_id[19]=PROJ_POST_COMPLETE;
$st_id[20]=PROJ_SUBMIT_PG_UNAVAILABLE;
$st_id[21]=PROJ_SUBMIT_PG_AVAILABLE;
$st_id[22]=PROJ_SUBMIT_PG_POSTING;
$st_id[23]=PROJ_SUBMIT_PG_POSTED;
$st_id[24]=PROJ_DELETE;
$st_id[25]=PROJ_COMPLETE;
// add more states onto the end of array, just send request
// in correct order to stateSelect() for printing

$st_sw=array();


function stateSelect($project_state,$print_req)
  {
    global $st_id;

    for ($i=1;$i<count($print_req);$i++)
    {
      $id=$print_req[$i];
      if($id !=0 || $project_state == $st_id[$i] || $i==24)
      {
        $id=$st_id[$i];
        echo "<option \r\nvalue=\"$id\"";
          if ($project_state == $id) { echo " SELECTED"; }
        echo ">".project_states_text($id)."</option>";
      }
    }
  }


function getSelect($state)
{
// only in the latest v. of php
//  $st_sw=array_fill (0, 26, 0);

  $st_sw=array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);

  if($state==PROJ_PROOF_FIRST_UNAVAILABLE)
    {$st_sw[3]=1;}
  else if($state==PROJ_PROOF_FIRST_WAITING_FOR_RELEASE || $state==PROJ_PROOF_FIRST_BAD_PROJECT || $state==PROJ_PROOF_FIRST_AVAILABLE)
    {$st_sw[2]=1;$st_sw[6]=1;}
  else if($state==PROJ_PROOF_FIRST_VERIFY)
    {$st_sw[2]=1;}
  else if($state==PROJ_PROOF_SECOND_UNAVAILABLE)
    {$st_sw[12]=1;}
  else if($state==PROJ_PROOF_SECOND_WAITING_FOR_RELEASE || $state==PROJ_PROOF_SECOND_BAD_PROJECT || $state==PROJ_PROOF_SECOND_AVAILABLE)
    {$st_sw[8]=1;$st_sw[12]=1;}
  else if($state==PROJ_POST_UNAVAILABLE || $state==PROJ_POST_AVAILABLE)
    {$st_sw[14]=1;$st_sw[15]=1;$st_sw[16]=1;}
  else if($state==PROJ_POST_CHECKED_OUT)
    {$st_sw[14]=1;$st_sw[15]=1;$st_sw[17]=1;$st_sw[19]=1;$st_sw[23]=1;}
  else if($state==PROJ_POST_VERIFYING)
    {$st_sw[19]=1;$st_sw[23]=1;}
  else if($state==PROJ_POST_COMPLETE)
    {$st_sw[20]=1;$st_sw[21]=1;$st_sw[22]=1;$st_sw[23]=1;}
  else if($state==PROJ_SUBMIT_PG_UNAVAILABLE)
    {$st_sw[21]=1;$st_sw[22]=1;}
  else if($state==PROJ_SUBMIT_PG_AVAILABLE || $state==PROJ_SUBMIT_PG_POSTING)
    {$st_sw[20]=1;$st_sw[21]=1;$st_sw[22]=1;$st_sw[23]=1;}
  stateSelect($state,$st_sw);
}
?>
