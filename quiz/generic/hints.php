<?php 
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc'); // get_enumerated_param
include_once($relPath.'quizzes.inc'); // get_quiz_page_id_param
include_once('../small_theme.inc'); // output_small_header

require_login();

$quiz_page_id = get_quiz_page_id_param($_REQUEST, 'quiz_page_id');
$error = get_param_matching_regex($_REQUEST, 'error', NULL, '/^\w+$/');
$number = get_integer_param($_REQUEST, 'number', NULL, 0, NULL);

include "./quiz_page.inc"; // qp_echo_hint_html

$quiz = get_Quiz_containing_page($quiz_page_id);

output_small_header($quiz);

// A margin
echo "<div style='margin: .5em;'>";

qp_echo_hint_html($error, $number);

echo " </div>\n";

// vim: sw=4 ts=4 expandtab
