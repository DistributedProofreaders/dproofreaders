<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'http_headers.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'control_bar.inc'); // get_control_bar_texts()
include_once('PPage.inc');

require_login();

$ppage = get_requested_PPage($_GET);
$user = User::load_current();

$js_files = [
    "$code_url/scripts/control_bar.js",
    "$code_url/tools/proofers/proof_image.js",
];

$storage_key = "proof-std" . (($user->profile->i_layout == 1) ? "-v" : "-h");

$image_data = json_encode([
    "imageUrl" => $ppage->url_for_image(),
    "storageKey" => $storage_key,
    "align" => "C",
]);

$header_args = [
    "js_files" => $js_files,
    "js_data" => get_control_bar_texts() . "
            var imageData = $image_data;
        ",
    "body_attributes" => 'id="standard_interface_image"',
];

slim_header("Image Frame", $header_args);

echo "<div style='height: 100vh; overflow: hidden;'>
<div id='image-view'>
</div></div>";
