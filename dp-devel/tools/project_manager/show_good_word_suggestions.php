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

// if format is 'text', all words and frequencies will be printed
// if format is not 'text', an HTML page is displayed
$format = @$_GET["format"];

$t_before = $watch->read();

$messages = array();

// load the suggestions
$suggestions = load_project_good_word_suggestions($projectid);
if(!is_array($suggestions)) {
    echo "Unable to load suggestions: $suggestions";
    exit;
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
    array_multisort( array_values( $round_suggestions_w_freq[$round]), SORT_DESC, array_keys( $round_suggestions_w_freq[$round] ), SORT_ASC, $round_suggestions_w_freq[$round] );
}

// now, remove any words that are already on the project's good word list
$all_suggestions = array_diff( $all_suggestions, $project_good_words );

// get a list of all rounds
$rounds = array_keys($round_suggestions_w_freq);

// get the number of suggestion occurances
$all_suggestions_w_occurances = generate_frequencies( $all_suggestions );

// $all_suggestions doesn't have frequency info,
// so start with the info in $all_words_w_freq,
// and extract the items where the key matches a key in $all_suggestions.
$all_suggestions_w_freq = array_intersect_key( $all_words_w_freq, array_flip( $all_suggestions ) );
// free up unused variable
unset( $all_suggestions );

// multisort screws up all-numeric words so we need to preprocess first
prep_numeric_keys_for_multisort( $all_suggestions_w_freq );

// sort the list by frequency, then by word
array_multisort( array_values( $all_suggestions_w_freq ), SORT_DESC, array_keys( $all_suggestions_w_freq ), SORT_ASC, $all_suggestions_w_freq );

$t_after = $watch->read();
$t_to_generate_data = $t_after - $t_before;

// if the user wants the list in text-only mode
if($format == "text") {
    $filename="${projectid}_proofer_suggestions.txt";
    header("Content-type: text/plain");
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    // The cache-control and pragma is a hack for IE not accepting filenames
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');

    // print out the complete list first
    echo "All rounds:\r\n";
    foreach( $all_suggestions_w_freq as $word => $freq )
        echo "$word - $freq\r\n";
    echo "\r\n";

    // now per round
    foreach($rounds as $round) {
        echo "Round: $round\r\n";
        echo "Number of pages with data: " . $round_page_count[$round] . "\r\n";
        foreach( $round_suggestions_w_freq[$round] as $word => $freq)
            echo "$word - $freq\r\n";
        echo "\r\n";
    }

    exit;
}

?>
<html>
<head>
<title>Candidates for project's Good Word List from Proofers</title>
</head>
<body>
<h1>Candidates for project's Good Word List from Proofers</h1>
<p>Below are the words that proofers have suggested (via the <img src="<?=$code_url;?>/graphics/Book-Plus-Small.gif"> button) in the WordCheck interface that have not been already added to the project's Good Words List. The words include a frequency of how often they occur in the project text and how many times they were suggested by proofers. You may want to consider adding these words to the project's Good Words list. See also the <a href="<?=$code_url;?>/faq/wordcheck-faq.php">WordCheck FAQ</a> for more information on the new WordCheck system.</p>

<?
echo_download_text( $projectid );

if ( count($messages) > 0 )
{
    echo "<p>\n";
    echo "The following warnings/errors were raised:<br>\n";
    foreach ( $messages as $message )
    {
        echo "$message<br>\n";
    }
    echo "</p>\n";
}

// how many instances (ie: frequency sections) are there?
$instances=count( $rounds ) + 1;
// what are the cutoff options?
$cutoffOptions = array(1,2,3,4,5,10,25,50);
// what is the intial cutoff frequecny?
$initialFreq=getInitialCutoff( 5,$cutoffOptions,$all_suggestions_w_freq );

echo_cutoff_script( $cutoffOptions,$instances );
echo_word_freq_style();
echo_cutoff_text( $initialFreq,$cutoffOptions );

// print out the complete list first
echo "<h2>All rounds</h2>";
printTableFrequencies($initialFreq,$cutoffOptions,$all_suggestions_w_freq,$instances--,$all_suggestions_w_occurances,"Times Suggested");

// now per round
foreach($rounds as $round) {
    echo "<h2>Round $round</h2>\n";
    echo "<p>Number of pages with data: " . $round_page_count[$round] . "</p>";
    printTableFrequencies( $initialFreq,$cutoffOptions,$round_suggestions_w_freq[$round],$instances--,$round_suggestions_w_occurances[$round],"Times Suggested" );
}

// vim: sw=4 ts=4 expandtab
?>
<p>Time to generate this data: <? echo sprintf('%.2f', $t_to_generate_data); ?> seconds</p>
</body>
</html>
