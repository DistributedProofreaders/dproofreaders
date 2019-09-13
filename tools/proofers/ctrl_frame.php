<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'misc.inc'); // get_enumerated_param()
include_once($relPath.'ProofreadingToolbox.inc');

$round_id = get_enumerated_param($_GET, 'round_id', null, array_keys($Round_for_round_id_));
$round = get_Round_for_round_id($round_id);

$header_args = [
    "js_files" => [
        "$code_url/tools/proofers/character_selector.js",
    ],
    "body_attributes" => "class='control-frame'",
];
slim_header(_("Control Frame"), $header_args);

$toolbox = new ProofreadingToolbox();
$toolbox->output($round);
