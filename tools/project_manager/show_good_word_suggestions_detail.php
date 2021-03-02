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
// if not set gives "horizontal"
$default_layout =  $userSettings->get_value("show_good_words_layout", "horizontal");

$details = json_encode([
    "layout" => $default_layout,
    "projectid" => $projectid,
    'storageKeyLayout' => "show_good_words_layout",
]);

$header_args = [
    "js_files" => [
        "$code_url/scripts/splitControl.js",
        "$code_url/scripts/page_browse.js",
        "./show_word_context.js",
    ],
    "js_data" => get_proofreading_interface_data_js() . "
        var showWordContext = $details;",

    "body_attributes" => 'class="no-margin overflow-hidden" style="height: 100vh; width: 100vw"',
];
slim_header(_("Suggestion Detail"), $header_args);
echo "<div id='show_word_context_container' style='flex: auto;width: 100%;height: 100%'>";
echo "<div class='overflow-auto'>";
echo "<div style='padding: 0.5em'>";

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
echo "<a href='javascript:void(0)' id='h_v_switch'></a>";
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
    echo "<b>" . _("Page") . "</b>: <a href='javascript:void(0)' class='page-select' data-value='$page'>$page</a><br>";
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
echo "</div></div>";

echo "<div id='page-browser' class='overflow-hidden'>";
echo "<p style='margin: 0.5em'>" . _("Select one of the page links to view the page image (scan).") . "</p>";
echo "</div>";
echo "</div>";

function _get_word_context_on_page($projectid,$page,$round,$word) {
    $lpage = new LPage($projectid, $page, "$round.page_saved", 0);
    $page_text = $lpage->get_text();
    return _get_word_context_from_text($page_text,$word);
}

// vim: sw=4 ts=4 expandtab
