<?
$relPath="./../../pinc/";
include($relPath.'metarefresh.inc');
include($relPath.'dp_main.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'project_trans.inc');

$project = $_GET['project'];

$new_state = PROJ_SUBMIT_PG_POSTED;

$error_msg = project_transition( $project, $new_state);

if ($error_msg == '')
{
	metarefresh(2, "editproject.php?project=$project&posted=1", "Project Completed Successful",
	    "This project has been marked as completed.");
}
else
{
	echo "$error_msg<br><br>\n";
	metarefresh(2, "index.php", "Project Completed Unsuccessful",
	    "Something went wrong, and this project has probably not been marked as completed.");
}

?>
