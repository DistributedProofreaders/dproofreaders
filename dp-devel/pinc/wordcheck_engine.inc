<?PHP
include_once($relPath.'site_vars.php');
include_once($relPath.'iso_639_list.inc');
include_once($relPath.'iso_lang_list.inc'); // langcode3_for_langname
include_once($relPath.'misc.inc'); // str_contains

// Handle dp-specific notation for non-Latin-1 characters
// See faq/proofreading_guidelines.php#d_chars
// $base and $mark are only used to define $bracketed_character_pattern
$base = '[a-zA-Z]';           // pattern for: markable base character
$mark = '[=:.`\\\\\'/v)~,^]'; // pattern for: diacritical mark
$bracketed_character_pattern = "\\[(oe|OE|$mark$base|$base$mark)\\]";

// regex of word characters
// This is used when splitting a text into words.
// NOTE: This should probably be changed when moving to UTF-8
//       at that time we can probably move to using \W instead of
//       the mess below
$word_letters= "a-zA-ZÀ-ÿ";
$word_characters = "0-9'$word_letters";
$word_pattern = "!([$word_characters]|$bracketed_character_pattern)+!";
// (The pattern is delimited by exclamation marks rather than the usual slashes,
// because slash is a character within the pattern.)

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

function get_bad_words_for_text( $text, $projectid, $imagefile, $aux_language, $adhoc_good_words )
// Arguments:
//   text - the text for which bad words are sought
//   projectid - id of project, needed for temp filename
//               and to load the custom dictionaries
//   imagefile - image filename, needed for temp filename
//   aux_languages - auxiliary language to check against
//   adhoc_good_words - array of words to treat as good for this invocation only.
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

         $acc->add_bad_words( get_bad_words_via_pattern($text,$langcode3s), WC_SITE );
    }

    // The project
    {
        $acc->remove_good_words( load_project_good_words($projectid) );

        $acc->add_bad_words( load_project_bad_words($projectid), WC_PROJECT );
    }

    // The page
    {
        $acc->remove_good_words( $adhoc_good_words );
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
                $aspell_command="cat $tmp_file_path | {$aspell_executable} list --prefix={$aspell_prefix} -d $dict_file --encoding {$charset}";
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

    // Consider non-Latin-1 characters, which we represent with []-notations.
    // For example, consider the oe ligature, which we represent as "[oe]",
    // and the words "b[oe]uf" (which we want the word-checker to not flag),
    // and "b[oe]ut", a scanno for the former (which we do want flagged).
    //
    // If we feed text like "b[oe]uf" (or "b[oe]ut") to aspell, it sees the
    // square brackets as word separators, and so complains that 'oe' and 'uf'
    // (or "oe" and "ut") are misspelled. But that does us no good, because we
    // don't see 'oe' and 'uf' (or 'ut') as words, and won't ever ask if 'oe' or
    // 'uf' (or 'ut') are misspelled. 
    //
    // Note that aspell appears to have no way to represent non-Latin-1
    // characters, so there's no way to transform "b[oe]uf" into something
    // (distinct from just "boeuf") that it understands.
    //
    // So we give up on aspell being any help with such words, and instead put
    // the burden on site-level and project-level word lists.
    //
    // The problem is that, although it's reasonable to ask someone to put
    // "b[oe]uf" in a good word list, it's not reasonable to ask someone to
    // think up all the possible ways of scannoing it (including "b[oe]ut"),
    // and put those in a bad word list. Instead, we simply mark *all* words
    // containing non-Latin-1 characters as bad at the world-level.
    //
    foreach( get_all_words_in_text($orig_text) as $word )
    {
        if ( str_contains($word, '[') )
        {
            $finalMisspellings[] = $word;
        }
    }

    // make a point *not* to unique the array, some callers might
    // want to know the frequency of the misspelled words

    return array($finalMisspellings,$messages);
}

// -----------------------------------------------------------------------------

// returns a list of 'bad' words in a text based on some pattern
// Arguments:
//   orig_text - original text
//   langcode3s - codes for languages to check against
//
// Returns:
//       -- an array of bad words
//
function get_bad_words_via_pattern( $orig_text, $langcode3s ) {
    global $word_letters;

    $badWords = array();
    $wordArray = get_all_words_in_text( $orig_text );

    // pattern #1
    // flag all words with embedded digits while
    // ignoring common valid <digit><letter> combinations
    //
    $exceptions_for_lang = array(
        'eng' => '\d*[04-9]th|\d*1[123]th|(\d*[02-9])?(1st|2nd|3rd)',
        'fre' => '\d+(er|me)',
        // etc
    );
    $exceptions = array();
    foreach ( $langcode3s as $langcode3 )
    {
        $exceptions[] = $exceptions_for_lang[$langcode3];
    }
    $exceptions = implode('|', $exceptions);
    $exceptions_pattern = "/^($exceptions)$/";

    foreach($wordArray as $word) {
        // if the word has both a digit and a word_letter
        if(preg_match("/\d/",$word) && preg_match("/[$word_letters]/",$word)) {

            // ignore common <digit><letter> combinations
            if(preg_match($exceptions_pattern,$word)) continue;

            array_push($badWords,$word);
        }
    }

    // placeholder for future patterns

    return $badWords;
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

function save_project_good_word_suggestions($projectid,$round,$page,$proofer,$words) {
    global $projects_dir;

    if(sizeof($words)==0 || (sizeof($words)==1 && $words[0]=="")) return "Success";

    $word_list=implode(' ',$words);

    $iw_name= "$projects_dir/$projectid/good_word_suggestions.txt";
    $fh = @fopen( $iw_name, "a" );
    // check to see that we were able to open the file
    if($fh) {
        // get an exclusive lock; lock is cleared by fclose
        flock($fh,LOCK_EX);
        fputs($fh,"$round/$page/$proofer/$word_list\n");
        fclose($fh);
    } else {
        return "Error: Unable to write to file $iw_name\n";
    }

    return "Success";
}

function load_project_good_word_suggestions($projectid) {
    global $projects_dir;

    $wordsArray = array();

    $iw_name = "$projects_dir/$projectid/good_word_suggestions.txt";
    if(!is_file($iw_name))
        return "Error: File does not exist: $iw_name\n";

    $fh = @fopen( $iw_name, "r" );
    // check to see that we were able to open the file
    if($fh) {
        // get a share-level lock; lock is cleared by fclose
        flock($fh,LOCK_SH);
        while(!feof($fh)) {
            $line = chop(fgets($fh));
            if($line == "") continue;
            list($round,$page,$proofer,$words) = explode('/',$line,4);
                // (Need to limit the explosion to 4, because '/' might occur in $words.)
            $words = explode(' ',$words);
            if(!is_array(@$wordsArray[$round][$page]))
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

    // get an exclusive lock; lock is cleared by fclose
    flock($fh,LOCK_EX);

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


// returns an associative array of site word lists
// with their corresponding URL
//   $array[$absolute_filename]=$url_link_to_file;
function get_site_word_lists() {
    global $dyn_dir;
    global $dyn_url;

    $wordLists=array();

    $wordsDir="$dyn_dir/words";
    if(is_dir($wordsDir)) {
       if($dirHandle=@opendir($wordsDir)) {
          while(($filename = readdir($dirHandle)) !== false) {
             // skip files that don't have 'words' in the name
             if(!preg_match("/words/",$filename)) continue;
             // skip files that are only one byte or smaller
             if(filesize("$wordsDir/$filename")<=1) continue;

             $wordLists["$wordsDir/$filename"]="$dyn_url/words/$filename";
          }
       closedir($dirHandle);
       }
    }

    return $wordLists;
}

// -----------------------------------------------------------------------------

function get_all_words_in_text( $text, $with_offsets=FALSE )
// Returns an array whose values are all occurrences of all words in $text, in order
// (i.e., with duplicates).
//
// $with_offsets:
//    If false (the default), the keys are just consecutive integers.
//    If true, then for each value, the corresponding key is the offset
//        (in $text) of the start of that word.
//
//    If $text is project-sized, you should use $with_offsets=FALSE, otherwise
//    you'll get back an array of (say) 200,000 entries with keys from about 0
//    to (say) 1,000,000, and for some reason, PHP 4.4.2 (and maybe others) is
//    ridiculously inefficient accessing such an array. (E.g., a simple var_dump
//    took roughly an hour just to print the first four items in the array!)
//    Note that it's *not* inefficient accessing an array with the same number
//    of items, but having consecutive integers as the keys!
{
    global $word_pattern;
    $flags = ( $with_offsets ? PREG_OFFSET_CAPTURE : 0 );
    $n_matches = preg_match_all( $word_pattern, $text, $matches, $flags );
    if ( $n_matches === FALSE )
    {
        die("get_all_words_in_text: An error occurred.");
    }

    if ( $with_offsets )
    {
        $result = array();
        foreach ( $matches[0] as $item )
        {
            list($word,$offset) = $item;
            $result[$offset] = $word;
        }
        return $result;
    }
    else
    {
        return $matches[0];
    }
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
