<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'wordcheck_engine.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'LPage.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'misc.inc'); // attr_safe(), html_safe()
include_once($relPath.'Stopwatch.inc');
include_once($relPath.'misc.inc'); // get_integer_param(), get_enumerated_param()
include_once('./post_files.inc');
include_once("./word_freq_table.inc");

require_login();

define("LAYOUT_HORIZ", 1);
define("LAYOUT_VERT",  2);

$datetime_format = _("%A, %B %e, %Y at %X");

$watch = new Stopwatch;
$watch->start();

set_time_limit(0); // no time limit

$projectid  = validate_projectID('projectid', @$_REQUEST['projectid']);
$encWord    = $_GET["word"];
$word       = decode_word($encWord);
$timeCutoff = get_integer_param($_REQUEST, 'timeCutoff', 0, 0, null);

enforce_edit_authorization($projectid);

// get the correct layout
$default_layout = @$_SESSION["show_good_word_suggestions_detail"]["layout"];
if (is_null($default_layout)) {
    // The normal case for the session's first visit.
    $default_layout = LAYOUT_HORIZ;
} else if ($default_layout === LAYOUT_HORIZ || $default_layout === LAYOUT_VERT) {
    // The normal case for the session's subsequent visits.
} else {
    // I don't know how this could happen.
    $default_layout = LAYOUT_HORIZ;
    // Alternatively, we could raise an error or warning.
}
$layout = get_integer_param($_GET, 'layout', $default_layout, LAYOUT_HORIZ, LAYOUT_VERT);
$_SESSION["show_good_word_suggestions_detail"]["layout"] = $layout;

// $frame determines which frame we're operating from
// 'master' - we're the master frame
//   'left' - we're the left frame with the text
//  'right' - we're the right frame for the image
$frame = get_enumerated_param($_GET, 'frame', 'master', array('master', 'left', 'right'));

if($frame=="master") {
    slim_header_frameset(_("Suggestion Detail"));
    if($layout == LAYOUT_HORIZ) $frameSpec='rows="30%,70%"';
    else $frameSpec='cols="30%,70%"';
?>
<frameset <?php echo $frameSpec; ?>>
<frame name="worddetailframe" src="show_good_word_suggestions_detail.php?projectid=<?php echo $projectid; ?>&amp;word=<?php echo $encWord; ?>&amp;timeCutoff=<?php echo $timeCutoff; ?>&amp;frame=left">
<frame name="imageframe" src="show_good_word_suggestions_detail.php?projectid=<?php echo $projectid; ?>&amp;word=<?php echo $encWord; ?>&amp;timeCutoff=<?php echo $timeCutoff; ?>&amp;frame=right">
</frameset>
<noframes>
<?php echo _("Your browser currently does not display frames!"); ?>
</noframes>
<?php
    exit;
}

// now load data in the left frame
if($frame=="left") {

    // load the suggestions
    $suggestions = load_wordcheck_events($projectid,$timeCutoff);
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

    slim_header(_("Suggestion Detail"));

    $project_name = get_project_name($projectid);
    echo "<h2>", 
        // TRANSLATORS: %1$s is a word and %2$s is a project name.
        sprintf(_("Suggestion context for '%1\$s' in %2\$s"),
            $word, $project_name),
        "</h2>";

    echo_word_freq_style();

    echo "<p>";
    echo "<a href='show_word_context.php?projectid=$projectid&amp;word=$encWord' target='_PARENT'>" .
         _("Show full context set for this word") . "</a>";

    echo " | ";
    echo "<a target='_PARENT' href='" . attr_safe($_SERVER['PHP_SELF']) . "?projectid=$projectid&amp;word=$encWord&amp;timeCutoff=$timeCutoff&amp;";
    if($layout == LAYOUT_HORIZ)
        echo "layout=" . LAYOUT_VERT . "'>" . _("Change to vertical layout");
    else
        echo "layout=" . LAYOUT_HORIZ . "'>" . _("Change to horizontal layout");
    echo "</a>";
    echo "</p>";

    foreach($word_suggestions as $suggestion) {
        list($time,$round,$page,$proofer,$words)=$suggestion;
        // get a context string
        list($context_strings,$totalLines)=_get_word_context_on_page($projectid,$page,$round,$word);

        # If the word was suggested on a page, but then changed before
        # being saved, let the PM know about it.
        if(!count($context_strings))
        {
            echo "<p>" . sprintf(_('The word was suggested in round %1$s for page %2$s, but no longer exists in the saved text for that round.'), $round, $page) . "</p>";
            continue;
        }

        echo "<p><b>" . _("Date") . "</b>: " . strftime($datetime_format,$time) . "<br>";
        echo "<b>" . _("Round") . "</b>: $round &nbsp; | &nbsp; ";
        echo "<b>" . _("Proofreader") . "</b>: " . private_message_link($proofer) . "<br>";
        echo "<b>" . _("Page") . "</b>: <a href='displayimage.php?project=$projectid&amp;imagefile=$page&amp;showreturnlink=0' target='imageframe'>$page</a><br>";
        foreach($context_strings as $lineNum => $context_string) {
            $context_string=_highlight_word(html_safe($context_string, ENT_NOQUOTES), $word);
            echo "<b>" . _("Line") . "</b>: ", 
                // TRANSLATORS: %1$d is the approximate line number, and 
                // %2$d is the total number of lines when displaying the 
                // context of a word.
                sprintf(_('~%1$d of %2$d'), $lineNum, $totalLines),
                " &nbsp; | &nbsp; ";
            echo "<b>" . _("Context") . "</b>:<br><span class='mono'>$context_string</span><br>";
        }
        echo "</p>";
        echo "<hr>";

    }

    exit;
}

if($frame=="right") {
    slim_header(_("Image Frame"));
    echo "<p>" . _("Select one of the page links to view the page image (scan).") . "</p>";
    exit;
}

function _get_word_context_on_page($projectid,$page,$round,$word) {
    $lpage = new LPage($projectid, $page, "$round.page_saved", 0);
    $page_text = $lpage->get_text();
    return _get_word_context_from_text($page_text,$word);
}

// vim: sw=4 ts=4 expandtab
?>
