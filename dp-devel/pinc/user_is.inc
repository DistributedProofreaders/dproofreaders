<?

// This file is for functions that ascertain various things about the user?
// (capabilities, roles)

function user_is_a_sitemanager()
{
	global $userP;

	if (!isset($userP)) return FALSE;

	return ( $userP['sitemanager'] == 'yes' );
}

?>
