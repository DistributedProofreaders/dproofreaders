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
    $err = resume_saved_page( $project, $proofstate, $fileid, $imagefile, $pagestate, $pguser, $userP );
    if ($err)
    {
        echo $err;
        exit();
    }
}
else
{

// see if they need a new page or not
$needPage=1;

  // 1 see if there is a cookie
    if ( dpsession_page_is_set() )
    {
      // see if the cookie is older than 3 seconds
        $npage=getPageCookie();
        if(!($npage['pageTime'] <= (time()-3)) && $npage['project']==$project)
          {$needPage=0;}
    }

  // give them a new page
    if ($needPage==1)
      {
        $npage = getAvailablePage($project,$proofstate,$pguser);
        // check to see if the user has exceeded the R1 BEGIN quota for this project
	    if ($npage['TooManyBegin'] == 1) 
            {
              $body = _("You have already proofread your allowed quota of pages from this Beginners Only project. Perhaps you could try proofreading an EASY project.<br> You will be taken back to the project listing page in 4 seconds.");
              $title = _("Beginners Only quota reached for this Project");
              metarefresh(4,"list_avail.php",$title,$body);
              exit;
            } //end R1 BEGIN quota check
        // check to see if project is open
          if ($npage['isopen'] == 0)
            {
              $body = _("No more files available for proofreading for this round of the project.<br> You will be taken back to the project listing page in 4 seconds.");
              $title = _("Project Round Complete");
              metarefresh(4,"list_avail.php",$title,$body);
              exit;
            } //end no pages left check
        $tpage = new processpage();
        $pagestate=$tpage->checkOutPage($project,$proofstate,$pguser,$npage['fileid'],$npage['image']);
        setPageCookie($project,$proofstate,$npage['fileid'],$npage['image'],$pagestate,0,0,0,0,0,0);
      }
}

//load the frame
if ($userP['i_type'] != 1)
  {include('proof_frame_nj.inc');}
else
  {metarefresh(0,"text_frame.php",_("Proofreading Text Frame"),_("Loading page...."));}
?>
