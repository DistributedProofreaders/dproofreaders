<?
$relPath="./../../pinc/";
include($relPath.'dp_main.inc');
include_once($relPath.'f_pages.inc');
/* $_POST $imagefile, $fileid, $proofstate, $button1, $button2, $button3, $button4,
          $projectname, $text_data, $orient, $lang, $js, $button1_x, $button2_x,
          $button3_x, $button4_x, $editone, $savedm $pagestate */
$project = $projectname;
$text_data = strip_tags($text_data, '<i>');

$tpage=new processpage();
$tpage->setPageState($pagestate,$project,$fileid,$imagefile);

// set tbutton
  if (isset($button1) || isset($button1_x)) {$tbutton=1;} // save (temp)
  if (isset($button2) || isset($button2_x)) {$tbutton=2;} // save and do next
  if (isset($button3) || isset($button3_x)) {$tbutton=3;} // Quit
  if (isset($button4) || isset($button4_x)) {$tbutton=4;} // change layout/save (temp)
  if (isset($button5) || isset($button5_x)) {$tbutton=5;} // save and Quit
  if (isset($button6) || isset($button6_x)) {$tbutton=6;} // Bad Page
  if (isset($button7) || isset($button7_x)) {$tbutton=7;} // Return to Round (abandon)
  if (isset($button8) || isset($button8_x)) {$tbutton=8;} // Revert text/save (temp)
  if (isset($button9) || isset($button9_x)) {$tbutton=9;} // Undo Revert text (to last save)

// set prefs
  if ($userP['i_type']==1)
  {
    $isChg=0;
      if ($userP['i_layout']==1)
      {
        if ($userP['v_fntf']!=$fntFace) {$userP['v_fntf']=$fntFace;$isChg=1;}
        if($userP['v_fnts']!=$fntSize) {$userP['v_fnts']=$fntSize;$isChg=1;}
        if ($userP['v_zoom']!=$zmSize) {$userP['v_zoom']=$zmSize;$isChg=1;}
      }
      else
      {
        if ($userP['h_fntf']!=$fntFace) {$userP['h_fntf']=$fntFace;$isChg=1;}
        if($userP['h_fnts']!=$fntSize) {$userP['h_fnts']=$fntSize;$isChg=1;}
        if ($userP['h_zoom']!=$zmSize) {$userP['h_zoom']=$zmSize;$isChg=1;}
      }
    $userP['prefschanged']=$isChg;
    $cookieC->setTempPrefs($userP,$pguser);
  }

//Make sure project is still available
  $sql = "SELECT state FROM projects WHERE projectid = '$project' LIMIT 1";
  $result = mysql_query($sql);
  $state = mysql_result($result, 0, "state");
    if ((($proofstate == AVAIL_PI_FIRST) && ($state != AVAIL_FIRST)) || (($proofstate == AVAIL_PI_SECOND) && ($state != AVAIL_SECOND)))
    {
      $tpage->noPages($userP['i_newwin']);
      exit;
    } // end project open check



// BUTTON CODE

// temp saves and revert
if ($tbutton==1 || $tbutton==4 || $tbutton==8 || $tbutton==9)
{
  if ($tbutton!=9) {$pagestate=$tpage->saveTemp($proofstate,$text_data,$pguser);}
  else {$pagestate=$tpage->getRevertState();}
    if ($tbutton==4)
    {
      $userP['i_layout']=$userP['i_layout']==1? 0:1;
      $userP['prefschanged']=1;
      $cookieC->setTempPrefs($userP, $pguser);
    } // end change layout prefs
    if ($tbutton==8) {$revert=1;}

  $project = 'project='.$project;
  $fileid = '&fileid='.$fileid;
  $imagefile = '&imagefile='.$imagefile;
  $proofstate = '&proofstate='.$proofstate;
  $pagestate = '&pagestate='.$pagestate;
  $lang='&lang='.$lang;
  $saved='&saved=1';
  $frame1 = 'proof.php?'.$project.$fileid.$imagefile.$proofstate.$pagestate.$lang.$saved;
    if (isset($editone)){$frame1=$frame1."&editone=1";}
    if (isset($revert)){$frame1=$frame1."&revert=1";}
  metarefresh(0,$frame1,' ',' ');
} // end save and continue same page button 1 & button 4 & button 8

// save and do another

if ($tbutton==2)
{
  $tpage->saveComplete($proofstate,$text_data,$pguser);
  $project = 'project='.$project;
  $proofstate = '&proofstate='.$proofstate;
  $lang='&lang='.$lang;
  $frame1 = 'proof.php?'.$project.$proofstate.$lang;
  metarefresh(0,$frame1,' ',' ');
} // end save and do another button 2

// quit
if ($tbutton==3)
{
  $tpage->exitInterface($userP['i_newwin']);
}

// save and quit send back to projects page
if ($tbutton==5)
{
  $tpage->saveComplete($proofstate,$text_data,$pguser);
  $tpage->exitInterface($userP['i_newwin']);
} // end button 5 quit

// bad page report
if ($tbutton==6)
{


} // end button 6 bad page

// return page to current round
if ($tbutton==7)
{
  $tpage->returnPage($pguser);
  $tpage->exitInterface($userP['i_newwin']);
} // end return to round

?>