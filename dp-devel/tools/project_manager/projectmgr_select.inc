<?
$st_id=array();
// 0 cannot be used
$st_id[0]=0;
$st_id[1]=NEW_PROJECT;
$st_id[2]=UNAVAIL_PI_FIRST;
$st_id[3]=WAITING_PI_FIRST;
$st_id[4]=BAD_PI_FIRST;
$st_id[5]=AVAIL_PI_FIRST;
$st_id[6]=VERIFY_PI_FIRST;
$st_id[7]=COMPLETE_PI_FIRST;
$st_id[8]=UNAVAIL_PI_SECOND;
$st_id[9]=WAITING_PI_SECOND;
$st_id[10]=BAD_PI_SECOND;
$st_id[11]=AVAIL_PI_SECOND;
$st_id[12]=VERIFY_PI_SECOND;
$st_id[13]=COMPLETE_PI_SECOND;
$st_id[14]=UNAVAIL_PP;
$st_id[15]=AVAIL_PP;
$st_id[16]=IN_PP;
$st_id[17]=VERIFY_PP;
$st_id[18]=VERIFYING_PP;
$st_id[19]=COMPLETE_PP;
$st_id[20]=UNAVAIL_GB;
$st_id[21]=AVAIL_GB;
$st_id[22]=POSTING_GB;
$st_id[23]=POSTED_GB;
$st_id[24]=DELETE_PROJECT;
$st_id[25]=COMPLETED_PROJECT;
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
  $st_sw=array_fill (0, 26, 0);
  if($state==UNAVAIL_PI_FIRST)
    {$st_sw[3]=1;}
  else if($state==WAITING_PI_FIRST || $state==BAD_PI_FIRST || $state==AVAIL_PI_FIRST)
    {$st_sw[2]=1;$st_sw[6]=1;}
  else if($state==VERIFY_PI_FIRST)
    {$st_sw[2]=1;}
  else if($state==UNAVAIL_PI_SECOND)
    {$st_sw[12]=1;}
  else if($state==WAITING_PI_SECOND || $state==BAD_PI_SECOND || $state==AVAIL_PI_SECOND)
    {$st_sw[8]=1;$st_sw[12]=1;}
  else if($state==UNAVAIL_PP || $state==AVAIL_PP)
    {$st_sw[14]=1;$st_sw[15]=1;$st_sw[16]=1;}
  else if($state==IN_PP)
    {$st_sw[14]=1;$st_sw[15]=1;$st_sw[17]=1;$st_sw[19]=1;$st_sw[23]=1;}
  else if($state==VERIFYING_PP)
    {$st_sw[19]=1;$st_sw[23]=1;}
  else if($state==COMPLETE_PP)
    {$st_sw[20]=1;$st_sw[21]=1;$st_sw[22]=1;$st_sw[23]=1;}
  else if($state==UNAVAIL_GB)
    {$st_sw[21]=1;$st_sw[22]=1;}
  else if($state==AVAIL_GB || $state==POSTING_GB)
    {$st_sw[20]=1;$st_sw[21]=1;$st_sw[22]=1;$st_sw[23]=1;}
  stateSelect($state,$st_sw);
}
?>