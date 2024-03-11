<?php

// Allow authorized users to remove a hold on a project.

$relPath = "./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'Project.inc'); // get_projectID_param()
include_once($relPath.'metarefresh.inc'); // metarefresh()

require_login();

$projectid = get_projectID_param($_POST, 'projectid');

$project = new Project($projectid);
if (!$project->can_be_managed_by_current_user) {
    die(_('You are not authorized to manage this project.'));
}

$return_uri = urldecode($_POST['return_uri']);
$curr_state = get_enumerated_param($_POST, 'curr_state', null, ProjectStates::get_states());

$project->remove_holds([$curr_state]);
$title = _("Removing hold");
metarefresh(0, $return_uri, $title, $title);
