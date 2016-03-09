<?php
$relPath="../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'project_trans.inc');

require_login();

undo_all_magic_quotes();

$projectid    = validate_projectID('projectid', @$_POST['projectid']);
$postcomments = @$_POST['postcomments'];

// Verify that it's the pp-er trying to perform this action.

$project = new Project($projectid);
if (! $project->PPer_is_current_user || $project->state != PROJ_POST_FIRST_CHECKED_OUT ) {
  echo _("The project is not checked out to you.");
  exit;
}

$qry =  mysql_query(sprintf("
    UPDATE projects
    SET postcomments = '%s'
    WHERE projectid = '$projectid'
", mysql_real_escape_string($postcomments)));

$msg = _("Comments added.");
metarefresh(1, "$code_url/project.php?id=$projectid", $msg, $msg);

// vim: sw=4 ts=4 expandtab
