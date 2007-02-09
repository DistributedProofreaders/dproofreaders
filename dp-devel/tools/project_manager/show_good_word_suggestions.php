<?
$relPath="./../../pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'dp_main.inc');
include_once($relPath.'word_checker.inc');
include_once("./word_freq_table.inc");

set_time_limit(0); // no time limit

$projectid = $_GET["projectid"];

// if format is 'text', all words and frequencies will be printed
// if format is not 'text', an HTML page is displayed
$format = @$_GET["format"];

// load the suggestions
$suggestions = load_project_good_word_suggestions($projectid);
if(!is_array($suggestions)) {
    echo "Unable to load suggestions: $suggestions";
    exit;
}

// array to hold all words
$allWords = array();
$pageCount = array();

// parse the suggestions complex array
// it is in the format: $suggestions[$round][$pagenum]=$wordsArray
foreach( $suggestions as $round => $pageArray ) {
    $roundWords[$round] = array();
    foreach( $pageArray as $page => $words) {
        // add the words to the per-round array
        $roundWords[$round] = array_merge($roundWords[$round],$words);

        // add the words to the combined array too
        $allWords = array_merge($allWords,$words);

        @$pageCount[$round]++;
    }

    // get the word frequencies
    $wordCount[$round] = generate_frequencies($roundWords[$round]);

    // sort the list by frequency, then by word
    array_multisort(array_values($wordCount[$round]), SORT_DESC, array_keys($wordCount[$round]), SORT_ASC, $wordCount[$round]);
}

$allCount = generate_frequencies($allWords);

// sort the list by frequency, then by word
array_multisort(array_values($allCount), SORT_DESC, array_keys($allCount), SORT_ASC, $allCount);

$rounds = array_keys($wordCount);

// if the user wants the list in text-only mode
if($format == "text") {
    # The following is a pure hack for evil IE not accepting filenames
    $filename="${projectid}_proofer_suggestions.txt";
    header("Content-type: text/plain");
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');

    // print out the complete list first
    echo "All rounds:\r\n";
    foreach( $allCount as $word => $freq )
        echo "$word - $freq\r\n";
    echo "\r\n";

    // now per round
    foreach($rounds as $round) {
        echo "Round: $round\r\n";
        echo "Number of pages with data: " . $pageCount[$round] . "\r\n";
        foreach( $wordCount[$round] as $word => $freq)
            echo "$word - $freq\r\n";
        echo "\r\n";
    }

    exit;
}

?>
<html>
<head>
<title>Suggestions from Proofers</title>
</head>
<body>
<h1>Suggestions from Proofers</h1>
<p>Below are the words that proofers have suggested (via the <img src="<?=$code_url;?>/graphics/Book-Plus-Small.gif"> button) in the WordCheck interface. The words have been sorted into rounds as well as an overall list. You may want to consider adding these words to the project's Good Words list. See also the <a href="<?=$code_url;?>/faq/spellcheck-faq.php">WordCheck FAQ</a> for more information on the new WordCheck system.</p>

<p>You can <a href="generate_acceptword_suggestions.php?projectid=<?=$projectid; ?>&amp;format=text">download</a> a copy of the full word list with frequencies for offline analysis. When adding the final list to the input box on the Edit Project page, the frequencies can be left in and the system will remove them.</p>

<?
// how many instances (ie: frequency sections) are there?
$instances=count($rounds)+1;
// what is the intial cutoff frequecny?
$initialFreq=5;
// what are the cutoff options?
$cutoffOptions = array(1,2,3,4,5,10,25,50);

echo_cutoff_script($cutoffOptions,$instances);
$cutoffString = get_cutoff_string($cutoffOptions);
?>

<p>Words that appear less than <b><span id="current_cutoff"><?=$initialFreq;?></span></b> times are not shown. Other cutoff options are available: <?=$cutoffString;?>.</p>

<?
// print out the complete list first
echo "<h2>All rounds</h2>";
printTableFrequencies($initialFreq,$cutoffOptions,$allCount,$instances--);

// now per round
foreach($rounds as $round) {
    echo "<h2>Round $round</h2>\n";
    echo "<p>Number of pages with data: " . $pageCount[$round] . "</p>";
    printTableFrequencies($initialFreq,$cutoffOptions,$wordCount[$round],$instances--);
}

// vim: sw=4 ts=4 expandtab
?>
</body>
</html>
