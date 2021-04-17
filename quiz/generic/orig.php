<?php
$relPath = '../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'quizzes.inc'); // get_quiz_page_id_param

require_login();

$quiz_page_id = get_quiz_page_id_param($_REQUEST, 'quiz_page_id');

include './quiz_page.inc'; // qp_page_image_path

slim_header("", ['css_data' => 'body { background-color: #ffffff; }']);
echo "<img src='" . qp_page_image_path() . "' alt=''>";
