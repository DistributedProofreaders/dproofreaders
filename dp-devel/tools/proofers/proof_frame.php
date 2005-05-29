<?PHP
$relPath="./../../pinc/";
include_once($relPath.'stages.inc');
include_once($relPath.'dp_main.inc');
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
    $err = resume_saved_page( $project, $proofstate, $fileid, $imagefile, $pagestate, $pguser );
    if ($err)
    {
        echo $err;
        exit();
    }
}
else
{
    // The user clicked "Start Proofreading".

// see if they need a new page or not
$needPage=1;

    // Consider the page (if any) that this user most recently "opened" in
    // this session, either via 'Start Proofreading' or via the 'Done' or
    // 'In Progress' trays. If the time at which that page was opened is
    // less than 3 seconds ago, and the page was from the same project as
    // the current one, we do *not* give the user a new page, but rather
    // leave the "page cookie" with its previous value, causing the proofing
    // interface to load the previous page again.

    // It's not clear why this is done. (The code has been here since rev 1.1.)
    // You might think it's meant to thwart click-through artists, but 3 seconds
    // isn't much of a delay. Probably it's some kind of request de-bouncer,
    // filtering out extraneous repeated HTTP requests for this script. I
    // suspect it's meant to detect users who click "Start Proofreading", and,
    // not seeing an immediate response, click it again a couple seconds later.

    if ( dpsession_page_is_set() )
    {
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
	      $round = get_Round_for_project_state($proofstate);
              $body = $err . "<br> " . sprintf(_("Return to the %sproject listing page%s."),
                                                 "<a href='round.php?round_id={$round->id}' target='_top'>","</a>");
              $title = _("Unable to get an available page");
              echo "<html><head><title>$title</title></head><body>$body</body></html>";
              exit;
            }
      }
}

//load the frame
if ($userP['i_type'] != 1)
  {include('proof_frame_std.inc');}
else
  {metarefresh(0,"text_frame.php",_("Proofreading Text Frame"),_("Loading page...."));}
?>
