<?
include($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'user_is.inc');


define('PROJECT_DOES_NOT_EXIST',  1);
define('USER_CANNOT_EDIT_PROJECT',2);
define('USER_CAN_EDIT_PROJECT',   3);

function user_can_edit_project( $projectid )
// Ascertain whether the current user ($pguser)
// is allowed to edit the specified project
// (i.e., is either the project's manager or a site manager).
{
    global $site_manager_email_addr;
    global $pguser;

    $res = mysql_query("SELECT username FROM projects WHERE projectid = '$projectid' LIMIT 1");
    if (mysql_num_rows($res) == 0)
    {
	return PROJECT_DOES_NOT_EXIST;
    }

    $project_manager = mysql_result($res, 0, "username");
    if ($pguser == $project_manager)
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

function abort_if_cant_edit_project( $projectid )
// Politely abort if the current user ($pguser)
// is not allowed to edit the specified project
{
    global $site_manager_email_addr;
    global $pguser;

    $result = user_can_edit_project($projectid);

    if ( $result == PROJECT_DOES_NOT_EXIST )
    {
        echo "
            <P>
            "._("There appears to be no such project")." ($projectid).
            <P>
            "._("If this message is an error, contact the")." <a href=\"mailto:$site_manager_email_addr\">"._("site manager")."</a>.
            <P><a href=\"projectmgr.php\">"._("Back")."</a>";
        exit;
    }
    else if ( $result == USER_CANNOT_EDIT_PROJECT )
    {
        echo "
            <P>
            "._("You are not allowed to change this project")." ($projectid).
            <P>
            "._("If this message is an error, contact the")." <a href=\"mailto:$site_manager_email_addr\">"._("site manager")."</a>.
            <P><a href=\"projectmgr.php\">"._("Back")."</a>";
        exit;
    }
    else if ( $result == USER_CAN_EDIT_PROJECT )
    {
	return;
    }
    else
    {
	echo "unexpected return value from user_can_edit_project: '$result'";
	exit;
    }
}

?>
