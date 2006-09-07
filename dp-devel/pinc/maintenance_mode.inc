<?PHP
include_once($relPath.'user_is.inc');
include_once($relPath.'site_vars.php');

function abort_if_in_maintenance_mode()
{
    global $maintenance, $pguser, $relPath;

    if ($maintenance)
    {
        if (!user_is_a_sitemanager())
        {
            $title = sprintf(_("%s Maintenance"),$site_name);
            $message = _("We are currently performing maintenance on the server.  Please be patient, we should have the site back up and running shortly.");
            include_once($relPath.'slim_header.inc');
            slim_header($title);
            echo "<center><h2>$pguser $message</h2></center></body></html>";
            exit();
        }
    }
}

// vim: sw=4 ts=4 expandtab
?>
