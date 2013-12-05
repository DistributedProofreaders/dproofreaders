<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'quizzes.inc'); // get_quiz_page_id_param
include_once('../small_theme.inc'); // output_small_header

$quiz_id = get_quiz_id_param($_REQUEST, 'quiz_id');
$quiz = get_Quiz_with_id($quiz_id);

$quiz_page_id = get_quiz_page_id_param($_REQUEST, 'type');

include "./data/qd_${quiz_page_id}.inc"; // $welcome $constant_message

output_small_header($quiz);

echo $welcome;
//If the quiz has a message to show all the time, put that in here
if (@$constant_message != "")
{
    echo $constant_message;
}
?>
</body>
</html>
