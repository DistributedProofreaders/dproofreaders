<?php
// This script can be called by:
// * project transitions (for individual projects)
// * PMs for their own projects
// * SA and PFs for all projects
//
// It is actually 4 scripts in one file:
//   - Cleanup Pages: Bad project detection / action & reclaims MIA pages
//   - Promote Level: If a project finished a round, it sends it to next round
//   - Complete Project: If a project has completed all rounds, it sends it to post-processing
//   - Release Projects: If there are not enough projects available to end users,
//     it will release projects waiting to be released (autorelease())
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'job_log.inc');
include_once($relPath.'automodify.inc');
include_once($relPath.'autorelease.inc');

$one_project = get_projectID_param($_GET, 'project', true);
$refresh_url = $_GET['return_uri'] ?? 'projectmgr.php';

require_login();

if (!user_is_a_sitemanager() && !user_is_proj_facilitator()) {
    if ($one_project) {
        $project = new Project($one_project);
        if (!$project->can_be_managed_by_user($pguser)) {
            die('You are not authorized to invoke this script.');
        }
    } else {
        die('You are not authorized to invoke this script.');
    }
}

echo "<pre>\n";
automodify($one_project);
echo "</pre>\n";

if (!$one_project) {
    echo "<pre>\n";
    autorelease();
    echo "</pre>\n";
} else {
    echo "<META HTTP-EQUIV=\"refresh\" CONTENT=\"0 ;URL=$refresh_url\">";
}
