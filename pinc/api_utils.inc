<?php
$relPath="../pinc/";
include_once($relPath.'base.inc');

function check_login()
{
    global $user_is_logged_in;

    if (!$user_is_logged_in)
    {
        throw new Exception(_("You must be logged-in to work here"));
    }
}
