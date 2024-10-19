<?php
include_once($relPath.'links.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'faq.inc');
include_once($relPath.'unicode.inc');

use voku\helper\UTF8;

/**
 * Returns a string with javascript links to effect showing or hiding parts of
 * the word list
 *
 * @param array $cutoffOptions
 *   List of javascript cutoff options (eg: `[1,2,3,4,5]`)
 */
function get_cutoff_string($cutoffOptions, $labelSuffix = "")
{
    $cutoff_links = [];
    foreach ($cutoffOptions as $cutoff) {
        $cutoff_links[] = "<button type='button' class='lean_button cut_off_link' data-cutoff='$cutoff'>$cutoff$labelSuffix</button>";
    }
    return implode("|", $cutoff_links);
}

/**
 * Given the list of cutoff options and the word count
 * make sure the initial cutoff shows something
 */
function getInitialCutoff($idealCutoff, $cutoffOptions, $wordCount = null)
{
    // validate the idealCutoff is in $cutoffOptions
    if (!in_array($idealCutoff, $cutoffOptions)) {
        $idealCutoff = $cutoffOptions[count($cutoffOptions) - 1];
    }

    if ($wordCount == null) {
        return $idealCutoff;
    }

    $nextCutoff = $idealCutoff;

    // find the biggest freq, in the first element
    $freq = current($wordCount);

    // if the idealCutoff is smaller than the biggest freq
    // there's nothing to do so continue
    if ($idealCutoff < $freq) {
        return $idealCutoff;
    }

    // find the smallest cutoff possible that will show at least
    // one result
    foreach ($cutoffOptions as $cutoff) {
        if ($cutoff > $freq) {
            break;
        }
        $nextCutoff = $cutoff;
    }

    return $nextCutoff;
}

/**
 * Outputs the word frequency table
 *
 * @param int $initialFreq
 *   Initial cutoff frequency, anything after this is hidden
 * @param array $cutoffOptions
 *   List of javascript cutoff options (eg: array(1,2,3,4,5))
 * @param array $wordCount
 *   A table containing the word/frequency pairs already
 *   sorted and ready for display.
 *   Can contain the following special keys used for formatting
 *   ```
 *   [[TITLE]] - title of the column
 *   [[CLASS]] - class of the td
 *   [[STYLE]] - style of the td
 *   ```
 * @param array $auxData
 *   Array with one or more of the following `[$word => $data]`
 *   Can contain the following special keys used for formatting
 *   ```
 *   [[TITLE]] - title of the column
 *   [[CLASS]] - class of the td
 *   [[STYLE]] - style of the td
 *   ```
 * @param array $checkbox
 *   Array containing checkbox form elements for each word
 * @param string $instance
 *  String identifying the table for cases where there is more than one
 */
function printTableFrequencies(int $initialFreq, array $cutoffOptions, array $wordCount, ?array $auxData = null, ?array $checkbox = null, string $instance = 'only')
{
    // check that auxData is an array of arrays
    if (is_array($auxData) && !is_array(@$auxData[0])) {
        $auxData = [$auxData];
    }

    echo "<table class='basic striped' id='table_$instance'>";

    // output the table heading
    echo '<thead><tr><th>' . _('Word') . '</th>';
    if (isset($wordCount['[[TITLE]]'])) {
        echo '<th>' . $wordCount['[[TITLE]]'] . '</th>';
        unset($wordCount['[[TITLE]]']);
    } else {
        echo '<th>' . _('Freq') . '</th>';
    }
    if (is_array($auxData)) {
        for ($index = 0; $index < count($auxData); $index++) {
            echo "<th>" . $auxData[$index]['[[TITLE]]'] . "</th>";
        }
    }
    echo "</tr></thead>\n";

    // now start processing the rows
    $freqCutoff = 100000;
    $printedTbody = false;
    $nextCutoff = array_pop($cutoffOptions);
    // print it out in a real-HTML table
    foreach ($wordCount as $word => $freq) {
        // skip any 'words' that are actually metadata
        if (in_array($word, ["[[TITLE]]", "[[STYLE]]", "[[CLASS]]"])) {
            continue;
        }

        if ($freq < $nextCutoff) {
            while ($freq < $nextCutoff && count($cutoffOptions)) {
                $nextCutoff = array_pop($cutoffOptions);
            }
            $freqCutoff = $nextCutoff;

            if ($printedTbody) {
                echo "</tbody>\n";
                $printedTbody = false;
            }
        }

        if (!$printedTbody) {

            // hide any span sections past our initialFreq
            echo "<tbody data-freq-cutoff='$freqCutoff'";
            if ($freqCutoff < $initialFreq) {
                echo " style='display: none;'";
            }
            echo ">\n";
            $printedTbody = true;
        }

        // start of the row
        $line = "<tr>";

        if ($checkbox) {
            $line .= "<td class='mono' style='white-space: nowrap;'><label>" . $checkbox[$word] . " " . rtrim($word) . "</label></td>";
        } else {
            $line .= "<td>" . rtrim($word) . "</td>";
        }

        $line .= "<td class='right-align'>$freq</td>";

        // if available, process any aux data
        if (is_array($auxData)) {
            for ($index = 0; $index < count($auxData); $index++) {
                $line .= "<td";
                if (!empty($auxData[$index]['[[CLASS]]'])) {
                    $line .= " class='" . $auxData[$index]['[[CLASS]]'] . "'";
                }
                if (!empty($auxData[$index]['[[STYLE]]'])) {
                    $line .= " style='" . $auxData[$index]['[[STYLE]]'] . "'";
                }
                $line .= ">" . @$auxData[$index][$word] . "</td>";
            }
        }

        $line .= "</tr>\n";
        echo $line;
    }

    echo "</tbody>\n";

    echo '</table>';
}

/**
 * Prep array for numeric sort
 *
 * Arrays with numeric and string keys don't multisort correctly
 * Appending a space to the end of the numeric keys forces them
 * to strings and multisort works correctly
 */
function prep_numeric_keys_for_multisort(&$assocArray)
{
    if (!is_array($assocArray)) {
        return;
    }
    foreach ($assocArray as $key => $val) {
        if (!is_string($key)) {
            unset($assocArray[$key]);
            $assocArray["$key "] = $val;
        }
    }
}

function echo_page_header($title, $projectid)
{
    global $code_url;

    echo "<h1>$title</h1>";

    $project_text = sprintf(_("Project: %s"), html_safe(get_project_name($projectid)));
    echo "<h2>$project_text</h2>\n";
    echo "<p>"
      . "<a href='$code_url/tools/project_manager/edit_project_word_lists.php?projectid=$projectid'>"
      . _("Edit project word lists")
      . "</a> | "
      . return_to_project_page_link($projectid)
      . "</p>\n";
}

function get_project_name($projectid)
{
    $project = new Project($projectid);
    return $project->nameofwork;
}

function echo_page_instruction_text($wordListType, $format)
{
    if ($wordListType == "good") {
        $page_instructions_string = _("If you find words in the results list that should not be flagged in the WordCheck interface, add them to the project's Good Words List.");
        if ($format == "html") {
            $page_instructions_string .= " ";
            $page_instructions_string .= _("You can do this either by using the checkboxes provided, or by copying from below and pasting them into the Good Words input box on the Edit Project Word Lists page (taking care not to overwrite any existing words).");
        }
    } elseif ($wordListType == "bad") {
        $page_instructions_string = _("If you find any words in the results list that should be flagged in the WordCheck interface for extra attention by proofreaders, add them to the project's Bad Words List.");
        if ($format == "html") {
            $page_instructions_string .= " ";
            $page_instructions_string .= _("You can do this either by using the checkboxes provided, or by copying them from below and pasting them into the Bad Words input box on the Edit Project Word Lists page (taking care not to overwrite any existing words).");
        }
    } else {
        throw new UnexpectedValueException("Unexpected wordListType $wordListType");
    }

    if ($format == "html") {
        echo "<p>$page_instructions_string</p>\n";
    } else {
        echo $page_instructions_string . "\r\n";
    }
}

function echo_download_text($projectid, $format, $queryString = "")
{
    global $code_url;
    if ($format == "html") {
        $download_text = sprintf(_("You can <a href='%s'>download</a> a copy of the candidate word results list for offline analysis."), "?projectid=$projectid&amp;format=file&amp;$queryString");
        $faq_text = sprintf(_("See also the <a href='%s'>WordCheck FAQ</a> for more information on the WordCheck system."), get_faq_url("wordcheck-faq.php"));
        echo "<p>$download_text $faq_text</p>\n";
    } elseif ($format == "file") {
        $instructions = _("When you paste data into the input box on the Edit Project Word Lists page, the system will remove anything after the first trailing space so the frequency counts can be left in.");
        echo $instructions . "\r\n";
    }
}

function echo_cutoff_text($initialFreq, $cutoffOptions)
{
    $cutoff_text = sprintf(_("Words that appear fewer than <b><span id='current_cutoff'>%1\$d</span></b> times are not shown. Other cutoff options are available: %2\$s"), $initialFreq, get_cutoff_string($cutoffOptions));
    echo "<p>$cutoff_text</p>\n";
}

function echo_any_warnings_errors($messages)
{
    if (count($messages) > 0) {
        echo "<p class='warning'>\n";
        echo _("The following warnings/errors were raised:") . "<br>\n";
        foreach ($messages as $message) {
            echo "$message<br>\n";
        }
        echo "</p>\n";
    }
}

function echo_page_footer($page_gen_time = null)
{
    if ($page_gen_time != null) {
        $time_generate_string = sprintf(_("Time to generate this data: %.2f seconds"), $page_gen_time);
        echo "<p>$time_generate_string</p>";
    }
}

function _get_word_context_from_text($page_text, $contextWord)
{
    $contexts = [];

    $lines = explode("\n", $page_text);
    $lineNum = 1;
    foreach ($lines as $line) {
        $words = get_all_words_in_text($line, true);
        foreach ($words as $offset => $word) {
            if ($word === $contextWord) {
                $contexts[$lineNum] = $line;
            }
        }
        $lineNum++;
    }

    return [$contexts, $lineNum - 1];
}

function _highlight_word($text, $hlWord)
{
    $wordOffsets = [];

    // get all the words in the text with their offsets
    $words = get_all_words_in_text($text, true);
    foreach ($words as $offset => $word) {
        // if this word matches the one to highlight, mark the offset
        if ($word == $hlWord) {
            array_push($wordOffsets, $offset);
        }
    }

    // we need to do the replacement from right to left
    // so reverse sort the offsets
    rsort($wordOffsets);

    // now do the replacements
    foreach ($wordOffsets as $offset) {
        $text = UTF8::substr_replace($text, "<span class='highlight'>$hlWord</span>", $offset, UTF8::strlen($hlWord));
    }

    return $text;
}

function build_context_array_links($words_w_freq, $projectid)
{
    $context_array = [];

    foreach ($words_w_freq as $word => $freq) {
        $encWord = encode_word($word);
        $context_array[$word] = recycle_window_link("show_word_context.php?projectid=$projectid&amp;word=$encWord", _("Context"), "context");
    }

    return $context_array;
}

function encode_word($word)
{
    return bin2hex($word);
}

function decode_word($word)
{
    return pack("H*", $word);
}

function enforce_edit_authorization($projectid)
{
    global $code_url;

    $project = new Project($projectid);

    if (!$project->can_be_managed_by_current_user) {
        $message = _("You are not authorized to manage this project.");
        $message .= " ";
        $message .= sprintf(_("Redirecting you to the project page in %d seconds."), 5);
        metarefresh(5, "$code_url/project.php?id=$projectid", _("Redirect to Project"), $message);
    }
}

function parse_posted_words($_array)
{
    $newProjectWords = [];
    foreach ($_array as $key => $val) {
        if (preg_match("/cb_(.*?)_(\d+)/", $key)) {
            $word = decode_word($val);
            array_push($newProjectWords, $word);
        }
    }
    return $newProjectWords;
}

function build_checkbox_array($array, $instance = 0)
{
    $count = 0;
    $word_checkbox = [];
    foreach ($array as $word => $freq) {
        $encWord = encode_word($word);
        $word_checkbox[$word] = "<input type='checkbox' class='checkbox' name='cb_{$instance}_{$count}' value='$encWord'>";
        $count++;
    }
    return $word_checkbox;
}

function echo_checkbox_selects(string $instance = 'only')
{
    echo "<p>";
    echo "<button type='button' class='lean_button check_all' data-instance='$instance'>", _("Select all"), "</button>";
    echo " | ";
    echo "<button type='button' class='lean_button un_check_all' data-instance='$instance'>", _("Unselect all"), "</button>";
    echo "</p>";
}

function echo_checkbox_form_start($checkbox_form)
{
    echo "<form action='" . attr_safe($_SERVER['PHP_SELF']) . "' method='post'>";
    echo "<input type='hidden' name='format' value='update'>";
    foreach ($checkbox_form as $name => $value) {
        echo "<input type='hidden' name='$name' value='$value'>";
    }
}

function echo_checkbox_form_submit($submit_label)
{
    echo "<input type='submit' value='$submit_label'>";
}

function echo_checkbox_form_end()
{
    echo "</form>";
}
