<?PHP
$relPath="./../../pinc/";
include_once($relPath.'stages.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'c_pages.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'project_continuity.inc');
include_once($relPath.'v_site.inc');

/* $_GET from IN PROGRESS/DONE
$project, $proofstate, $fileid, $imagefile, $pagestate
*/

/* $_GET from "Start Proofreading" etc.
$project, $proofstate
*/

if (isset($pagestate)) {
    // The user clicked on a saved page.

    // Make sure project is still in same state.
    project_continuity_check($project,$proofstate,FALSE);

    $err = resume_saved_page( $project, $proofstate, $imagefile, $pagestate, $pguser );
    if ($err)
    {
        echo $err;
        exit();
    }
}
else
{
    // The user clicked "Start Proofreading".

    // Make sure project is still in same state.
    project_continuity_check($project,$proofstate,TRUE);

// see if they need a new page or not
$needPage=1;

    // Consider the page (if any) that this user most recently "opened" in
    // this session, either via 'Start Proofreading' or via the 'Done' or
    // 'In Progress' trays. If the time at which that page was opened is
    // less than 3 seconds ago, and the page was from the same project as
    // the current one, we do *not* give the user a new page, but rather
    // return an error page.

    // It's not clear why this is done. (The code has been here since rev 1.1.)
    // You might think it's meant to thwart click-through artists, but 3 seconds
    // isn't much of a delay. Probably it's some kind of request de-bouncer,
    // filtering out extraneous repeated HTTP requests for this script.
    //
    // At first, I thought it was meant to detect users who click "Start
    // Proofreading", and, not seeing an immediate response, click it again
    // a couple seconds later. And maybe it *was* meant to do that, and maybe
    // it actually catches those cases. (Though we were unable to recreate
    // such an occurrence.)
    //
    // But it seems there's a worse problem (which this debouncer code was
    // perhaps meant to correct), in which a single user action sometimes
    // results in two nearly-identical nearly-simultaneous requests being sent
    // to the server. The requests are for the same resource, and arrive within
    // a second of each other. The second usually (perhaps always) has "-" as
    // its referrer. So far, I've seen GET+GET pairs, and also POST+GET pairs.
    // In the two most recent cases exhibiting this behaviour, both users were
    // using Firefox 1.0.4 on Windows (XP2 and 2000). However, this isn't a
    // sufficient condition, as others with the same setup did not have the same
    // problem.  Our best guess at this point is that it's an interaction
    // between Firefox and a caching proxy.

    if ( dpsession_page_is_set() )
    {
        $npage=getPageCookie();
        if(!($npage['pageTime'] <= (time()-3)) && $npage['project']==$project)
          {
                // It probably doesn't matter what we say here.
                // 1) Indications are that users will never see this.
                // 2) The important thing is that we neither assign the user a
                //    new page, nor send a proofing interface.
                header('HTTP/1.1 409 Conflict');
                echo "<p>";
                echo "We received two near-simultaneous requests from you\n";
                echo "for the same resource, so we are ignoring the second one\n";
                echo "(other than to send this error message).\n";
                echo "Please inform a Site Admin that you received this message.\n";
                echo "</p>";
                exit();
          }
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

include('proof_frame.inc');
?>
