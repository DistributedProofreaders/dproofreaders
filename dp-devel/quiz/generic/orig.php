<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'quizzes.inc'); // get_quiz_page_id_param

$quiz_page_id = get_quiz_page_id_param($_REQUEST, 'type');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head>
<META http-equiv="Content-Type" content="text/html; charset=<?php echo "$charset";?>">
<title></title></head>
<body bgcolor='#ffffff'>
<img src="./images/qi_<?php echo $quiz_page_id; ?>.png" alt="">
</body>
</html>
