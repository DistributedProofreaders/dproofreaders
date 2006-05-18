<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'ProjectTransition.inc');
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
    $always = @$_GET['always'];

    $project = new Project( $projectid );

    function fatal_error( $msg )
    {
        global $project, $newstate;

        echo "<pre>\n";
        echo "You requested:\n";
        echo "    projectid  = $project->projectid ($project->nameofwork)\n";
        echo "    curr_state = $project->state\n";
        echo "    next_state = $newstate\n";
        echo "\n";
        echo "$msg\n";
        echo "\n";
        echo "Back to <a href=\"projectmgr.php\">project manager</a> page.\n";
        echo "</pre>\n";
        exit;
    }

    $transition = get_transition( $project->state, $newstate );
    if ( is_null($transition) )
    {
        fatal_error( "This transition is not recognized." );
    }
    if ( !$transition->is_valid_for( $project, $pguser ) )
    {
        fatal_error( "You are not permitted to perform this action." );
    }

    // If there's a question associated with this transition,
    // and we haven't just asked it, ask it now.
    if ( !is_null($transition->confirmation_question) && $always != 'yes' )
    {
        echo $transition->confirmation_question;
        echo "<br><br>";
        echo "If so, click <A HREF=\"changestate.php?project=$projectid&state=$newstate&always=yes\">here</a>, otherwise back to <a href=\"projectmgr.php\">project listings</a>.";
        exit();
    }

    // At this point, we know that either there's no question associated
    // with the transition, or there is and it has been answered yes.

    function redirect()
    {
        global $project, $transition;
        $refresh_url = str_replace( '<PROJECTID>', $project->projectid, $transition->destination );
        metarefresh( 0, $refresh_url, "Project Transition Succeeded", "" );
    }

    if ( $transition->action_type == 'redirect' )
    {
        redirect();
        exit;
    }

    $oldstate = $project->state;

    $extras = array();

    if ($newstate == PROJ_SUBMIT_PG_POSTED)
    {
        $refresh_url = "editproject.php?action=edit&project=$projectid&posted=1";
    }
    else if (
	   ($newstate == PROJ_DELETE && $always == 'yes')
	|| ($newstate == PROJ_POST_FIRST_CHECKED_OUT)
	|| ($always == 'yes')
	|| ($oldstate == PROJ_POST_FIRST_CHECKED_OUT)
	|| ($oldstate == PROJ_NEW)
    )
    {
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
	$refresh_url = "projectmgr.php";

	if ( $oldstate == $round->project_waiting_state &&
	     $newstate == $round->project_available_state )
	{
	    $errors = project_pre_release_check( get_object_vars($project), $round );
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
            else
            {
                maybe_mail_project_manager( get_object_vars($project),
             	   "This project has been manually released by $pguser and has just become available in '{$round->name}'.",
                   "DP Proofreading Started (Manual Release)");
            }
	}
    }
    else
    {
        assert( FALSE );
    }

    // -------------------------------------------------------------------------

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
