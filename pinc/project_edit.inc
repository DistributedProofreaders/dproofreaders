<?php
include_once($relPath.'site_vars.php');
include_once($relPath.'user_is.inc');
include_once($relPath.'project_states.inc');


function user_can_add_project_pages($projectid)
{
    $project = new Project($projectid);
    $state = $project->state;

    // Load text+images from uploads area into project.
    // Can do this if it's a new project (as measured by the state it's in)
    // If the user is disabled from uploading new projects, they can only
    // do this if the project already has some pages loaded.
    if (($state == PROJ_NEW || $state == PROJ_P1_UNAVAILABLE)
        && ($project->get_num_pages() > 0 || ! user_has_project_loads_disabled())
        ) {
        return true;
    } else {
        return false;
    }
}

function abort_if_cant_edit_project($projectid)
// Politely abort if the current user
// is not allowed to edit the specified project
{
    global $site_manager_email_addr;

    try {
        $project = new Project($projectid);
    } catch (NonexistentProjectException $exception) {
        echo "
            <P>
            "._("There appears to be no such project")." ($projectid).
            <P>
            ", sprintf(_("If this message is an error, contact a <a href='%s'>site manager</a>"),
                "mailto:$site_manager_email_addr"), "
            <P><a href=\"projectmgr.php\">"._("Back")."</a>";
        exit;
    }

    if (!$project->can_be_managed_by_current_user) {
        echo "
            <P>
            "._("You are not authorized to manage this project.")." ($projectid).
            <P>
            ", sprintf(_("If this message is an error, contact a <a href='%s'>site manager</a>"),
                "mailto:$site_manager_email_addr"), "
            <P><a href=\"projectmgr.php\">"._("Back")."</a>";
        exit;
    }
}

function check_user_can_load_projects($exit_if_not)
{
    global $site_manager_email_addr;
    if (user_has_project_loads_disabled()) {
        echo "
              <div class='display-flex' style='margin-bottom:0.5em'><div class='callout'>"
            ._("You are not currently permitted to create new projects, or move projects out of the new project or unavailable states.")
            ."<br>\n"
            .sprintf(_("If you believe you are receiving this message in error, please contact a <a href='%s'>site manager</a>."),
                     "mailto:$site_manager_email_addr"
                     )
            ."
              </div></div>\n";
        if ($exit_if_not) {
            exit;
        }
    }
}
