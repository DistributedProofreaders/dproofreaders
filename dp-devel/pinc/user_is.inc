<?
// $Id$

include_once($relPath.'page_tally.php');
include_once($relPath.'SettingsClass.inc');

global $userSettings;

if(!isset($userSettings))
    $userSettings = new Settings();

// This file is for functions that ascertain various things about the user?
// (capabilities, roles)

function user_is_a_sitemanager()
{
    global $userSettings;
    return $userSettings->get_boolean("sitemanager");
}

function user_is_taskcenter_mgr()
{
    global $userSettings;
    return $userSettings->get_boolean("task_center_mgr");
}

function user_is_proj_facilitator()
{
    global $userSettings;
    return user_is_a_sitemanager() || $userSettings->get_boolean("proj_facilitator");
}

function user_is_PM()
{
    global $userSettings;
    return user_is_a_sitemanager()
            || user_is_proj_facilitator()
            || $userSettings->get_boolean("manager");
}

function user_is_PP()
{
    global $userSettings;
    return user_is_a_sitemanager() || $userSettings->get_boolean("postprocessor");
}

function user_is_post_proof_verifier()
{
    global $userSettings;
    return user_is_a_sitemanager() || $userSettings->get_boolean('post_proof_verifier');
}


function user_is_PM_of($project)
{
	global $pguser;

	if (!isset($pguser)) return FALSE;

	if (user_is_a_sitemanager()) return TRUE;

	$result = mysql_query("SELECT username FROM projects WHERE projectid = '$project'");
	if (mysql_num_rows($result) == 0) return FALSE;
    return (mysql_result($result, 0, "username") == $pguser);
}

function user_is_PP_of($project)
{
	global $pguser;

	if (!isset($pguser)) return FALSE;
	if (user_is_a_sitemanager()) return TRUE;

	$result = mysql_query("SELECT checkedoutby FROM projects
                           WHERE projectid = '$project'
                            AND state IN ('proj_post_first_checked_out',
                                            'proj_post_second_checked_out')");
	if (mysql_num_rows($result) == 0) return FALSE;
    return (mysql_result($result, 0, "username") == $pguser);
}

function user_is_PPV_of($project)
{
    if(user_is_a_sitemanager()) return True;
    if(!user_is_post_proof_verifier()) return False;

	// Note that if someone with PPV-ability is PPing a project,
	// they can (and probably will) directly post to PG,
	// with no explicit PPV phase. Thus, they are effectively
	// both the PPer and PPVer of the project.
	$result = mysql_query("SELECT checkedoutby FROM projects
                           WHERE projectid = '$project'
                            AND state IN ('proj_post_first_checked_out',
                                            'proj_post_second_checked_out')");
	if (mysql_num_rows($result) == 0) return FALSE;
	return (mysql_result($result, 0, "checkedoutby") == $pguser);
}

function user_is_site_news_editor()
{
    global $userSettings;
    return user_is_a_sitemanager() || $userSettings->get_boolean("site_news_editor");
}

function user_is_site_translator()
{
    global $userSettings;
    return user_is_a_sitemanager() || $userSettings->get_boolean("site_translator");
}

function user_can_see_BEGIN_in_round($round_number)
{
    global $userSettings;
    if (user_is_a_sitemanager()
            || user_is_proj_facilitator()
            || $userSettings->get_boolean("see_BEGIN_R".$round_number))
        return TRUE;

    $n_pages = user_get_page_tally( $pguser );
    if($round_number == 1)
        return $n_pages <= 40;
    else
        return $n_pages >= 300;
}
    
function user_is_authors_db_manager()
{
    global $userSettings;
	return user_is_a_sitemanager() || $userSettings->get_boolean('authors_db_manager');
}

?>
