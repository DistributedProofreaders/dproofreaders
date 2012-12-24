<?php
//clear cookie if one is already set
$relPath='./../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'forum_interface.inc');

if($user_is_logged_in)
{
    // Log out of phpBB2
    if (is_dir($forums_dir))
    {
        $user_id = get_forum_user_id($pguser);
        define('IN_PHPBB', true);
        $phpbb_root_path = $forums_dir."/";
        include($phpbb_root_path.'extension.inc');
        include($phpbb_root_path.'common.php');
        include($phpbb_root_path.'config.php');
        $session_id = $_COOKIE['phpbb2mysql_sid'];
        session_end($session_id, $user_id);
    }

    dpsession_end();
}

metarefresh(0, "../default.php", _("Logout Complete"),
     "<a href=\"../default.php\">"._("Return to DP Home Page.")."</a>");

// vim: sw=4 ts=4 expandtab
