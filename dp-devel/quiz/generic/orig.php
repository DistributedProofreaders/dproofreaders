<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'quizzes.inc'); // get_quiz_page_id_param

$quiz_page_id = get_quiz_page_id_param($_REQUEST, 'quiz_page_id');

include './quiz_page.inc'; // qp_page_image_path

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head>
<META http-equiv="Content-Type" content="text/html; charset=<?php echo "$charset";?>">
<title></title></head>
<body bgcolor='#ffffff'>
<img src="<?php echo qp_page_image_path(); ?>" alt="">
</body>
</html>
