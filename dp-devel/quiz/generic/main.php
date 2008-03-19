<? $relPath='../../pinc/';
include_once($relPath.'site_vars.php');
include_once($relPath.'theme.inc');
include_once($relPath.'prefs_options.inc');

$page_id = get_enumerated_param($_REQUEST, 'type', NULL, $valid_page_ids);
$quiz_id = get_enumerated_param($_REQUEST, 'quiz_id', NULL, $valid_quiz_ids);

include "./data/qd_${page_id}.inc";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<title><?php echo $browser_title; ?></title>
<META http-equiv="Content-Type" content="text/html; charset=<?echo "$charset";?>">
</head>
<frameset cols="60%,*">
<frameset name="left" rows="40%,*">
<frame src="orig.php?type=<?=$page_id?>">
<frame name="pf" src="proof.php?type=<?=$page_id?>&quiz_id=<?=$quiz_id?>">
</frameset>
<frame name="right" src="right.php?type=<?=$page_id?>&quiz_id=<?=$quiz_id?>">
</frameset>
</html>
