<?
include_once($relPath.'connect.inc');
include_once($relPath.'gettext_setup.inc');

$db_Connection=new dbConnect();

function mysql_session_open($session_path, $session_name) {
	return true; //We've already opened the database so let's not open it again
}

function mysql_session_close() {
	return true; //We use a persistent connection so we don't need to worry about closing it
}

function mysql_session_select($sid) {
	$result = mysql_query("SELECT value FROM sessions WHERE sid = '$sid' AND expiration > ".time()."") or die(mysql_error());
	if (mysql_num_rows($result) == 1) {
		return mysql_result($result, 0);
	} else {
		return false;
	}
}

function mysql_session_write($sid, $value) {
	$expiration = time() + get_cfg_var("session.gc_maxlifetime");
	$result = mysql_query("SELECT COUNT(*) FROM sessions WHERE sid = '$sid'");
	$SessionExists = mysql_result($result, 0);

	if (empty($SessionExists)) {
		$result = mysql_query("INSERT INTO sessions (sid, expiration, value) VALUES ('$sid', '$expiration', '$value')") or die(mysql_error());
	} else {
		$result = mysql_query("UPDATE sessions SET expiration = '$expiration', value = '$value' WHERE sid = '$sid' AND expiration > ".time()."") or die(mysql_error());
	}
	return $result;
}

function mysql_session_destroy($sid) {
	$result = mysql_query("DELETE FROM sessions WHERE sid = '$sid'") or die(mysql_error());
	return $result;
}

function mysql_session_garbage_collect($lifetime) {
	$result = mysql_query("DELETE FROM sessions WHERE expiration < ".time()-$lifetime."") or die(mysql_error());
	return $result;
}

function startSession($userID) {
	$session_id = session_id();
	if (empty($session_id)) {
		session_set_save_handler("mysql_session_open", "mysql_session_close", "mysql_session_select", "mysql_session_write", "mysql_session_destroy", "mysql_session_garbage_collect");
		session_start();
	}

	if (!empty($userID)) { $_SESSION['pguser'] = $userID; }
	if (!isset($_SESSION['userP']) && !empty($userID)) { updateSessionPreferences($_SESSION['pguser']); }
}

function updateSessionPreferences($userID) {
	$db_users = mysql_query("SELECT u_id, u_profile, u_lang, email_updates, u_plist, u_top10, u_neigh, u_align, i_prefs, i_theme, i_pmdefault, id, manager, postprocessor, sitemanager, team_1, team_2, team_3, u_intlang, u_privacy, last_login FROM users WHERE username='$userID'");
	$u_usersData = mysql_fetch_assoc($db_users);

	$db_query = mysql_query("SELECT profilename, i_res, i_type, i_layout, i_toolbar, i_statusbar, i_newwin, v_fnts, v_fntf, v_zoom, v_tframe, v_tlines, v_tchars, v_tscroll, v_twrap, h_fnts, h_fntf, h_zoom, h_tframe, h_tlines, h_tchars, h_tscroll, h_twrap FROM user_profiles WHERE u_ref='{$u_usersData['u_id']}' AND id='{$u_usersData['u_profile']}'");
	$uData = mysql_fetch_assoc($db_query);

	$prefsChangedArray = array("prefschanged" => 0);

	$_SESSION['userP'] = array_merge($u_usersData, $uData, $prefsChangedArray);
}

function updateTempSessionPreferences($tempPrefs, $userID) {
	$_SESSION['userP'] = $tempPrefs;
}

function checkForActiveSession() {
	global $relPath;

	if (isset($_SESSION['userP']) && !empty($_SESSION['userP'])) {
		return 0; //User is logged in.  Nothing to do
	} else {
		// Remember the current REQUEST_URI, so that we can send the user there
		// after the login process.
		$url=$relPath."../accounts/signin.php?destination={$_SERVER['REQUEST_URI']}";
		$body="<A HREF=\"$url\">" . _("Please sign in") . "</A>";
		$title = _("Please sign in");
		metarefresh(0,$url,$title,$body);
		exit;
	}
}
?>