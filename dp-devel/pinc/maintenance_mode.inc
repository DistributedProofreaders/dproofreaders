<?PHP
include_once($relPath.'user_is.inc');

function isMaintenanceMode() {
	global $maintenance, $pguser;

	if ($maintenance) {
		if (!user_is_a_sitemanager()) {
			$title = _("Distributed Proofreaders Maintenance");
			$message = _("We are currently performing maintenance on the server.  Please be patient, we should have the site back up and running shortly.");
			include_once($relPath.'slim_header.inc');
			slim_header($title);
			echo "<center><h2>$pguser $message</h2></center></body></html>";
			exit();
		}
	}
}

?>
