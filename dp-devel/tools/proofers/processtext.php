<?
$relPath="./../../pinc/";
include($relPath.'dp_main.inc');
include_once($relPath.'c_pages.inc');
include_once($relPath.'v_keepmarkup.inc');

/* $_POST $imagefile, $fileid, $proofstate, $button1, $button2, $button3, $button4,
          $projectname, $text_data, $orient, $lang, $js, $button1_x, $button2_x,
          $button3_x, $button4_x, $editone, $savedm $pagestate */
$project = isset($projectname)?$projectname:0;
$text_data = isset($text_data)?strip_tags($text_data, PROOF_SECOND_TAGS_KEEP):'';

$tpage=new processpage();
  if ($project !='')
  {$tpage->setPageState($pagestate,$project,$fileid,$imagefile,$proofstate);}

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
  if (isset($button10) || isset($button10_x)) {$tbutton=10;} // Run Spelling Check
  if (isset($button11) || isset($button11_x)) {$tbutton=11;} // Run Common Errors Check

  if (isset($spcorrect)) {$tbutton=101;} // Make Spelling Corrections
  if (isset($spexit)) {$tbutton=102;} // Exit Spelling Corrections
  if (isset($errcorrect)) {$tbutton=111;} // Make Spelling Corrections
  if (isset($errexit)) {$tbutton=112;} // Exit Spelling Corrections

// set prefs
  if ($userP['i_type']==1)
  {
    $isChg=0;
      if ($userP['i_layout']==1)
      {
        if (isset($fntFace) && $userP['v_fntf']!=$fntFace) {$userP['v_fntf']=$fntFace;$isChg=1;}
        if (isset($fntSize) && $userP['v_fnts']!=$fntSize) {$userP['v_fnts']=$fntSize;$isChg=1;}
        if (isset($zmSize) && $userP['v_zoom']!=$zmSize) {$userP['v_zoom']=$zmSize;$isChg=1;}
      }
      else
      {
        if (isset($fntFace) && $userP['h_fntf']!=$fntFace) {$userP['h_fntf']=$fntFace;$isChg=1;}
        if (isset($fntSize) && $userP['h_fnts']!=$fntSize) {$userP['h_fnts']=$fntSize;$isChg=1;}
        if (isset($zmSize) && $userP['h_zoom']!=$zmSize) {$userP['h_zoom']=$zmSize;$isChg=1;}
      }
    $userP['prefschanged']=$isChg;
    $cookieC->setTempPrefs($userP,$pguser);
  }

//Make sure project is still available
  // only if not in a check
  if ($tbutton <100 && $project !=0)
  {
    $sql = "SELECT state FROM projects WHERE projectid = '$project' LIMIT 1";
    $result = mysql_query($sql);
    $state = mysql_result($result, 0, "state");
      if ((($proofstate == PROJ_PROOF_FIRST_AVAILABLE) && ($state != AVAIL_FIRST)) ||
          (($proofstate == PROJ_PROOF_SECOND_AVAILABLE) && ($state != AVAIL_SECOND)))
      {
        $tpage->noPages($userP['i_newwin']);
        exit;
      } // end project open check
  }

// BUTTON CODE

// temp saves and revert
if ($tbutton==1 || $tbutton==4 || $tbutton==8 || $tbutton==9)
{
  $npage=$tpage->getPageCookie();
  if ($tbutton!=9) {$npage['pagestate']=$tpage->saveTemp($proofstate,$text_data,$pguser);}
  else {$npage['pagestate']=$tpage->getRevertState();}
    if ($tbutton==4)
    {
      $userP['i_layout']=$userP['i_layout']==1? 0:1;
      $userP['prefschanged']=1;
      $cookieC->setTempPrefs($userP, $pguser);
    } // end change layout prefs
    if ($tbutton==8) {$npage['revert']=1;}
    else {$npage['revert']=0;}
  $npage['saved']=1;
  $npage['spcheck']=0;
  $npage['errcheck']=0;
  $tpage->setTempPageCookie($npage);
  if ($userP['i_type'] != 1)
    {include('proof_frame_nj.inc');}
  else
    {metarefresh(0,"text_frame.php","Proofing Text Frame","Loading next available page....");}
  exit;
} // end save and continue same page button 1 & button 4 & button 8

// save and do another

if ($tbutton==2)
{
  $tpage->saveComplete($proofstate,$text_data,$pguser,$userP);
  $project = 'project='.$project;
  $proofstate = '&amp;proofstate='.$proofstate;
  $frame1 = 'proof_frame.php?'.$project.$proofstate;
  metarefresh(0,$frame1,'Save and Do Next Page','Page saved.');
} // end save and do another button 2

// quit
if ($tbutton==3)
{
  $project = 'project='.$project;
  $proofstate = '&amp;proofstate='.$proofstate;
  $frame1 = 'projects.php?'.$project.$proofstate;
  metarefresh(0,$frame1,'Quit Proofing','Exiting proofing interface....');
//  $editone=isset($editone)?$editone:0;
//  $tpage->exitInterface($userP['i_newwin'],$editone);
}

// save and quit send back to projects page
if ($tbutton==5)
{
  $tpage->saveComplete($proofstate,$text_data,$pguser,$userP);
  $project = 'project='.$project;
  $proofstate = '&amp;proofstate='.$proofstate;
  $frame1 = 'projects.php?'.$project.$proofstate;
  metarefresh(4,$frame1,'Save and Quit Proofing','Page Saved. Exiting proofing interface....');
//  $editone=isset($editone)?$editone:0;
//  $tpage->exitInterface($userP['i_newwin'],$editone);
} // end button 5 quit

// bad page report
if ($tbutton==6)
{
$badState=$tpage->bad_page;
include('badpage.php');
} // end button 6 bad page

// return page to current round
if ($tbutton==7)
{
  $tpage->returnPage($proofstate,$pguser,$userP);
  $project = 'project='.$project;
  $proofstate = '&amp;proofstate='.$proofstate;
  $frame1 = 'projects.php?'.$project.$proofstate;
  metarefresh(4,$frame1,'Return to Round','Page Returned to Round.  Exiting proofing interface....');
//  $editone=isset($editone)?$editone:0;
//  $tpage->exitInterface($userP['i_newwin'],$editone);
} // end return to round

// run spelling check
if ($tbutton==10)
{
  $npage=$tpage->getPageCookie();
  $npage['spcheck']=1;
  $tpage->setTempPageCookie($npage);
  include('spellcheck.inc');
} // end spelling check

// run common errors check
if ($tbutton==11)
{
  $npage=$tpage->getPageCookie();
  $npage['errcheck']=1;
  $tpage->setTempPageCookie($npage);
//  include('errcheck.inc');
} // end common errors check

// Make Spelling Corrections
if ($tbutton==101)
{
  $inCheck=1;
  include('spellcorrect.inc');
} // end spelling corrections

// Exit Spelling Corrections
if ($tbutton==102)
{
  // just give them the text
    $correct_text=str_replace("[lf]","\r\n",stripslashes($text_data));
    $npage=$tpage->getPageCookie();
    if ($userP['i_type']==1)
      {$npage['spcheck']=0;}
    else
      {$npage['spcheck']=2;}
    $tpage->setTempPageCookie($npage);
    $inCheck=1;
    if ($userP['i_type']==1)
      {include('text_frame.php');}
    else
      {
        // write file
          include_once($relPath.'sp_check_user.inc');
          $text_file= $project.substr($imagefile,0,-4).".txt";
          $text_array= explode("[lf]",$text_data);
          $correct_text.=implode("\r\n",$text_array);
          if ($fd=fopen($text_dir.$text_file,"w"))
            {fwrite($fd,stripslashes($correct_text));}
        include ('proof_frame_nj.inc');
      }
} // end exit spelling corrections
?>
