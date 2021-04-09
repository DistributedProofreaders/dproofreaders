<?php
include_once($relPath.'site_vars.php');
include_once($relPath.'user_is.inc');
include_once($relPath.'project_states.inc');

define('PROJECT_DOES_NOT_EXIST',  1);
define('USER_CANNOT_EDIT_PROJECT',2);
define('USER_CAN_EDIT_PROJECT',   3);

function user_can_edit_project( $projectid )
// Ascertain whether the current user ($pguser)
// is allowed to edit the specified project
// (i.e., is either the project's manager or a site manager).
{
    global $pguser;

    $res = mysqli_query(DPDatabase::get_connection(), "SELECT username FROM projects WHERE projectid = '$projectid' LIMIT 1");
    $row = mysqli_fetch_assoc($res);
    if(!$row)
    {
        return PROJECT_DOES_NOT_EXIST;
    }

    if ($pguser == $row["username"])
    {
        // The current user is the project manager for the project.
        return USER_CAN_EDIT_PROJECT;
    }

    if ( user_is_a_sitemanager() || user_is_proj_facilitator() )
    {
        // The user is a site manager or project facilitator, and thus can edit any existing project.
        return USER_CAN_EDIT_PROJECT;
    }

    return USER_CANNOT_EDIT_PROJECT;
}

function user_can_add_project_pages( $projectid, $page_type = "normal" )
{
    global $site_supports_metadata;

    $project = new Project($projectid);
    $state = $project->state;

    if ($page_type == "tp&v" ) # TP&V = Title Page & Verso
    {
        if ($site_supports_metadata && $state == PROJ_NEW)
            return TRUE;
        else
            return FALSE;
    }
    elseif ($page_type == "normal")
    {
        // Load text+images from uploads area into project.
        // Can do this if it's a new project (as measured by the state it's in)
        // If the user is disabled from uploading new projects, they can only
        // do this if the project already has some pages loaded.
        if (
            ( ($state == PROJ_NEW && ! $site_supports_metadata)
              || ( $site_supports_metadata && ($state == PROJ_NEW_APPROVED || $state == PROJ_NEW_FILE_UPLOADED) )
              || $state == PROJ_P1_UNAVAILABLE )
            && ( $project->get_num_pages() > 0 || ! user_has_project_loads_disabled() )
            )
        {
            return TRUE;
        }
        else
            return FALSE;
    }
    else
    {
        assert(FALSE);
    }
}

function abort_if_cant_edit_project( $projectid )
// Politely abort if the current user
// is not allowed to edit the specified project
{
    global $site_manager_email_addr;

    $result = user_can_edit_project($projectid);

    if ( $result == PROJECT_DOES_NOT_EXIST )
    {
        echo "
            <P>
            "._("There appears to be no such project")." ($projectid).
            <P>
            ", sprintf(_("If this message is an error, contact a <a href='%s'>site manager</a>"),
                "mailto:$site_manager_email_addr"), "
            <P><a href=\"projectmgr.php\">"._("Back")."</a>";
        exit;
    }
    else if ( $result == USER_CANNOT_EDIT_PROJECT )
    {
        echo "
            <P>
            "._("You are not allowed to manage this project")." ($projectid).
            <P>
            ", sprintf(_("If this message is an error, contact a <a href='%s'>site manager</a>"),
                "mailto:$site_manager_email_addr"), "
            <P><a href=\"projectmgr.php\">"._("Back")."</a>";
        exit;
    }
    else if ( $result == USER_CAN_EDIT_PROJECT )
    {
        return;
    }
    else
    {
        echo _("unexpected return value from user_can_edit_project") . ": '$result'";
        exit;
    }
}

function user_can_delete_project_in_state( $project_state )
// Can the current user delete a project in the given state?
// (assuming that the current user can edit/manage the project)
{
    // PM/PF/SA can delete a project when it's new.
    // SA can 'delete' a project when it's in any state but already-deleted.
    // (There's a difference to what 'deletion' means in the latter case.)
    return (
        $project_state == PROJ_NEW
        ||
        $project_state != PROJ_DELETE && user_is_a_sitemanager()
    );
}

function check_user_can_load_projects($exit_if_not)
{
    global $site_manager_email_addr;
    if (user_has_project_loads_disabled())
    {
        echo "
              <div class='display-flex'><div class='callout'>"
            ._("You are not currently permitted to create new projects, or move projects out of the unavailable state.")
            ."<br>\n"
            .sprintf(_("If you believe you are receiving this message in error, please contact a <a href='%s'>site manager</a>."),
                     "mailto:$site_manager_email_addr"
                     )
            ."
              </div></div>\n";
        if ($exit_if_not) 
        {
            exit;
        }
    }
}

?>
