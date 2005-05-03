<?
$relPath="./../../pinc/";
include($relPath.'v_site.inc');
include_once($relPath.'connect.inc');
include_once($relPath.'project_states.inc');
include_once('./post_files.inc');

if (!user_is_a_sitemanager())
{
    echo _('You are not authorized to invoke this script.');
    exit;
}

set_time_limit(0);

$dbConnection = new dbConnect();

$myresult = mysql_query("
	SELECT projectid, nameofwork FROM projects WHERE state = '".PROJ_POST_FIRST_AVAILABLE."'" . " OR state='".PROJ_POST_FIRST_CHECKED_OUT."'");

while ($row = mysql_fetch_assoc($myresult)) 
{
	$projectid = $row['projectid'];
	$title = $row['nameofwork'];
	echo "<p>generating files for $projectid ($title) ...</p>\n";
	flush();
	generate_post_files( $projectid );
	flush();
}

?>
