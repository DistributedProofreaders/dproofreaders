<?php
$relPath="./../../pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'stages.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'project_edit.inc');
include_once('projectmgr.inc');

$projectid   = $_GET['project'];
$image       = $_GET['image'];
$L_round_num = $_GET['L_round_num'];
$R_round_num = $_GET['R_round_num'];

$title = sprintf( _('Difference for page %s'), $image );

$no_stats=1;
$extra_args = array("css_data" => "span.custom_font {font-family: DPCustomMono2, Courier New, monospace;}");
theme($title, "header", $extra_args);

echo "<h3 align='center'>$title</h3>";


if ( $L_round_num == 0 )
{
	$L_text_column_name = 'master_text';
	$L_label = _('OCR');
}
else
{
	$L_round = get_Round_for_round_number($L_round_num);
	$L_text_column_name = $L_round->text_column_name;
	$L_label = $L_round->id;
}

{
	$R_round = get_Round_for_round_number($R_round_num);
	$R_text_column_name = $R_round->text_column_name;
	$R_label = $R_round->id;
}

$res = mysql_query("
	SELECT $L_text_column_name, $R_text_column_name
	FROM $projectid
	WHERE image='$image'
");
list($L_text, $R_text) = mysql_fetch_row($res);

// ---------------------------------------------------------

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
	$L_text,
	$R_text,
	$L_label,
	$R_label
);

theme("", "footer");
?>
