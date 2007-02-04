<?php
$relPath="./../../pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'dp_main.inc');
include_once($relPath.'word_checker.inc');

set_time_limit(0); // no time limit

$projectid = $_GET["projectid"];

// if format is 'text', all words and frequencies will be printed
// if format is not 'text', an HTML page is displayed
$format = @$_GET["format"];

// anything that appears in the list less than this number
// won't show up in the list
$minFreq = array_get($_GET, 'minFreq', 5);

// load the suggestions
$suggestions = load_project_good_word_suggestions($projectid);
if(!is_array($suggestions)) {
    echo "Unable to load suggestions: $suggestions";
    exit;
}

// array to hold all words
$allWords = array();

// parse the suggestions complex array
// it is in the format: $suggestions[$round][$pagenum]=$wordsArray
foreach( $suggestions as $round => $pageArray ) {
    $roundWords[$round] = array();
    foreach( $pageArray as $page => $words) {
        // add the words to the per-round array
        $roundWords[$round] = array_merge($roundWords[$round],$words);

        // add the words to the combined array too
        $allWords = array_merge($allWords,$words);

        $pageCount[$round]++;
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
    header("Content-type: application/octet-stream");
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');

    // print out the complete list first
    echo "All rounds:\n";
    foreach( $allCount as $word => $freq )
        echo "$word - $freq\n";
    echo "\n";

    // now per round
    foreach($rounds as $round) {
        echo "Round: $round\n";
        echo "Number of pages with data: " . $pageCount[$round] . "\n";
        foreach( $wordCount[$round] as $word => $freq)
            echo "$word - $freq\n";
        echo "\n";
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

<?
$cutoffOptions = array(1,2,3,4,5,10,25,50);
$cutoffString = "";
foreach($cutoffOptions as $cutoff) {
    if($cutoff == $minFreq)
        $cutoffString .= "$cutoff | ";
    else
        $cutoffString .= "<a href='generate_acceptword_suggestions.php?projectid=$projectid&amp;minFreq=$cutoff'>$cutoff</a> | ";
}
$cutoffString = preg_replace("/ \| $/","",$cutoffString);
?>
<p>Words that appear less than <?php echo $minFreq;?> times are not shown. Other cutoff options are available: <?PHP echo $cutoffString; ?>.</p>

<p>You can also <a href="generate_acceptword_suggestions.php?projectid=<?PHP echo $projectid; ?>&amp;format=text">download</a> a copy of the word list with frequencies for offline analysis. When adding the final list to the input box on the Edit Project page, the frequencies can be left in and the system will remove them.</p>

<?php
// print out the complete list first
echo "<h2>All rounds</h2>";
_printTableFrequencies($allCount);

// now per round
foreach($rounds as $round) {
    echo "<h2>Round $round</h2>\n";
    echo "<p>Number of pages with data: " . $pageCount[$round] . "</p>";
    _printTableFrequencies($wordCount[$round]);
}

function _printTableFrequencies($wordCount) {
    global $minFreq;

    echo '<table>';
    echo '<tr><th>' . _('Frequency') . '</th><th>' . _('Word') . '</th></tr>';

    // we'll do it in a table so project managers can copy-paste
    // the values list into the accept textarea
    // words printed
    $words_printed = 0;

    // freq side
    echo "<tr><td><hr>";
    foreach( $wordCount as $word => $freq ) {
        if($freq < $minFreq) break;
        echo "$freq<br>\n";
        $words_printed++;
    }
    echo "</td>\n";

    // word side
    echo "<td><hr>";
    foreach( $wordCount as $word => $freq ) {
        if($freq < $minFreq) break;
        echo "$word<br>\n";
    }
    echo '</td></tr></table>';

    $freqString = _('%d additional words with frequency less than %d were found and not shown.');
    echo '<p>' . sprintf($freqString,sizeof($wordCount)-$words_printed,$minFreq) . '</p>';
}

// vim: sw=4 ts=4 expandtab
?>
</body>
</html>
