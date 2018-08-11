<?php
$relPath="../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'project_trans.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'ProjectTransition.inc');
include_once($relPath.'misc.inc'); // get_enumerated_param()

require_login();

header("Content-Type: text/html; charset=$charset");

// Get Passed parameters to code
$projectid  = validate_projectID('projectid', @$_POST['projectid']);
$curr_state = get_enumerated_param($_POST, 'curr_state', null, $PROJECT_STATES_IN_ORDER);
$next_state = get_enumerated_param($_POST, 'next_state', null, array_merge($PROJECT_STATES_IN_ORDER, array('automodify')));
$confirmed  = get_enumerated_param($_POST, 'confirmed', null, array('yes'), true);
$return_uri = @$_POST['return_uri'];

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

$project = new Project($projectid);

if ( $project->state != $curr_state )
{
    fatal_error( sprintf(_("Your request appears to be out-of-date. The project's current state is now '%s'."), $project->state));
}

$transition = get_transition( $curr_state, $next_state );
if ( is_null($transition) )
{
    fatal_error( _("This transition is not recognized.") );
}

if ( !$transition->is_valid_for( $project, $pguser ) )
{
    fatal_error( _("You are not permitted to perform this action.") );
}

if ($transition->why_disabled($project) == 'SR')
{
    $body = '<p>' . _("This function is disabled while the project is in the Smooth Reading Pool.")  . '</p>' .
            '<p>' . _("If you believe this is an error, please contact db-req for assistance.")      . '</p>';

    fatal_error($body);
}

function fatal_error( $msg )
{
    global $projectid, $project, $curr_state, $next_state;

    echo "<pre>\n";
    echo _("You requested:") . "\n";
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
    echo "<p><b>" . _("Project ID") . ":</b> $projectid<br>\n";
    echo "<b>" . _("Title") . ":</b> {$project->nameofwork}<br>\n";
    echo "<b>" . _("Author") . ":</b> {$project->authorsname}</p>\n";
    echo $transition->confirmation_question;
    echo "<br>
        <form action='changestate.php' method='POST'>
        <input type='hidden' name='projectid'  value='$projectid'>
        <input type='hidden' name='curr_state' value='$curr_state'>
        <input type='hidden' name='next_state' value='$next_state'>
        <input type='hidden' name='confirmed'  value='yes'>
        <input type='hidden' name='return_uri' value='$return_uri'>"
        // TRANSLATORS: %1$s is a button labeled "confirm transition change".
        . sprintf(_("If so, %1\$s, otherwise go back to <a href='%2\$s'>where you were</a>"),
            "<input type='submit' value='" . attr_safe(_("confirm transition change")) . "'>",
            $return_uri)
        . "</form>";
    exit();
}

// At this point, we know that either there's no question associated
// with the transition, or there is and it has been answered yes.

if ( !empty($transition->detour) )
{
    // Detour (to collect data).
    $title = _("Transferring...");
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
        $title = _("Action Successful");
        $body = sprintf( _("Your request ('%s') was successful."), $transition->action_name);
    }
    else
    {
        fatal_error(
            sprintf(
                _("Something went wrong, and your request ('%s') has probably not been carried out."), 
                $transition->action_name
            )
            . "\n"
            . _("Error") . ":"
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
