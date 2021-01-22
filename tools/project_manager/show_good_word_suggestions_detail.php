<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'wordcheck_engine.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'LPage.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'misc.inc'); // array_get(), attr_safe(), html_safe()
include_once($relPath.'Stopwatch.inc');
include_once($relPath.'misc.inc'); // get_integer_param(), get_enumerated_param()
include_once('./post_files.inc');
include_once("./word_freq_table.inc");
include_once($relPath.'page_controls.inc');

require_login();

define("LAYOUT_HORIZ", "horizontal");
define("LAYOUT_VERT",  "vertical");

// TRANSLATORS: This is a strftime-formatted string for the date with year and time
$datetime_format = _("%A, %B %e, %Y at %X");

$watch = new Stopwatch;
$watch->start();

set_time_limit(0); // no time limit

$projectid  = get_projectID_param($_REQUEST, 'projectid');
$encWord    = array_get($_GET, "word", '');
$word       = decode_word($encWord);
$timeCutoff = get_integer_param($_REQUEST, 'timeCutoff', 0, 0, null);
$imagefile  = get_page_image_param($_GET, 'imagefile', true);
enforce_edit_authorization($projectid);

// get the correct layout
$userSettings =& Settings::get_Settings($pguser);
// if not set gives LAYOUT_HORIZ
$default_layout =  $userSettings->get_value("show_good_words_layout", LAYOUT_HORIZ);

$layout_choices = array(LAYOUT_HORIZ, LAYOUT_VERT);
$layout = get_enumerated_param($_GET, 'layout', $default_layout, $layout_choices);
if($layout != $default_layout)
{
    $userSettings->set_value("show_good_words_layout", $layout);
}

$details = json_encode([
    "layout" => $layout,
    "projectid" => $projectid,
    "timeCutoff" => $timeCutoff,
    "word" => $encWord
]);

$header_args = [
    "js_files" => [
        "$code_url/scripts/splitControl.js",
        "$code_url/scripts/page_browse.js",
        "./show_good_word_suggestions_detail.js",
    ],
    "js_data" => get_page_data_js($projectid, $imagefile, 'OCR', "") . get_proofreading_interface_data_js() . "
        var showGoodWordSuggestionsDetail = $details;",
    "body_attributes" => 'class="no-margin overflow-hidden" style="height: 100vh; width: 100vw"',
];
slim_header(_("Suggestion Detail"), $header_args);
echo "<div id='show_good_word_suggestions_detail_container' style='flex: auto;width: 100%;height: 100%'>";
echo "<div class='overflow-auto' style='padding: 0.5em'>";

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

$project_name = get_project_name($projectid);
echo "<h2>", 
    // TRANSLATORS: %1$s is a word and %2$s is a project name.
    sprintf(_("Suggestion context for '%1\$s' in %2\$s"),
        $word, $project_name),
    "</h2>";

echo "<p>";
echo "<a href='show_word_context.php?projectid=$projectid&amp;word=$encWord'>" .
      _("Show full context set for this word") . "</a>";

echo " | ";
echo "<a href='?projectid=$projectid&amp;word=$encWord&amp;timeCutoff=$timeCutoff&amp;";
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
    echo "<b>" . _("Page") . "</b>: <a href='?projectid=$projectid&amp;imagefile=$page&amp;word=$encWord&amp;timeCutoff=$timeCutoff'>$page</a><br>";
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
echo "</div>";

echo "<div class='overflow-hidden display-flex' style='flex-direction: column;'>";
if (isset($imagefile)) {
    echo "<div id='page-browser' class='overflow-hidden'></div>";
} else {
    echo "<p style='margin: 0.5em'>" . _("Select one of the page links to view the page image (scan).") . "</p>";
}
echo "</div>";
echo "</div>";

function _get_word_context_on_page($projectid,$page,$round,$word) {
    $lpage = new LPage($projectid, $page, "$round.page_saved", 0);
    $page_text = $lpage->get_text();
    return _get_word_context_from_text($page_text,$word);
}

// vim: sw=4 ts=4 expandtab
