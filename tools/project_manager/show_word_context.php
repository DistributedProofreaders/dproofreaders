<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'wordcheck_engine.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'misc.inc'); // attr_safe(), html_safe()
include_once($relPath.'LPage.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'misc.inc'); // array_get(), get_integer_param()
include_once($relPath.'page_controls.inc'); // get_proofreading_interface_data_js()
include_once($relPath.'control_bar.inc'); // get_control_bar_texts()
include_once('./post_files.inc'); // page_info_query()
include_once("./word_freq_table.inc"); // enforce_edit_authorization(), decode_word()

require_login();

define("MAX_WORD_INSTANCES", 100);

set_time_limit(0); // no time limit

$projectid = get_projectID_param($_GET, 'projectid');
$encWord = @$_GET["word"];
$word = rtrim(decode_word($encWord));

enforce_edit_authorization($projectid);

$wordInstances = get_integer_param($_GET, 'wordInstances', 20, 0, MAX_WORD_INSTANCES);

$details = json_encode([
    "projectid" => $projectid,
    'storageKey' => 'show_word_context',
]);

$header_args = [
    "js_files" => [
        "$code_url/scripts/splitControl.js",
        "$code_url/scripts/control_bar.js",
        "$code_url/scripts/widgets.js",
        "$code_url/scripts/page_browse.js",
        "$code_url/tools/project_manager/show_word_context.js",
    ],
    "js_data" => get_proofreading_interface_data_js() .
        get_control_bar_texts() . "
        var showWordContext = $details;",

    "body_attributes" => 'class="no-margin overflow-hidden" style="height: 100vh; width: 100vw;"',
];

slim_header(_("Word Context"), $header_args);
echo "<div id='show_word_context_container' style='flex: auto; width: 100%; height: 100%;'>";
echo "<div class='overflow-auto'>";
echo "<div style='padding: 0.5em;'>";

$project_name = get_project_name($projectid);
// TRANSLATORS: %1$s is a word, %2$s is the project name.
echo "<h2>", sprintf(_("Context for '%1\$s' in %2\$s"), $word, $project_name), "</h2>";

echo "<p>";
echo "<a href='javascript:void(0)' id='h_v_switch'></a>";
echo "</p>";

echo "<form method='GET' id='wordInstancesForm'>";
echo "<input type='hidden' name='projectid' value='$projectid'>";
echo "<input type='hidden' name='word' value='$encWord'>";
echo "<label for='wordInstancesSelect'>" . _("Number of word context results: ") . "</label>";
echo "<select id='wordInstancesSelect' name='wordInstances' style='margin-left: 2px;' onchange='document.getElementById(\"wordInstancesForm\").submit()'>";
foreach (range(10, MAX_WORD_INSTANCES, 10) as $option) {
    echo "<option value='$option'" . ($option == $wordInstances ? " selected" : "") . ">$option</option>";
}
echo "</select>";
echo "</form>";


// get the latest possible round
$last_possible_round = get_Round_for_round_number(MAX_NUM_PAGE_EDITING_ROUNDS);
$pages_res = page_info_query($projectid, $last_possible_round->id, 'LE');
// iterate through all the pages until we find $wordInstances of the word
// we're looking for
$foundInstances = 0;
while ([$page_text, $page, $proofer_names] = page_info_fetch($pages_res)) {
    // get a context string
    [$context_strings, $totalLines] = _get_word_context_from_text($page_text, $word);
    if (!count($context_strings)) {
        continue;
    }

    echo "<p>";
    echo "<b>" . _("Page") . "</b>: <a href='javascript:void(0)' class='page-select' data-value='$page'>$page</a><br>";
    foreach ($context_strings as $lineNum => $context_string) {
        $context_string = _highlight_word(html_safe($context_string, ENT_NOQUOTES), $word);
        echo "<b>", _("Line"), "</b>: ",
        // TRANSLATORS: %1$d is the approximate line number, %2$d is the total number of lines
        sprintf(_('~%1$d of %2$d'), $lineNum, $totalLines),
        " &nbsp; | &nbsp; ";
        echo "<b>" . _("Context") . "</b>:<br><span class='mono'>$context_string</span><br>";
    }
    echo "</p>";
    echo "<hr>";

    $foundInstances++;

    if ($foundInstances >= $wordInstances) {
        break;
    }
}
mysqli_free_result($pages_res);

if ($foundInstances >= $wordInstances) {
    echo "<p>" . _("More instances were found; please choose how many to show from the drop-down.") . "</p>";
}
echo "</div></div>";

echo "<div id='page-browser' class='overflow-hidden'>";
echo "<p style='margin: 0.5em;'>" . _("Select one of the page links to view the page image (scan).") . "</p>";
echo "</div>";
echo "</div>";
