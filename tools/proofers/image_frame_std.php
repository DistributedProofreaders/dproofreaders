<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'http_headers.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'abort.inc');
include_once('PPage.inc');

require_login();

try {
    $ppage = get_requested_PPage($_GET);
} catch (ProjectException | ProjectPageException $exception) {
    abort($exception->getMessage());
}
$user = User::load_current();

$storage_key = "proof-std" . (($user->profile->i_layout == 1) ? "-v" : "-h");

$image_data = json_encode([
    "imageUrl" => $ppage->url_for_image(),
    "storageKey" => $storage_key,
    "align" => "C",
]);

$header_args = [
    "js_modules" => ["$code_url/tools/proofers/proof_image.js"],
    "js_data" => "
            var imageData = $image_data;
        ",
    "body_attributes" => 'id="standard_interface_image"',
];

slim_header("Image Frame", $header_args);

echo "<div style='height: 100vh;'>
<div id='image-view'>
</div></div>";
