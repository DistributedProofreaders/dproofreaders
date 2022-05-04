<?php
global $relPath;
$relPath = '../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'wordcheck_engine.inc');
include_once($relPath.'TableDocumentation.inc');

$apiPath = '../../api/';
include_once($apiPath.'v1.inc');

include_once("phpunit_test_helpers.inc");
