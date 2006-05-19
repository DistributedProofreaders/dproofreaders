<?
$relPath="../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'project_trans.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'ProjectTransition.inc');
include_once($relPath.'maybe_mail.inc');

// Get Passed parameters to code
$projectid  = $_GET['projectid'];
$curr_state = $_GET['curr_state'];
$next_state = $_GET['next_state'];
$confirmed = @$_GET['confirmed'];

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

header("Content-Type: text/plain; charset=$charset");

$project = new Project($projectid);

if ( $project->state != $curr_state )
{
    fatal_error( "Your request appears to be out-of-date.\nThe project's current state is now '$project->state'." );
}

$transition = get_transition( $curr_state, $next_state );
if ( is_null($transition) )
{
    fatal_error( "This transition is not recognized." );
}

if ( !$transition->is_valid_for( $project, $pguser ) )
{
    fatal_error( "You are not permitted to perform this action." );
}

function fatal_error( $msg )
{
    global $projectid, $project, $curr_state, $next_state;

    echo "You requested:\n";
    echo "    projectid  = $projectid ($project->nameofwork)\n";
    echo "    curr_state = $curr_state\n";
    echo "    next_state = $next_state\n";
    echo "\n";
    echo "$msg\n";
    exit;
}

// -----------------------------------------------------------------------------

header("Content-Type: text/html; charset=$charset");

// If there's a question associated with this transition,
// and we haven't just asked it, ask it now.
if ( !is_null($transition->confirmation_question) && $confirmed != 'yes' )
{
    echo $transition->confirmation_question;
    echo "<br><br>";
    echo "If so, click <A HREF=\"changestate.php?projectid=$projectid&curr_state=$curr_state&next_state=$next_state&confirmed=yes\">here</a>, otherwise back to <a href=\"projectmgr.php\">project listings</a>.";
    exit();
}

// At this point, we know that either there's no question associated
// with the transition, or there is and it has been answered yes.

if ( $transition->action_type == 'transit_and_redirect' )
{
    $extras = array();
    if ( $transition->checkedoutby_to_transit )
    {
        $extras['checkedoutby'] = $pguser;
    }

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
            $bad_state = $round->project_bad_state;
            $error_msg = project_transition( $projectid, $bad_state, $extras );
            if ($error_msg)
            {
                echo "<p>$error_msg <p>Back to <a href=\"projectmgr.php\">project manager</a> page.";
            }
            exit;
        }
        else
        {
            maybe_mail_project_manager( get_object_vars($project),
               "This project has been manually released by $pguser and has just become available in '{$round->name}'.",
               "DP Proofreading Started (Manual Release)");
        }
    }

    // -------------------------------------------------------------------------

    $error_msg = project_transition( $projectid, $next_state, $extras );

    if ($error_msg == '')
    {
        $title = "Action Successful";
        $body = "Your request ('$transition->action_name') was successful.";
    }
    else
    {
        $title = "Action Unsuccessful";
        $body = "$error_msg<br><br>\n"
            . "Something went wrong, and your request ('$transition->action_name') has probably not been carried out.";
    }
}
elseif ( $transition->action_type == 'redirect' )
{
    $title = "Transferring...";
    $body = "";
}
else
{
    die("bad transition->action_type: '$transition->action_type'");
}

if ( $transition->destination == '<RETURN>' )
{
    // Return the user to the screen they were at
    // when they requested the state change.

    // We can't assume that HTTP_REFERER is set.
    $referer = @$_SERVER['HTTP_REFERER'];
    if ( empty($referer) )
    {
        // Punt to the project home.
        $refresh_url = "$code_url/project.php?id=$projectid";
    }
    else
    {
        // This is somewhat kludgey:
        // If the referer URL included a statement of an expected state,
        // and we refresh to that URL, the user will get a warning
        // that the project is no longer in that expected state.
        // So we delete any such statement.
        $refresh_url = preg_replace('/expected_state=\w+/', '', $referer );
    }
}
else
{
    $refresh_url = str_replace( '<PROJECTID>', $projectid, $transition->destination );
}

metarefresh(2, $refresh_url, $title, $body);

// vim: sw=4 ts=4 expandtab
?>
