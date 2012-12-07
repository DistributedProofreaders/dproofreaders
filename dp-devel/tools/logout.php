<?php
//clear cookie if one is already set
$relPath='./../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'metarefresh.inc');

if ( dpsession_resume() )
{
    dpsession_end();
}

metarefresh(0, "../default.php", _("Logout Complete"),
     "<A HREF=\"../default.php\">"._("Return to DP Home Page.")."</A>");

// vim: sw=4 ts=4 expandtab
