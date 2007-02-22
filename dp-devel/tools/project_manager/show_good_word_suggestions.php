<?
$relPath="./../../pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'dp_main.inc');
include_once($relPath.'wordcheck_engine.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'Stopwatch.inc');
include_once('./post_files.inc');
include_once("./word_freq_table.inc");

$watch = new Stopwatch;
$watch->start();

set_time_limit(0); // no time limit

$projectid = $_GET["projectid"];


// $format determins what is presented from this page:
//   'html' - page is rendered with frequencies included
//   'file' - all words and frequencies are presented as a
//            downloaded file
// 'prompt' - page to prompt for downloading the results
$format = array_get($_GET, "format", "html");

if($format == "file") {
    $t_before = $watch->read();
    list($all_suggestions_w_freq,$all_suggestions_w_occurances,$round_suggestions_w_freq,$round_suggestions_w_occurances,$rounds,$round_page_count,$messages) =
        _get_word_list($projectid);
    $t_after = $watch->read();
    $t_to_generate_data = $t_after - $t_before;

    $filename="${projectid}_proofer_suggestions.txt";
    header("Content-type: text/plain");
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    // The cache-control and pragma is a hack for IE not accepting filenames
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');

    echo _("Candidates for project's Good Words List from Proofers") . "\r\n";
    echo sprintf(_("Project: %s"),get_project_name($projectid)) . "\r\n";
    echo _("Format: [word] - [frequency in text] - [frequency suggested]") . "\r\n";
    echo "\r\n";

    // print out the complete list first
    echo _("All rounds") . "\r\n";
    foreach( $all_suggestions_w_freq as $word => $freq )
        echo "$word - $freq - " . $all_suggestions_w_occurances[$word] . "\r\n";
    echo "\r\n";

    // now per round
    foreach($rounds as $round) {
        $round_string=sprintf(_("Round %s"),$round);
        $page_num_string=sprintf(_("Number of pages with data: %d"), $round_page_count[$round]);
        echo "$round_string\r\n";
        echo "$page_num_string\r\n"; 
        foreach( $round_suggestions_w_freq[$round] as $word => $freq)
            echo "$word - $freq - " . $round_suggestions_w_occurances[$round][$word] . "\r\n";
        echo "\r\n";
    }

    // we're done here, exit
    exit;
} elseif($format == "prompt") {
    $title = _("Download candidates for project's Good Words List from Proofers");
    $page_text = sprintf(_("A list of the words that proofers have suggested (via the %s button) in the WordCheck interface that have not been already included in the project's Good Words List is available for download."),"<img src='$code_url/graphics/Book-Plus-Small.gif' title='Unflag All &amp; Suggest'>");
} elseif($format == "html") {
    $title = _("Candidates for project's Good Words List from Proofers");
    $page_text = sprintf(_("Displayed below are the words that proofers have suggested (via the %s button) in the WordCheck interface that have not been already included in the project's Good Words List."),"<img src='$code_url/graphics/Book-Plus-Small.gif'>");

    $t_before = $watch->read();
    list($all_suggestions_w_freq,$all_suggestions_w_occurances,$round_suggestions_w_freq,$round_suggestions_w_occurances,$rounds,$round_page_count,$messages) =
        _get_word_list($projectid);
    $t_after = $watch->read();
    $t_to_generate_data = $t_after - $t_before;
} else {
    assert(false);
}

$page_text .= " ";
$page_text .= _("The results list also shows how many times each word occurs in the project text and how many times each word was suggested by proofers.");

echo_page_header($title,$projectid);

if($format == "html") {
    // how many instances (ie: frequency sections) are there?
    $instances=count( $rounds ) + 1;
    // what are the cutoff options?
    $cutoffOptions = array(1,2,3,4,5,10,25,50);
    // what is the intial cutoff frequecny?
    $initialFreq=getInitialCutoff(5,$cutoffOptions,$all_suggestions_w_freq);

    // echo page support text, like JS and stylesheets
    echo_cutoff_script($cutoffOptions,$instances);
}

echo_word_freq_style();

echo "<p>$page_text</p>";

echo_page_instruction_text( "good" );

echo_download_text( $projectid, $format );

echo_wordcheck_faq_text();

echo_any_warnings_errors( $messages );

echo_page_instruction_reiteration_text( "good" );

if($format == "html") {
    echo_cutoff_text( $initialFreq,$cutoffOptions );

    // print out the complete list first
    echo "<h2>" . _("All rounds") . "</h2>";
    printTableFrequencies($initialFreq,$cutoffOptions,$all_suggestions_w_freq,$instances--,$all_suggestions_w_occurances,_("Times Suggested"));

    // now per round
    foreach($rounds as $round) {
        $round_string=sprintf(_("Round %s"),$round);
        $page_num_string=sprintf(_("Number of pages with data: %d"), $round_page_count[$round]);
        echo "<h2>$round_string</h2>";
        echo "<p>$page_num_string</p>";
        printTableFrequencies( $initialFreq,$cutoffOptions,$round_suggestions_w_freq[$round],$instances--,$round_suggestions_w_occurances[$round],"Times Suggested" );
    }
} elseif($format == "prompt") {
    $link_string = sprintf(_("<a href='%s'>Download</a> the word list file for offline analysis."),"?projectid=$projectid&amp;format=file");
    echo "<p>$link_string</p>";

    $link_string = sprintf(_("Alternatively you can view the results <a href='%s'>online</a>."),"?projectid=$projectid");
    echo "<p>$link_string</p>";
}

echo_page_footer($t_to_generate_data);


//---------------------------------------------------------------------------
// supporting page functions

function _get_word_list($projectid) {
    $messages = array();

    // load the suggestions
    $suggestions = load_project_good_word_suggestions($projectid);
    if(!is_array($suggestions)) {
        $messages[] = sprintf(_("Unable to load suggestions: %s"),$suggestions);
        return array( array(), array(), $messages);
    }

    // load project good words
    $project_good_words = load_project_good_words($projectid);

    // get the latest project text of all pages up to last possible round
    $last_possible_round = get_Round_for_round_number(MAX_NUM_PAGE_EDITING_ROUNDS);
    $pages_res = page_info_query($projectid,$last_possible_round->id,'LE');
    $all_words_w_freq = get_distinct_words_in_text( get_page_texts( $pages_res ));

    // array to hold all words
    $all_suggestions = array();
    $round_page_count = array();

    // parse the suggestions complex array
    // it is in the format: $suggestions[$round][$pagenum]=$wordsArray
    foreach( $suggestions as $round => $pageArray ) {
        $round_suggestions = array();
        foreach( $pageArray as $page => $words) {
            // add the words to the per-round array
            $round_suggestions = array_merge($round_suggestions,$words);

            // add the words to the combined array too
            $all_suggestions = array_merge($all_suggestions,$words);

            @$round_page_count[$round]++;
        }

        // remove any words already on the project's good word list
        $round_suggestions = array_diff( $round_suggestions, $project_good_words );

        // get the suggestion occurances
        $round_suggestions_w_occurances[$round] = generate_frequencies($round_suggestions);

        // get suggestion with project word frequency
        $round_suggestions_w_freq[$round] = array_intersect_key( $all_words_w_freq, array_flip( $round_suggestions ) );

        // multisort screws up all-numeric words so we need to preprocess first
        prep_numeric_keys_for_multisort( $round_suggestions_w_freq[$round] );

        // sort the list by frequency, then by word
        array_multisort( array_values( $round_suggestions_w_freq[$round]), SORT_DESC, array_map( 'strtolower', array_keys( $round_suggestions_w_freq[$round] )), SORT_ASC, $round_suggestions_w_freq[$round] );
    }

    // now, remove any words that are already on the project's good word list
    $all_suggestions = array_diff( $all_suggestions, $project_good_words );

    // get the number of suggestion occurances
    $all_suggestions_w_occurances = generate_frequencies( $all_suggestions );

    // $all_suggestions doesn't have frequency info,
    // so start with the info in $all_words_w_freq,
    // and extract the items where the key matches a key in $all_suggestions.
    $all_suggestions_w_freq = array_intersect_key( $all_words_w_freq, array_flip( $all_suggestions ) );

    // multisort screws up all-numeric words so we need to preprocess first
    prep_numeric_keys_for_multisort( $all_suggestions_w_freq );

    // sort the list by frequency, then by word
    array_multisort( array_values( $all_suggestions_w_freq ), SORT_DESC, array_map( 'strtolower', array_keys( $all_suggestions_w_freq ) ), SORT_ASC, $all_suggestions_w_freq );

    // get a list of all rounds
    $rounds = array_keys($round_suggestions_w_freq);

    return array($all_suggestions_w_freq, $all_suggestions_w_occurances, $round_suggestions_w_freq, $round_suggestions_w_occurances,$rounds, $round_page_count, $messages);
}

// vim: sw=4 ts=4 expandtab
?>
