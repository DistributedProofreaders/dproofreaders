<?PHP
$relPath="./../../pinc/";
include_once($relPath.'RoundDescriptor.inc');
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
        $err = get_available_page( $project, $proofstate, $pguser );
          if ($err)
            {
	      $prd = get_PRD_for_project_state($proofstate);
              $body = $err . "<br> " . _("You will be taken back to the project listing page in 4 seconds.");
              $title = _("Unable to get an available page");
              metarefresh(4, "round.php?round_id={$prd->round_id}", $title, $body);
              exit;
            }
      }
}

//load the frame
if ($userP['i_type'] != 1)
  {include('proof_frame_nj.inc');}
else
  {metarefresh(0,"text_frame.php",_("Proofreading Text Frame"),_("Loading page...."));}
?>
