<?php
include_once($relPath.'site_vars.php');
include_once($relPath.'Project.inc');
include_once($relPath.'languages.inc'); // proj_lang_code
include_once($relPath.'iso_lang_list.inc'); // langcode3_for_langname
include_once($relPath.'misc.inc'); // str_contains, array_get
include_once($relPath.'unicode.inc');

// Handle dp-specific notation for non-Latin-1 characters
// See faq/proofreading_guidelines.php#d_chars
// and tools/proofers/process_diacritcal_markup.js
// $base and $mark are only used to define $bracketed_character_pattern
$base = "\w{1,2}";    // pattern for: markable base character
$mark = '[=:.`\\\\\'/v)(~,^\\\\\*]'; // pattern for: diacritical mark
$bracketed_character_pattern = "\\[(?:oe|OE|$mark$base|$base$mark)\\]";

// This is used when splitting a text into words.
$char_pattern = "(?:\w\pM*|$bracketed_character_pattern)";
$word_pattern = "!$char_pattern+(?:'$char_pattern+)*!uS";
// (The pattern is delimited by exclamation marks rather than the usual slashes,
// because slash is a character within the pattern.)
// (Supposedly, the 'S' modifier makes the regex engine spend more time
// analyzing the pattern in order to speed up the time taken for matching,
// but the effect seems pretty close to negligible.)

// string of punctuation characters
// this is only used for punctuation highlighting
$puncCharacters = '.,;:?!*/()#@%+=[]{}<>\"$|_¬¢£¥©®§°±¶·´¸º×¦¡¿-»«¯÷¹²³¼½¾¤';

// -----------------------------------------------------------------------------

// There are four "levels" of specificity
// at which words are deemed bad or good.
define('WC_WORLD',   1);
define('WC_SITE',    2);
define('WC_PROJECT', 3);
define('WC_PAGE',    4);


// Wrapper functions for _get_bad_words_for_project_text() that abstract if
// the function returns FREQS or LEVELS. See that function for arguments
// and return values.
function get_bad_word_freqs_for_project_text($text, $projectid, $aux_language='', $adhoc_good_words=[])
{
    return _get_bad_words_for_project_text('FREQS', $text, $projectid, $aux_language, $adhoc_good_words);
}

function get_bad_word_levels_for_project_text($text, $projectid, $aux_language='', $adhoc_good_words=[])
{
    return _get_bad_words_for_project_text('LEVELS', $text, $projectid, $aux_language, $adhoc_good_words);
}

// Arguments:
//   which_result_values - select either the bad word FREQS or LEVELS
//   text - the text for which bad words are sought
//          This can be a string or an array of strings. If the latter, the
//          strings are conceptually separated by whitespace.  (So it's never
//          the case that a word begins in one string and ends in the next.)
//   projectid - id of project, needed for project languages
//               and to load the custom dictionaries
//   aux_languages - auxiliary language to check against
//   adhoc_good_words - array of words to treat as good for this invocation only.
//
// Returns an array consisting of:
//  -- an array: each key is a word in the text that is deemed bad;
//     the corresponding value is either:
//      -- the word's frequency in $text
//         (if $which_result_values is 'FREQS')
//     or
//      -- one of the WC_* constants, denoting the (most specific) level at which
//         the word was deemed bad
//         (if $which_result_values is 'LEVELS')
//  -- an array: each value is the name of a language that was used
//  -- an array: each value is a warning/error.
function _get_bad_words_for_project_text($which_result_values, $text, $projectid, $aux_language, $adhoc_good_words)
{
    $messages = [];

    // Get the list of languages that we'll use.
    $languages = array_values(get_project_languages($projectid, $aux_language));

    // And the langcode3s for those languages
    list($langcode3s, $new_messages) = get_langcode3s_for_languages($languages);
    $messages += $new_messages;

    $word_lists = [
        "adhoc_good" => $adhoc_good_words,
        "site_good" => load_site_good_words($langcode3s),
        "site_bad" => load_site_bad_words($langcode3s),
        "project_good" => load_project_good_words($projectid),
        "project_bad" => load_project_bad_words($projectid)
    ];

    list($input_words_w_freq, $bad_words, $new_messages) =
        get_bad_words_for_text($text, $languages, $word_lists);
    $messages += $new_messages;

    // At this point, we have two arrays whose keys are words:
    // $input_words_w_freq (words in the text, that may or may not be bad)
    // $acc->words (words that are bad, that may or may not be in the text)
    // The key-intersection of these two arrays will give us the set of words
    // that are both in the text and bad.
    // But the way we do the intersection determines the values that are
    // associated with those words.
    //
    if($which_result_values == 'FREQS')
    {
        // Associate each bad word with its frequency in the text.
        $bad_words_in_text = array_intersect_key($input_words_w_freq, $bad_words);
    }
    elseif($which_result_values == 'LEVELS')
    {
        // Associate each bad word with the most specific level at which it was deemed bad.
        $bad_words_in_text = array_intersect_key($bad_words, $input_words_w_freq);
    }
    else
    {
        assert(FALSE);
    }

    return [$bad_words_in_text, $languages, $messages];
}


// Returns a list of bad words from a given text using the specified
// languages to run the spellchecker and a set of word lists
// Arguments:
//      text - string or array of strings
//      languages - array of language names
//      word_lists - array with the keys:
//          site_good - array of site good words
//          site_bad  - array of site bad words
//          project_good - array of good words from project
//          project_bad - array of bad words from project
//          adhoc_good - array of adhoc good words
// Returns an array that includes:
//      - array of text words with frequency
//      - array of bad words
//      - array of possible warning/error messages
function get_bad_words_for_text($text, $languages, $word_lists=[])
{
    $input_words_w_freq = get_distinct_words_in_text($text);

    $acc = new BadWordAccumulator();

    // Go to various sources for good+bad words,
    // in order of increasing specificity to the given text,
    // and accumulate the results.

    // The world
    {
        list($external_bad_words, $messages) =
            get_bad_words_via_external_checker($input_words_w_freq, $languages);
        $acc->messages += $messages;
        $acc->add_bad_words($external_bad_words, WC_WORLD);

        $acc->add_bad_words(
            get_bad_words_with_diacritical_markup($input_words_w_freq), WC_WORLD);
    }

    // The site
    {
        $acc->remove_good_words(array_get($word_lists, "site_good", []));
        $acc->add_bad_words(array_get($word_lists, "site_bad", []), WC_SITE);

        $acc->add_bad_words(
            get_bad_words_via_pattern($input_words_w_freq, $languages), WC_SITE);

        $acc->add_bad_words(
            get_bad_words_with_multi_scripts($input_words_w_freq), WC_SITE);
    }

    // The project
    {
        $acc->remove_good_words(array_get($word_lists, "project_good", []));

        $acc->add_bad_words(array_get($word_lists, "project_bad", []), WC_PROJECT);
    }

    // The page
    {
        $acc->remove_good_words(array_get($word_lists, "adhoc_good", []));
    }

    return [$input_words_w_freq, $acc->words, $acc->messages];
}

// -----------------------------------------------------------------------------

class BadWordAccumulator
{
    function __construct()
    {
        $this->words = [];
        // Each key is a bad word, and its value is
        // a constant denoting the (most specific) level
        // at which the word was deemed bad.

        $this->messages = [];
    }

    function add_bad_words($bad_words, $val)
    {
        if(is_string($bad_words))
        {
            $this->messages[] = $bad_words;
        }
        else
        {
            foreach($bad_words as $word)
            {
                $this->words[$word] = $val;
            }
        }
    }

    function remove_good_words($good_words)
    {
        if(is_string($good_words))
        {
            $this->messages[] = $good_words;
        }
        else
        {
            foreach($good_words as $word)
            {
                unset($this->words[$word]);
            }
        }
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
// World-Level

// returns a list of 'bad' words on a page
// this implementation passes the text to aspell
// Arguments:
//   input_words_w_freq - an array whose keys are the distinct words of the input text
//   languages - array of languages, used to load aspell dictionary
//               for those languages if available
//
// Returns an array consisting of:
//       -- an array of misspelled words,
//       -- an array of messages (errors/warnings)
//
function get_bad_words_via_external_checker($input_words_w_freq, $languages)
{
    global $aspell_temp_dir;
    global $aspell_executable, $aspell_prefix, $charset;

    $messages = [];

    $tmp_file_path = tempnam($aspell_temp_dir, "pagetext-");

    $tmp_file_text = join("\n", array_keys($input_words_w_freq));
    $wasWritten = file_put_contents($tmp_file_path, $tmp_file_text);
    unset($tmp_file_text);
    if($wasWritten === FALSE)
    {
        $messages[] = sprintf(_("Error: unable to write text to temp file: %s"), $tmp_file_path);
        return [[], $messages];
    }

    $misspellings = [];
    foreach($languages as $language) {
        $langcode = proj_lang_code($language, "primary");
        if($langcode) {
            $dict_file = "$aspell_prefix/lib/aspell/$langcode.multi";
            if(is_file($dict_file)) {
                // run aspell using this language

                // create the aspell command
                $aspell_command="cat $tmp_file_path | {$aspell_executable} list --prefix={$aspell_prefix} -d $dict_file --encoding {$charset}";
                //echo "<!-- aspell command: $aspell_command -->\n"; // Very useful for debugging
                // run aspell
                // "asr" stands for "aspell result"
                $asr_text = trim(shell_exec($aspell_command));
                $asr_text = str_replace(["\r","\n"], ['',"[lf]"], $asr_text);
                // build our list of possible misspellings
                $misspellings[$langcode] = explode("[lf]", $asr_text);
                unset($asr_text);
            }
            else
            {
                $messages[] = sprintf(_("Warning: no external dictionary installed for '%s'"), $language);
            }
        }
        else
        {
            $messages[] = sprintf(_("Warning: unknown language '%s'"), $language);
        }
    }

    // now remove the temporary file
    if(is_file($tmp_file_path)) {
        unlink($tmp_file_path);
    }

    if(count($misspellings) == 0) {
        $finalMisspellings = [];
        return [$finalMisspellings, $messages];
    }

    // now we need to find the intersection between the languages that we've checked
    // for instance, if we've checked against an english and a french dictionary
    // the words that are misspelled in both are the ones actually misspelled
    $langKeys = array_keys($misspellings);
    $finalMisspellings = $misspellings[array_shift($langKeys)];
    foreach($langKeys as $langkey) {
        $finalMisspellings = array_intersect($finalMisspellings, $misspellings[$langkey]);
    }
    unset($misspellings);

    // Finally, only return words that were in our original set. This ensures
    // that aspell hasn't parsed words differently than we did and we then
    // return partial words that aren't actually in the list.
    $finalMisspellings = array_intersect($finalMisspellings, array_keys($input_words_w_freq));

    return [$finalMisspellings, $messages];
}

// Return all words containing diacritical markup
// Arguments:
//   input_words_w_freq - an array whose keys are the distinct words of the input text
//
// Returns:
//       -- an array of bad words
//
function get_bad_words_with_diacritical_markup($input_words_w_freq)
{
    // Consider words that contain diacritical markup via []-notation.
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
    // So we give up on aspell being any help with such words, and instead put
    // the burden on site-level and project-level word lists.
    //
    // The problem is that, although it's reasonable to ask someone to put
    // "b[oe]uf" in a good word list, it's not reasonable to ask someone to
    // think up all the possible ways of scannoing it (including "b[oe]ut"),
    // and put those in a bad word list. Instead, we simply mark *all* words
    // containing non-Latin-1 characters as bad at the world-level.
    //
    $bad_words = [];
    foreach($input_words_w_freq as $word => $freq)
    {
        if(str_contains($word, '['))
        {
            $bad_words[] = $word;
        }
    }

    return($bad_words);
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
// Site-Level

// returns a list of 'bad' words in a text based on some pattern
// Arguments:
//   input_words_w_freq - an array whose keys are the distinct words of the input text
//   languages - array of languages to check against
//
// Returns:
//       -- an array of bad words
//
function get_bad_words_via_pattern($input_words_w_freq, $languages)
{
    list($langcode3s, $messages) = get_langcode3s_for_languages($languages);

    $badWords = [];

    // pattern #1
    // flag all words with embedded digits while
    // ignoring common valid <digit><letter> combinations
    //
    $exceptions_for_lang = [
        'eng' => '\d*[04-9]th|\d*1[123]th|(\d*[02-9])?(1st|2nd|3rd)',
        'fre' => '\d+(er|me)',
        // etc
    ];
    $exceptions = [];
    foreach($langcode3s as $langcode3)
    {
        $exceptions[] = array_get($exceptions_for_lang, $langcode3, "");
    }
    $exceptions = join('|', $exceptions);
    $exceptions_pattern = "/^($exceptions)$/u";

    foreach($input_words_w_freq as $word => $freq) {
        // if the word has both a digit and a word_letter
        if(preg_match("/\d/u", $word) && preg_match("/\D/u", $word)) {

            // ignore common <digit><letter> combinations
            if(preg_match($exceptions_pattern, $word)) continue;

            $badWords[] = $word;
        }
    }

    // placeholder for future patterns

    return $badWords;
}

// Return a list of "bad" words where characters within a word are not all
// of the same Unicode script type. For instance, this detects words that
// contain a mix of Latin and Cyrillic characters.
// Arguments:
//   input_words_w_freq - an array whose keys are the distinct words of the input text
//
// Returns:
//       -- an array of bad words
//
function get_bad_words_with_multi_scripts($input_words_w_freq)
{
    $bad_words = [];

    foreach($input_words_w_freq as $word => $freq)
    {
        $scripts = utf8_string_scripts($word);
        if(count(array_diff($scripts, ["Common", "Inherited"])) > 1)
        {
            $bad_words[] = $word;
        }
    }

    return $bad_words;
}

// -----------------------------------------------------------------------------

function load_site_good_words($langcode3s)
{
    return _load_site_words("good", $langcode3s);
}

function load_site_bad_words($langcode3s)
{
    return _load_site_words("bad", $langcode3s);
}

function load_site_possible_bad_words($langcode3s)
{
    return _load_site_words("possible_bad", $langcode3s);
}

function _load_site_words($type, $langcode3s)
{
    if(!is_array($langcode3s))
        $langcode3s = [$langcode3s];

    $words = [];
    foreach($langcode3s as $langcode3)
    {
        $fileObject = get_site_word_file($langcode3, $type);
        $words = array_merge($words, load_word_list($fileObject->abs_path));
    }

    return array_unique($words);
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
// Project-Level

function load_project_good_words($projectid)
{
    $fileObject = get_project_word_file($projectid, "good");

    return load_word_list($fileObject->abs_path);
}

function save_project_good_words($projectid, $words)
{
    $fileObject = get_project_word_file($projectid, "good");

    return save_word_list($fileObject->abs_path, normalize_word_list($words));
}

// -----------------------------------------------------------------------------

function load_project_bad_words($projectid)
{
    $fileObject = get_project_word_file($projectid, "bad");

    return load_word_list($fileObject->abs_path);
}

function save_project_bad_words($projectid, $words)
{
    $fileObject = get_project_word_file($projectid, "bad");

    return save_word_list($fileObject->abs_path, normalize_word_list($words));
}

// -----------------------------------------------------------------------------

function normalize_word_list($words)
{
    // normalize the strings
    $words = array_map('utf8_normalize', $words);

    // trim out leading spaces
    $words = preg_replace('/^\s+/u', '', $words);

    // trim out any word frequencies if they exist
    $words = preg_replace('/\s.*$/u', '', $words);

    return $words;
}

// -----------------------------------------------------------------------------

// Delete any wordcheck_events for this project.
// This is called when deleting a project.
function delete_project_wordcheck_events($projectid)
{
    $sql = sprintf("
        DELETE FROM wordcheck_events
        WHERE projectid='%s'",
        DPDatabase::escape($projectid)
    );
    DPDatabase::query($sql);
}

// Merge the word list from the source project into the destination
// project, and also copy the wordcheck events to the destination
// project.
function merge_project_wordcheck_data($from_projectid, $to_projectid)
{
    // good words
    $from_words = load_project_good_words($from_projectid);
    $to_words = load_project_good_words($to_projectid);
    $to_words = array_merge($to_words, $from_words);
    save_project_good_words($to_projectid, $to_words);

    // bad words
    $from_words = load_project_bad_words($from_projectid);
    $to_words = load_project_bad_words($to_projectid);
    $to_words = array_merge($to_words, $from_words);
    save_project_bad_words($to_projectid, $to_words);

    // suggestions
    copy_project_wordcheck_events($from_projectid, $to_projectid);
}

// Copy wordcheck events from one project to another project.
// (This is used for instance when splitting or rejoining
// project parts.)
function copy_project_wordcheck_events($from_projectid, $to_projectid)
{
    $sql = sprintf("
        INSERT INTO wordcheck_events
            (projectid, timestamp, image, round_id, username, suggestions, corrections)
        SELECT '%s', timestamp, image, round_id, username, suggestions, corrections
        FROM wordcheck_events
        WHERE projectid = '%s'",
        DPDatabase::escape($to_projectid),
        DPDatabase::escape($from_projectid)
    );
    DPDatabase::query($sql);
    // no results for an INSERT clause, so no need to free the results.
}

// -----------------------------------------------------------------------------

function save_wordcheck_event($projectid, $round, $page, $proofer, $suggestions, $corrections)
{
    // save the corrections in wdiff format
    $correction_list = [];
    foreach ($corrections as list($orig, $new)) {
        $correction_list[] = "[-$orig-] {+$new+}";
    }

    $setters = join(", ", [
        set_col_str("projectid", $projectid),
        set_col_num("timestamp", time()),
        set_col_str("image", $page),
        set_col_str("round_id", $round),
        set_col_str("username", $proofer),
        set_col_str("suggestions", join("\n", $suggestions)),
        set_col_str("corrections", join("\n", $correction_list))
    ]);
    $sql = "INSERT INTO wordcheck_events
            SET $setters";
    DPDatabase::query($sql);
}

// Count the number of wordcheck_events for this project
// which are more recent than $start_time, and which contain
// suggestions. This function can be used as a faster alternative
// to counting the result of load_wordcheck_events, when one does
// not need the precise number of suggested words.
function count_wordcheck_suggestion_events($projectid, $start_time=0)
{
    $sql = sprintf("
        SELECT count(*) AS numevents FROM wordcheck_events
        WHERE projectid='%s'
        AND timestamp>%d AND suggestions<>''",
        DPDatabase::escape($projectid),
        $start_time
    );

    $res = DPDatabase::query($sql);

    $row = mysqli_fetch_assoc($res);

    mysqli_free_result($res);

    return $row["numevents"];
}

function load_wordcheck_events($projectid, $start_time=0)
{
    $sql = sprintf("
        SELECT * FROM wordcheck_events
        WHERE projectid='%s',
        AND timestamp>%d",
        DPDatabase::escape($projectid),
        $start_time
    );

    $res = DPDatabase::query($sql);

    $eventArray = [];
    while($resultSet = mysqli_fetch_assoc($res)) {
        $time = $resultSet["timestamp"];
        $round = $resultSet["round_id"];
        $page = $resultSet["image"];
        $proofer = $resultSet["username"];
        $words = $resultSet["suggestions"];
        $corrections = $resultSet["corrections"];

        if($words != "")
            $words = explode("\n", $words);
        else
            $words = [];

        if($corrections != "")
            $corrections = explode("\n", $corrections);
        else
            $corrections = [];

        $eventArray[] = [$time, $round, $page, $proofer, $words, $corrections];
    }

    mysqli_free_result($res);

    return $eventArray;
}


function load_project_good_word_suggestions($projectid, $start_time=0)
{
    $eventArray = load_wordcheck_events($projectid, $start_time);

    $wordsArray = [];

    if(!count($eventArray)) return $wordsArray;

    foreach($eventArray as $event) {
        list($time, $round, $page, $proofer, $words, $corrections) = $event;

        if(count($words)) {
            if(!is_array(@$wordsArray[$round][$page]))
                $wordsArray[$round][$page] = [];
            $wordsArray[$round][$page] = array_merge($wordsArray[$round][$page], $words);
        }
    }

    return $wordsArray;
}

// -----------------------------------------------------------------------------

function load_site_good_words_given_project($projectid)
{
    $languages = array_values(get_project_languages($projectid));
    list($langcode3s, $messages) = get_langcode3s_for_languages($languages);
    return load_site_good_words($langcode3s);
}

function load_site_bad_words_given_project($projectid)
{
    $languages = array_values(get_project_languages($projectid));
    list($langcode3s, $messages) = get_langcode3s_for_languages($languages);
    return load_site_bad_words($langcode3s);
}

function load_site_possible_bad_words_given_project($projectid)
{
    $languages = array_values(get_project_languages($projectid));
    list($langcode3s, $messages) = get_langcode3s_for_languages($languages);
    return load_site_possible_bad_words($langcode3s);
}

// -----------------------------------------------------------------------------

// $code must match one of the keys in the $filename_for_code array below.
function get_project_word_file($projectid, $code)
{
    global $projects_dir, $projects_url;

    $filename_for_code = [
        'good'       => 'good_words.txt',
        'bad'        => 'bad_words.txt',
    ];
    assert(array_key_exists($code, $filename_for_code));

    return get_file_info_object($filename_for_code[$code],
                "$projects_dir/$projectid",
                "$projects_url/$projectid");
}

// $code must match one of the keys in the $filename_for_code array below.
function get_site_word_file($langcode3, $code)
{
    global $dyn_dir, $dyn_url;

    $filename_for_code = [
        'good'         => "good_words.$langcode3.txt",
        'bad'          => "bad_words.$langcode3.txt",
        'possible_bad' => "possible_bad_words.$langcode3.txt",
    ];
    assert(array_key_exists($code, $filename_for_code));

    return get_file_info_object($filename_for_code[$code],
                "$dyn_dir/words",
                "$dyn_url/words");
}

// Returns an object containing information about the specified
// word-related file for the given project:
// --- filename: its filename;
// --- abs_path: its absolute path;
// --- abs_url:  its absolute URL;
// --- exists:   a boolean indicating whether it exists;
// --- size:     its size in bytes;
// --- mod_time: the time it was last modified, as a unix timestamp.
// (size and mod_time are set to zero if the file doesn't exist.)
function get_file_info_object($filename, $base_dir, $base_url)
{

    clearstatcache();

    $o = new StdClass;

    $o->filename = $filename;
    $o->abs_path = "$base_dir/{$o->filename}";
    $o->abs_url  = "$base_url/{$o->filename}";
    $o->exists   = @file_exists($o->abs_path);
    $o->size     = $o->exists ? filesize($o->abs_path) : 0;
    $o->mod_time = $o->exists ? filemtime($o->abs_path) : 0;

    return $o;
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// Load a list of words (one per line) from a file.
// Returns:
//   on success: an array of words
//   on error:   a string containing an error message
function load_word_list($path)
{
    if(!is_file($path))
    {
        // The file does not exist.
        // Treat that the same as if it existed and was empty.
        return [];
    }

    // Note: we're unable to use the file() function's FILE_IGNORE_NEW_LINES
    // parameter since it wasn't added until PHP 5.0.0 *and* because
    // it only removes \n, not \r\n, so we'd still need to rtrim later
    $words = file($path);
    if($words === FALSE)
    {
        return sprintf(_("Error reading file %s"), $path);
    }

    // trim word list to remove trailing spaces
    // needed in case site word list has trailing spaces
    // And if/since the file has \r\n line-ends,
    // this will also remove the trailing \r.
    array_walk($words, 'rtrim_walk');

    // remove the last "empty" word caused by the last newline
    if(count($words) && $words[count($words)-1]=="")
        array_pop($words);

    return $words;
}

// Save a list of words (one per line) to a file.
// Return a string, either "Success" or an error message.
function save_word_list($path, $words)
{
    // standardize word list
    array_walk($words, 'rtrim_walk');
    $words = array_unique($words);
    uasort($words,"deterministicStrnatcasecmp");

    // remove any empty words
    $words = array_diff($words, ['']);

    // pull out the values to reset the numeric keys;
    // needed for the array == array comparison below
    $words = array_values($words);

    // see if list has changed before saving it
    $oldWords = load_word_list($path);

    // if no change was made, return Success
    // without modifying file
    if($words == $oldWords)
    {
        return "Success";
    }


    // now open the file
    $fh = @fopen($path, "w");

    if($fh === FALSE)
    {
        return _("Error: Unable to write to wordlist file.");
    }

    // get an exclusive lock; lock is cleared by fclose
    flock($fh, LOCK_EX);

    foreach($words as $word)
    {
        // skip any blank words
        if ($word == "") continue;

        fputs($fh, "$word\r\n");
    }
    fclose($fh);

    return "Success";
}

// -----------------------------------------------------------------------------

// Returns an associative array of all languages for a project
//   $array[$langcode]=$language;
function get_project_languages($projectid, $aux_language=NULL)
{
    $returnArray = [];

    try
    {
        $project = new Project($projectid);
    }
    catch(NonexistentProjectException $exception)
    {
        return $returnArray;
    }
    $languages = preg_split('/ with /', $project->language);
    if($aux_language)
        $languages[] = $aux_language;

    foreach($languages as $language) {
        $langcode = proj_lang_code($language, "primary");
        if($langcode) {
            $returnArray[$langcode] = $language;
        }
    }

    return array_unique($returnArray);
}


// Returns an associative array of site word lists
// with their corresponding URL
//   $array[$absolute_filename]=$url_link_to_file;
function get_site_good_bad_word_lists()
{
    return get_site_word_files("/^(good|bad)_words\.[a-z]{3}\.txt$/");
}

// Returns an associative array of site possible bad word lists
// with their corresponding URL
//   $array[$absolute_filename]=$url_link_to_file;
function get_site_possible_bad_word_lists()
{
    return get_site_word_files("/^possible_bad_words\.[a-z]{3}\.txt$/");
}

// Arguments:
//   $pattern - regex that files must match to be included
//              in the returned array. If blank all files
//              will be returned.
//   $urlbase - if TRUE files will be turned as a URL,
//              if FALSE absolute filesystem paths are returned
//
// Returns an associative array of words ifiles in the site's words
// with their corresponding URL
//   $array[$absolute_filename]=$url_link_to_file;
//
// Note: 'empty' files (2 bytes in size or smaller) are not returned
// regardless of the pattern specified
function get_site_word_files($pattern="", $urlbase=TRUE)
{
    global $dyn_dir;
    global $dyn_url;

    if($urlbase)
        $base = $dyn_url;
    else
        $base = $dyn_dir;

    $wordLists = [];

    $wordsDir="$dyn_dir/words";
    if(is_dir($wordsDir)) {
        foreach(scandir($wordsDir) as $filename) {
            // skip files that don't match the pattern
            if(!empty($pattern) && !preg_match($pattern, $filename)) continue;

            // skip files that are only two bytes or smaller
            // as these usually contain just \r and/or \n
            if(filesize("$wordsDir/$filename")<=2) continue;

            $wordLists["$wordsDir/$filename"] = "$base/words/$filename";
        }
    }

    // sort the list before returning
    ksort($wordLists);

    return $wordLists;
}

// -----------------------------------------------------------------------------

// Returns an array: each key is a distinct word in $text, and
// the corresponding value is that word's frequency in $text.
//
// This function is equivalent to
//     array_count_values(get_all_words_in_text($text));
// but (when appropriate) avoids reifying the intermediate array.
function get_distinct_words_in_text($text)
{
    // For a $text with typical-sized words, the memory consumed by the result
    // of get_all_words_in_text($text) is about 20 times the size of $text.
    // So if $text is project-sized (say, 1.5Mb), the resulting array will be
    // rather large (about 30 Mb).)
    //
    // Somewhat arbitrarily, we say that an intermediate array of about 1Mb is
    // the maximum acceptable, which roughly works out to a $text of 50kb.
    $break_point = 50 * 1024;
    // In practice, $text is either a page (usually under 5kb) or a project
    // (usually at least 100kb), so any value for $break_point in the range of
    // 5kb to 100kb would have the same effect on branching.

    if(is_string($text))
    {
        if(strlen($text) < $break_point)
        {
            // The result of get_all_words_in_text($text) will be small enough.
            $input_words_w_freq = array_count_values(get_all_words_in_text($text));
        }
        else
        {
            // The result of get_all_words_in_text($text) would be rather large,
            // so avoid it.  The following alternative is somewhat slower but
            // more space efficient.
            global $word_pattern;

            $input_words_w_freq = [];
            $offset = 0;
            $flags = PREG_OFFSET_CAPTURE;
            while(preg_match($word_pattern, $text, $matches, $flags, $offset))
            {
                list($word, $word_start) = $matches[0];
                @$input_words_w_freq[$word]++;
                $offset = $word_start + strlen($word);
            }
        }
    }
    elseif(is_array($text))
    {
        // We assume that the strings in $text are smaller than $break_point,
        // so it's okay to call get_all_words_in_text() on each. (Currently,
        // each string is a single page-text, so the assumption is valid.)

        $input_words_w_freq = [];
        foreach($text as $chunk)
        {
            foreach(get_all_words_in_text($chunk) as $word)
            {
                @$input_words_w_freq[$word] += 1;
            }
        }
    }
    else
    {
        assert(FALSE);
    }

    return $input_words_w_freq;
}

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
function get_all_words_in_text($text, $with_offsets=FALSE)
{
    global $word_pattern;
    $flags = $with_offsets ? PREG_OFFSET_CAPTURE : 0;
    $n_matches = preg_match_all($word_pattern, $text, $matches, $flags);
    if($n_matches === FALSE)
    {
        die("get_all_words_in_text: An error occurred.");
    }

    if($with_offsets)
    {
        $result = [];
        foreach($matches[0] as list($word, $offset_bytes))
        {
            $search_offset = 0;
            // offsets from preg_match_all() are in bytes, but we need the
            // offset in characters
            $offset = utf8_strlen(substr($text, 0, $offset_bytes));
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
function generate_frequencies($wordList)
{
    $wordCount = array_count_values($wordList);
    unset($wordCount['']);
    return $wordCount;
}

// Given an array of language names, return a list of valid langcode3s.
// Arguments:
//      languages - array of language names
// Returns an array of:
//      array of langcode3s
//      array of zero or more error/warning messages
function get_langcode3s_for_languages($languages)
{
    $messages = [];
    $langcode3s = [];
    foreach($languages as $language)
    {
        $langcode3 = langcode3_for_langname($language);
        if(is_null($langcode3))
        {
            $messages[] = sprintf(_("Warning: unknown language '%s'"), $language);
        }
        else
        {
            $langcode3s[] = $langcode3;
        }
    }

    return [$langcode3s, $messages];
}

// function for comparing words similar to strnatcasecmp
// but deterministicly; without the strnatcmp() call
// if the words are the same discounting case, the calling
// sort function would be non-deterministic based on PHP
// docs (see online docs for usort()) and manual confirmation
function deterministicStrnatcasecmp($a, $b)
{
    return ($cmp = strnatcasecmp($a, $b)) != 0 ? $cmp : strnatcmp($a, $b);
}

// rtrim version to use in array_walk
function rtrim_walk(&$item)
{
    $item = rtrim($item);
}

// vim: sw=4 ts=4 expandtab
