<?
//clear cookie if one is already set
$relPath='./../pinc/';
include_once($relPath.'dpsession.inc');
include_once($relPath.'metarefresh.inc');

if ( dpsession_resume() )
{
	dpsession_end();
}

metarefresh(0, "../default.php", "Logout Complete",
     "<A HREF=\"../default.php\">Return to DP Home Page.</A>");
?>
