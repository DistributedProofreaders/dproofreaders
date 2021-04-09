<?php
include_once($relPath.'site_vars.php');
include_once($relPath.'Project.inc');
include_once($relPath.'ProjectTransition.inc');

function project_transition( $projectid, $new_state, $who, $extras = array() )
// Move the given project to the given state,
// and perform any attendant processing.
// If there are any problems, return a string containing an error message.
// Otherwise, return an empty string.
//
// This function produces no output except for debugging messages.
{
    global $testing;

    // error_reporting(E_ALL);

    $project = new Project($projectid);

    $current_state = $project->state;

    $transition_str = "$projectid: $current_state -> $new_state";
    if ($testing)
    {
        $indent = '    ';
        if (FALSE)
        {
            // The trace output will be going into a <pre> element,
            // or to a plain-text document.
            $eol = "\n";
        }
        else
        {
            // The trace output will be going to an HTML doc,
            // but not into a <pre> element.
            $eol = "<br>\n";
        }

        // echo "{$indent}$transition_str{$eol}";
    }

    // -------------------------------------------------------------------------

    if ( $new_state == $current_state )
    {
        // The transition succeeds trivially.
        // No attendant processing.
        if ($testing)
        {
            $sentence = _("Transition succeeded trivially - states are the same.");
            echo "$indent$transition_str: $sentence$eol";
        }
        return '';
    }

    // -------------------------------------------------------------------------

    $transition = get_transition( $current_state, $new_state );
    if ( is_null($transition) )
    {
        // Requested transition is illegal.
        $sentence = _("Transition is illegal.");
        $error = "$transition_str: $sentence";
        return $error;
    }

    // -------------------------------------------------------------------------

    $error = $transition->do_state_change( $project, $who, $extras );
    if ( $error == '' )
    {
        // success
        if ($testing)
        {
            $sentence = _("Transition succeeded.");
            echo "$indent$transition_str: $sentence$eol";
        }
    }
    else
    {
        // failure
        // Prepend $transition_str to the error message.
        $error = "$transition_str: $error";
    }
    return $error;
}
