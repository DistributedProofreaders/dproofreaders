<?PHP
include_once($relPath.'site_vars.php');
include_once($relPath.'word_checker.inc');

// returns HTML to display the text spellchecked
// Arguments:
//   orig_text - original text to run through dictionary
//   projectid - id of projected, needed for temp filename
//               and to load the custom dictionaries
//   imagefile - image filename, needed for temp filename
//   aux_languages - auxiliarary language to check against
//   accept_words - array of words that should not be considered misspelled
// Return:
//   on success, the function returns the array(array(misspelled words),array(checked lang codes))
//   on error, the function returns an error string
function spellcheck_text( $orig_text, $projectid, $imagefile, $aux_language = "", $accept_words = "" ) {

    global $nonWordCharacters, $puncCharacters;

    // variable holding final string
    $returnString="";

    // change all EOL characters to [lf]
    $orig_text = str_replace(array("\r","\n\n","\n"),array("\n","[lf]","[lf]"),$orig_text);

    // split the lines out into an array for later processing
    $orig_lines = explode("[lf]",$orig_text);

    // save the original text in a hidden form field
    // this is used when applying spelling corrections
    $returnString.="<input type='hidden' name='text_data' value='" . html_safe($orig_text) . "'>\n";

    $languages=array_values(load_project_languages($projectid));

    // add the aux language if available
    if($aux_language != "" && $aux_language != "Language") {
        array_push($languages,$aux_language);
    }

    // unique the array, no point in checking one language more than once
    $languages = array_unique($languages);

    // prepare the text for checking, in this case change all EOLs to spaces
    $orig_text = str_replace('[lf]',' ',$orig_text);

    // get the list of misspelled words from aspell
    list($badWordsFromText,$lang_codes) = get_bad_words_from_text($orig_text,$projectid,$imagefile,$languages);

    // confirm we received something from aspell
    // on success, get_bad_words_from_text returns an array
    // on error, it returns an error message
    if(!is_array($badWordsFromText)) {
        $returnString.="Error during dictionary check<br>: $badWordsFromText";
        $badWordsFromText = array();
    } else {
        $badWordsFromText = array_unique($badWordsFromText);
        rsort($badWordsFromText);
    }

    // load the scanno words
    $scannos = array_unique(load_scanno_words());

    // load the flag words
    $flags = array_unique(load_project_flag_words($projectid));

    // if we have a list of words to accept (via the AW or othewise)
    // filter them out of the $flags and $badWordsFromText
    if(is_array($accept_words) && sizeof($accept_words)>0) {
        $badWordsFromText = array_diff($badWordsFromText,$accept_words);
        $flags = array_diff($flags,$accept_words);
    }

    // create the badWordHash, the keys are the words
    // the values are 1 if we want an AW button for that word
    // or a 0 if we don't want the AW button
    $badWordHash = array();
    foreach($flags as $word) $badWordHash[$word] = 1;
    foreach($scannos as $word) $badWordHash[$word] = 0;
    foreach($badWordsFromText as $word) $badWordHash[$word] = 1;

    // ok, at this point we have a finalized list of possible misspellings
    // and likely a list of scannos too, start preparing the page

    // define the highlight for the punctuation
    $returnString.="<style type='text/css'> .hl { background-color:yellow;color:black;}</style>";
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
        // $workLine -- line without punctuation
        //  $newLine -- new line fully futzed with

        $newLine = $origLine;
        $workLine = preg_replace($nonWordCharacters,' ',$origLine);

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
        $lineIndex = 0;
        $wordArray = explode(" ",$workLine);
        foreach($wordArray as $word) {
            if(in_array($word,$badWords) && $word != "")
                $indexArray[$lineIndex] = $word;
            $lineIndex += strlen($word)+1;
        }

        // now do the search/replace
        krsort($indexArray);
        foreach($indexArray as $lineIndex => $word) {
            if($word == "") continue;

            $wordLen = strlen($word);

            // see if we are punctuation
            if(in_array($word,$puncArray)) {
                $newLine=substr_replace($newLine,_wrapPunc($word),$lineIndex,$wordLen);
            } else {
                // not punctuation, handle word

                // sanitize the words for the AW javascript
                $jsSanitizedWord = preg_replace("/\W/",'',$word);
                $jsSafeWord = preg_replace("/'/","\'",$word);
                @$wordCount[$word]++;
                $numBadWords++;
                $wordSafe = html_safe($word);

                // set the size of the edit box
                $textBoxLen = $wordLen+2;

                // reset the string that will hold the edit box
                $replaceString = "";

                // if the AW button is wanted, add the initial span
                if($badWordHash[$word]) {
                    $replaceString .= "<span id=\"{$jsSanitizedWord}_{$wordCount[$word]}\">";
                    $onChange = " onChange=\"disableAcceptWord('{$jsSanitizedWord}_{$wordCount[$word]}');\"";
                }

                // create the edit box
                $replaceString.=
                    "<input type='hidden' name='posit{$numBadWords}' value='$origLineNum|$lineIndex|$wordLen'>" .
                    "<input type='hidden' name='wd{$numBadWords}' value='{$wordSafe}'>" .
                    "<input type='text' name='sp{$numBadWords}' size='{$textBoxLen}' value='{$wordSafe}' style='font-family: DPCustomMono2;' $onChange>";

                // if the AW button is wanted, add the closing span and the button
                if($badWordHash[$word]) {
                    $replaceString .=
                        "<input id='button_{$jsSanitizedWord}_{$wordCount[$word]}' type='button' value='AW' onClick=\"acceptWord('$jsSanitizedWord','$jsSafeWord');\">" .
                        "</span>";
                }

                $newLine = substr_replace($newLine,$replaceString,$lineIndex,strlen($word));
            }
        }

        // output the final line
        $returnString.=$newLine . "<br>\n";
    }

    $returnString.="</pre>";
    $returnString.="<input type='hidden' name='sptotal' value='$numBadWords'>";

    return array($returnString,$lang_codes);
}

// adds HTML code to a string (usually punctuation) to highlight it
function _wrapPunc($word) {
    return "<span class=\"hl\">$word</span>";
}

function html_safe( $str )
// Return a copy of $str which has been rendered safe to send as
// element-content or attribute-content in an HTML document.
{
        return htmlspecialchars($str,ENT_QUOTES);    
}

function spellcheck_echo_script()
{
?>
    <script language="JavaScript" type="text/javascript">
    <!--
    // function to accept specified words in the spellcheck
    // it works by finding (span) elements with IDs in the format
    // word_# and when found sets the content of the span
    // to be just the word thereby removing the select and button
    function acceptWord(word,wordOrig) {
        var wordIndex=1;
        var wordSpan=1;
        while(wordSpan) {
            // find occurances of the word
            var wordID = word + "_" + wordIndex;
            wordSpan = document.getElementById(wordID);
            if(wordSpan) {
                // check to see if this instance has been edited already
                // by checking the status of the AW button
                var button = document.getElementById("button_" + wordID);
                if(button && button.disabled == false) {
                    // set contents to be the word itself
                    wordSpan.innerHTML=wordOrig;
                }

                // save the word as the accept_words
                var acceptWordInput = document.getElementById("accept_words");
                acceptWordInput.value = acceptWordInput.value + " " + word;
                wordIndex ++;
            }
            // protection against run-away processes
            if(wordIndex>=1000) { break; }
        }
    }

    function disableAcceptWord(wordID) {
        var button = document.getElementById("button_" + wordID);
        if(button) {
            button.disabled = true;
        }
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
            $inst_array = explode('|',$_POST['posit'.$i]);
            $orig_line_i      = $inst_array[0];
            $orig_word_offset = $inst_array[1];
            $orig_word_length = $inst_array[2];

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
                $inst_array = explode('|',$_POST['posit'.$i]);
                $orig_line_i      = $inst_array[0];
                $orig_word_offset = $inst_array[1];
                $orig_word_length = $inst_array[2];

                // hidden word
                $correct_word = stripslashes($_POST['sp'.$i]);
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
