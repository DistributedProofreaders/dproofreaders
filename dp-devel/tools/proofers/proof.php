<?
$relPath="./../../pinc/";
include($relPath.'dp_main.inc');
include_once($relPath.'f_pages.inc');
/* $_GET $project, $proofstate, $orient, $text_data, $fileid, $imagefile, $js,
         $saved, $editone, $lang, $pagestate */

$tpage=new processpage();


if (!isset($saved))
{
  //Make sure project is still available
  $sql = "SELECT state FROM projects WHERE projectid = '$project' LIMIT 1";
  $result = mysql_query($sql);
  $state = mysql_result($result, 0, "state");
    if ((($proofstate == AVAIL_PI_FIRST) && ($state != AVAIL_FIRST)) || (($proofstate == AVAIL_PI_SECOND) && ($state != AVAIL_SECOND)))
    {
      $tpage->noPages($userP['i_newwin']);
      exit;
    } // end project open check

  $npage=$tpage->getAvailablePage($project,$proofstate,$pguser);
    if ($npage['isopen'] == 0)
    {
      $tpage->noPages($userP['i_newwin']);
      exit;
    } //end no pages left check
    else
    {
      $pagestate=$tpage->checkOutPage($project,$proofstate,$pguser,$npage['fileid'],$npage['image']);
    } // end get new page

} // end get new page (not saved) process
else
{
  $npage['image']=$imagefile;
  $npage['fileid']=$fileid;
} // end load from saved page

// create pretty page number
  $pageNum=substr($npage['image'],0,-4);

// create get strings for current pages
  $fileid = '&fileid='.$npage['fileid'];
  $imagefile = '&imagefile='.$npage['image'];
  $newproofstate = '&proofstate='.$proofstate;
  $pagestate= '&pagestate='.$pagestate;
  // will need to add a true language option to this in future
    $lang=isset($lang)? $lang:'1';
    $lang="&lang=$lang";

  // which page goes in image spot?
    $frame1=isset($saved)? 'saved':'imageframe';

  // frame get strings
  $frame1 = $frame1.'.php?project='.$project.$imagefile;
  $frame3 = 'textframe.php?project='.$project.$imagefile.$fileid.$pagestate.$newproofstate.$lang;

  // add saved and editone if set
    if (isset($editone)) {$editone="&editone=$editone"; $frame3.=$editone;}
    if (isset($saved)) {$saved="&saved=$saved"; $frame3.=$saved;}
    if (isset($revert)) {$revert="&revert=$revert"; $frame3.=$revert;}

// display page
            include('frameset.inc');
?>
