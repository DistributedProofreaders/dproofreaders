<?

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

function user_is_taskcenter_mgr() {
	global $userP, $pguser;

	if (!isset($userP)) { return FALSE; }

	if (user_is_a_site_manager()) return TRUE;

	$result = mysql_query("SELECT value FROM usersettings WHERE username = '$pguser' AND setting = 'task_center_mgr'");
	if (mysql_num_rows($result) >= 1) {
		if (mysql_result($result, 0, "value") == "yes") { return TRUE; } else { return FALSE; }
	} else {
		return FALSE;
	}
}

function user_is_proj_facilitator()
{
	global $userP, $pguser;

	if (!isset($userP)) { return FALSE; }

	if (user_is_a_site_manager()) return TRUE;

	$result = mysql_query("SELECT value FROM usersettings WHERE username = '$pguser' AND setting = 'proj_facilitator'");
	if (mysql_num_rows($result) >= 1) {
		if (mysql_result($result, 0, "value") == "yes") { return TRUE; } else { return FALSE; }
	} else {
		return FALSE;
	}
}

function user_is_post_proof_verifier()
{
	global $userP, $pguser;

	if (!isset($userP)) { return FALSE; }

	if (user_is_a_site_manager()) return TRUE;

	$result = mysql_query("SELECT value FROM usersettings WHERE username = '$pguser' AND setting = 'post_proof_verifier'");
	if (mysql_num_rows($result) >= 1) {
		if (mysql_result($result, 0, "value") == "yes") { return TRUE; } else { return FALSE; }
	} else {
		return FALSE;
	}
}

function user_is_PM_of($project)
{
	global $userP, $pguser;

	if (!isset($userP)) { return FALSE; }

	if (user_is_a_site_manager()) return TRUE;

	$result = mysql_query("SELECT userid FROM projects WHERE projectid = '$project'");
	if (mysql_num_rows($result) >= 1) {
		if (mysql_result($result, 0, "value") == $pguser) { return TRUE; } else { return FALSE; }
	} else {
		return FALSE;
	}
}



function user_is_PP_of($project)
{
	global $userP, $pguser;

	if (!isset($userP)) { return FALSE; }

	if (user_is_a_site_manager()) return TRUE;

	$result = mysql_query("SELECT checkedoutby FROM projects WHERE projectid = '$project' AND state = 'proj_post_first_checked_out'");
	if (mysql_num_rows($result) >= 1) {
		if (mysql_result($result, 0, "value") == $pguser) { return TRUE; } else { return FALSE; }
	} else {
		return FALSE;
	}
}


function user_is_site_news_editor()
{
	global $userP, $pguser;

	if (!isset($userP)) { return FALSE; }

	if (user_is_a_site_manager()) return TRUE;

	$result = mysql_query("SELECT value FROM usersettings WHERE username = '$pguser' AND setting = 'site_news_editor'");
	if (mysql_num_rows($result) >= 1) {
		if (mysql_result($result, 0, "value") == "yes") { return TRUE; } else { return FALSE; }
	} else {
		return FALSE;
	}
}



?>
