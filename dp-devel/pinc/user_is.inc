<?
// $Id$

include_once($relPath.'page_tally.php');
include_once($relPath.'SettingsClass.inc');

global $settings;

if(!isset($settings))
    $settings = new Settings();

// This file is for functions that ascertain various things about the user?
// (capabilities, roles)

function user_is_a_sitemanager()
{
	global $userP, $pguser;

	if (!isset($userP)) return FALSE;

	if ( $userP['sitemanager'] != 'yes' ) return FALSE;

	$res = mysql_query("
	    SELECT sitemanager FROM users WHERE username = '$pguser'
	");
	return (mysql_result($res,0,"sitemanager") == "yes");
}

function user_is_taskcenter_mgr()
{
    global $settings;
    return $settings->get_boolean("task_center_mgr");
}

/*
function user_is_taskcenter_mgr()
	global $userP, $pguser;

	if (!isset($userP)) { return FALSE; }

	if (user_is_a_sitemanager()) return TRUE;

	$result = mysql_query("SELECT value FROM usersettings WHERE username = '$pguser' AND setting = 'task_center_mgr'");
	if (mysql_num_rows($result) >= 1) {
		if (mysql_result($result, 0, "value") == "yes") { return TRUE; } else { return FALSE; }
	} else {
		return FALSE;
	}
}
*/

function user_is_proj_facilitator()
{
    global $settings;
    return user_is_a_sitemanager() || $settings->get_boolean("proj_facilitator");
}

/*
function user_is_proj_facilitator()
{
	global $userP, $pguser;

	if (!isset($userP)) { return FALSE; }

	if (user_is_a_sitemanager()) return TRUE;

	$result = mysql_query("SELECT value FROM usersettings WHERE username = '$pguser' AND setting = 'proj_facilitator'");
	if (mysql_num_rows($result) >= 1) {
		if (mysql_result($result, 0, "value") == "yes") { return TRUE; } else { return FALSE; }
	} else {
		return FALSE;
	}
}
*/

function user_is_PM()
{
	global $userP, $pguser;

	if (!isset($userP)) { return FALSE; }

	if (user_is_a_sitemanager()) return TRUE;

	if (user_is_proj_facilitator()) return TRUE;

	$result = mysql_query("SELECT manager FROM users WHERE username = '$pguser'");
	if (mysql_num_rows($result) >= 1) {
		if (mysql_result($result, 0, 'manager') == 'yes') { return TRUE; } 
		else { return FALSE; }
	} else {
		return FALSE;
	}
}

function user_is_PP()
{
	global $userP, $pguser;

	if (!isset($userP)) { return FALSE; }

	if (user_is_a_sitemanager()) return TRUE;

	$result = mysql_query("SELECT postprocessor FROM users WHERE username = '$pguser'");
	if (mysql_num_rows($result) >= 1) {
		if (mysql_result($result, 0, "postprocessor") == 'yes') { return TRUE; } 
		elseif (user_get_page_tally($pguser) >= 400) { return TRUE; } 
		else { return FALSE; }
	} else {
		return FALSE;
	}
}

function user_is_post_proof_verifier()
{
    global $settings;
    return user_is_a_sitemanager() || $settings->get_boolean('post_proof_verifier');
}


/*
function user_is_post_proof_verifier()
{
	global $userP, $pguser;

	if (!isset($userP)) { return FALSE; }

	if (user_is_a_sitemanager()) return TRUE;

	$result = mysql_query("SELECT value FROM usersettings WHERE username = '$pguser' AND setting = 'post_proof_verifier'");
	if (mysql_num_rows($result) >= 1) {
		if (mysql_result($result, 0, "value") == "yes") { return TRUE; } else { return FALSE; }
	} else {
		return FALSE;
	}
}
*/

function user_is_PM_of($project)
{
	global $userP, $pguser;

	if (!isset($userP)) { return FALSE; }

	if (user_is_a_sitemanager()) return TRUE;

	$result = mysql_query("SELECT username FROM projects WHERE projectid = '$project'");
	if (mysql_num_rows($result) >= 1) {
		if (mysql_result($result, 0, "username") == $pguser) { return TRUE; } else { return FALSE; }
	} else {
		return FALSE;
	}
}



function user_is_PP_of($project)
{
	global $userP, $pguser;

	if (!isset($userP)) { return FALSE; }

	if (user_is_a_sitemanager()) return TRUE;

	$result = mysql_query("SELECT checkedoutby FROM projects WHERE projectid = '$project' AND (state = 'proj_post_first_checked_out' or state = 'proj_post_second_checked_out')");
	if (mysql_num_rows($result) >= 1) {
		if (mysql_result($result, 0, "checkedoutby") == $pguser) { return TRUE; } else { return FALSE; }
	} else {
		return FALSE;
	}
}

function user_is_PPV_of($project)
{
    if(user_is_a_sitemanager()) return True;
    if(!user_is_post_proof_verifier()) return False;

    /*
	global $userP, $pguser;

	if (!isset($userP)) { return FALSE; }

	if (user_is_a_sitemanager()) return TRUE;

	$result = mysql_query("SELECT value FROM usersettings WHERE username = '$pguser' AND setting = 'post_proof_verifier'");
	if (mysql_num_rows($result) >= 1) {
		if (mysql_result($result, 0, "value") != "yes") { return FALSE; }
	} else {
		return FALSE;
	}
    */

	// Note that if someone with PPV-ability is PPing a project,
	// they can (and probably will) directly post to PG,
	// with no explicit PPV phase. Thus, they are effectively
	// both the PPer and PPVer of the project.
	$result = mysql_query("SELECT checkedoutby
                           FROM projects
                           WHERE projectid = '$project'
                            AND (state = 'proj_post_first_checked_out' or state = 'proj_post_second_checked_out')");
	return (mysql_num_rows($result) >= 1 && mysql_result($result, 0, "checkedoutby") == $pguser);
}

function user_is_site_news_editor()
{
    global $settings;
    return user_is_a_sitemanager() || $settings->get_boolean("site_news_editor");
}

/*
function user_is_site_news_editor()
{
	global $userP, $pguser;

	if (!isset($userP)) { return FALSE; }

	if (user_is_a_sitemanager()) return TRUE;

	$result = mysql_query("SELECT value FROM usersettings WHERE username = '$pguser' AND setting = 'site_news_editor'");
	if (mysql_num_rows($result) >= 1) {
		if (mysql_result($result, 0, "value") == "yes") { return TRUE; } else { return FALSE; }
	} else {
		return FALSE;
	}
}
*/

function user_is_site_translator()
{
    global $settings;
    return user_is_a_sitemanager() || $settings->get_boolean("site_translator");
}

/*
function user_is_site_translator()
{
	global $userP, $pguser;

	if (!isset($userP)) { return FALSE; }

	if (user_is_a_sitemanager()) return TRUE;

	$result = mysql_query("SELECT value FROM usersettings WHERE username = '$pguser' AND setting = 'site_translator'");
	if (mysql_num_rows($result) >= 1) {
		if (mysql_result($result, 0, "value") == "yes") { return TRUE; } else { return FALSE; }
	} else {
		return FALSE;
	}
}
*/

function user_can_see_BEGIN_in_round($round_number)
{
    global $settings;
    if (user_is_a_sitemanager()
            || user_is_proj_facilitator()
            || $settings->get_boolean("see_BEGIN_R".$round_number))
        return TRUE;

    $n_pages = user_get_page_tally( $pguser );
    if($round_number == 1)
        return $n_pages <= 40;
    else
        return $n_pages >= 300;
}
    
/*
function user_can_see_BEGIN_in_round($round_number)
{
	global $userP, $pguser;

	if (!isset($userP)) { return FALSE; }

	if (user_is_a_sitemanager() || user_is_proj_facilitator() ) return TRUE;

		$n_pages = user_get_page_tally( $pguser );
		switch ($round_number)
		{
			case 1:
				if ($n_pages <= 40) { return TRUE; }
				break;
			case 2:
				if ($n_pages >= 300) { return TRUE; }
				break;
			default:
				return TRUE;
				// The round-restriction is sufficient.
		}

	$setting = "see_BEGIN_R$round_number";
	$result = mysql_query("SELECT value FROM usersettings WHERE username = '$pguser' AND setting = '$setting'");
	if (mysql_num_rows($result) >= 1) {
		if (mysql_result($result, 0, "value") == "yes") { return TRUE; } else { return FALSE; }
	} else {
		return FALSE;
	}
}
*/


function user_is_authors_db_manager()
{
    global $settings;
	return user_is_a_sitemanager() || $settings->get_boolean('authors_db_manager');
}

/*
function user_is_authors_db_manager()
{
	global $userP, $pguser;

	if (!isset($userP)) { return FALSE; }

	if (user_is_a_sitemanager()) return TRUE;

	$result = mysql_query("SELECT value FROM usersettings WHERE username = '$pguser' AND setting = 'authors_db_manager'");
	if (mysql_num_rows($result) >= 1) {
		if (mysql_result($result, 0, 'value') == 'yes') { return TRUE; } else { return FALSE; }
	} else {
		return FALSE;
	}
}
*/

?>
