<?PHP
$relPath="./../../pinc/";
include_once($relPath.'stages.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'LPage.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'site_vars.php');
include_once('PPage.inc');

/* $_GET from IN PROGRESS/DONE and from 'Edit' links on Images,Diffs screen
url_for_pi_do_particular_page()
$projectid, $proj_state, $imagefile, $page_state
*/

/* $_GET from "Start Proofreading" etc.
url_for_pi_do_whichever_page()
$projectid, $proj_state
*/

if (isset($page_state)) {
    // The user clicked on a saved page.

    // get_requested_PPage() expects a 'reverting' parameter.
    $_GET['reverting'] = '0';

    $ppage = get_requested_PPage($_GET);

    $ppage->lpage->resume_saved_page( $pguser );
}
else
{
    // The user clicked "Start Proofreading".


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
        $npage=getDebounceInfo();
        if(!($npage['pageTime'] <= (time()-3)) && $npage['project']==$projectid)
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
    $lpage = get_available_page( $projectid, $proj_state, $pguser, $err );
    if (is_null($lpage))
    {
        $round = get_Round_for_project_state($proj_state);
        $body = $err . "<br> " . sprintf(_("Return to the %sproject listing page%s."),
            "<a href='round.php?round_id={$round->id}' target='_top'>","</a>");
        $title = _("Unable to get an available page");
        echo "<html><head><title>$title</title></head><body>$body</body></html>";
        exit;
    }

    setDebounceInfo( $lpage->projectid );

    $ppage = new PPage( $lpage, $proj_state );
}

include('proof_frame.inc');

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function setDebounceInfo($project)
{
    dpsession_page_set( $project . '|' . time() );
}

function getDebounceInfo()
{
    list($project,$time) = explode( "|", dpsession_page_get() );
    return array( 'project' => $project, 'pageTime' => $time );
}

// vim: sw=4 ts=4 expandtab
?>
