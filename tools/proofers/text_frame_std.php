<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'http_headers.inc');
include_once('PPage.inc');
include_once('text_frame_std.inc');

require_login();

$ppage = get_requested_PPage($_GET);

echo_text_frame_std($ppage);
