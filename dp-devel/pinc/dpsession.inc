<?

// An API of session-handling functions:
//
// dpsession_begin( $userID )
//	Start a session. (The user has logged in.)
//	Record the session variables 'pguser' (from $userID)
//	and 'userP'(via a call to dpsession_set_preferences_from_db).
//
// dpsession_resume()
//	If the request claimed to belong to a session,
//	and that session is valid/current/non-expired,
//	then refresh the session,
//	reinstate the global variables ($pguser and $userP) of that session,
//	and return TRUE.
//	Otherwise, return FALSE.
//
// dpsession_set_preferences_from_db()
//	Read the user's preferences from the database
//	and install them for this session.
//
// dpsession_set_preferences_temp( $user_preferences )
//	Install the given preferences for this session.
//	(If the caller doesn't put them in the database too,
//	they'll only last for this session.)
//
// dpsession_end()
//	End a session. (The user has logged out.)
//
// Page-info...
// dpsession_page_set($info)
// dpsession_page_end()
// dpsession_page_is_set()
// dpsession_page_get()

$pguser = NULL;
$userP  = NULL;

include_once($relPath.'v_site.inc');

if ( $use_php_sessions )
{
	include_once( $relPath.'dpsession_via_php_sessions.inc' );
}
else
{
	include_once( $relPath.'dpsession_via_cookies.inc' );
}

?>
