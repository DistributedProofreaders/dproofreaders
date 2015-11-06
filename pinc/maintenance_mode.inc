<?php
include_once($relPath.'site_vars.php');
include_once($relPath.'slim_header.inc');
/*
Including user_is.inc causes a perf impact because it includes
page_tally.inc, which includes stages.inc - all of which have a
great deal of overhead. That's a whole lot of work to satisfy a
rare edge case (that we're in maintenance mode). 
Until that's resolved, conditionally include it. This is a band-aid,
not a fix!
*/
if ($maintenance)
{
    include_once($relPath.'user_is.inc');
}

function abort_if_in_maintenance_mode()
{
    global $maintenance, $maintenance_message, $pguser, $site_name;

    if ($maintenance)
    {
        if (!user_is_a_sitemanager())
        {
            $title = sprintf(_("%s Maintenance"),$site_name);
            $message = _("We are currently performing maintenance on the server.  Please be patient, we should have the site back up and running shortly.");
            if($maintenance_message)
                $message .= "<br>$maintenance_message";
            slim_header($title);
            echo "<center><h2>";
            if (isset($pguser))
                echo "$pguser ";
            echo "$message</h2></center>";
            slim_footer();
            exit();
        }
    }
}

// vim: sw=4 ts=4 expandtab
