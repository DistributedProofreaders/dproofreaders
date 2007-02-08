<?PHP
include_once($relPath.'site_vars.php');
include_once($relPath.'word_checker.inc');

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
function spellcheck_text( $orig_text, $projectid, $imagefile, $aux_language, $accepted_words ) {

    global $puncCharacters;
    global $code_url;

    // variable holding final string
    $returnString="";

    // change all EOL characters to [lf]
    $orig_text = str_replace(array("\r","\n\n","\n"),array("\n","[lf]","[lf]"),$orig_text);

    // split the lines out into an array for later processing
    $orig_lines = explode("[lf]",$orig_text);

    // save the original text in a hidden form field
    // this is used when applying spelling corrections
    $returnString.="<input type='hidden' name='text_data' value='" . html_safe($orig_text) . "'>\n";

    // prepare the text for checking, in this case change all EOLs to spaces
    $orig_text = str_replace('[lf]',' ',$orig_text);

    // Handle a UI-ism so that func only needs to handle ''.
    if ( $aux_language == "Language" ) $aux_language = '';

    list($badWordHash,$languages,$messages) =
        get_bad_words_for_text( $orig_text, $projectid, $imagefile, $aux_language, $accepted_words );

    // ok, at this point we have a finalized list of bad words.
    // start preparing the page

    // define the styles used for the interface (highlight for the punctuation, AW button, etc)
    $returnString.="<style type='text/css'>" .
                   "  .hl { background-color: yellow; color: black; }" .
                   "  img.aw { border: 0; margin-left: 5px; }" .
                   "  .dpmono { font-family: DPCustomMono2; }" .
                   "  span.aw { background-color: white; color: black; }" .
                   "</style>";
    $returnString.="<pre>\n";

    $puncArray = str_split($puncCharacters);

    // initialize the wordCount and the numBadWords
    $numBadWords = 0;
    $wordCount = array();
    $badWords = array_keys($badWordHash);

    // loop through all the original lines one at a time
    for ( $origLineNum = 0; $origLineNum < count($orig_lines); $origLineNum++ ) {
        // get the line in a string
        $origLine = $orig_lines[$origLineNum];

        // $origLine -- original line, punctuation and all
        //  $newLine -- new line fully futzed with

        $newLine = $origLine;

        $lineIndex = $indexArray = array();

        // find the index for each punctuation
        for($lineIndex = 0; $lineIndex<strlen($origLine); $lineIndex++) {
            $character = $origLine[$lineIndex];
            if(in_array($character,$puncArray)) {
                // check to see if we're deaing with an escaped HTML char
                // (aka: &amp; &lt; &gt) and if so, skip it
                if($character==';' && preg_match("/&\w+;$|&#\d+;$/",substr($origLine,0,$lineIndex+1))) {
                   continue;
                }
                $indexArray[$lineIndex] = $character;
            }
        }

        // find the index for each word before we futz with the line
        foreach ( get_all_words_in_text($origLine,TRUE) as $lineIndex => $word ) {
            if($word != "" && (in_array($word,$badWords) || in_array($word,$accepted_words))) {
                // erase any punctuation-markers covered by this word
                for( $li = $lineIndex; $li < $lineIndex + strlen($word); $li++ )
                {
                    unset($indexArray[$li]);
                }
                // and now mark the word
                $indexArray[$lineIndex] = $word;
            }
        }

        // now do the search/replace
        krsort($indexArray);
        foreach($indexArray as $lineIndex => $word) {
            if($word == "") continue;

            $wordLen = strlen($word);

            // see if we are punctuation
            if(in_array($word,$puncArray)) {
                $newLine=substr_replace($newLine,_wrapPunc($word),$lineIndex,$wordLen);
            } elseif(in_array($word,$accepted_words)) {
                // see if we're an AW word
                $newLine=substr_replace($newLine,_wrapAW($word),$lineIndex,$wordLen);
            } else {
                // not punctuation, handle word

                // sanitize the words for the AW javascript
                $jsSanitizedWord = bin2hex($word);
                $jsSafeWord = preg_replace("/'/","\'",$word);
                @$wordCount[$word]++;
                $wordID="{$jsSanitizedWord}_{$wordCount[$word]}";
                $numBadWords++;
                $wordSafe = html_safe($word);

                // set the size of the edit box
                $textBoxLen = $wordLen+2;

                // reset the string that will hold the edit box
                $replaceString = "";

                // if the AW button is wanted, add the initial span
                if($badWordHash[$word] == WC_WORLD ) {
                    $replaceString .= "<span id='$wordID'>";
                    $onChange = " onChange=\"disableAW('$wordID');\"";
                    $onChange.= " onKeyup=\"disableAW('$wordID');\"";
                } else {
                    $onChange = '';
                }

                // create the edit box
                $replaceString.=
                    "<input type='hidden' name='posit{$numBadWords}' value='$origLineNum|$lineIndex|$wordLen'>" .
                    "<input type='text' id='input_$wordID' name='sp$numBadWords' size='$textBoxLen' title='$wordSafe' value='$wordSafe' class='dpmono'$onChange>";

                // if the AW button is wanted, add the closing span and the button
                if($badWordHash[$word] == WC_WORLD ) {
                    $replaceString .=
                        "<a href='#' id='a_$wordID' onClick=\"return acceptWord('$jsSanitizedWord','$wordCount[$word]');\">" .
                        "<img id='button_$wordID' src='$code_url/graphics/Book-Plus-Small.gif' title='" . _("Unflag All &amp; Suggest Word") . "' class='aw'></a>" .
                        "</span>";
                }

                $newLine = substr_replace($newLine,$replaceString,$lineIndex,strlen($word));
            }
        }

        // output the final line
        $returnString.=$newLine . "<br>\n";
    }

    $returnString.="</pre>";
    $returnString.="<input id='sptotal' type='hidden' name='sptotal' value='$numBadWords'>";

    return array($returnString,$languages,$messages);
}

// adds HTML code to punctuation to highlight it
function _wrapPunc($word) {
    return "<span class='hl'>$word</span>";
}

// adds HTML code to an accepted word to highlight it
function _wrapAW($word) {
    return "<span class='aw'>$word</span>";
}


function html_safe( $str )
// Return a copy of $str which has been rendered safe to send as
// element-content or attribute-content in an HTML document.
{
        return htmlspecialchars($str,ENT_QUOTES);    
}

function spellcheck_echo_script()
{
    global $code_url;
?>
    <script language="JavaScript" type="text/javascript">
    <!--
    // function to accept specified words in the spellcheck
    // it works by finding (span) elements with IDs in the format
    // word_# and when found sets the content of the span
    // to be just the word thereby removing the select and button
    function acceptWord(wordIDprefix,wordNumber) {
        var wordID=wordIDprefix + "_" + wordNumber;

        // Get the original word
        var input = document.getElementById("input_" + wordID);

        // Double-check that the value hadn't changed
        if(input && input.value != input.title) {
            // what? it has? disable that button & bail!
            disableAW(wordID);
            return false;
        }
        // get the original word value
        var wordOrig = input.value;

        // loop through all bad words
        var totalNumWords = document.getElementById("sptotal").value;
        for(wordIndex=1; wordIndex<totalNumWords; wordIndex++) {
            // find occurances of the word
            var wordOccurID = wordIDprefix + "_" + wordIndex;

            // check to see if this instance has been edited already
            // by comparing the before and after words
            var input = document.getElementById("input_" + wordOccurID);
            if(input && input.value == input.title) {
                // get the span
                var wordSpan = document.getElementById(wordOccurID);
                // set contents to be the word itself
                wordSpan.innerHTML=wordOrig;
                wordSpan.className="aw";

                // save the word in the accepted_words list
                var acceptedWordsInput = document.getElementById("accepted_words");
                acceptedWordsInput.value = acceptedWordsInput.value + " " + wordOrig;
            }
        }

        return false;
    }

    // Disable the Unflag button
    function disableAW(wordID) {
        var input = document.getElementById("input_" + wordID);
        var a = document.getElementById("a_" + wordID);

        // If the value of the input field hasn't changed, don't disable
        if(input && input.value == input.title) {
           // if the current and original values are the same
           // and the button has been disabled, re-enable it
           if(a && !a.href) { enableAW(wordID); }
           return false;
        }

        // If we're here, we should be disabling the button
        var button = document.getElementById("button_" + wordID);
        if(button && a && a.href) {
            button.src = "<?=$code_url;?>/graphics/Book-Plus-Small-Disabled.gif";
            button.title = "<?=_("Word has been edited; unable to Suggest");?>";
            a.removeAttribute('href');
        }

        // mark the page as having been changed
        document.getElementById("is_changed").value=1;

        return false;
    }

    // Enable an already-disabled button
    function enableAW(wordID) {
        var button = document.getElementById("button_" + wordID);
        var a = document.getElementById("a_" + wordID);
        if(button && a) {
            button.src = "<?=$code_url;?>/graphics/Book-Plus-Small.gif";
            button.title = "<?=_("Unflag All & Suggest Word");?>";
            a.href="#";
        }
        return false;
    }

    // Confirm exit if changes have been made
    function confirmExit() {
        // see if changes have been made
        var changesMade = document.getElementById("is_changed").value;
        if(changesMade==1) {
            return confirm("<?=_("Changes have been made. OK to quit without saving?");?>");
        }

        // return true (ie: confirm exit) if no changes were made
        return true;
    }
    // -->
    </script>
<?
}

// --------------------------------------------

// Because we have PHP configured with magic_quotes_gpc on,
// anything we pull out of $_POST will automatically have
// each single-quote, double-quote, and backslash
// preceeded by an extra backslash.
// We don't want these extra backslashes here,
// so we apply stripslashes to anything (textual) we get from $_POST.


function spellcheck_quit()
{
    $orig_text = stripslashes($_POST['revert_text']);
    return str_replace( "[lf]", "\r\n", $orig_text );
}

function spellcheck_apply_corrections() {
    $orig_text = stripslashes($_POST['text_data']);

    // were there any corrections?
    $n_bad_words = isset($_POST['sptotal'])?$_POST['sptotal']:0;
    if($n_bad_words == 0) {
        // just give them the text
        $correct_text = str_replace("[lf]","\r\n",$orig_text);
    }
    else {
        // make corrections
        $text_array = explode("[lf]",$orig_text);

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
        $offsetList = array();
        for ( $i = $n_bad_words; $i >= 1; $i-- ) {
            if ( !isset($_POST['posit'.$i]) ) continue;
            // hidden values line|offset|word length
            list($orig_line_i,$orig_word_offset,$orig_word_length) =
                explode('|',$_POST['posit'.$i]);

            if(!isset($offsetList[$orig_line_i])) $offsetList[$orig_line_i]=array();
            array_push($offsetList[$orig_line_i],$orig_word_offset);
            $offsetLookup["{$orig_line_i}_{$orig_word_offset}"]=$i;
        }

        // sort each row's offset array descending
        $affectedRows = array_keys($offsetList);
        foreach($affectedRows as $affectedRow) {
            rsort($offsetList[$affectedRow]);
        }

        // now iterate over the lines and offsets doing the corrections
        foreach($affectedRows as $affectedRow) {
            foreach($offsetList[$affectedRow] as $offsetKey) {
                $i = $offsetLookup["{$affectedRow}_{$offsetKey}"];

                // hidden values line|offset|word length
                list($orig_line_i,$orig_word_offset,$orig_word_length) =
                    explode('|',$_POST['posit'.$i]);

                // get the modified word, strip off any trailing spaces
                $correct_word = rtrim(stripslashes($_POST['sp'.$i]));

                // replace word in string
                $text_array[$orig_line_i] =
                    substr($text_array[$orig_line_i],0,$orig_word_offset) .
                    $correct_word .
                    substr($text_array[$orig_line_i],$orig_word_offset+$orig_word_length);
            }
        }
        $correct_text = implode("\r\n",$text_array);
    }
    return $correct_text;
}

// vim: sw=4 ts=4 expandtab
?>
