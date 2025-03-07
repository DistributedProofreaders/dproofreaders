<?php
global $relPath, $code_url, $projects_dir;

$relPath = '../../../pinc/';
include_once($relPath.'base.inc');

// Reconnect to the test DB
DPDatabase::close();
// Note: The values need to be kept in sync with other files in SETUP/tests/.
DPDatabase::connect("localhost", "dp_test_db", "dp_test_user", "dp_test_password");

include_once($relPath.'wordcheck_engine.inc');
include_once($relPath.'TableDocumentation.inc');
include_once($relPath.'../api/v1.inc');

include_once("phpunit_test_helpers.inc");
include_once("ProjectUtils.inc");

// Define top-level temporary directories
$projects_dir = "/tmp";
