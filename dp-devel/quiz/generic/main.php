<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'quizzes.inc'); // get_quiz_page_id_param

$quiz_page_id = get_quiz_page_id_param($_REQUEST, 'type');

include "./quiz_page.inc"; // qp_full_browser_title qp_round_id_for_pi_toolbox

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<title>
<?php echo qp_full_browser_title(); ?>
</title>
<META http-equiv="Content-Type" content="text/html; charset=<?php echo "$charset";?>">
<script language="JavaScript" type="text/javascript" src="../../tools/proofers/dp_proof.js?1.62"></script>
<script language="JavaScript" type="text/javascript" src="../../tools/proofers/dp_scroll.js?1.18"></script>
</head>
<frameset rows="*,73">
<frameset cols="60%,*">
<frameset name="left" rows="40%,*">
<frame name="imageframe" src="orig.php?type=<?php echo $quiz_page_id;?>">
<frame name="proofframe" src="proof.php?type=<?php echo $quiz_page_id;?>">
</frameset>
<frame name="right" src="right.php?type=<?php echo $quiz_page_id;?>">
</frameset>
<frame name="menuframe" src="../../tools/proofers/ctrl_frame.php?round_id=<?php echo qp_round_id_for_pi_toolbox(); ?>" marginwidth="2" marginheight="2" frameborder="0">
</frameset>
</html>
