<?php
include_once($relPath.'Project.inc');
include_once($relPath.'ProjectTransition.inc');

/**
 * Move the given project to the given state, and perform any attendant processing.
 *
 * If there are any problems, return a string containing an error message.
 * Otherwise, return an empty string.
 *
 * This function produces no output except for debugging messages.
 */
function project_transition(string $projectid, string $new_state, string $who, array $extras = [])
{
    $project = new Project($projectid);

    $current_state = $project->state;

    $transition_str = "$projectid: $current_state -> $new_state";
    if (SiteConfig::get()->testing) {
        $indent = '    ';
        // The trace output will be going to an HTML doc,
        // but not into a <pre> element.
        $eol = "<br>\n";
    }

    // -------------------------------------------------------------------------

    if ($new_state == $current_state) {
        // The transition succeeds trivially.
        // No attendant processing.
        if (SiteConfig::get()->testing) {
            $sentence = _("Transition succeeded trivially - states are the same.");
            echo "$indent$transition_str: $sentence$eol";
        }
        return '';
    }

    // -------------------------------------------------------------------------

    $transition = get_transition($current_state, $new_state);
    if (is_null($transition)) {
        // Requested transition is illegal.
        $sentence = _("Transition is illegal.");
        $error = "$transition_str: $sentence";
        return $error;
    }

    // -------------------------------------------------------------------------

    $error = $transition->do_state_change($project, $who, $extras);
    if ($error == '') {
        // success
        if (SiteConfig::get()->testing) {
            $sentence = _("Transition succeeded.");
            echo "$indent$transition_str: $sentence$eol";
        }
    } else {
        // failure
        // Prepend $transition_str to the error message.
        $error = "$transition_str: $error";
    }
    return $error;
}
