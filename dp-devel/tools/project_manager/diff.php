<?php
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'RoundDescriptor.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'project_edit.inc');
include_once('projectmgr.inc');

$no_stats=1;
theme(_("Difference"), "header");

//define a CSS span class to make the diffs display in DPCustomMono2
echo "<style type='text/css'>";
echo "span.custom_font {font-family: DPCustomMono2, Courier New, monospace;}";
echo "</style>";


$projectid = $_GET['project'];

$fileid=$_GET['file'];
$round=$_GET['round'];

$prd = get_PRD_for_round($round);

$res = mysql_query("SELECT $prd->prevtext_column_name, $prd->text_column_name FROM $projectid WHERE fileid='$fileid'");

$txt=mysql_fetch_row($res);

class OutputPage {
	function addHTML($text) {
		echo $text;
	}
}

function wfMsg($key) {
	return ($key=="lineno")?_("Line $1"):$key;
}

$wgOut=new Outputpage();

include("DifferenceEngine.php");
DifferenceEngine::showDiff($txt[0],$txt[1],_("Old text").": $fileid",_("New text".": $fileid"));

theme("", "footer");
?>
