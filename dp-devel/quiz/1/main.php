<? $relPath='../../../pinc/';
include_once($relPath.'v_site.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'prefs_options.inc');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<title>DP -- Proofing Quiz</title>
<META http-equiv="Content-Type" content="text/html; charset=<?echo "$charset";?>">
</head>
<frameset cols="60%,*">
<frameset name="left" rows="40%,*">
<frame src="orig.php">
<frame name="pf" src="proof.php">
</frameset>
<frame name="right" src="right.php">
</frameset>
</html>
