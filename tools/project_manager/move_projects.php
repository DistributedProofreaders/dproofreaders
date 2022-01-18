<?php
$relPath = '../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'project_trans.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'misc.inc'); // get_enumerated_param(), html_safe()
include_once('projectmgr.inc');

require_login();

abort_if_not_manager();

$curr_state = get_enumerated_param($_GET, 'curr_state', null, $PROJECT_STATES_IN_ORDER);
$new_state = get_enumerated_param($_GET, 'new_state', null, $PROJECT_STATES_IN_ORDER);
$projectids = [];
foreach (explode(',', @$_GET['projects']) as $projectid) {
    validate_projectID($projectid);
    $projectids[] = $projectid;
}

echo "<pre>\n";

echo sprintf(_("Moving projects from '%1\$s' to '%2\$s'..."), $curr_state, $new_state);
echo "\n\n";

foreach ($projectids as $projectid) {
    echo "\n";
    echo "$projectid ...\n";

    try {
        $project = new Project($projectid);
    } catch (NonexistentProjectException $exception) {
        echo "    " . _("does not exist.") . "\n";
        continue;
    }

    if (!$project->can_be_managed_by_current_user) {
        echo "    " . _("You are not authorized to manage this project.") . "\n";
        continue;
    }

    if ($project->state != $curr_state) {
        // TRANSLATORS: %1$s is a project name, %2$s and %3$s are project states
        echo "    " . sprintf(_('%1$s is no longer in %2$s. Now in %3$s.'), $project->nameofwork, $curr_state, $project->state) . "\n";
        continue;
    }

    $error_msg = project_transition($projectid, $new_state, $pguser);
    if ($error_msg) {
        echo "    " . html_safe($project->nameofwork) . "\n";
        echo "    $error_msg\n";
        continue;
    }

    // TRANSLATORS: %s is a project name
    echo "    " . sprintf(_("%s successfully moved."), html_safe($project->nameofwork)) . "\n";
}

echo "</pre>\n";

echo "<hr>\n";
echo "<p>" . sprintf(_("Back to the <a href='%s'>project manager</a> page."), "projectmgr.php") . "</p>\n";
