<?PHP
$relPath="./../../pinc/";
include($relPath.'dp_main.inc');
include_once($relPath.'c_pages.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'project_continuity.inc');
include_once($relPath.'v_site.inc');

/* $_GET from IN PROGRESS/DONE
$project, $proofstate, $fileid, $imagefile, $pagestate, $saved=1, $editone=1
*/

/* $_GET from "Start Proofreading" etc.
$project, $proofstate
*/

//Make sure project is still in same state.
project_continuity_check($project,$proofstate,!isset($editone));

if (isset($saved)) {
	$result = mysql_query("SELECT round1_user, round2_user FROM $project WHERE fileid = $fileid");
	$firstrounduser = mysql_result($result, 0, "round1_user");
	$secondrounduser = mysql_result($result, 0, "round2_user");
	if (($pguser != $firstrounduser) && ($pguser != $secondrounduser)) {
		echo "An error has occured.  Please close & relogin.";
		exit();
	}
}

$tpage=new processpage();
//$tpage->deletePageCookie();

// proof single page
if (isset($editone))
{

  // set the cookie
  $tpage->setPageCookie($project,$proofstate,$fileid,$imagefile,$pagestate,1,$editone,0,0,0,0);

  // plug user page count cheat - if they reopen a saved page, subtract it from their count
  // as it is 'unproofreading' it; they will get it back if they save it again
  // if page comes from DONE (???)

  if (($pagestate == SAVE_FIRST) ||
      ($pagestate == SAVE_SECOND))
  {
     // deleteUserCount assumes PageState has been set;
     // could rewrite to take extra variables instead (see earlier debugging versions)
     $tpage->setPageState($pagestate,$project,$fileid,$imagefile,$proofstate);
     $tpage->deleteUserCount($proofstate,$pguser,$userP);
     // new function only to be called from here
     $tpage->reOpen($proofstate,$pguser);
  }

  // load the frame
  if ($userP['i_type'] != 1)
    {include('proof_frame_nj.inc');}
  else
    {metarefresh(0,"text_frame.php","Proofreading Text Frame","Loading page....");}
  exit;
}


// see if they need a new page or not
$needPage=1;

  // 1 see if there is a cookie
    if (($use_cookies?isset($_COOKIE['userPage']):isset($_SESSION['userPage'])))
    {
      // see if the cookie is older than 3 seconds
        $npage=$tpage->getPageCookie();
        if(!($npage['pageTime'] <= (time()-3)) && $npage['project']==$project)
          {$needPage=0;}
    }

  // give them a new page
    if ($needPage==1)
      {
        $npage=$tpage->getAvailablePage($project,$proofstate,$pguser);
        // check to see if project is open
          if ($npage['isopen'] == 0)
            {
              $tpage->noPages($userP['i_newwin']);
              exit;
            } //end no pages left check
        $pagestate=$tpage->checkOutPage($project,$proofstate,$pguser,$npage['fileid'],$npage['image']);
        $tpage->setPageCookie($project,$proofstate,$npage['fileid'],$npage['image'],$pagestate,0,0,0,0,0,0);
      }

//load the frame
if ($userP['i_type'] != 1)
  {include('proof_frame_nj.inc');}
else
  {metarefresh(0,"text_frame.php","Proofreading Text Frame","Loading next available page....");}
?>
