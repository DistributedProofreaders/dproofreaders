<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'http_headers.inc');
include_once('PPage.inc');
include_once('text_frame_std.inc');

require_login();

undo_all_magic_quotes();

// This script is invoked only for the standard interface now.
// cpeel - disabled assert 2016-04-04. foofAid is loading this
// page even if the user's editing interface is set to enhanced
// resulting in PHP errors.
//assert($userP['i_type'] == 0);

$ppage = get_requested_PPage($_GET);

echo_text_frame_std($ppage);

// vim: sw=4 ts=4 expandtab
