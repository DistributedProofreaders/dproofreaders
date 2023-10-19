<?php
//clear cookie if one is already set
$relPath = './../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'forum_interface.inc');

if ($user_is_logged_in) {
    logout_forum_user();

    dpsession_end();

    destroy_csrf_token();
}

metarefresh(
    0,
    "../default.php",
    _("Logout Complete"),
    "<a href=\"../default.php\">"._("Return to DP Home Page.")."</a>"
);
