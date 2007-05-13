<?
$relPath="./../../pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'dp_main.inc');
include_once($relPath.'wordcheck_engine.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'LPage.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'slim_header.inc');
include_once('./post_files.inc');
include_once("./word_freq_table.inc");

define("LAYOUT_HORIZ", 1);
define("LAYOUT_VERT",  2);

set_time_limit(0); // no time limit

$projectid = $_GET["projectid"];
$encWord   = $_GET["word"];
$word      = rtrim(decode_word($encWord));
$return    = $_GET["return"];

enforce_edit_authorization($projectid);

// get the right layout
$layout = array_get($_GET,"layout",@$_SESSION["show_word_context"]["layout"]);
if(empty($layout)) $layout=LAYOUT_HORIZ;
$_SESSION["show_word_context"]["layout"]=$layout;


$wordInstances = min(array_get($_GET,"wordFreq",20),20);

// $frame determines which frame we're operating from
//    none - we're the master frame
//  'left' - we're the left frame with the text
// 'right' - we're the right frame for the image
$frame = array_get($_GET,"frame","master");

if($frame=="master") {
    slim_header(_("Word Context"),TRUE,FALSE);
    if($layout == LAYOUT_HORIZ) $frameSpec='rows="30%,70%"';
    else $frameSpec='cols="30%,70%"';
?>
</head>
<frameset <?=$frameSpec;?>>
<frame name="worddetailframe" src="show_word_context.php?projectid=<?=$projectid;?>&amp;word=<?=$encWord;?>&amp;wordInstances=<?=$wordInstances;?>&amp;return=<?=$return;?>&amp;frame=left">
<frame name="imageframe" src="show_word_context.php?projectid=<?=$projectid;?>&amp;word=<?=$encWord;?>&amp;wordInstances=<?=$wordInstances;?>&amp;return=<?=$return;?>&amp;frame=right">
</frameset>
<noframes>
<? _("Your browser currently does not display frames!"); ?>
</noframes>
</html>
<?
    exit;
}

// now load data in the left frame
if($frame=="left") {

    slim_header(_("Suggestion Detail"),TRUE,TRUE);

    $project_name = get_project_name($projectid);
    echo "<h2>Context for '$word' in $project_name</h2>";

    echo_word_freq_style();

    echo "<p>";

    echo "<a target='_PARENT' href='" . $_SERVER["PHP_SELF"] . "?projectid=$projectid&amp;word=$encWord&amp;wordInstances=$wordInstances&amp;return=$return&amp;";
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
            $context_string=_highlight_word($context_string,$word);
            echo "<b>" . _("Line") . "</b>: ~$lineNum of $totalLines &nbsp; | &nbsp; ";
            echo "<b>" . _("Context") . "</b>:<br><span class='mono'>$context_string</span><br>";
        }
        echo "</p>";
        echo "<hr>";

        $foundInstances++;

        if($foundInstances>=$wordInstances) break;
    }
    mysql_free_result($pages_res);

    if($foundInstances>=$wordInstances)
        echo "<p>" . _("More instances were found, stopping after a small sample.") . "</p>";

    echo "</body>";
    echo "</html>";
    exit;
}

if($frame=="right") {
    slim_header(_("Image Frame"),TRUE,TRUE);
    echo "<p>" . _("Select one of the page links to view the page image (scan).") . "</p>";
    echo "</body>";
    echo "</html>";
    exit;
}

// vim: sw=4 ts=4 expandtab
?>
