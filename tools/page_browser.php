<?php
$relPath = '../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'user_is.inc'); // user_can_mentor_in_any_round()
include_once($relPath.'page_controls.inc'); // get_page_data_js()

require_login();

$title = _("Browse pages");
$mentorMode = user_can_mentor_in_any_round() ? "true" : "false";

$header_args = [
    "js_modules" => [
        "$code_url/tools/page_browser.js",
    ],
    "js_data" => get_proofreading_interface_data_js() . "
            var mentorMode = $mentorMode;
        ",
    "body_attributes" => 'class="no-margin overflow-hidden fix-full"',
];

slim_header($title, $header_args);

echo "<div id='page-browser'></div>";
