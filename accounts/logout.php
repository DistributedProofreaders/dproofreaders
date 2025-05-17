<?php
//clear cookie if one is already set
$relPath = './../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'forum_interface.inc');

if (User::is_logged_in()) {
    logout_forum_user();

    dpsession_end();

    destroy_csrf_token();
}

metarefresh(
    0,
    "../index.php",
    _("Logout Complete"),
    "<a href=\"../index.php\">"._("Return to DP Home Page.")."</a>"
);
