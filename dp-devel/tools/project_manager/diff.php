<?php
$relPath="./../../pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'stages.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'project_edit.inc');
include_once($relPath.'Project.inc');
include_once('projectmgr.inc');

$projectid   = $_GET['project'];
$image       = $_GET['image'];
$L_round_num = $_GET['L_round_num'];
$R_round_num = $_GET['R_round_num'];

$project = new Project( $projectid );
$state = $project->state;
$project_title = $project->nameofwork;

$title = sprintf( _('Difference for page %s'), $image );

$no_stats=1;
$extra_args = array("css_data" => "span.custom_font {font-family: DPCustomMono2, Courier New, monospace;}");
theme("$title: $project_title", "header", $extra_args);

echo "<h1>$project_title</h1>\n";
echo "<h2>$title</h2>\n";

do_navigation($projectid, $image, $L_round_num, $R_round_num);

$url = "$code_url/project.php?id=$projectid&amp;expected_state=$state";
$label = _("Go to Project Page");

echo "<a href='$url'>$label</a>";
echo "<br>\n";



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

function do_navigation($projectid, $image, $L_round_num, $R_round_num) 
{
    $jump_to_js = "this.form.image.value=this.form.jumpto[this.form.jumpto.selectedIndex].value; this.form.submit();";

    echo "\n<form method='get' action='diff.php'>";
    echo "\n<input type='hidden' name='project' value='$projectid'>";
    echo "\n<input type='hidden' name='image' value='$image'>";
    echo "\n<input type='hidden' name='L_round_num' value='$L_round_num'>";
    echo "\n<input type='hidden' name='R_round_num' value='$R_round_num'>";
    echo "\nJump to: <select name='jumpto' onChange='$jump_to_js'>\n";

    $query = "SELECT image FROM $projectid ORDER BY image ASC";
    $res = mysql_query( $query) or die(mysql_error());
    $num_rows = mysql_num_rows($res);
    $prev_image = "";
    $next_image = "";
    // construct the dropdown
    for ($row=0; $row<$num_rows;$row++)
    {
        $this_val = mysql_result($res, $row, "image");
        echo "\n<option value='$this_val'";
        if ($this_val == $image)
        {
            echo " selected";
            if ( $row != 0 ) 
            {
                $prev_image = mysql_result($res, $row-1, "image");
            }
            if ( $row != $num_rows-1 ) 
            {
                $next_image = mysql_result($res, $row+1, "image");
            }
        }
        echo ">$this_val</option>";
    }
    echo "\n</select>";
    $previous_js = "this.form.image.value='$prev_image'; this.form.submit();";
    $next_js = "this.form.image.value='$next_image'; this.form.submit();";

    echo "\n<input type='button' value='Previous' onClick=\"$previous_js\"";
    if ( $prev_image == "" ) {
        echo " disabled";
    }
    echo ">";
    echo "\n<input type='button' value='Next' onClick=\"$next_js\"";
    if ( $next_image == "" ) {
        echo " disabled";
    }
    echo ">";

    echo "\n</form>";
}

?>
