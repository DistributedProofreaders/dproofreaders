<?php
$relPath="./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

$signin = _("Sign In");
theme($signin, "header");

echo _("ID and Password are case sensitive.<BR>Make sure your caps lock is not on.");

theme("", "footer");
// vim: sw=4 ts=4 expandtab
