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

	$result = mysql_query("SELECT value FROM usersettings WHERE username = '$pguser' AND setting = 'task_center_mgr'");
	if (mysql_num_rows($result) >= 1) {
		if (mysql_result($result, 0, "value") == "yes") { return TRUE; } else { return FALSE; }
	} else {
		return FALSE;
	}
}

?>
