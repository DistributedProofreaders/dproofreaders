<?php
// DP includes
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'forum_interface.inc');

require_login();

// Which project?
$project_id = validate_projectID('project', @$_GET['project']);

$project = new Project($project_id);

$topic_id = $project->ensure_topic();

$redirect_url = get_url_to_view_topic($topic_id);
header("Location: $redirect_url");

// vim: sw=4 ts=4 expandtab
