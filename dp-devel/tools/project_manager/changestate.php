<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'project_trans.inc');
include_once($relPath.'project_edit.inc');
include_once($relPath.'maybe_mail.inc');

function is_a_page_editing_transition_that_doesnt_need_a_warning( $oldstate, $newstate )
{
    $round_old = get_Round_for_project_state($oldstate);
    $round_new = get_Round_for_project_state($newstate);

    if ( is_null($round_old) || is_null($round_new) )
    {
	// Transition to or from a non-round state.
       	return FALSE;
    }
   
    if ( $round_old != $round_new )
    {
	// Transition between different rounds.
	// (Normally, this page doesn't see such transitions.)
	return FALSE;
    }

    // States belong to same round.
    $round = $round_old;

    if (
	$newstate == $round->project_unavailable_state ||
	$oldstate == $round->project_unavailable_state ||
	$oldstate == $round->project_waiting_state
    )
    {
	return $round;
    }
    else
    {
	return FALSE;
    }
}

    // Get Passed parameters to code
    $projectid = $_GET['project'];
    $newstate = $_GET['state'];
    $always = $_GET['always'];

    // Get more information about the project
    $sql = mysql_query("SELECT * FROM projects WHERE projectid = '$projectid'");
    $project = mysql_fetch_assoc($sql);
    $oldstate = $project['state'];
    $nameofwork = $project['nameofwork'];
    $author = $project['authorsname'];

    abort_if_cant_edit_project( $projectid );

    $do_transition = FALSE;

    $extras = array();

    if ($newstate == 'automodify')
    {
        metarefresh(0, "automodify.php?project=$projectid", "?", "");
        // which will leave the project in the appropriate
	// BAD, AVAILABLE, or COMPLETE state.
    }
    else if ($newstate == PROJ_DELETE && $always != 'yes')
    {
	// Give them a warning before deleting a project, explaining why it should not be done.
	echo "<P><B>NOTE:</B> You no longer delete a project from the site, you move it to the posted to Project Gutenberg status. Deleting is only for a project that is beyond repair.";
	print "<P>Are you sure you want to change this state and delete $nameofwork by $author ($projectid)?<br><br>If so, click <A HREF=\"changestate.php?project=$projectid&state=$newstate&always=yes\">here</a>, otherwise back to <a href=\"projectmgr.php\">project listings</a>.";
    }
    else if ($newstate == PROJ_SUBMIT_PG_POSTED)
    {
	$do_transition = TRUE;
        $refresh_url = "editproject.php?project=$projectid&posted=1";
    }
    else if (
	   ($newstate == PROJ_DELETE && $always == 'yes')
	|| ($newstate == PROJ_POST_FIRST_CHECKED_OUT)
	|| ($always == 'yes')
	|| ($oldstate == PROJ_POST_FIRST_CHECKED_OUT)
	|| ($oldstate == PROJ_NEW)
    )
    {
	$do_transition = TRUE;
        $refresh_url = "projectmgr.php";

	if ( $newstate == PROJ_POST_FIRST_CHECKED_OUT ||
	     $newstate == PROJ_POST_SECOND_CHECKED_OUT )
	{
	    $extras = array( 'checkedoutby' => $pguser );
	}
    }
    else if (
	// assignment-in-condition
	$round = is_a_page_editing_transition_that_doesnt_need_a_warning( $oldstate, $newstate )
    )
    {
	$do_transition = TRUE;
	$refresh_url = "projectmgr.php";

	if ( $oldstate == $round->project_waiting_state &&
	     $newstate == $round->project_available_state )
	{
	    $errors = project_pre_release_check( $project );
	    if ($errors)
	    {
		echo "<pre>\n";
		echo "The pre-release check found the following problems:\n";
		echo $errors;
		echo "\n";
		echo "The project has been marked bad.\n";
		echo "Please fix the problems and resubmit.\n";
		echo "</pre>\n";
		$newstate = $round->project_bad_state;
		$refresh_url = '';
	    }
	    else if ( ! user_is_a_sitemanager() && ! user_is_proj_facilitator() )
	    {
		echo "<p>";
		echo "This option has been disabled -- ";
		echo "project managers can no longer manually force the release of their projects.";
		echo "</p>\n";
		echo "<p>";
		echo "Please contact a site admin or project facilitator if you think this project should be released.";
		echo "</p>\n";
		echo "<p>Back to <a href=\"projectmgr.php\">project manager</a> page.</p>";
		$do_transition = FALSE;
	    }
            else
            {
                maybe_mail_project_manager( $project,
             	   "This project has been manually released by $pguser and has just become available in '{$round->name}'.",
                   "DP Proofreading Started (Manual Release)");
            }
	}
    }
    else
    {
        // This option should never appear if they follow the options on the page, only for those that know what they are doing...
        echo "<P><B>NOTE:</B> The choice you made is one that should not be chosen quickly. If you do not know what it will do, it is best to keep things the way they are.";
        print "<P>Are you sure you want to change this state? If so, click <A HREF=\"changestate.php?project=$projectid&state=$newstate&always=yes\">here</a>, otherwise back to <a href=\"projectmgr.php\">project listings</a>.";
    }

    // -------------------------------------------------------------------------

    if ( $do_transition )
    {
	$error_msg = project_transition( $projectid, $newstate, $extras );
	if ( $error_msg )
	{
	    echo "<p>$error_msg <p>Back to <a href=\"projectmgr.php\">project manager</a> page.";
	    die();
	}
	else if ($refresh_url)
	{
	    metarefresh( 0, $refresh_url, "Project Transition Succeeded", "" );
	}
    }
?>
