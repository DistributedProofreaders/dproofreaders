<?php

// Allow authorized users to remove a hold on a project.

$relPath="./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'Project.inc'); // validate_projectID() Project()
include_once($relPath.'project_holds.inc'); // remove_holds()
include_once($relPath.'metarefresh.inc'); // metarefresh()

require_login();

$projectid = validate_projectID('projectid', @$_POST['projectid']);

$project = new Project($projectid);
if (!$project->can_be_managed_by_current_user)
{
    echo "<p>", _('You are not authorized to manage this project.'), "</p>\n";
    exit;
}

$return_uri = urldecode($_POST['return_uri']);
$states = array($_POST['curr_state']);

remove_holds($projectid, $states);
$title = _("Removing hold");
metarefresh(1, $return_uri, $title, $title);

// vim: sw=4 ts=4 expandtab
