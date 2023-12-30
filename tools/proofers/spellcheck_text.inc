<?php
include_once($relPath.'wordcheck_engine.inc');
include_once($relPath.'prefs_options.inc'); // get_user_proofreading_font()
include_once($relPath.'unicode.inc');
include_once($relPath.'links.inc'); // new_window_link
include_once($relPath.'faq.inc');
include_once('image_block_enh.inc');

use voku\helper\UTF8;

/**
 * Build HTML for the WordCheck UI
 *
 * Returns an array consisting of:
 * - a string containing the HTML code for the 'text' part of the spellcheck interface.
 * - an array of messages (errors/warnings)
 * - an array of names of languages used.
 *
 * @param string $orig_text
 *   Original text to run through dictionary
 * @param string $projectid
 *   Id of projected, needed for temp filename and to load the custom dictionaries
 * @param string $imagefile
 *   Image filename, needed for temp filename
 * @param array $languages
 *   Languages to check against
 * @param array $accepted_words
 *   Array of words that should not be considered misspelled
 *
 * @return array
 */
function spellcheck_text($orig_text, $projectid, $imagefile, $languages, $accepted_words)
{
    global $puncCharacters;

    // variable holding final string
    $returnString = "";

    // change all EOL characters to [lf]
    $orig_text = str_replace(["\r", "\n\n", "\n"], ["\n", "[lf]", "[lf]"], $orig_text);

    // split the lines out into an array for later processing
    $orig_lines = explode("[lf]", $orig_text);

    // save the original text in a hidden form field
    // this is used when applying spelling corrections
    $returnString .= "<input type='hidden' name='text_data' value='" . attr_safe($orig_text) . "'>\n";

    // prepare the text for checking, in this case change all EOLs to spaces
    $orig_text = str_replace('[lf]', ' ', $orig_text);

    [$badWordHash, $languages, $messages] =
        get_bad_word_levels_for_project_text($orig_text, $projectid, $languages, $accepted_words);

    [$uncommonScriptWords, $uncommonScripts] =
        get_words_with_uncommon_scripts(array_keys(get_distinct_words_in_text($orig_text)));

    // If Greek is present in $uncommonScripts, we always want it to be first
    // so it always has the same color across pages and projects. We do this
    // only for Greek as it is the most common non-Latin script at pgdp.net.
    if (in_array("Greek", $uncommonScripts)) {
        $uncommonScripts = array_merge(["Greek"], array_diff($uncommonScripts, ["Greek"]));
    }
    $uncommonScriptMap = array_flip($uncommonScripts);

    // Prepare the style block which requires some dynamic content from
    // the user's preferences and the page itself.
    [$font_style, $font_size, $font_family] = get_user_proofreading_font();
    if ($font_size != '') {
        $font_size = "font-size: $font_size";
    } else {
        $font_size = "font-size: unset";
    }

    $highlight_colors = ["lightpink", "lightgreen", "CadetBlue", "mediumpurple"];
    $highlight_styles = "  .hl-punc { background-color: yellow; color: black; }\n";
    for ($index = 0; $index < count($uncommonScripts); $index++) {
        $color_index = min($index, count($highlight_colors) - 1);
        $color = $highlight_colors[$color_index];
        $highlight_styles .= "  .hl-script-{$index} { background-color: $color; color: black; }\n";
    }

    $returnString .= "<style>" .
                   "  pre { line-height: 2.7; }".
                   "  .proofingfont { font-family: $font_family; $font_size }" .
                   $highlight_styles .
                   "  img.aw { border: 0; margin-left: 5px; }" .
                   "  span.aw { background-color: white; color: black; }" .
                   "</style>";
    $returnString .= "<pre class='proofingfont'>\n";

    $puncArray = UTF8::str_split($puncCharacters);

    $badWords = array_keys($badWordHash);
    // we need to force PHP to treat bad words that are numbers
    // as strings (and compare them as such), otherwise things like
    // having a bad word of '0' messes up the in_array function
    $badWords = array_map('strval', $badWords);
    $numBadWords = 0;

    // loop through all the original lines one at a time
    for ($origLineNum = 0; $origLineNum < count($orig_lines); $origLineNum++) {
        // get the line in a string
        $origLine = $orig_lines[$origLineNum];

        // $origLine -- original line, punctuation and all
        //  $newLine -- new line fully futzed with

        $newLine = $origLine;

        $lineIndex = $indexArray = [];

        // find the index for each punctuation
        for ($lineIndex = 0; $lineIndex < UTF8::strlen($origLine); $lineIndex++) {
            $character = UTF8::access($origLine, $lineIndex);
            if (in_array($character, $puncArray)) {
                // check to see if we're dealing with an escaped HTML char
                // (aka: &amp; &lt; &gt) and if so, skip it
                if ($character == ';' && preg_match("/&\w+;$|&#\d+;$/", UTF8::substr($origLine, 0, $lineIndex + 1))) {
                    continue;
                }
                $indexArray[$lineIndex] = $character;
            }
        }

        // find the index for each word before we futz with the line
        foreach (get_all_words_in_text($origLine, true) as $lineIndex => $word) {
            if ($word != "" && (
                in_array($word, $badWords) || in_array($word, $accepted_words) ||
                in_array($word, $uncommonScriptWords)
            )) {
                // erase any punctuation-markers covered by this word
                for ($li = $lineIndex; $li < $lineIndex + UTF8::strlen($word); $li++) {
                    unset($indexArray[$li]);
                }
                // and now mark the word
                $indexArray[$lineIndex] = $word;
            }
        }

        // now do the search/replace from right-to-left so our line index
        // remains valid while the length of the string grows
        krsort($indexArray);
        foreach ($indexArray as $lineIndex => $word) {
            if ($word == "") {
                continue;
            }

            $wordLen = UTF8::strlen($word);

            $replaceString = null;

            // Process the words in the most to least important if the word
            // at the index is found in multiple arrays
            if (in_array($word, $accepted_words)) {
                // an accepted word (AW)
                $replaceString = _wrapAW($word);
            } elseif (in_array($word, $badWords)) {
                // a bad word
                [$replaceString, $numBadWords] = _wrapBadWord($word, $origLineNum, $lineIndex, $wordLen, @$badWordHash[$word]);
            } elseif (in_array($word, $puncArray)) {
                // punctuation
                $replaceString = _wrapPunc($word);
            } elseif (in_array($word, $uncommonScriptWords)) {
                // word with one or more characters in an "uncommon" script
                $replaceString = _wrapScriptWord($word, $uncommonScriptMap);
            }

            if ($replaceString !== null) {
                $newLine = UTF8::substr_replace($newLine, $replaceString, $lineIndex, $wordLen);
            }
        }

        // output the final line
        $returnString .= $newLine . "\n";
    }

    $returnString .= "</pre>";
    $returnString .= "<input id='sptotal' type='hidden' name='sptotal' value='$numBadWords'>";

    $highlightsUsed = [];
    $highlightsUsed["hl-punc"] = _("punctuation");
    foreach ($uncommonScriptMap as $script => $index) {
        $highlightClass = "hl-script-$index";
        $highlightsUsed[$highlightClass] = _wrapHighlight(sprintf(_("Unicode script: %s"), $script), $highlightClass);
    }

    return [$returnString, $languages, $messages, $highlightsUsed];
}

/**
 * Adds HTML code to highlight text
 */
function _wrapHighlight($word, $highlightClass)
{
    return "<span class='$highlightClass'>$word</span>";
}

/**
 * Adds HTML code to punctuation to highlight it
 */
function _wrapPunc($word)
{
    return _wrapHighlight($word, "hl-punc");
}

/**
 * Adds HTML code to highlight words in multiple scripts
 */
function _wrapScriptWord($word, $scriptMap)
{
    global $common_unicode_scripts;

    $returnString = "";
    foreach (split_multiscript_string($word) as $chunk) {
        $chunkScript = utf8_char_script(UTF8::str_split($chunk)[0]);
        if (in_array($chunkScript, $common_unicode_scripts)) {
            $returnString .= $chunk;
        } else {
            $highlightClass = "hl-script-" . $scriptMap[$chunkScript];
            $returnString .= _wrapHighlight($chunk, $highlightClass);
        }
    }
    return $returnString;
}

/**
 * Adds HTML code to an accepted word to highlight it
 */
function _wrapAW($word)
{
    return "<span class='aw'>$word</span>";
}

/**
 * Adds HTML code to manage a bad word
 */
function _wrapBadWord($word, $origLineNum, $lineIndex, $wordLen, $badWordType)
{
    global $code_url;

    static $wordCount = [];
    static $numBadWords = 0;

    $numBadWords++;

    // sanitize the words for the AW javascript
    $jsSanitizedWord = bin2hex($word);
    @$wordCount[$word]++;
    $wordID = "{$jsSanitizedWord}_{$wordCount[$word]}";
    $wordSafe = attr_safe($word);

    // set the size of the edit box
    // note: in some browsers the edit box is not wide enough
    // for longer words, hence the scaling mechanism
    $textBoxLen = $wordLen + max(1 + round($wordLen / 5), 2);

    // reset the string that will hold the edit box
    $replaceString = "";

    // if the AW button is wanted, add the initial span
    if ($badWordType == WC_WORLD) {
        $replaceString .= "<span id='$wordID'>";
        $onChange = " onBlur=\"markBox('$wordID');\"";
        $onChange .= " oninput=\"disableAW('$wordID');\"";
    } else {
        $onChange = " onBlur=\"markBox('$wordID');\"";
        $onChange .= " oninput=\"evaluateWordChange('$wordID');\"";
    }

    // create the edit box
    $replaceString .=
        "<input type='hidden' name='posit{$numBadWords}' value='$origLineNum|$lineIndex|$wordLen'>" .
        "<input type='text' id='input_$wordID' name='sp$numBadWords' size='$textBoxLen' value='$wordSafe' class='proofingfont'$onChange>";

    // if the AW button is wanted, add the closing span and the button
    if ($badWordType == WC_WORLD) {
        $replaceString .=
            "<a href='#' id='a_$wordID' onClick=\"return acceptWord('$jsSanitizedWord','$wordCount[$word]');\">" .
            "<img id='button_$wordID' src='$code_url/graphics/Book-Plus-Small.gif' title='" . attr_safe(_("Unflag All &amp; Suggest Word")) . "' class='aw'></a>" .
            "</span>";
    }

    return [$replaceString, $numBadWords];
}

// --------------------------------------------

function spellcheck_quit()
{
    $orig_text = $_POST['revert_text'];
    return str_replace("[lf]", "\r\n", $orig_text);
}

function spellcheck_apply_corrections()
{
    $orig_text = $_POST['text_data'];

    $corrections = [];

    // were there any corrections?
    $n_bad_words = $_POST['sptotal'] ?? 0;
    if ($n_bad_words == 0) {
        // just give them the text
        $correct_text = str_replace("[lf]", "\r\n", $orig_text);
    } else {
        // make corrections
        $text_array = explode("[lf]", $orig_text);

        // If there are multiple corrections on a single line,
        // and you do them from left to right,
        // any correction (after the first) will have its offset info invalidated
        // (or at least, rendered harder to use) by the corrections to its left
        // (if any of them has a $correct_word with a different length from the
        // $orig_word).
        // So we want to the corrections on a line from right to left.

        // First we go through them and build a list of the words on a given line,
        // sort descending by the offset, and then do the corrections from RtL
        //
        $offsetList = [];
        for ($i = $n_bad_words; $i >= 1; $i--) {
            if (!isset($_POST['posit'.$i])) {
                continue;
            }
            // hidden values line|offset|word length
            [$orig_line_i, $orig_word_offset, $orig_word_length] =
                explode('|', $_POST['posit'.$i]);

            if (!isset($offsetList[$orig_line_i])) {
                $offsetList[$orig_line_i] = [];
            }
            array_push($offsetList[$orig_line_i], $orig_word_offset);
            $offsetLookup["{$orig_line_i}_{$orig_word_offset}"] = $i;
        }

        // sort each row's offset array descending
        $affectedRows = array_keys($offsetList);
        foreach ($affectedRows as $affectedRow) {
            rsort($offsetList[$affectedRow]);
        }

        // now iterate over the lines and offsets doing the corrections
        foreach ($affectedRows as $affectedRow) {
            foreach ($offsetList[$affectedRow] as $offsetKey) { /** @phpstan-ignore-line Fixed in https://github.com/phpstan/phpstan/issues/9403 */
                $i = $offsetLookup["{$affectedRow}_{$offsetKey}"];

                // hidden values line|offset|word length
                [$orig_line_i, $orig_word_offset, $orig_word_length] =
                    array_map('intval', explode('|', $_POST['posit'.$i]));

                // get the modified word, strip off any trailing spaces
                $correct_word = rtrim($_POST['sp'.$i]);

                // pull the original word
                $orig_word = UTF8::substr($text_array[$orig_line_i], $orig_word_offset, $orig_word_length);

                if ($orig_word != $correct_word) {
                    $corrections[] = [$orig_word, $correct_word];
                }

                // replace word in string
                $text_array[$orig_line_i] =
                    UTF8::substr($text_array[$orig_line_i], 0, $orig_word_offset) .
                    $correct_word .
                    UTF8::substr($text_array[$orig_line_i], $orig_word_offset + $orig_word_length);
            }
        }
        $correct_text = implode("\r\n", $text_array);
    }
    return [$correct_text, $corrections];
}

//----------------------------------------------------------------------------
// The following were originally in tools/proofers/spellcheck.inc -- see that
// file in git for history.

global $word_check_messages;
$word_check_messages = [
    "disableAwLabel" => _("Word has been edited; unable to Suggest"),
    "enableAwLabel" => _("Unflag All & Suggest Word"),
    "confirmExitString" => _("Changes have been made. OK to quit without saving?"),
    "pageChangedError" => _("Cannot save page from WordCheck. Words have been modified."),
    "badCharsError" => _("There are invalid characters in the page"),
    "keepCorrectons" => _("Keep corrections and return to proofreading this page"),
    "rerun" => _("Check page against an additional language"),
];

function output_wordcheck_interface($user, $ppage, $text_data, $is_changed, $accepted_words, $languages)
{
    global $code_url, $word_check_messages;

    $revert_text = $_POST['revert_text'] ?? $text_data;

    $is_header_visible = array_get($_SESSION, "is_header_visible", 1);

    // print basic image html
    if ($user->profile->i_type == 1) {
        ibe_echo_block();
        echo '<div id="controlframe">';
    } else {
        echo '<div>';
    }
    echo '<form name="spcorrects" action="processtext.php" method="POST" onsubmit="return top.submitForm(this)">';

    // change all EOL characters to [lf]
    $revert_text = str_replace(["\r", "\n\n", "\n"], ["\n", "[lf]", "[lf]"], $revert_text);

    echo "<input type='hidden' name='revert_text' value='" . attr_safe($revert_text) . "'>\n";
    echo "<input type='hidden' id='is_changed' name='is_changed' value='" . attr_safe($is_changed) . "'>\n";
    echo "<input type='hidden' id='accepted_words' name=\"accepted_words\" value='"
        . attr_safe(implode(' ', $accepted_words)) . "'>\n";
    echo "<input type='hidden' id='is_header_visible' name=\"is_header_visible\" value='" . attr_safe($is_header_visible) . "'>\n";

    // run the text through the spellcheck - returns the form contents and a list of languages the page was checked against
    [$page_contents, $languages, $messages, $highlights] =
        spellcheck_text($text_data, $ppage->projectid(), $ppage->imagefile(), $languages, $accepted_words);

    if (!$languages) {
        $messages[] = _("No check against a dictionary has been made.");
    }

    // start the div containing the link to show/hide the WordCheck header
    echo "<div>";
    echo "<a id='wc_header_link' href='#' onClick='return toggleWCHeader();'>";
    if ($is_header_visible) {
        echo _("Hide WordCheck Header");
        $header_style = "";
    } else {
        echo _("Show WordCheck Header");
        $header_style = "style='display: none;'";
    }
    echo "</a>";
    echo "</div>";

    // start the div that consists of the WordCheck header
    // that can be toggled shown/hidden
    echo "<div id='wc_header' $header_style>";
    if (count($messages) != 0) {
        // warnings or errors were raised, print them out
        echo "<p class='warning'>" . _("The following warnings/errors were raised:") . "<br>\n";
        foreach ($messages as $message) {
            echo html_safe($message) . "<br>\n";
        }
        echo "</p>";
    }

    // for the vertical layout, use line breaks between pieces
    // for the horizontal one, use two spaces instead
    $spacer = $user->profile->i_layout == 1 ? "<br>" : "&nbsp; ";

    // Handle dictionary selection
    echo "<div class='dict-selection'>";
    $project_languages = get_project_languages($ppage->projectid());
    $used_project_languages = array_intersect($languages, $project_languages);
    if (count($used_project_languages) > 0) {
        echo "<div class='dict-section'>";
        echo "<div class='dict-section-title'>" . _("Project-based dictionaries:") . "</div>$spacer";
        output_wordcheck_language_buttons($used_project_languages);
        echo "</div>";
    }

    $aux_languages = array_diff($languages, $project_languages);
    if (count($aux_languages) > 0) {
        echo "<div class='dict-section'>";
        echo "<div class='dict-section-title'>" . _("Additional dictionaries: ") . "</div>$spacer";
        output_wordcheck_language_buttons($aux_languages);
        echo "</div>";
    }

    // limit the total number of dictionaries to 5
    if (count($languages) < 5) {
        // output the code allowing the user to select another language
        echo "<select name='aux_language'>";
        echo "<option value=''>" . _("Select additional language...") . "</option>\n";

        // get a list of languages with dictionaries installed on the system
        $dict_list = get_languages_with_dictionaries();
        asort($dict_list);
        foreach ($dict_list as $langcode => $language) {
            // skip the languages that we just used.
            if (in_array($language, $languages)) {
                continue;
            }
            echo "<option value='" .  attr_safe($language) . "'>";
            echo html_safe($language);
            echo "</option>\n";
        }
        echo "</select>";

        $value = attr_safe(_("Check"));
        $title = attr_safe($word_check_messages["rerun"]);
        echo "<input type='submit' name='rerunauxlanguage' id='rerunauxlanguage' value='$value' title='$title'>";
    }
    echo "</div>";

    // show help blurb on the UA&S icon
    echo "<p>";
    echo sprintf(
        _("The %s icon accepts the word for this page and suggests it for the dictionary."),
        "<img src=\"$code_url/graphics/Book-Plus-Small.gif\" border=\"0\">"
    );
    echo "</p>";

    // text highlight legend
    if ($highlights) {
        echo "<p>";
        echo _("The following highlights have been used:") . $spacer;
        foreach ($highlights as $hl_class => $message) {
            echo "<span class='$hl_class'>$message</span>" . $spacer;
        }
        echo "</p>";
    }

    echo "</div>";
    // here ends the div for the WordCheck header

    echo '<table id="tbtext">';

    // print the link to the WordCheck FAQ
    echo '<tr><td id="tdtop">';
    echo new_window_link(get_faq_url("wordcheck-faq.php"), _("WordCheck FAQ"));
    echo '</td></tr>';

    echo '<tr>';
    echo '<td id="tdtext">';
    $ppage->echo_hidden_fields();
    echo $page_contents;
    echo '</td>';
    echo '</tr>';

    echo '<tr>';
    echo '<td id="tdtop">';
    $value = attr_safe(_("Submit Corrections"));
    $title = attr_safe($word_check_messages['keepCorrectons']);
    echo "<input type='submit' name='spcorrect' id='spcorrect' value='$value' title='$title'>";
    echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

    $value = attr_safe(_("Quit WordCheck"));
    $title = attr_safe(_("Abandon corrections and return to proofreading this page"));
    echo "<input type='submit' name='spexit' value='$value' title='$title' onClick='return confirmExit();'>";
    echo "&nbsp;";

    $value = attr_safe(_("Save as 'Done' & Proofread Next Page"));
    $title = attr_safe(_("Save page as done and proofread the next available page"));
    $disabled = $is_changed ? "disabled" : "";
    echo "<input type='submit' name='spsaveandnext' id='spsaveandnext' value='$value' title='$title' $disabled>";
    echo '</td>';
    echo '</tr>';

    echo '</table>';
    echo '</form>';
    echo '</div>';
}

function output_wordcheck_language_buttons($languages)
{
    foreach ($languages as $language) {
        $name = attr_safe("remove_language[$language]");
        $alt = attr_safe(_("Remove language"));
        $value = attr_safe($language);
        echo "<div class='remove-language'>";
        echo "<input type='hidden' name='languages[]' value='$value'>";
        echo "<input type='submit' name='$name' value='☒' title='$alt'>" . html_safe($language);
        echo "</div>";
    }
}

function get_page_js($interface_type_init_value)
{
    global $charset;

    $show_wc_header_label = javascript_safe(_("Show WordCheck Header"), $charset);
    $hide_wc_header_label = javascript_safe(_("Hide WordCheck Header"), $charset);

    return <<<PAGE_JS
                function ldAll() {
                    top.initializeStuff($interface_type_init_value);
                }

                function toggleWCHeader() {
                    var header=document.getElementById('wc_header');
                    var headerLink=document.getElementById('wc_header_link');
                    var headerStateSave=document.getElementById('is_header_visible');

                    if(header) {
                        if(header.style.display=='') {
                            header.style.display='none';
                            headerLink.innerHTML='$show_wc_header_label';
                            headerStateSave.value=0;
                        } else{
                            header.style.display='';
                            headerLink.innerHTML='$hide_wc_header_label';
                            headerStateSave.value=1;
                        }
                    }
                    return false;
                }
        PAGE_JS;
}

function get_wordcheck_page_header_args($user, $ppage)
{
    global $code_url, $word_check_messages;

    $project = new Project($ppage->projectid());
    $valid_character_pattern = build_character_regex_filter($project->get_valid_codepoints(), "js");

    $storage_key = "proof-enh" . (($user->profile->i_layout == 1) ? "-v" : "-h");

    $image_data = json_encode([
        "imageUrl" => $ppage->url_for_image(),
        "storageKey" => $storage_key,
        "align" => "L",
    ]);

    $header_args = [
        "js_files" => [
            "$code_url/pinc/3rdparty/xregexp/xregexp-all.js",
            "$code_url/tools/proofers/wordcheck.js",
            "$code_url/scripts/character_test.js",
            "$code_url/scripts/control_bar.js",
            "$code_url/tools/proofers/proof_image.js",
        ],
        "js_data" => get_page_js($user->profile->i_type == 1 ? 2 : 3) .
            get_control_bar_texts() . "
            var imageData = $image_data;
            var validCharacterPattern = '$valid_character_pattern';
            var wordCheckMessages = " . json_encode($word_check_messages) . ";
        ",

        "body_attributes" => 'id="wordcheck_interface" onload="ldAll()"',
    ];

    if ($user->profile->i_type == 1) {
        $header_args["css_data"] = ibe_get_styles();
    }

    return $header_args;
}
