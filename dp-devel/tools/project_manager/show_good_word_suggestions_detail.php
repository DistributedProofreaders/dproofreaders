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
include_once($relPath.'Stopwatch.inc');
include_once('./post_files.inc');
include_once("./word_freq_table.inc");

define("LAYOUT_HORIZ", 1);
define("LAYOUT_VERT",  2);

$datetime_format = _("%A, %B %e, %Y at %X");

$watch = new Stopwatch;
$watch->start();

set_time_limit(0); // no time limit

$projectid  = $_GET["projectid"];
$encWord    = $_GET["word"];
$word       = decode_word($encWord);
$timeCutoff = array_get($_GET,"timeCutoff",0);

enforce_edit_authorization($projectid);

// get the correct layout
$layout = array_get($_GET,"layout",@$_SESSION["show_good_word_suggestions_detail"]["layout"]);
if(empty($layout)) $layout=LAYOUT_HORIZ;
$_SESSION["show_good_word_suggestions_detail"]["layout"]=$layout;

// $frame determines which frame we're operating from
//    none - we're the master frame
//  'left' - we're the left frame with the text
// 'right' - we're the right frame for the image
$frame = array_get($_GET,"frame","master");

if($frame=="master") {
    slim_header(_("Suggestion Detail"),TRUE,FALSE);
    if($layout == LAYOUT_HORIZ) $frameSpec='rows="30%,70%"';
    else $frameSpec='cols="30%,70%"';
?>
</head>
<frameset <?=$frameSpec;?>>
<frame name="worddetailframe" src="show_good_word_suggestions_detail.php?projectid=<?=$projectid;?>&amp;word=<?=$encWord;?>&amp;timeCutoff=<?=$timeCutoff;?>&amp;frame=left">
<frame name="imageframe" src="show_good_word_suggestions_detail.php?projectid=<?=$projectid;?>&amp;word=<?=$encWord;?>&amp;timeCutoff=<?=$timeCutoff;?>&amp;frame=right">
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

    // load the suggestions
    $suggestions = load_project_good_word_suggestions($projectid,$timeCutoff,TRUE);
    if(!is_array($suggestions)) {
        $messages[] = sprintf(_("Unable to load suggestions: %s"),$suggestions);
    }

    // parse the suggestions complex array
    // it was pulled in the raw format
    $word_suggestions = array();
    foreach( $suggestions as $suggestion) {
        list($time,$round,$page,$proofer,$words)=$suggestion;
        if(in_array($word,$words)) {
            array_push($word_suggestions,$suggestion);
        }
    }

    slim_header(_("Suggestion Detail"),TRUE,TRUE);

    $project_name = get_project_name($projectid);
    echo "<h2>Suggestion context for '$word' in $project_name</h2>";

    echo_word_freq_style();

    echo "<p>";
    echo "<a href='show_word_context.php?projectid=$projectid&amp;&amp;word=$encWord&amp;return=show_good_word_suggestions.php' target='_PARENT'>" .
         _("Show full context set for this word") . "</a>";

    echo " | ";
    echo "<a target='_PARENT' href='" . $_SERVER["PHP_SELF"] . "?projectid=$projectid&amp;word=$encWord&amp;wordInstances=$wordInstances&amp;return=$return&amp;timeCutpff=$timeCutoff&amp;";
    if($layout == LAYOUT_HORIZ)
        echo "layout=" . LAYOUT_VERT . "'>" . _("Change to vertical layout");
    else
        echo "layout=" . LAYOUT_HORIZ . "'>" . _("Change to horizontal layout");
    echo "</a>";
    echo "</p>";

    foreach($word_suggestions as $suggestion) {
        list($time,$round,$page,$proofer,$words)=$suggestion;
        // get the phpBB user ID for the proofer
        $userid=_get_uid_from_username($proofer);
        // get a context string
        list($context_strings,$totalLines)=_get_word_context_on_page($projectid,$page,$round,$word);
        if(!count($context_strings)) continue;

        echo "<p><b>" . _("Date") . "</b>: " . strftime($datetime_format,$time) . "<br>";
        echo "<b>" . _("Round") . "</b>: $round &nbsp; | &nbsp; ";
        echo "<b>" . _("Proofer") . "</b>: <a href='$forums_url/privmsg.php?mode=post&amp;u=$userid' target='_TOP'>$proofer</a><br>";
        echo "<b>" . _("Page") . "</b>: <a href='displayimage.php?project=$projectid&amp;imagefile=$page&amp;showreturnlink=0' target='imageframe'>$page</a><br>";
        foreach($context_strings as $lineNum => $context_string) {
            $context_string=_highlight_word($context_string,$word);
            echo "<b>" . _("Line") . "</b>: ~$lineNum of $totalLines &nbsp; | &nbsp; ";
            echo "<b>" . _("Context") . "</b>:<br><span class='mono'>$context_string</span><br>";
        }
        echo "</p>";
        echo "<hr>";

    }

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

function _get_word_context_on_page($projectid,$page,$round,$word) {
    $lpage = new LPage($projectid, $page, "$round.page_saved", 0);
    $page_text = $lpage->get_text();
    return _get_word_context_from_text($page_text,$word);
}

// vim: sw=4 ts=4 expandtab
?>
