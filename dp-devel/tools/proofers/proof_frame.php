<?PHP
$relPath="./../../pinc/";
include($relPath.'dp_main.inc');
include_once($relPath.'c_pages.inc');
include_once($relPath.'metarefresh.inc');

if (isset($saved)) {
	$result = mysql_query("SELECT round1_user, round2_user FROM $project WHERE fileid = $fileid");
	$firstrounduser = mysql_result($result, 0, "round1_user");
	$secondrounduser = mysql_result($result, 0, "round2_user");
	if (($pguser != $firstrounduser) && ($pguser != $secondrounduser)) {
		echo "An error has occured.  Please close & relogin.";
		exit();
	}
}

/* $_GET from recently done
$project, $fileid, $imagefile, $proofstate, $pagestate, $editone
*/

/* $_GET from start proofing
$project, $proofstate
*/

$tpage=new processpage();
//$tpage->deletePageCookie();

// proof single page
if (isset($editone))
{

  // set the cookie
    $tpage->setPageCookie($project,$proofstate,$fileid,$imagefile,$pagestate,1,$editone,0,0,0,0);


  // plug user page count cheat - if they reopen a saved page, subtract it from their count
  // as it is 'unproofing' it; they will get it back if they save it again

  // if page comes from My Recently Completed (???)
  if (($pagestate == SAVE_FIRST) || 
      ($pagestate == SAVE_SECOND))
  {
     $tpage->deleteUserCount($proofstate, $pguser,$userP);
  }

  // load the frame
  if ($userP['i_type'] != 1)
    {include('proof_frame_nj.inc');}
  else
    {metarefresh(0,"text_frame.php","Proofing Text Frame","Loading page....");}
  exit;
}


//Make sure project is still available
  $sql = "SELECT state FROM projects WHERE projectid = '$project' LIMIT 1";
  $result = mysql_query($sql);
  $state = mysql_result($result, 0, "state");
    if ((($proofstate == PROJ_PROOF_FIRST_AVAILABLE)  && ($state != PROJ_PROOF_FIRST_AVAILABLE)) ||
        (($proofstate == PROJ_PROOF_SECOND_AVAILABLE) && ($state != PROJ_PROOF_SECOND_AVAILABLE)))
    {
      $tpage->noPages($userP['i_newwin']);
      exit;
    } // end project open check

// see if they need a new page or not
$needPage=1;

  // 1 see if there is a cookie
    if (isset($_COOKIE['userPage']))
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
  {metarefresh(0,"text_frame.php","Proofing Text Frame","Loading next available page....");}
?>
