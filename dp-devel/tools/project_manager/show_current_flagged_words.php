<?php
$relPath="./../../pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'wordcheck_engine.inc');
include_once('./post_files.inc');
include_once('./word_freq_table.inc');

set_time_limit(0); // no time limit

$projectid  = array_get($_REQUEST, "projectid",  "");
$freqCutoff = array_get($_REQUEST, "freqCutoff", 5);

enforce_edit_authorization($projectid);

// $format determins what is presented from this page:
//   'html' - page is rendered with frequencies included
//   'file' - all words and frequencies are presented as a 
//            downloaded file
// 'update' - update the list
$format = array_get($_REQUEST, "format", "html");

if($format=="update") {
    $postedWords = parse_posted_words($_POST);

    $words = load_project_good_words($projectid);
    $words = array_merge($words,$postedWords);
    save_project_good_words($projectid,$words);

    $format="html";
}

list($bad_words_w_freq ,$messages) = _get_word_list($projectid);
$title = _("Candidates for Good Words List from WordCheck");
$page_text = _("Displayed below are the words from this project that WordCheck would currently flag for proofers.");
$page_text .= " ";
$page_text .= _("The results list was generated by accessing the most recent text of each page and running it through the WordCheck engine, including the project's current Word Lists. The results list also shows how many times each word occurs in the project text.");

if($format == "file") {
    $filename="${projectid}_WordCheck_flag_words.txt";
    header("Content-type: text/plain");
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    // The cache-control and pragma is a hack for IE not accepting filenames
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');

    
    echo $title . "\r\n";
    echo sprintf(_("Project: %s"),get_project_name($projectid)) . "\r\n";
    echo "\r\n";
    echo $page_text . "\r\n";
    echo "\r\n";
    echo_page_instruction_text( "good", $format );
    echo "\r\n";
    echo_download_text( $projectid, $format );
    echo "\r\n";
    echo _("Format: [word] - [frequency in text]") . "\r\n";
    echo "\r\n";

    foreach( $bad_words_w_freq as $word => $freq)
        echo "$word - $freq\r\n";

    // we're done here, exit
    exit;
} elseif($format == "html") {
    // fall-through
} else {
    assert(false);
}


$no_stats=1;
theme($title,'header');

echo_page_header($title,$projectid);

// how many instances (ie: frequency sections) are there?
$instances=1;

// what are the cutoff options?
$cutoffOptions = array(1,2,3,4,5,10,25,50);
// what is the intial cutoff frequecny?
$initialFreq=getInitialCutoff($freqCutoff,$cutoffOptions,$bad_words_w_freq);

// echo page support text, like JS and stylesheets
echo_cutoff_script($cutoffOptions,$instances);

echo_word_freq_style();

echo "<p>$page_text</p>";

echo_page_instruction_text( "good", $format );

echo_download_text( $projectid, $format );

echo_any_warnings_errors( $messages );

echo_cutoff_text( $initialFreq,$cutoffOptions );

$context_array=build_context_array_links($bad_words_w_freq,$projectid,$word);

// load the project and site bad words to include in the Notes column
// load project languages
$languages = array_unique(array_values(get_project_languages($projectid)));

// load site word lists for project languages
$site_bad_words = array();
foreach ( $languages as $language ) {
    $langcode3 = langcode3_for_langname( $language );
    $site_bad_words = array_merge($site_bad_words, load_site_bad_words($langcode3));
}
$site_bad_words = array_unique($site_bad_words);

// load project bad words
$project_bad_words = load_project_bad_words($projectid);

$word_notes=array();
foreach($site_bad_words as $word)
    $word_notes[$word]=_("On site BWL");

foreach($project_bad_words as $word)
    $word_notes[$word]=_("On project BWL");

$context_array["[[TITLE]]"]=_("Show Context");
$word_notes["[[TITLE]]"]=_("Notes");

$word_checkbox = build_checkbox_array($bad_words_w_freq);

echo_checkbox_selects(count($bad_words_w_freq));

$checkbox_form["projectid"]=$projectid;
$checkbox_form["freqCutoff"]=$freqCutoff;
echo_checkbox_form_start($checkbox_form);
echo_checkbox_form_submit(_("Add selected words to Good Words List"));

printTableFrequencies($initialFreq,$cutoffOptions,$bad_words_w_freq,$instances--,array($context_array,$word_notes), $word_checkbox);

echo_checkbox_form_end();

theme('','footer');

//---------------------------------------------------------------------------
// supporting page functions

function _get_word_list($projectid) {
    $messages = array();

    // get the latest project text of all pages up to last possible round
    $last_possible_round = get_Round_for_round_number(MAX_NUM_PAGE_EDITING_ROUNDS);
    $pages_res = page_info_query($projectid,$last_possible_round->id,'LE');
    $page_texts = get_page_texts($pages_res);

    // now run it through WordCheck
    list($bad_words_w_freq,$languages,$messages) =
    get_bad_words_for_text($page_texts,$projectid,'all','',array(),'FREQS');

    // multisort screws up all-numeric words so we need to preprocess first
    prep_numeric_keys_for_multisort($bad_words_w_freq);

    // sort the list by frequency, then by word
    array_multisort(array_values($bad_words_w_freq), SORT_DESC, array_map( 'strtolower', array_keys($bad_words_w_freq) ), SORT_ASC, $bad_words_w_freq);

    return array($bad_words_w_freq,$messages);
}

// vim: sw=4 ts=4 expandtab
?>
