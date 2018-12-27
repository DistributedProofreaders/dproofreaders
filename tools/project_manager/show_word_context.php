<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'wordcheck_engine.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'misc.inc'); // attr_safe(), html_safe()
include_once($relPath.'LPage.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'misc.inc'); // array_get(), get_integer_param()
include_once('./post_files.inc');
include_once("./word_freq_table.inc");

require_login();

define("LAYOUT_HORIZ", "h");
define("LAYOUT_VERT",  "v");

set_time_limit(0); // no time limit

$projectid = validate_projectID('projectid', @$_GET['projectid']);
$encWord   = @$_GET["word"];
$word      = rtrim(decode_word($encWord));

enforce_edit_authorization($projectid);

// get the correct layout
$userSettings =& Settings::get_Settings($pguser);
// if not set gives LAYOUT_HORIZ
$default_layout =  $userSettings->get_value("show_word_context", LAYOUT_HORIZ);

$layout_choices = array(LAYOUT_HORIZ, LAYOUT_VERT);
$layout = get_enumerated_param($_GET, 'layout', $default_layout, $layout_choices);
if($layout != $default_layout)
{
    $userSettings->set_value("show_word_context", $layout);
}

$wordInstances =  get_integer_param($_GET, 'wordInstances', 20, 0, null);

// $frame determines which frame we're operating from
// 'master' - we're the master frame
//  'left'  - we're the left frame with the text
// 'right'  - we're the right frame for the image
$frame = get_enumerated_param($_GET, 'frame', 'master', array('master', 'left', 'right'));

if($frame=="master") {
    slim_header_frameset(_("Word Context"));
    if($layout == LAYOUT_HORIZ) $frameSpec='rows="30%,70%"';
    else $frameSpec='cols="30%,70%"';
?>
<frameset <?php echo $frameSpec; ?>>
<frame name="worddetailframe" src="show_word_context.php?projectid=<?php echo $projectid; ?>&amp;word=<?php echo $encWord; ?>&amp;wordInstances=<?php echo $wordInstances; ?>&amp;frame=left">
<frame name="imageframe" src="show_word_context.php?projectid=<?php echo $projectid; ?>&amp;word=<?php echo $encWord; ?>&amp;wordInstances=<?php echo $wordInstances; ?>&amp;frame=right">
</frameset>
<noframes>
<?php echo _("Your browser currently does not display frames!"); ?>
</noframes>
<?php
    exit;
}


// now load data in the left frame
if($frame=="left") {

    slim_header(_("Suggestion Detail"));

    $project_name = get_project_name($projectid);
    // TRANSLATORS: %1$s is a word, %2$s is the project name.
    echo "<h2>", sprintf(_("Context for '%1\$s' in %2\$s"), $word, $project_name), "</h2>";

    echo "<p>";

    echo "<a target='_PARENT' href='" . attr_safe($_SERVER['PHP_SELF']) . "?projectid=$projectid&amp;word=$encWord&amp;wordInstances=$wordInstances&amp;";
    if($layout == LAYOUT_HORIZ)
        echo "layout=" . LAYOUT_VERT . "'>" . _("Change to vertical layout");
    else
        echo "layout=" . LAYOUT_HORIZ . "'>" . _("Change to horizontal layout");
    echo "</a>";
    echo "</p>";

    // get the latest possible round
    $last_possible_round = get_Round_for_round_number(MAX_NUM_PAGE_EDITING_ROUNDS);
    $pages_res = page_info_query($projectid,$last_possible_round->id,'LE');
    // iterate through all the pages until we find $wordInstances of the word
    // we're looking for
    $foundInstances = 0;
    while( list($page_text,$page,$proofer_names) = page_info_fetch($pages_res) ) {
        // get a context string
        list($context_strings,$totalLines)=_get_word_context_from_text($page_text,$word);
        if(!count($context_strings)) continue;

        echo "<p>";
        echo "<b>" . _("Page") . "</b>: <a href='displayimage.php?project=$projectid&amp;imagefile=$page&amp;showreturnlink=0' target='imageframe'>$page</a><br>";
        foreach($context_strings as $lineNum => $context_string) {
            $context_string=_highlight_word(html_safe($context_string, ENT_NOQUOTES),$word);
            echo "<b>", _("Line"), "</b>: ", 
                // TRANSLATORS: %1$d is the approximate line number, %2$d is the total number of lines
                sprintf(_('~%1$d of %2$d'), $lineNum, $totalLines),
                " &nbsp; | &nbsp; ";
            echo "<b>" . _("Context") . "</b>:<br><span class='mono'>$context_string</span><br>";
        }
        echo "</p>";
        echo "<hr>";

        $foundInstances++;

        if($foundInstances>=$wordInstances) break;
    }
    mysqli_free_result($pages_res);

    if($foundInstances>=$wordInstances)
        echo "<p>" . _("More instances were found, stopping after a small sample.") . "</p>";

    exit;
}

if($frame=="right") {
    slim_header(_("Image Frame"));
    echo "<p>" . _("Select one of the page links to view the page image (scan).") . "</p>";
    exit;
}

// vim: sw=4 ts=4 expandtab
?>
