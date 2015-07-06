<?php
$relPath="./pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'slim_header.inc');
include_once('faq/pophelp/prefs/prefs_pophelp.inc');
include_once('faq/pophelp/teams/teams_pophelp.inc');

$pophelp = array(
    'prefs' => $prefs_pophelp,
    'teams' => $teams_pophelp,
);

undo_all_magic_quotes();

$category = get_enumerated_param($_GET, 'category', null, array_keys($pophelp));
$name     = get_enumerated_param($_GET, 'name',     null, array_keys($pophelp[$category]));

$title   = $pophelp[$category][$name]['title'];
$content = $pophelp[$category][$name]['content'];

slim_header($title, true, false);
echo "<div align='center'>\n";
echo "<table border='1' width='360' cellpadding='6'>\n";
echo "<tr><td align='center' bgcolor='#cccccc'><b>$title</b></td></tr>\n";
echo "<tr><td>$content</td></tr>\n";
echo "<tr><td align='center' bgcolor='#cccccc'><b><a href='javascript:window.close();'>" . _("Close Window") . "</a></b></td></tr>\n";
echo "</table></div>\n";

slim_footer();

// vim: sw=4 ts=4 expandtab
