<?php
$relPath="../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'smooth_read_change.inc'); // handle_smooth_reading_change()

class ActionException extends Exception {}

require_login();

$projectid = validate_projectID('project', @$_REQUEST['project']);
$project = new Project($projectid);
$days = get_integer_param($_REQUEST, 'days', 0, 0, 56);

$title = _("Extend the Smooth Reading deadline");

output_header($title);

echo "<h1>$title</h1>";
echo "<h2>", sprintf("Project: %s", $project->nameofwork), "</h2>";

try
{
    // validate the user has the ability to do this action
    if(!($project->PPer_is_current_user || user_is_a_sitemanager()))
    {
        throw new ActionException(_("You do not have permission to perform this action."));
    }
    // validate the project is in the correct state
    if(!(PROJ_POST_FIRST_CHECKED_OUT == $project->state))
    {
        throw new ActionException(_("The project is not in the correct state for this action."));
    }
    else if($project->smoothread_deadline == 0)
    {
        throw new ActionException(_("The project has not been made available for Smooth Reading."));
    }
    else if($project->smoothread_deadline < time())
    {
        // this can happen if project page was stale
        throw new ActionException(_("The Smooth Reading deadline for this project has passed and cannot be extended in this way."));
    }

    // postcomments is not translated because it can be viewed by anyone not just the present PPer
    $postcomments = "\n----------\n" . date("Y-m-d H:i") . " Smooth Reading deadline extended by $pguser";
    handle_smooth_reading_change($project, $postcomments, $days, true);
    echo "<p>", sprintf(_("The Smooth Reading deadline has been extended by %d day(s)"), $days), "</p>";
}
catch(ActionException $e)
{
    echo "<p class='error'>", $e->getMessage(), "</p>\n";
}

$new_state = PROJ_POST_FIRST_CHECKED_OUT;
$back_url = "$code_url/project.php?id=$projectid&amp;expected_state=$new_state#smooth_start";
echo "<a href='$back_url'>", _("Return to the Project Page"), "</a>";
