<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'wordcheck_engine.inc');
include_once($relPath.'LPage.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'Stopwatch.inc');
include_once($relPath.'page_controls.inc');
include_once($relPath.'control_bar.inc'); // get_control_bar_texts()
include_once('./post_files.inc');
include_once("./word_freq_table.inc");

require_login();

$watch = new Stopwatch();
$watch->start();

set_time_limit(0); // no time limit

$projectid = get_projectID_param($_REQUEST, 'projectid');
$encWord = array_get($_GET, "word", '');
$word = decode_word($encWord);
$timeCutoff = get_integer_param($_REQUEST, 'timeCutoff', 0, 0, null);
$imagefile = get_page_image_param($_GET, 'imagefile', true);
enforce_edit_authorization($projectid);

$details = json_encode([
    "projectid" => $projectid,
    'storageKey' => 'show_good_word_suggestions_detail',
]);

$header_args = [
    "js_files" => [
        "$code_url/scripts/splitControl.js",
        "$code_url/scripts/control_bar.js",
        "$code_url/scripts/view_splitter.js",
        "$code_url/scripts/text_view.js",
        "$code_url/scripts/page_browse.js",
        "$code_url/tools/project_manager/show_word_context.js",
    ],
    "js_data" => get_proofreading_interface_data_js() .
        get_control_bar_texts() . "
        var showWordContext = $details;",

    "body_attributes" => 'class="no-margin overflow-hidden" style="height: 100vh; width: 100vw;"',
];
slim_header(_("Suggestion Detail"), $header_args);
echo "<div id='show_word_context_container' style='flex: auto; width: 100%; height: 100%;'>";
echo "<div class='overflow-auto'>";
echo "<div style='padding: 0.5em;'>";

// load the suggestions
$suggestions = load_wordcheck_events($projectid, $timeCutoff);
if (!is_array($suggestions)) {
    $messages[] = sprintf(_("Unable to load suggestions: %s"), $suggestions);
}

// parse the suggestions complex array
// it was pulled in the raw format
$word_suggestions = [];
foreach ($suggestions as $suggestion) {
    [$time, $roundid, $page, $proofer, $words] = $suggestion;
    if (in_array($word, $words)) {
        array_push($word_suggestions, $suggestion);
    }
}

$project_name = get_project_name($projectid);
echo "<h2>",
sprintf(
    // TRANSLATORS: %1$s is a word and %2$s is a project name.
    _("Suggestion context for '%1\$s' in %2\$s"),
    $word,
    $project_name
),
"</h2>";

echo "<p>";
echo "<a href='show_word_context.php?projectid=$projectid&amp;word=$encWord'>" .
      _("Show full context set for this word") . "</a>";

echo " | ";
echo "<a href='javascript:void(0)' id='h_v_switch'></a>";
echo "</p>";

foreach ($word_suggestions as $suggestion) {
    [$time, $roundid, $page, $proofer, $words] = $suggestion;
    // get a context string
    [$context_strings, $totalLines] = _get_word_context_on_page($projectid, $page, $roundid, $word);

    // If the word was suggested on a page, but then changed before
    // being saved, let the PM know about it.
    if (!count($context_strings)) {
        echo "<p>" . sprintf(_('The word was suggested in round %1$s for page %2$s, but no longer exists in the saved text for that round.'), $roundid, $page) . "</p>";
        continue;
    }

    echo "<p><b>" . _("Date") . "</b>: " . icu_date_template("long+time", $time) . "<br>";
    echo "<b>" . _("Round") . "</b>: $roundid &nbsp; | &nbsp; ";
    echo "<b>" . _("Proofreader") . "</b>: " . private_message_link($proofer) . "<br>";
    echo "<b>" . _("Page") . "</b>: <a href='javascript:void(0)' class='page-select' data-value='$page'>$page</a><br>";
    foreach ($context_strings as $lineNum => $context_string) {
        $context_string = _highlight_word(html_safe($context_string, ENT_NOQUOTES), $word);
        echo "<b>" . _("Line") . "</b>: ",
        // TRANSLATORS: %1$d is the approximate line number, %2$d is the total number of lines
        sprintf(_('~%1$d of %2$d'), $lineNum, $totalLines),
        " &nbsp; | &nbsp; ";
        echo "<b>" . _("Context") . "</b>:<br><span class='mono'>$context_string</span><br>";
    }
    echo "</p>";
    echo "<hr>";
}
echo "</div></div>";

echo "<div id='page-browser' class='overflow-hidden'>";
echo "<p style='margin: 0.5em;'>" . _("Select one of the page links to view the page image (scan).") . "</p>";
echo "</div>";
echo "</div>";

function _get_word_context_on_page($projectid, $page, $roundid, $word)
{
    $page_text = Page_getText($projectid, $page, get_Round_for_round_id($roundid)->text_column_name);
    return _get_word_context_from_text($page_text, $word);
}
