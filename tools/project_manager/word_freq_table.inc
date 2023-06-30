<?php
include_once($relPath.'links.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'misc.inc'); // attr_safe(), html_safe()
include_once($relPath.'faq.inc');
include_once($relPath.'unicode.inc');

use voku\helper\UTF8;

// get_cutoff_string
// Arguments:
//   cutoffOptions - list of javascript cutoff options (eg: array(1,2,3,4,5))
//
// Returns a string with javascript links to effect showing or hiding parts of
// the word list
function get_cutoff_string($cutoffOptions, $labelSuffix = "")
{
    $cutoffString = "";
    foreach ($cutoffOptions as $cutoff) {
        $cutoffString .= "<a href='#' onClick='return setCutoff($cutoff);'>$cutoff$labelSuffix</a> | ";
    }
    $cutoffString = preg_replace("/ \| $/", "", $cutoffString);
    return $cutoffString;
}

// get_cutoff_script
// Arguments:
//   cutoffOptions - list of javascript cutoff options (eg: array(1,2,3,4,5))
//   instances     - number of word frequency tables to support
//                   (required for hiding/showing items of the correct table)
//
// Outputs the javascript used to show/hide parts of the word frequency tables
function get_cutoff_script($cutoffOptions, $instances)
{
    $cutoffJSArray = "";
    foreach ($cutoffOptions as $cutoff) {
        $cutoffJSArray .= "$cutoff,";
    }
    $cutoffJSArray = preg_replace("/,$/", "", $cutoffJSArray);
    return <<<EOS
            function setCutoff(newCutoff) {
                var cutoffArray = new Array($cutoffJSArray);
                var instances = $instances;
                for(cutoffIndex in cutoffArray.reverse()) {
                    cutoff = cutoffArray[cutoffIndex];
                    for(instance = instances; instance>=0; instance--) {
                        var cutoffObject = document.getElementById(cutoff + "_" + instance);
                        if(cutoffObject) {
                            if(cutoff < newCutoff) {
                                cutoffObject.style.display='none';
                            } else {
                                cutoffObject.style.display='';
                            }
                        }
                    }
                }
                if(document.getElementById("freqCutoffValue")) document.getElementById("freqCutoffValue").value=newCutoff;
                document.getElementById("current_cutoff").innerHTML=newCutoff;
                return false;
            }

            // this function will check all checkboxes with id in form "cb_*"
            // it will only check visible checkboxes
            // however, it will uncheck *all* checkboxes
            function checkAll(instance,totalCount,state) {
                // IE sucks - the 'currentStyle' property doesn't actually
                // tell you the display of an item, just that it inherits
                // (kinda defeats the purpose of the currentStyle, right)
                // so we need to calculate it based on the ancestor tbody
                // but that can take a while so we'll assume they all
                // display by default and when we encounter the first
                // hidden one we'll assume all the rest of them are hidden
                // note that FF's getComputedStyle works as expected
                var currentDisplay = 'inline';

                for(var index=0;index<=totalCount;index++) {
                    var checkboxObject=document.getElementById("cb_" + instance + "_" + index);
                    if(checkboxObject) {
                        if(state==false) {
                            if(checkboxObject.checked==true)
                                checkboxObject.checked=false;
                        } else {
                            if(currentDisplay != 'none') {
                                var parent = checkboxObject.parentNode;
                                while(parent && parent.nodeName.toLowerCase() != 'tbody') {
                                    parent=parent.parentNode;
                                }
                                if(parent.nodeName.toLowerCase() == 'tbody' && parent.style.display=='none') currentDisplay = 'none';
                            }
                            if(currentDisplay != 'none')
                                checkboxObject.checked=true;
                            else break;
                        }
                    }
                }

                return false;
            }
        EOS;
} // end of get_cutoff_script

// given the list of cutoff options and the word count
// make sure the initial cutoff shows something
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

// printTableFrequencies
// Arguments:
//   initialFreq   - initial cutoff frequency, anything after this is hidden
//   cutoffOptions - list of javascript cutoff options (eg: array(1,2,3,4,5))
//   wordCount     - a table containing the word/frequency pairs already
//                   sorted and ready for display
//   instance      - number uniquely identifying this instance, must be less
//                   than the $instances passed into get_cutoff_script
//   auxData       - array with one or more of the following:
//                       array($word => $data)
//   checkbox      - array containing checkbox form elements for each word
//
// both wordCount and auxData component arrays can contain the following
// special keys used for formatting
//   [[TITLE]] - title of the column
//   [[CLASS]] - class of the td
//   [[STYLE]] - style of the td
//
// Outputs the word frequency table
function printTableFrequencies($initialFreq, $cutoffOptions, $wordCount, $instance, $auxData = null, $checkbox = null)
{
    // check that auxData is an array of arrays
    if (is_array($auxData) && !is_array(@$auxData[0])) {
        $auxData = [$auxData];
    }

    echo '<table class="basic striped">';

    // output the table heading
    echo '<tr><th>' . _('Word') . '</th>';
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
    echo "</tr>\n";

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
            echo "<tbody id='{$freqCutoff}_{$instance}'";
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

// arrays with numeric and string keys don't multisort correctly
// appending a space to the end of the numeric keys forces them
// to strings and multisort works correctly
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
        assert(false);
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
        exit;
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
        $word_checkbox[$word] = "<input type='checkbox' id='cb_{$instance}_{$count}' name='cb_{$instance}_{$count}' value='$encWord'>";
        $count++;
    }
    return $word_checkbox;
}

function echo_checkbox_selects($wordCount, $instance = 0)
{
    echo "<p>";
    echo "<a href='#' onClick='return checkAll(\"$instance\",$wordCount,true);'>" . _("Select all") . "</a>";
    echo " | ";
    echo "<a href='#' onClick='return checkAll(\"$instance\",$wordCount,false);'>" . _("Unselect all") . "</a>";
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
