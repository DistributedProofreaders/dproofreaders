<?
// DP includes
$relPath="./../../pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'dp_main.inc');
include_once($relPath.'Project.inc');

// Which project?
$project_id = $_GET['project'];

$project = new Project($project_id);

$topic_id = $project->ensure_topic();

$redirect_url = "$forums_url/viewtopic.php?t=$topic_id";
header("Location: $redirect_url");
?>
