<?
$relPath="../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'project_trans.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'ProjectTransition.inc');

// Get Passed parameters to code
$projectid  = $_GET['projectid'];
$curr_state = $_GET['curr_state'];
$next_state = $_GET['next_state'];

/*
Compare this file to tools/project_manager/changestate.php.
Both handle requests to change the state of a project.
The one in project_manager handles requests that can only be
done by the project's project manager, or by a site admin.
This one handles requests that can be done by people other than the PM.
They should maybe be merged.
*/

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

if ( $transition->action_type == 'transit_and_redirect' )
{
    $extras = array();
    if ( $transition->checkedoutby_to_transit )
    {
        $extras['checkedoutby'] = $pguser;
    }

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

$refresh_url = str_replace( '<PROJECTID>', $projectid, $transition->destination );

metarefresh(2, $refresh_url, $title, $body);

// vim: sw=4 ts=4 expandtab
?>
