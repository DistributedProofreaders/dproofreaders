<?php
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'project_edit.inc');
include_once('projectmgr.inc');

$no_stats=1;
theme("Difference", "header");

$projectid = $_GET['project'];

$fileid=$_GET['file'];
$round=$_GET['round'];

if($round==1) {
	$fields="master_text,round1_text";
} else {
	$fields="round1_text,round2_text";
}

$res = mysql_query("SELECT $fields FROM $projectid WHERE fileid='$fileid'");

$txt=mysql_fetch_row($res);

class OutputPage {
	function addHTML($text) {
		echo $text;
	}
}

function wfMsg($key) {
	return ($key=="lineno")?"Line $1":$key;
}

$wgOut=new Outputpage();

include("DifferenceEngine.php");
DifferenceEngine::showDiff($txt[0],$txt[1],"Old text","New text");

theme("", "footer");
?>
