<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'wordcheck_engine.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'misc.inc'); // get_enumerated_param()
include_once('./post_files.inc');
include_once('./word_freq_table.inc');

require_login();

set_time_limit(0); // no time limit

$projectid = get_projectID_param($_REQUEST, 'projectid');

enforce_edit_authorization($projectid);

// $format determines what is presented from this page:
//   'html' - page is rendered with frequencies included
//   'file' - all words and frequencies are presented as a
//            downloaded file
// 'update' - update the list
$format = get_enumerated_param($_REQUEST, 'format', 'html', ['html', 'file', 'update']);

if ($format == "update") {
    $postedWords = parse_posted_words($_POST);

    $words = load_project_bad_words($projectid);
    $words = array_merge($words, $postedWords);
    save_project_bad_words($projectid, $words);

    $format = "html";
}

$title = _("Candidates for project's Bad Words List from diff analysis");
$page_text = _("Displayed below are words from this project that are likely stealth scannos based on changes proofreaders have made to the project text.");
$page_text .= " ";
$page_text .= _("The results list was generated by comparing the uploaded OCR text and the most recent text of each page. OCRed words that WordCheck would not currently flag, but some instances of which were changed by proofreaders and some instances of which still appear in the project text are included in the results. The results list also shows how often, and how, the word was changed by proofreaders.");

[$percent_changed, $instances_left, $messages, $instances_changed_to, $instances_changed] = _get_word_list($projectid);

if ($format == "file") {
    $filename = "${projectid}_project_scannos.txt";
    header("Content-type: text/plain");
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    // The cache-control and pragma is a hack for IE not accepting filenames
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');

    // Process the $instances_[changed_to|changed|left] arrays with the
    // prep_numeric_keys_for_multisort() function to ensure numeric keys
    // are evaluated as strings. This is necessary as the $percent_changed
    // array (iterated over below) has had this function used against it
    // and we use the $percent_changed keys as lookups into the $instances_*
    // arrays.
    prep_numeric_keys_for_multisort($instances_changed_to);
    prep_numeric_keys_for_multisort($instances_changed);
    prep_numeric_keys_for_multisort($instances_left);

    echo $title . "\r\n";
    echo sprintf(_("Project: %s"), get_project_name($projectid)) . "\r\n";
    echo "\r\n";
    echo strip_tags($page_text) . "\r\n";
    echo "\r\n";
    echo_page_instruction_text("bad", $format);
    echo "\r\n";
    echo_download_text($projectid, $format);
    echo "\r\n";
    // xgettext:no-php-format
    echo _("Format: [word] - [% changed] - [last changed to] - [# changed] - [# left] - [# in OCR]") . "\r\n";
    echo "\r\n";

    foreach ($percent_changed as $word => $percentChanged) {
        $percentChanged = $percent_changed[$word];
        $numChanged = $instances_changed[$word];
        $numLeft = @$instances_left[$word];
        $total = $numLeft + $numChanged;
        $corrected = $instances_changed_to[$word];
        echo "$word - $percentChanged - $corrected - $numLeft - $numChanged - $total\r\n";
    }

    // we're done here, exit
    exit;
}

// how many instances (ie: frequency sections) are there?
$instances = 1;
// what are the cutoff options?
$cutoffOptions = [0, 10, 20, 30, 40, 50, 60, 70, 80, 90];

output_header($title, NO_STATSBAR, ["js_data" => get_cutoff_script($cutoffOptions, $instances)]);
echo_page_header($title, $projectid);

// what is the initial cutoff frequency?
$initialFreq = getInitialCutoff(50, $cutoffOptions, $percent_changed);

echo "<p>$page_text</p>";

echo_page_instruction_text("bad", $format);

echo_any_warnings_errors($messages);

echo_download_text($projectid, $format);

// output customized cutoff text
$cutoff_text = sprintf(_("Words with fewer than <b><span id='current_cutoff'>%1\$d</span>%%</b> of the instances changed are not shown. Other cutoff options are available: %2\$s"), $initialFreq, get_cutoff_string($cutoffOptions, "%"));
echo "<p>$cutoff_text</p>\n";

$project_good_words = load_project_good_words($projectid);

$word_checkbox = build_checkbox_array($percent_changed);

$context_array = build_context_array_links($instances_left, $projectid);

// build the word_note and the instances_total arrays
$word_notes = [];
$instances_total = [];
foreach ($instances_left as $word => $freq) {
    if (in_array($word, $project_good_words)) {
        $word_notes[$word] = _("On project GWL");
    }
    $instances_total[$word] = $instances_changed[$word] + $instances_left[$word];
}
$word_notes["[[TITLE]]"] = _("Notes");

// xgettext:no-php-format
$percent_changed["[[TITLE]]"] = _("% Changed");
$percent_changed["[[STYLE]]"] = "text-align: right;";
$instances_changed_to["[[TITLE]]"] = _("Last changed to");
$instances_changed_to["[[CLASS]]"] = "mono";
$instances_changed["[[TITLE]]"] = _("# Changed");
$instances_changed["[[STYLE]]"] = "text-align: right;";
$instances_left["[[TITLE]]"] = _("# Left");
$instances_left["[[STYLE]]"] = "text-align: right;";
$instances_total["[[TITLE]]"] = _("# in OCR");
$instances_total["[[STYLE]]"] = "text-align: right;";
$context_array["[[TITLE]]"] = _("Show Context");

prep_numeric_keys_for_multisort($instances_changed_to);
prep_numeric_keys_for_multisort($instances_changed);
prep_numeric_keys_for_multisort($instances_left);
prep_numeric_keys_for_multisort($instances_total);
prep_numeric_keys_for_multisort($context_array);
prep_numeric_keys_for_multisort($word_notes);

echo_checkbox_selects(count($percent_changed));

$checkbox_form["projectid"] = $projectid;
echo_checkbox_form_start($checkbox_form);
echo_checkbox_form_submit(_("Add selected words to Bad Words List"));

printTableFrequencies($initialFreq, $cutoffOptions, $percent_changed, $instances--, [$instances_changed_to, $instances_changed, $instances_left, $instances_total, $context_array, $word_notes], $word_checkbox);

echo_checkbox_form_submit(_("Add selected words to Bad Words List"));
echo_checkbox_form_end();


//---------------------------------------------------------------------------
// supporting page functions

function _get_word_list($projectid)
{
    global $aspell_temp_dir;

    $ocr_filename = "$aspell_temp_dir/${projectid}_ocr.txt";
    $latest_filename = "$aspell_temp_dir/${projectid}_latest.txt";

    $messages = [];

    // get the OCR text
    // Note: If the code is changed to allow selecting of arbitrary round text
    //       instead of just the OCR round, edit_project_word_lists.php should
    //       be updated to allow this page to be accessed for those type-in
    //       projects with no OCR text.
    $pages_res = page_info_query($projectid, '[OCR]', 'LE');
    $all_page_text = get_page_texts($pages_res);
    // remove any formatting tags and add a final \r\n to each page-text
    // to ensure that there is whitespace between pages so they don't run together
    $all_page_text = preg_replace(['#<[/]?\w+>#', '#$#'], ['', "\r\n"], $all_page_text);
    file_put_contents($ocr_filename, $all_page_text);

    // get the latest project text of all pages up to last possible round
    $last_possible_round = get_Round_for_round_number(MAX_NUM_PAGE_EDITING_ROUNDS);
    $pages_res = page_info_query($projectid, $last_possible_round->id, 'LE');
    $all_page_text = get_page_texts($pages_res);
    // remove any formatting tags and add a final \r\n to each page-text
    // to ensure that there is whitespace between pages so they don't run together
    $all_page_text = preg_replace(['#<[/]?\w+>#', '#$#'], ['', "\r\n"], $all_page_text);
    file_put_contents($latest_filename, $all_page_text);

    $all_words_w_freq = get_distinct_words_in_text($all_page_text);
    // clean up unused variables
    unset($all_page_text);

    // make external call to wdiff
    exec("wdiff -3 $ocr_filename $latest_filename", $wdiff_output, $return_code);

    // check to see if wdiff wasn't found to execute
    if ($return_code == 127) {
        die("Error invoking wdiff to do the diff analysis. Perhaps it is not installed.");
    }
    if ($return_code == 2) {
        die("Error reported from wdiff while attempting to do the diff analysis.");
    }

    // clean up the temporary files
    if (is_file($ocr_filename)) {
        unlink($ocr_filename);
    }
    if (is_file($latest_filename)) {
        unlink($latest_filename);
    }

    // specify the separator between the wdiff segments
    $separator = '======================================================================';

    $possible_scannos_w_correction = [];
    $possible_scannos_w_count = [];

    // parse the incoming data one segment at a time
    // from the original datastream to conserve memory
    $lineIndex = 0;
    $totalLines = count($wdiff_output);
    while ($lineIndex < $totalLines) {
        // pull the next segment
        $segment = "";
        while ($lineIndex <= $totalLines) {
            $line = $wdiff_output[$lineIndex];
            $lineIndex++;
            if ($line == $separator) {
                break;
            }
            $segment .= "$line\n";
        }

        // note that we're handling the case where two adjacent
        // words are updated
        $ocr_words = $latest_words = [];

        // pull out the original word(s)
        if (preg_match("/\[-(.*?)-\]/", $segment, $matches)) {
            $ocr_words = $matches[1];
            $ocr_words = get_all_words_in_text($ocr_words);
        }

        // if we don't have any ocr_words (probably because
        // the correction spanned lines) then don't bother
        // continuing with this segment
        if (!count($ocr_words)) {
            continue;
        }

        // pull out the replacement(s)
        if (preg_match("/{\+(.*?)\+}/", $segment, $matches)) {
            $latest_words = $matches[1];
            $latest_words = get_all_words_in_text($latest_words);
        }

        // if the number of words isn't the same between the two
        // bail since we don't handle that case yet
        if (count($ocr_words) != count($latest_words)) {
            continue;
        }

        // process the words, handles multi-words strings
        for ($index = 0; $index < count($ocr_words); $index++) {
            $ocr_word = $ocr_words[$index];
            $latest_word = $latest_words[$index];

            // if the words are the same or one of them empty, skip it
            if (($ocr_word == $latest_word) || empty($ocr_word) || empty($latest_word)) {
                continue;
            }

            $possible_scannos_w_correction[$ocr_word] = $latest_word;
            @$possible_scannos_w_count[$ocr_word]++;
        }
    }
    // $wdiff_output can be very large
    // so unset it here to be nice for the rest of the function
    unset($wdiff_output);

    $possible_scannos = array_keys($possible_scannos_w_correction);

    // create a string of words to run through WordCheck
    $text_to_check = implode(" ", $possible_scannos);

    // run the list through WordCheck to see which it would flag
    [$possible_scannos_via_wordcheck, $languages, $messages] =
        get_bad_word_freqs_for_project_text($text_to_check, $projectid);

    // load site words
    $site_bad_words = load_site_bad_words_given_project($projectid);

    // load the project bad words
    $project_bad_words = load_project_bad_words($projectid);

    // remove words that WordCheck would flag
    $possible_scannos = array_diff($possible_scannos, array_keys($possible_scannos_via_wordcheck));

    // remove any scannos already on the site and project bad word lists
    $possible_scannos = array_diff($possible_scannos, $site_bad_words, $project_bad_words);

    // $possible_scannos doesn't have frequency info,
    // so start with the info in $all_words_w_freq,
    // and extract the items where the key matches a key in $possible_scannos
    $possible_scannos_w_freq = array_intersect_key($all_words_w_freq, array_flip($possible_scannos));

    $percent_changed = [];

    foreach ($possible_scannos as $word) {
        $count = $possible_scannos_w_count[$word];
        $totalInstances = @$possible_scannos_w_freq[$word] + $count;
        $percent_changed[$word] = sprintf("%0.2f", $count / $totalInstances * 100);
        if ($percent_changed[$word] >= 100 && $totalInstances == 1) {
            unset($percent_changed[$word]);
        }
    }

    // multisort screws up all-numeric words so we need to preprocess first
    prep_numeric_keys_for_multisort($percent_changed);

    // sort the list by frequency, then by word
    array_multisort(array_values($percent_changed), SORT_DESC, array_map('strtolower', array_keys($percent_changed)), SORT_ASC, $percent_changed);

    return [$percent_changed, $possible_scannos_w_freq, $messages, $possible_scannos_w_correction, $possible_scannos_w_count];
}
