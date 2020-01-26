<?php
// DP includes
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'forum_interface.inc');
include_once($relPath.'slim_header.inc');

require_login();

// Which project?
$project_id = validate_projectID('project', @$_GET['project']);

$project = new Project($project_id);

$topic_id = $project->ensure_topic();

if($topic_id == NULL)
{
    // an error occurred trying to create the topic, let the user know
    $title = _("Failure creating project topic");
    slim_header($title);
    echo "<h1>$title</h1>";
    echo "<p class='error'>" . sprintf(_("An error occurred when trying to create a new forum topic for this project. Please contact <a href='%s'>a site manager</a> and let them know the following information."), "mailto:$site_manager_email_addr") . "</p>";

    echo <<<EOF
<pre>
ProjectID: $project_id
Title: $project->nameofwork
Author: $project->authorsname
</pre>
EOF;
}
else
{
    $redirect_url = get_url_to_view_topic($topic_id);
    header("Location: $redirect_url");
}

// vim: sw=4 ts=4 expandtab
