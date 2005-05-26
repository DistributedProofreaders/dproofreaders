<?php
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'project_edit.inc');
include_once('projectmgr.inc');

$no_stats=1;

$extra_args = array("css_data" => "span.custom_font {font-family: DPCustomMono2, Courier New, monospace;}");
theme(_("Difference"), "header", $extra_args);




$projectid = $_GET['project'];

$fileid=$_GET['file'];
$round_num=$_GET['round_num'];

$round = get_Round_for_round_number($round_num);

$res = mysql_query("SELECT $round->prevtext_column_name, $round->text_column_name, image FROM $projectid WHERE fileid='$fileid'");

$txt=mysql_fetch_row($res);
$image_name = $txt[2];

class OutputPage {
	function addHTML($text) {
		echo $text;
	}
}

function wfMsg($key) {
	return ($key=="lineno")?_("Line $1"):$key;
}

$wgOut=new Outputpage();

include("DifferenceEngine.inc");
DifferenceEngine::showDiff(
	$txt[0],
	$txt[1],
	_("Old text") . ": $image_name",
	_("New text") . ": $image_name"
);

theme("", "footer");
?>
