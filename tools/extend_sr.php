<?php
$relPath = "../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'smoothread.inc'); // handle_smooth_reading_change()
include_once($relPath.'metarefresh.inc');

class ActionException extends Exception
{
}

require_login();

$projectid = get_projectID_param($_REQUEST, 'project');
$project = new Project($projectid);
$days = get_integer_param($_REQUEST, 'days', 0, 0, 56);

$title = _("Extend the Smooth Reading deadline");
$new_state = PROJ_POST_FIRST_CHECKED_OUT;
$back_url = "$code_url/project.php?id=$projectid&amp;expected_state=$new_state#smooth_start";

try {
    // validate the user has the ability to do this action
    if (!($project->PPer_is_current_user || user_is_a_sitemanager())) {
        throw new ActionException(_("You do not have permission to perform this action."));
    }
    // validate the project is in the correct state
    if (!(PROJ_POST_FIRST_CHECKED_OUT == $project->state)) {
        throw new ActionException(_("The project is not in the correct state for this action."));
    } elseif ($project->smoothread_deadline == 0) {
        throw new ActionException(_("The project has not been made available for Smooth Reading."));
    } elseif ($project->smoothread_deadline < time()) {
        // this can happen if project page was stale
        throw new ActionException(_("The Smooth Reading deadline for this project has passed and cannot be extended in this way."));
    }

    // postcomments is not translated because it can be viewed by anyone not just the present PPer
    $postcomments = "\n----------\n" . date("Y-m-d H:i") . " Smooth Reading deadline extended by $pguser";
    handle_smooth_reading_change($project, $postcomments, $days, true);
    $body = sprintf(_("The Smooth Reading deadline has been extended by %d day(s)"), $days);
    metarefresh(1, $back_url, $title, $body);
} catch (ActionException $e) {
    slim_header($title);
    echo "<h1>$title</h1>";
    echo "<h2>", sprintf("Project: %s", html_safe($project->nameofwork)), "</h2>";
    echo "<p class='error'>", $e->getMessage(), "</p>\n";
    echo "<a href='$back_url'>", _("Return to the Project Page"), "</a>";
}
