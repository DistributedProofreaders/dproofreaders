<? $relPath='../../pinc/';
include_once($relPath.'v_site.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'prefs_options.inc');
include './data/qd_' . $_REQUEST['type'] . '.inc';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<title><?php echo $browser_title; ?></title>
<META http-equiv="Content-Type" content="text/html; charset=<?echo "$charset";?>">
</head>
<frameset cols="60%,*">
<frameset name="left" rows="40%,*">
<frame src="orig.php?type=<?php echo $_REQUEST['type']; ?>">
<frame name="pf" src="proof.php?type=<?php echo $_REQUEST['type']; ?>">
</frameset>
<frame name="right" src="right.php?type=<?php echo $_REQUEST['type']; ?>">
</frameset>
</html>
