<?php
$relPath="./../../pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'stages.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'Project.inc');

$projectid   = $_GET['project'];
$image       = $_GET['image'];
$L_round_num = $_GET['L_round_num'];
$R_round_num = $_GET['R_round_num'];

$project = new Project( $projectid );
$state = $project->state;
$project_title = $project->nameofwork;

// --------------------------------------------------------------
// get information about this diff
if ( $L_round_num == 0 )
{
    $L_text_column_name = 'master_text';
    $L_user_column_name = "'none'";  // string literal, not column name
    $L_label = _('OCR');
}
else
{
    $L_round = get_Round_for_round_number($L_round_num);
    $L_text_column_name = $L_round->text_column_name;
    $L_user_column_name = $L_round->user_column_name;
    $L_label = $L_round->id;
}

{
    $R_round = get_Round_for_round_number($R_round_num);
    $R_text_column_name = $R_round->text_column_name;
    $R_user_column_name = $R_round->user_column_name;
    $R_label = $R_round->id;
}

$query = "
    SELECT $L_text_column_name, $R_text_column_name,
           $L_user_column_name, $R_user_column_name
    FROM $projectid
    WHERE image='$image'";

$res = mysql_query($query);
list($L_text, $R_text, $L_user, $R_user) = mysql_fetch_row($res);
$can_see_names_for_this_page = can_see_names_for_page($projectid, $image);
if ( $can_see_names_for_this_page) {
    $L_label .= " ($L_user)";
    $R_label .= " ($R_user)";
}
// now have the image, users, labels etc all set up
// -----------------------------------------------------------------------------

$title = sprintf( _('Difference for page %s'), $image );

$no_stats=1;
$extra_args = array("css_data" => "span.custom_font {font-family: DPCustomMono2, Courier New, monospace;}");
theme("$title: $project_title", "header", $extra_args);

echo "<h1>$project_title</h1>\n";
echo "<h2>$title</h2>\n";

do_navigation($projectid, $image, $L_round_num, $R_round_num, $L_user);

$url = "$code_url/project.php?id=$projectid&amp;expected_state=$state";
$label = _("Go to Project Page");

echo "<a href='$url'>$label</a>";
echo "<br>\n";

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

function do_navigation($projectid, $image, $L_round_num, $R_round_num, $L_user) 
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
    $got_there = FALSE;
    $got_to_next = FALSE;
    // construct the dropdown; work out where previous and next buttons should take us
    for ($row=0; $row<$num_rows;$row++)
    {
        $this_val = mysql_result($res, $row, "image");
        echo "\n<option value='$this_val'";
        if ($this_val == $image)
        {
            echo " selected";  // make the correct element of the drop down selected
            $got_there = TRUE;
        }
        else if ($got_there && ! $got_to_next) {
            // we are at the one after the current one
            $got_to_next = TRUE;
            $next_image = $this_val;
        }
        if ( !$got_there )
        {
            $prev_image = $this_val;  // keep track of what the previous image was
        }
        echo ">$this_val</option>";
    }
    echo "\n</select>";
    $previous_js = "this.form.image.value='$prev_image'; this.form.submit();";
    $next_js = "this.form.image.value='$next_image'; this.form.submit();";

    echo "\n<input type='button' value='" . _("Previous") . "' onClick=\"$previous_js\"";
    if ( $prev_image == "" ) {
        echo " disabled";
    }
    echo ">";
    echo "\n<input type='button' value='" . _("Next") . "' onClick=\"$next_js\"";
    if ( $next_image == "" ) {
        echo " disabled";
    }
    echo ">";

    echo "\n</form>";
}

// discover whether the user is allowed to see proofer names for this page
function can_see_names_for_page($projectid, $image)
{
    global $pguser, $Round_for_round_id_;

    // If requestor isn't logged in, they can't see any names.
    if ( $pguser == '' ) return FALSE;

    $project = new Project( $projectid );
    $answer = $project->names_can_be_seen_by_current_user; // can see for all pages
    if (! $answer) 
    {
        $fields = "";
        foreach ( $Round_for_round_id_ as $round_id => $round )
        {
            if ($fields != "") {
                $fields .= ", ";
            }
            $fields .= $round->user_column_name;
        }
        $query = "SELECT $fields from $projectid WHERE image = '$image'";
        $res = mysql_query($query) or die(mysql_error());
        $page_res = mysql_fetch_array($res);
        foreach ($page_res as $page_user) {
            if ($page_user == $pguser) {
                $answer = TRUE;
                break;
            }
        }
    }
    return $answer;
}

// vim: sw=4 ts=4 expandtab
?>
