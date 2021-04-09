<?php
$relPath="./pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'slim_header.inc');
include_once('faq/pophelp/prefs/prefs_pophelp.inc');
include_once('faq/pophelp/teams/teams_pophelp.inc');

require_login();

$pophelp = array(
    'prefs' => $prefs_pophelp,
    'teams' => $teams_pophelp,
);

$category = get_enumerated_param($_GET, 'category', null, array_keys($pophelp));
$name     = get_enumerated_param($_GET, 'name',     null, array_keys($pophelp[$category]));

$title   = $pophelp[$category][$name]['title'];
$content = $pophelp[$category][$name]['content'];

slim_header($title);
echo "<table class='basic' style='width: 360px; margin-left: auto; margin-right: auto;'>\n";
echo "<tr><th>$title</th></tr>\n";
echo "<tr><td>$content</td></tr>\n";
echo "<tr><td class='center-align'><b><a href='javascript:window.close();'>" . _("Close Window") . "</a></b></td></tr>\n";
echo "</table>\n";

