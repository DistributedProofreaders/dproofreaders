<?
$relPath="./../../pinc/";
include_once($relPath.'dp_main.inc');
include_once($relPath.'http_headers.inc');
include_once('PPage.inc');
include_once('text_frame_std.inc');

// This script is invoked only for the standard interface now.
assert($userP['i_type'] == 0);

$ppage = get_requested_PPage($_GET);

echo_text_frame_std($ppage);

// vim: sw=4 ts=4 expandtab
?>
