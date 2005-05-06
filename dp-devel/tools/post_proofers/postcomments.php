<?
$relPath="../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'project_trans.inc');

// Verify that it's the pp-er trying to perform this action.

$project = new Project($projectid);
if (! $project->PPer_is_current_user) {
  echo _("The project is not checked out to you.");
  exit;
}

add_postcomments($projectid, $postcomments, $pguser);

$msg = _("Comments added.");
metarefresh(1, "$code_url/project.php?id=$projectid", $msg, $msg);

?>