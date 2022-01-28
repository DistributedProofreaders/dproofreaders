<?php
include_once($relPath.'project_states.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'stages.inc');

function project_continuity_test($project, $orig_state, $no_more_pages)
{
    $curr_state = $project->state;
    if ($curr_state != $orig_state) {
        $orig_state_text = project_states_text($orig_state);
        $curr_state_text = project_states_text($curr_state);
        $err = sprintf(
            _("This project is no longer '%1\$s', it is now '%2\$s', so your request is no longer valid."),
            $orig_state_text,
            $curr_state_text
        );

        if ($no_more_pages) {
            $err .= "\n" . _("The project has no more pages available for proofreading in that round.");
        }
        return $err;
    }
}
