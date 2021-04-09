<?php
$relPath="./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

$title = _("Login Required");
output_header($title);

if(isset($_REQUEST["destination"]))
    echo "<p>" . _("The page you requested requires a login. You will be redirected there once you have signed in.") . "</p>";

echo "<p>" . _("Use the form above to log in.") . "</p>";

