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

?>
