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

header("Content-Type: text/html; charset=$charset");

// Get Passed parameters to code
$projectid  = $_POST['projectid'];
$curr_state = $_POST['curr_state'];
$next_state = $_POST['next_state'];
$confirmed = @$_POST['confirmed'];
$return_uri = $_POST['return_uri'];

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

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

    echo "<pre>\n";
    echo "You requested:\n";
    echo "    projectid  = $projectid ($project->nameofwork)\n";
    echo "    curr_state = $curr_state\n";
    echo "    next_state = $next_state\n";
    echo "\n";
    echo "$msg\n";
    echo "</pre>\n";
    exit;
}

// -----------------------------------------------------------------------------

// If there's a question associated with this transition,
// and we haven't just asked it, ask it now.
if ( !is_null($transition->confirmation_question) && $confirmed != 'yes' )
{
    echo $transition->confirmation_question;
    echo <<<EOS
        <br>
        <form action='changestate.php' method='POST'>
        <input type='hidden' name='projectid'  value='$projectid'>
        <input type='hidden' name='curr_state' value='$curr_state'>
        <input type='hidden' name='next_state' value='$next_state'>
        <input type='hidden' name='confirmed'  value='yes'>
        <input type='hidden' name='return_uri' value='$return_uri'>
        If so, click
        <input type='submit' value='here'>,
        otherwise go back to <a href='$return_uri'>where you were</a>.
        </form>
EOS;
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

    // -------------------------------------------------------------------------

    $error_msg = project_transition( $projectid, $next_state, $extras );

    if ($error_msg == '')
    {
        $round = get_Round_for_project_state($next_state);
        if ( !is_null($round) &&
             $curr_state == $round->project_waiting_state &&
             $next_state == $round->project_available_state )
        {
            maybe_mail_project_manager( get_object_vars($project),
               "This project has been manually released by $pguser and has just become available in '{$round->name}'.",
               "DP Proofreading Started (Manual Release)");
        }

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
    $refresh_url = $return_uri;
}
else
{
    $refresh_url = str_replace( '<PROJECTID>', $projectid, $transition->destination );

    // Pass $return_uri on to the next page, in hopes it can use it.
    $connector = ( strpos($refresh_url,'?') === FALSE ? '?' : '&' );
    $encoded_return_uri = urlencode($return_uri);
    $refresh_url .= "{$connector}return_uri=$encoded_return_uri";
}

metarefresh(2, $refresh_url, $title, $body);

// vim: sw=4 ts=4 expandtab
?>
