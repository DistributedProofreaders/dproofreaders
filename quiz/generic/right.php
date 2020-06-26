<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'quizzes.inc'); // get_quiz_page_id_param
include_once('../small_theme.inc'); // output_small_header

require_login();

$quiz_page_id = get_quiz_page_id_param($_REQUEST, 'quiz_page_id');

include "./quiz_page.inc"; // qp_echo_introduction_html

$quiz = get_Quiz_containing_page($quiz_page_id);

output_small_header($quiz);

qp_echo_introduction_html();
