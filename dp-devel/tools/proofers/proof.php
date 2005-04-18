<?
$relPath="./../../pinc/";
include($relPath.'dp_main.inc');
include($relPath.'slim_header.inc');

// (User clicked on "Start Proofreading" link or
// one of the links in "Done" or "In Progress" trays.)

/* $_GET register_globals:
$project, $proofstate
*/

$res = mysql_fetch_assoc(mysql_query("
    SELECT nameofwork FROM projects WHERE projectid = '$project';
"));
$nameofwork = $res['nameofwork'];
$round = get_Round_for_project_state($proofstate);

//load the master frameset
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<?php

// Add name of round before nameofwork
$rn = $round->id;
$nameofwork = "[" . $rn . "] " . $nameofwork;
slim_header($nameofwork." - "._("Proofreading Interface"),FALSE,FALSE);
$frameGet="?" . $_SERVER['QUERY_STRING'];
?>
<script language="JavaScript" type="text/javascript" src="dp_proof.js?1.33.1"></script>
<script language="JavaScript" type="text/javascript" src="dp_scroll.js"></script>
</head>
<frameset rows="*,73">
<frame name="proofframe" src="<?PHP echo "$code_url/tools/proofers/proof_frame.php{$frameGet}";?>" marginwidth="2" marginheight="2" frameborder="0">
<frame name="menuframe" src="ctrl_frame.php" marginwidth="2" marginheight="2" frameborder="0">
</frameset>
<noframes>
<? _("Your browser currently does not display frames!"); ?>
</noframes>
</html>
