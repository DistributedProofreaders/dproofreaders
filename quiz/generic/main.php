<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'quizzes.inc'); // get_quiz_page_id_param

$quiz_page_id = get_quiz_page_id_param($_REQUEST, 'quiz_page_id');

include "./quiz_page.inc"; // qp_full_browser_title qp_round_id_for_pi_toolbox

$header_args = array(
    'js_files' => array(
        "$code_url/tools/proofers/dp_proof.js",
        "$code_url/tools/proofers/dp_scroll.js",
    ),
);

slim_header_frameset(qp_full_browser_title(), $header_args);
?>
<frameset rows="*,73">
<frameset cols="60%,*">
<frameset name="left" rows="40%,*">
<frame name="imageframe" src="orig.php?quiz_page_id=<?php echo $quiz_page_id;?>">
<frame name="proofframe" src="proof.php?quiz_page_id=<?php echo $quiz_page_id;?>">
</frameset>
<frame name="right" src="right.php?quiz_page_id=<?php echo $quiz_page_id;?>">
</frameset>
<frame name="menuframe" src="../../tools/proofers/ctrl_frame.php?round_id=<?php echo qp_round_id_for_pi_toolbox(); ?>" marginwidth="2" marginheight="2" frameborder="0">
</frameset>
