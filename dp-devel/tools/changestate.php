<?
$relPath="../pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'project_states.inc');
include_once($relPath.'project_trans.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'ProjectTransition.inc');

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

if ( !empty($transition->detour) )
{
    // Detour (to collect data).
    $title = "Transferring...";
    $body = "";
    $refresh_url = prepare_url( $transition->detour );
    metarefresh(2, $refresh_url, $title, $body);
    exit;
}

// There's no detour, so we can proceed with the actual state-transition.

{
    $extras = array();

    // -------------------------------------------------------------------------

    $error_msg = $transition->do_state_change( $project, $pguser, $extras );

    if ($error_msg == '')
    {
        $title = "Action Successful";
        $body = "Your request ('$transition->action_name') was successful.";
    }
    else
    {
        fatal_error(
            sprintf(
                _("Something went wrong, and your request ('%s') has probably not been carried out."), 
                $transition->action_name
            )
            . "\n"
            . _("Here is the error message:")
            . "\n"
            . $error_msg
        );
    }
}

    // Return the user to the screen they were at
    // when they requested the state change.
    $refresh_url = $return_uri;

metarefresh(2, $refresh_url, $title, $body);

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function prepare_url( $url_template )
{
    global $projectid, $return_uri;

    $url = str_replace( '<PROJECTID>', $projectid, $url_template );

    // Pass $return_uri on to the next page, in hopes it can use it.
    $connector = ( strpos($url,'?') === FALSE ? '?' : '&' );
    $encoded_return_uri = urlencode($return_uri);
    $url .= "{$connector}return_uri=$encoded_return_uri";

    return $url;
}

// vim: sw=4 ts=4 expandtab
?>
