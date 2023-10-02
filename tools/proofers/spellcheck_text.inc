<?php
include_once($relPath.'site_vars.php');
include_once($relPath.'wordcheck_engine.inc');
include_once($relPath.'prefs_options.inc'); // get_user_proofreading_font()
include_once($relPath.'misc.inc');  // attr_safe(), javascript_safe()
include_once($relPath.'unicode.inc');

use voku\helper\UTF8;

// Arguments:
//   orig_text - original text to run through dictionary
//   projectid - id of projected, needed for temp filename
//               and to load the custom dictionaries
//   imagefile - image filename, needed for temp filename
//   aux_languages - auxiliary language to check against
//   accepted_words - array of words that should not be considered misspelled
//
// Returns an array consisting of:
//     -- a string containing the HTML code for the 'text' part of the spellcheck interface.
//     -- an array of messages (errors/warnings)
//     -- an array of names of languages used.
//

function spellcheck_text($orig_text, $projectid, $imagefile, $aux_language, $accepted_words)
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

    // Handle a UI-ism so that func only needs to handle ''.
    if ($aux_language == _("Language")) {
        $aux_language = '';
    }

    [$badWordHash, $languages, $messages] =
        get_bad_word_levels_for_project_text($orig_text, $projectid, $aux_language, $accepted_words);

    [$uncommonScriptWords, $uncommonScripts] =
        get_words_with_uncommon_scripts(array_keys(get_distinct_words_in_text($orig_text)));
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

// adds HTML code to highlight text
function _wrapHighlight($word, $highlightClass)
{
    return "<span class='$highlightClass'>$word</span>";
}

// adds HTML code to punctuation to highlight it
function _wrapPunc($word)
{
    return _wrapHighlight($word, "hl-punc");
}

// adds HTML code to highlight words in multiple scripts
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

// adds HTML code to an accepted word to highlight it
function _wrapAW($word)
{
    return "<span class='aw'>$word</span>";
}

// adds HTML code to manage a bad word
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
            foreach ($offsetList[$affectedRow] as $offsetKey) {
                $i = $offsetLookup["{$affectedRow}_{$offsetKey}"];

                // hidden values line|offset|word length
                [$orig_line_i, $orig_word_offset, $orig_word_length] =
                    explode('|', $_POST['posit'.$i]);

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
