<?PHP
include_once($relPath.'site_vars.php');
include_once($relPath.'iso_639_list.inc');
include_once($relPath.'iso_lang_list.inc'); // langcode3_for_langname

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

// There are four "levels" of specificity
// at which words are deemed bad or good.
define('WC_WORLD',   1);
define('WC_SITE',    2);
define('WC_PROJECT', 3);
define('WC_PAGE',    4);

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
//     the corresponding value is a constant denoting the
//     (most specific) level at which the word was deemed bad.
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

    $acc = new BadWordAccumulator();

    // Go to various sources for good+bad words,
    // in order of increasing specificity to the given text,
    // and accumulate the results.

    // The world
    {
        list($external_bad_words, $messages) =
            get_bad_words_via_external_checker(
                $text, $projectid, $imagefile, $languages);
        $acc->messages += $messages;
        $acc->add_bad_words( $external_bad_words, WC_WORLD );
    }

    // The site
    {
        $langcode3s = array();
        foreach ( $languages as $language )
        {
            $langcode3 = langcode3_for_langname( $language );
            if ( is_null($langcode3) )
            {
                $acc->messages[] = "warning: unknown language '$language'";
            }
            else
            {
                $langcode3s[] = $langcode3;
            }
        }

        foreach ( $langcode3s as $langcode3 )
        {
            $acc->remove_good_words( load_site_good_words($langcode3) );
        }

        foreach ( $langcode3s as $langcode3 )
        {
            $acc->add_bad_words( load_site_bad_words($langcode3), WC_SITE );
        }
    }

    // The project
    {
        $acc->remove_good_words( load_project_good_words($projectid) );

        $acc->add_bad_words( load_project_bad_words($projectid), WC_PROJECT );
    }

    // The page
    {
        $acc->remove_good_words( $accept_words );
    }

    return array($acc->words, $languages, $acc->messages);
}

// -----------------------------------------------------------------------------

class BadWordAccumulator
{
    function BadWordAccumulator()
    {
        $this->words = array();
        // Each key is a bad word, and its value is
        // a constant denoting the (most specific) level
        // at which the word was deemed bad.

        $this->messages = array();
    }

    function add_bad_words( $bad_words, $val )
    {
        if ( is_string($bad_words) )
        {
            $this->messages[] = $bad_words;
        }
        else
        {
            foreach ( $bad_words as $word )
            {
                $this->words[$word] = $val;
            }
        }
    }

    function remove_good_words( $good_words )
    {
        if ( is_string($good_words) )
        {
            $this->messages[] = $good_words;
        }
        else
        {
            foreach ( $good_words as $word )
            {
                unset( $this->words[$word] );
            }
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
    global $aspell_executable, $aspell_prefix, $iso_639, $charset;

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

    $misspellings = array();
    foreach( $languages as $language ) {
        $langcode = array_search( $language, $iso_639 );
        if( $langcode ) {
            $dict_file = "$aspell_prefix/lib/aspell/$langcode.multi";
            if( is_file( $dict_file ) ) {
                // run aspell using this language

                // create the aspell command
                $aspell_command="cat $tmp_file_path | {$aspell_executable} list --prefix={$aspell_prefix} -d $dict_file --encoding {$charset} -H";
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

function load_site_good_words( $langcode3 ) {
    global $dyn_dir;

    return load_word_list( "$dyn_dir/words/good_words.$langcode3.txt" );
}

function load_site_bad_words( $langcode3 ) {
    global $dyn_dir;

    return load_word_list( "$dyn_dir/words/bad_words.$langcode3.txt" );
}

// -----------------------------------------------------------------------------

function load_project_good_words( $projectid ) {
    global $projects_dir;

    return load_word_list( "$projects_dir/$projectid/good_words.txt" );
}

function save_project_good_words( $projectid, $words ) {
    global $projects_dir;

    // trim out any word frequencies if they exist
    $words = preg_replace('/ - \d+$/', '', $words);

    return save_word_list( "$projects_dir/$projectid/good_words.txt", $words );
}

// -----------------------------------------------------------------------------

function load_project_bad_words($projectid) {
    global $projects_dir;

    return load_word_list( "$projects_dir/$projectid/bad_words.txt" );
}

function save_project_bad_words($projectid, $words) {
    global $projects_dir;

    return save_word_list( "$projects_dir/$projectid/bad_words.txt", $words );
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

function load_word_list( $path )
// Load a list of words (one per line) from a file.
// Returns:
//   on success: an array of words
//   on error:   a string containing an error message
{
    if ( !is_file($path) )
    {
        // The file does not exist.
        // Treat that the same as if it existed and was empty.
        return array();
    }

    $word_list = file_get_contents($path);
    if ( $word_list === FALSE )
    {
        return "Error reading file $path";
    }

    return explode("\n", $word_list);
}

function save_word_list( $path, $words )
// Save a list of words (one per line) to a file.
// Return a string, either "Success" or an error message.
{
    $fh = @fopen( $path, "w" );

    if ( $fh === FALSE )
    {
        return "Error: Unable to write to file $path\n";
    }

    foreach($words as $word)
    {
        // skip any blank words
        if ($word == "") continue;

        fputs($fh, "$word\n");
    }
    fclose($fh);

    return "Success";
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
