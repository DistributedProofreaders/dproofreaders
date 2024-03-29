<?php
$relPath = '../../pinc/';
include_once($relPath.'base.inc');
include_once('../small_theme.inc'); // output_small_header

require_login();

$quiz_page_id = get_quiz_page_id_param($_REQUEST, 'quiz_page_id');
$error = get_param_matching_regex($_REQUEST, 'error', null, '/^\w+$/');
$number = get_integer_param($_REQUEST, 'number', null, 0, null);

include "./quiz_page.inc"; // qp_echo_hint_html

$quiz = get_Quiz_containing_page($quiz_page_id);

output_small_header($quiz);

// A margin
echo "<div style='margin: .5em;'>";

qp_echo_hint_html($error, $number);

echo " </div>\n";
