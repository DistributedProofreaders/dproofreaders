<?
// DP includes
$relPath="./../../pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'dp_main.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'forum_interface.inc');

// Which project?
$project_id = $_GET['project'];

$project = new Project($project_id);

$topic_id = $project->ensure_topic();

$redirect_url = get_url_to_view_forum($topic_id);
header("Location: $redirect_url");
?>
