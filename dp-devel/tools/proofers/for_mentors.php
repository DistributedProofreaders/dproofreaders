<?
$relPath='./../../pinc/';
include_once($relPath.'dp_main.inc');
include_once($relPath.'f_dpsql.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'project_states.inc');


theme(_("For Mentors"), "header");

echo "<br><br><h2>"._("For Mentors")."</h2><br>\n";

echo "<br>\n";


// MENTORS ONLY books available in Round 2


echo "<h3>"._("Currently Available MENTORS ONLY projects")."</h3><br><br>\n";
echo _("Listed oldest first, so please start at the top")."<br><br>\n";

$result = mysql_query("SELECT projectid, nameofwork, authorsname FROM projects WHERE difficulty = 'beginner' AND (
			state='".PROJ_PROOF_SECOND_AVAILABLE."' || state='".PROJ_PROOF_SECOND_VERIFY."')
			ORDER BY modifieddate ASC");

while ($row =  mysql_fetch_array($result)) 
{

echo "<b>".$row['nameofwork']." by ".$row['authorsname']."</b><br>Number of pages by each proofer<br><br>"; 

dpsql_dump_query("
	SELECT
		round1_user as 'Proofer', count(image) as 'Pages done in this project'
	FROM ".$row['projectid']." 
	GROUP BY 1
	ORDER BY 1
");

echo "<br><br><b>".$row['nameofwork']." by ".$row['authorsname']."</b><br>Which proofer did which page<br><br>"; 

dpsql_dump_query("
	SELECT
		SUBSTRING_INDEX(image,'.',1) as 'Page', round1_user as 'Proofer'
	FROM ".$row['projectid']." 
	ORDER BY 1
");


echo "<br><br><br><hr>\n";

}






theme("","footer");
?>

