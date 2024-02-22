<?php
global $relPath, $forum_type, $projects_dir, $aspell_temp_dir;
global $waiting_projects_forum_idx, $projects_forum_idx, $pp_projects_forum_idx, $posted_projects_forum_idx,
$completed_projects_forum_idx, $deleted_projects_forum_idx;

$relPath = '../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'wordcheck_engine.inc');
include_once($relPath.'TableDocumentation.inc');
include_once($relPath.'../api/v1.inc');

include_once("phpunit_test_helpers.inc");
include_once("ProjectUtils.inc");

// Define top-level temporary directories
$projects_dir = $aspell_temp_dir = "/tmp";
