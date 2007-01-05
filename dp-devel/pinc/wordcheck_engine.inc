<?PHP
include_once($relPath.'site_vars.php');
include_once($relPath.'iso_639_list.inc');

// regex of non-word characters
// this is used when munging text for spellchecking
// NOTE: This should probably be changed when moving to UTF-8
//       at that time we can probably move to using \W instead of
//       the mess below
$word_characters = "0-9a-zA-Z'À-ÿ";
$word_pattern = "/[$word_characters]+/";
$nonword_character_pattern = "/[^$word_characters]/";

// string of punctuation characters
// this is only used for punctuation highlighting
$puncCharacters = '.,;:?!*/()#@%+=[]{}<>\"¡¿-';

// -----------------------------------------------------------------------------

function get_bad_words_for_text( $text, $projectid, $imagefile, $aux_language, $accept_words )
// Arguments:
//   text - the text for which bad words are sought
//   projectid - id of project, needed for temp filename
//               and to load the custom dictionaries
//   imagefile - image filename, needed for temp filename
//   aux_languages - auxiliary language to check against
//   accept_words - array of words that should not be considered misspelled
// 
// Returns an array consisting of:
//  -- an array: each key is a word that is (or would be) bad for this text;
//     the corresponding value is 0 for scannos, 1 otherwise.
//  -- an array: each value is the name of a language that was used
//  -- an array: each value is a warning/error.
{
    // Get the list of languages that we'll use.
    {
        $languages = array_values(get_project_languages($projectid));

        // add the aux_language if supplied
        if ($aux_language != "")
        {
            $languages[] = $aux_language;
        }

        // unique the array, no point in checking one language more than once
        $languages = array_unique($languages);
    }

    // get the list of misspelled words from aspell
    list($badWordsFromText, $messages) =
        get_bad_words_via_external_checker($text, $projectid, $imagefile, $languages);

    $site_bad_words = load_site_bad_words();

    $project_bad_words = load_project_bad_words($projectid);

    $acc = new BadWordAccumulator();
    $acc->add_bad_words( $project_bad_words, 1 );
    $acc->add_bad_words( $site_bad_words, 0 );
    $acc->add_bad_words( $badWordsFromText, 1 );

    $acc->remove_good_words( $accept_words );

    return array($acc->words, $languages, $messages);
}

// -----------------------------------------------------------------------------

class BadWordAccumulator
{
    function BadWordAccumulator()
    {
        $this->words = array();
        // Each key is a bad word, and its value is
        // 1 if we want an AW button for that word, or
        // 0 if we don't want the AW button.
    }

    function add_bad_words( $bad_words, $val )
    {
            foreach ( $bad_words as $word )
            {
                $this->words[$word] = $val;
            }
    }

    function remove_good_words( $good_words )
    {
            foreach ( $good_words as $word )
            {
                unset( $this->words[$word] );
            }
    }
}

// -----------------------------------------------------------------------------

// returns a list of 'bad' words on a page
// this implementation passes the text to aspell
// Arguments:
//   orig_text - original text to run through dictionary
//   projectid - id of projected, needed for temp filename
//               and to load the custom dictionaries
//   imagefile - image filename, needed for temp filename
//   languages - array of languages, used to load aspell dictionary
//               for those languages if available
//
// Returns an array consisting of:
//       -- an array of misspelled words,
//       -- an array of messages (errors/warnings)
//
function get_bad_words_via_external_checker( $orig_text, $projectid, $imagefile, $languages) {
    global $aspell_temp_dir;
    global $aspell_executable, $aspell_prefix, $iso_639, $charset, $projects_dir;

    $messages = array();

    // clean up the original text
    $orig_text = str_replace(array("\r","\n\n"),array("\n","\n"),$orig_text);

    $tmp_file_name = $projectid . $imagefile . ".txt";
    $tmp_file_path = "$aspell_temp_dir/$tmp_file_name";

    $wasWritten = file_put_contents($tmp_file_path,$orig_text);
    if($wasWritten === FALSE)
    {
        $messages[] = "Error: unable to write text to temp file: $tmp_file_path";
        return array( array(), $messages );
    }

    // find project word list if available
    $personal_string = "";
    if( is_file( "$projects_dir/$projectid/good_words.txt" ) ) {
        $personal_string = "-p $projects_dir/$projectid/good_words.txt";
    }

    $misspellings = array();
    foreach( $languages as $language ) {
        $langcode = array_search( $language, $iso_639 );
        if( $langcode ) {
            $dict_file = "$aspell_prefix/lib/aspell/$langcode.multi";
            if( is_file( $dict_file ) ) {
                // run aspell using this language

                // create the aspell command
                $aspell_command="cat $tmp_file_path | {$aspell_executable} list --prefix={$aspell_prefix} -d $dict_file $personal_string --encoding {$charset} -H";
                //echo "<!-- aspell command: $aspell_command -->\n"; // Very useful for debugging
                // run aspell
                // "asr" stands for "aspell result"
                $asr_text = `$aspell_command`;
                $asr_text = str_replace(array("\r","\n"),array('',"[lf]"),$asr_text);
                // build our list of possible misspellings 
                $misspellings[$langcode] = explode("[lf]",$asr_text);
            }
            else
            {
                $messages[] = "Warning: no external dictionary installed for '$language'";
            }
        }
        else
        {
            $messages[] = "Warning: unknown language name '$language'";
        }
    }

    // now remove the temporary file
    if(is_file($tmp_file_path)) {
        unlink($tmp_file_path);
    }

    if(count($misspellings) == 0) {
        $finalMisspellings = array();
        return array($finalMisspellings, $messages);
    }

    // now we need to find the intersection between the languages that we've checked
    // for instance, if we've checked against an english and a french dictionary
    // the words that are misspelled in both are the ones actually misspelled
    $langKeys = array_keys($misspellings);
    $finalMisspellings = $misspellings[array_shift($langKeys)];
    foreach($langKeys as $langkey) {
        $finalMisspellings = array_intersect($finalMisspellings,$misspellings[$langkey]);
    }

    // make a point *not* to unique the array, some callers might
    // want to know the frequency of the misspelled words

    return array($finalMisspellings,$messages);
}

// -----------------------------------------------------------------------------

function load_site_bad_words() {
    global $projects_dir;

    $scanno_words = array();

    $scanno_name = "$projects_dir/scanno-words.txt";

    // check to see that we were able to open the file
    if(is_file($scanno_name)) {
        $word_list = file_get_contents($scanno_name);
        $scanno_words = explode("\n",$word_list);
    }

    return $scanno_words;
}

// -----------------------------------------------------------------------------

// Return:
//   on success, the function returns an array of the words in the custom dictionary
//   on error, the function returns an error string
function load_project_good_words( $projectid ) {
    global $projects_dir;

    $custom_words = array();

            $dict_name = "$projects_dir/$projectid/good_words.txt";

            // find project word list if available
            if( is_file( $dict_name ) ) {
                $word_list = file_get_contents($dict_name);
                $word_list = preg_replace('/personal.*?\n/','',$word_list);
                $custom_words = explode('\n',$word_list);
            }

    return $custom_words;
}

function save_project_good_words( $projectid, $words ) {
    global $projects_dir;

    $numwords = sizeof($words);

    // now write them to a file

        $dict_name = "$projects_dir/$projectid/good_words.txt";

        $fh = @fopen( $dict_name, "w" );
        // check to see that we were able to open the file
        if($fh) {
            fputs($fh,"personal_ws-1.1 $langcode $numwords\n");
            foreach($words as $word) {
                // skip any blank words
                if($word == "") continue;

                // trim out any word frequencies if they exist
                $word = preg_replace('/ - \d+$/','',$word);

                fputs($fh,"$word\n");
            }
            fclose($fh);
        } else {
            return "Error: Unable to write to file $dict_name\n";
        }

    return "Success";
}

// -----------------------------------------------------------------------------

function load_project_bad_words($projectid) {
    global $projects_dir;

    $flag_words = array();

    $flag_name = "$projects_dir/$projectid/flag-words.txt";

    // check to see that we were able to open the file
    if(is_file($flag_name)) {
        $word_list = file_get_contents($flag_name);
        $flag_words = explode("\n",$word_list);
    }

    return $flag_words;
}

function save_project_bad_words($projectid, $words) {
    global $projects_dir;

    $flag_name = "$projects_dir/$projectid/flag-words.txt";
    $fh = @fopen( $flag_name , "w" );
    // check to see that we were able to open the file
    if($fh) {
        foreach($words as $word) {
            // skip any blank words
            if($word == "") continue;

            fputs($fh,"$word\n");
        }
        fclose($fh);
    } else {
        return "Error: Unable to write to file $flag_name\n";
    }

    return "Success";
}

// -----------------------------------------------------------------------------

// save user-selected Accept Words for a project
function save_accept_words($projectid,$round,$page,$proofer,$words) {
    global $projects_dir;

    if(sizeof($words)==0 || (sizeof($words)==1 && $words[0]=="")) return "Success";

    $iw_name= "$projects_dir/$projectid/accept-words-suggestions.txt";
    $fh = @fopen( $iw_name, "a" );
    // check to see that we were able to open the file
    if($fh) {
        fputs($fh,"$round/$page/$proofer/" . implode(' ',$words) . "\n");
        fclose($fh);
    } else {
        return "Error: Unable to write to file $iw_name\n";
    }

    return "Success";
}

// load user-selected Accept Words for a project
function load_accept_words($projectid) {
    global $projects_dir;

    $wordsArray = array();

    $iw_name = "$projects_dir/$projectid/accept-words-suggestions.txt";
    if(!is_file($iw_name))
        return "Error: File does not exist: $iw_name\n";

    $fh = @fopen( $iw_name, "r" );
    // check to see that we were able to open the file
    if($fh) {
        while(!feof($fh)) {
            $line = chop(fgets($fh));
            if($line == "") continue;
            list($round,$page,$proofer,$words) = explode('/',$line);
            $words = explode(' ',$words);
            if(!is_array($wordsArray[$round][$page]))
                $wordsArray[$round][$page] = array();
            if($round != "" && $page != "")
                $wordsArray[$round][$page] = array_merge($wordsArray[$round][$page],$words);
        }
        fclose($fh);
    } else {
        return "Error: Unable to read from file $iw_name\n";
    }

    return $wordsArray;
}

// -----------------------------------------------------------------------------

// return an array of those languages with a dictionary installed
// returned associative array is in format
//   $array[$langcode]=$language;
function get_languages_with_dictionaries() {
    global $iso_639, $aspell_prefix;

    $returnArray = array();

    foreach($iso_639 as $langcode => $language) {
        $dict_file = "$aspell_prefix/lib/aspell/$langcode.multi";
        if( is_file( $dict_file ) ) {
            $returnArray[$langcode] = $language;
        }
    }

    return $returnArray;
}


// return an array of all languages for a project
// returned associative array is in format
//   $array[$langcode]=$language;
function get_project_languages($projectid) {
    global $iso_639;

    $returnArray = array();

    $project_language_property = mysql_result(mysql_query("
        SELECT language FROM projects WHERE projectid='$projectid'
        "),0);
    $languages = preg_split('/ with /', $project_language_property );

    foreach( $languages as $language ) {
        $langcode = array_search( $language, $iso_639 );
        if( $langcode ) {
            $returnArray[$langcode] = $language;
        }
    }

    return $returnArray;
}

// -----------------------------------------------------------------------------

function get_all_words_in_text( $text )
// Returns an array whose values are all occurrences of all words in $text, in order
// (i.e., with duplicates).
{
    global $word_pattern;
    $n_matches = preg_match_all( $word_pattern, $text, $matches );
    if ( $n_matches === FALSE )
    {
        die("get_all_words_in_text: An error occurred.");
    }
    return $matches[0];
}

// given an array of words, calculate the frequency
function generate_frequencies($wordList) {
    $wordCount = array();

    // now count the frequency of the words
    foreach($wordList as $word) {
        if($word == "") continue;
        @$wordCount[$word]++;
    }

    return $wordCount;
}


// vim: sw=4 ts=4 expandtab
?>
