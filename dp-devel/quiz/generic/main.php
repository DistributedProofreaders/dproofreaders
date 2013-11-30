<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'quizzes.inc'); // get_quiz_page_id_param get_quiz_id_param

$page_id = get_quiz_page_id_param($_REQUEST, 'type');
$quiz_id = get_quiz_id_param($_REQUEST, 'quiz_id');

include "./data/qd_${page_id}.inc"; // $browser_title

get_activity_type_for_quiz($quiz_id);
if ($activity_type_for_quiz == "proof")
{
    $round_id = 'P1';
}
else
{
    $round_id = 'F1';
}
$round = get_Round_for_round_id($round_id);
assert( !is_null($round) );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<title>
<?php echo "$site_abbreviation";
    if (isset($browser_title)) { echo ": $browser_title"; } ?>
</title>
<META http-equiv="Content-Type" content="text/html; charset=<?php echo "$charset";?>">
<script language="JavaScript" type="text/javascript" src="../../tools/proofers/dp_proof.js?1.62"></script>
<script language="JavaScript" type="text/javascript" src="../../tools/proofers/dp_scroll.js?1.18"></script>
</head>
<frameset rows="*,73">
<frameset cols="60%,*">
<frameset name="left" rows="40%,*">
<frame name="imageframe" src="orig.php?type=<?php echo $page_id;?>">
<frame name="proofframe" src="proof.php?type=<?php echo $page_id;?>&quiz_id=<?php echo $quiz_id;?>">
</frameset>
<frame name="right" src="right.php?type=<?php echo $page_id;?>&quiz_id=<?php echo $quiz_id;?>">
</frameset>
<frame name="menuframe" src="../../tools/proofers/ctrl_frame.php?round_id=<?php echo $round->id; ?>" marginwidth="2" marginheight="2" frameborder="0">
</frameset>
</html>
