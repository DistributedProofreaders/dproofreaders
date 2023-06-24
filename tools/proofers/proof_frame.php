<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'LPage.inc');
include_once($relPath.'abort.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'Project.inc'); // get_projectID_param()
include_once($relPath.'slim_header.inc');
include_once($relPath.'links.inc');
include_once('PPage.inc');
include_once('proof_frame.inc');

require_login();

/* $_GET from IN PROGRESS/DONE and from 'Edit' links on Images,Diffs screen
url_for_pi_do_particular_page()
$projectid, $proj_state, $imagefile, $page_state
*/

/* $_GET from "Start Proofreading" etc.
url_for_pi_do_whichever_page()
$projectid, $proj_state
*/

if (isset($_GET['page_state'])) {
    // The user clicked on a saved page.

    // get_requested_PPage() expects a 'reverting' parameter.
    $_GET['reverting'] = '0';

    try {
        $ppage = get_requested_PPage($_GET);
        $ppage->lpage->resume_saved_page($pguser);
    } catch (ProjectException | ProjectPageException $exception) {
        abort($exception->getMessage());
    }
} else {
    // The user clicked "Start Proofreading" or "Save as 'Done' & Proofread Next Page".

    $projectid = get_projectID_param($_REQUEST, 'projectid');
    $proj_state = $_REQUEST['proj_state'];

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

    if (dpsession_page_is_set()) {
        $npage = getDebounceInfo();
        if (!($npage['pageTime'] <= (time() - 3)) && $npage['project'] == $projectid) {
            // It probably doesn't matter what we say here.
            // 1) Indications are that users will never see this.
            // 2) The important thing is that we neither assign the user a
            //    new page, nor send a proofing interface.
            header('HTTP/1.1 409 Conflict');
            $message = _("We received two near-simultaneous requests from you for the same resource, so we are ignoring the second one (other than to send this error message).");
            abort($message);
        }
    }

    // give them a new page
    try {
        $lpage = get_available_page($projectid, $proj_state, $pguser, $err);
    } catch (ProjectPageException $exception) {
        abort($exception->getMessage());
    }
    if (is_null($lpage)) {
        $round = get_Round_for_project_state($proj_state);

        // If the user can manage the project, they'll most likely want to look at the
        // project state once the last page has been done. This is especially true of,
        // for example, missing page projects and the like. Redirect these users to
        // the project page.
        $project = new Project($projectid);
        if ($project->can_be_managed_by_user($pguser)) {
            $body = $err . "<br> " . return_to_project_page_link($projectid);
        } else {
            $body = $err . "<br> " . sprintf(_("Return to the <a %s>project listing page</a>."),
                "href='round.php?round_id={$round->id}' target='_top'");
        }
        $title = _("Unable to get an available page");
        slim_header($title);
        echo $body;
        exit;
    }

    setDebounceInfo($lpage->projectid);

    $url = "$code_url/tools/proofers/proof_frame.php?projectid=$lpage->projectid&imagefile=$lpage->imagefile&proj_state=$proj_state&page_state=$lpage->page_state";
    metarefresh(0, $url);
}

echo_proof_frame($ppage);

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function setDebounceInfo($project)
{
    dpsession_page_set($project . '|' . time());
}

function getDebounceInfo()
{
    [$project, $time] = explode("|", dpsession_page_get());
    return ['project' => $project, 'pageTime' => $time];
}
