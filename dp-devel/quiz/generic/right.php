<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc'); // get_enumerated_param
include_once($relPath.'quizzes.inc'); // $valid_page_ids

include_once('../small_theme.inc');

$page_id = get_enumerated_param($_REQUEST, 'type', NULL, $valid_page_ids);

include "./data/qd_${page_id}.inc"; // $welcome $constant_message
 
echo $welcome;
//If the quiz has a message to show all the time, put that in here
if (@$constant_message != "")
{
    echo $constant_message;
}
?>
</body>
</html>
