<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'misc.inc'); // get_enumerated_param()
include_once($relPath.'Project.inc'); // validate_projectID()
include_once($relPath.'ProofreadingToolbox.inc');
include_once($relPath.'Project.inc'); // validate_projectID()

$round_id = get_enumerated_param($_GET, 'round_id', null, array_keys($Round_for_round_id_));
$round = get_Round_for_round_id($round_id);
// if this is used in a quiz there will not be a real projectid, 'quiz' will be
// used for the MRU local storage prefix and the character selector will
// provide the quiz pickers
$projectid = array_get($_GET, 'projectid', 'quiz');
if($projectid != "quiz")
{
    $projectid = validate_projectID('projectid', $projectid);
}

$mru_title = javascript_safe(_("Most recently used"));
// TRANSLATORS: This is an abbreviation for "Most recently used"
$mru_abbreviation = javascript_safe(_("MRU"));

$header_args = [
    "js_files" => [
        "$code_url/tools/proofers/character_selector.js",
        "$code_url/tools/proofers/toolbox.js",
    ],
    "js_data" => "
        var projectID = '$projectid';
        var mruTitle = '$mru_title';
        var mruAbbrev = '$mru_abbreviation';
    ",
    "body_attributes" => "class='control-frame'",
];
slim_header(_("Control Frame"), $header_args);

$toolbox = new ProofreadingToolbox();
$toolbox->output($projectid, $round);
