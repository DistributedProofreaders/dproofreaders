<?php
$relPath="../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'misc.inc'); // get_enumerated_param()
include_once($relPath.'ProofreadingToolbox.inc');
include_once($relPath.'key_titles.inc');

$round_id = get_enumerated_param($_GET, 'round_id', null, array_keys($Round_for_round_id_));
$round = get_Round_for_round_id($round_id);

$header_args = [
    "css_files" => [
        "$code_url/styles/toolbox.css",
    ],
    "js_files" => [
        "$code_url/scripts/api.js",
        "$code_url/scripts/misc.js",
        "$code_url/scripts/character_selector.js",
    ],
    "js_data" => "
        var apiUrl = '$code_url/api/';
        var keyTitles = $key_titles;
    ",
];
slim_header(_("Control Frame"), $header_args);

$toolbox = new ProofreadingToolbox();
$toolbox->output($round);
