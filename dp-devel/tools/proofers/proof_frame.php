<?PHP
$relPath="./../../pinc/";
include($relPath.'dp_main.inc');
include_once($relPath.'c_pages.inc');
include_once($relPath.'metarefresh.inc');

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
  // load the frame
  if ($userP['i_type'] != 1)
    {include($relPath.'proof_frame_nj.inc');}
  else
    {metarefresh(0,"text_frame.php","Proofing Text Frame","Loading next available page....");}
  exit;
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
  {include($relPath.'proof_frame_nj.inc');}
else
  {metarefresh(0,"text_frame.php","Proofing Text Frame","Loading next available page....");}
?>