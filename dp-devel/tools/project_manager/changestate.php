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

    // Get Passed parameters to code
    $projectid = $_GET['projectid'];
    $next_state = $_GET['next_state'];
    $confirmed = @$_GET['confirmed'];

    $project = new Project( $projectid );

    function fatal_error( $msg )
    {
        global $project, $next_state;

        echo "<pre>\n";
        echo "You requested:\n";
        echo "    projectid  = $project->projectid ($project->nameofwork)\n";
        echo "    curr_state = $project->state\n";
        echo "    next_state = $next_state\n";
        echo "\n";
        echo "$msg\n";
        echo "\n";
        echo "Back to <a href=\"projectmgr.php\">project manager</a> page.\n";
        echo "</pre>\n";
        exit;
    }

    $transition = get_transition( $project->state, $next_state );
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
    if ( !is_null($transition->confirmation_question) && $confirmed != 'yes' )
    {
        echo $transition->confirmation_question;
        echo "<br><br>";
        echo "If so, click <A HREF=\"changestate.php?projectid=$projectid&next_state=$next_state&confirmed=yes\">here</a>, otherwise back to <a href=\"projectmgr.php\">project listings</a>.";
        exit();
    }

    // At this point, we know that either there's no question associated
    // with the transition, or there is and it has been answered yes.

    if ( $transition->destination == '<RETURN>' )
    {
        $refresh_url = "projectmgr.php";
    }
    else
    {
        $refresh_url = str_replace( '<PROJECTID>', $project->projectid, $transition->destination );
    }

    if ( $transition->action_type == 'redirect' )
    {
        metarefresh( 0, $refresh_url, "Project Transition Succeeded", "" );
        exit;
    }

    $curr_state = $project->state;

        $round = get_Round_for_project_state($next_state);
        if ( !is_null($round) &&
             $curr_state == $round->project_waiting_state &&
             $next_state == $round->project_available_state )
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
		$next_state = $round->project_bad_state;
		$refresh_url = '';
	    }
            else
            {
                maybe_mail_project_manager( get_object_vars($project),
             	   "This project has been manually released by $pguser and has just become available in '{$round->name}'.",
                   "DP Proofreading Started (Manual Release)");
            }
	}

    // -------------------------------------------------------------------------

    {
        $extras = array();
        if ( $transition->checkedoutby_to_transit )
        {
            $extras['checkedoutby'] = $pguser;
        }

	$error_msg = project_transition( $projectid, $next_state, $extras );
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
