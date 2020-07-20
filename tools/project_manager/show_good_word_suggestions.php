<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'wordcheck_engine.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'misc.inc'); // get_integer_param(), get_enumerated_param()
include_once('./post_files.inc');
include_once("./word_freq_table.inc");

require_login();

$datetime_format = "%A, %B %e, %Y %X";

set_time_limit(0); // no time limit

$projectid  = get_projectID_param($_REQUEST, 'projectid');
$fileObject = get_project_word_file($projectid,"good");
$timeCutoff = get_integer_param($_REQUEST, 'timeCutoff', $fileObject->mod_time, 0, null);
$freqCutoff = get_integer_param($_REQUEST, 'freqCutoff', 5, 0, null);

enforce_edit_authorization($projectid);

if($timeCutoff==0)
    $time_cutoff_text = _("<b>All proofreader suggestions</b> are included in the results.");
else
    $time_cutoff_text = sprintf(_("Only proofreader suggestions made <b>after %s</b> are included in the results."),strftime($datetime_format,$timeCutoff));


// $format determines what is presented from this page:
//   'html' - page is rendered with frequencies included
//   'file' - all words and frequencies are presented as a
//            downloaded file
// 'update' - update the list
$format = get_enumerated_param($_REQUEST, 'format', 'html', array('html', 'file', 'update'));

if($format=="update") {
    $postedWords = parse_posted_words($_POST);

    $words = load_project_good_words($projectid);
    $words = array_merge($words,$postedWords);
    save_project_good_words($projectid,$words);

    $format="html";
}

list($all_suggestions_w_freq,$all_suggestions_w_occurrences,$round_suggestions_w_freq,$round_suggestions_w_occurrences,$rounds,$round_page_count,$messages) =
    _get_word_list($projectid,$timeCutoff);

$title = _("Candidates for Good Words List from Proofreaders");
$page_text = sprintf(_("Displayed below are the words that proofreaders have suggested (via the %s button) in the WordCheck interface that have not been already included in the project's Good Words List."),"<img src='$code_url/graphics/Book-Plus-Small.gif'>");
$page_text .= " ";
$page_text .= _("The results list also shows how many times each word occurs in the project text and how many times each word was suggested by proofreaders.");

if($format == "file") {
    $filename="${projectid}_proofer_suggestions.txt";
    header("Content-type: text/plain");
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    // The cache-control and pragma is a hack for IE not accepting filenames
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');

    echo $title . "\r\n";
    echo sprintf(_("Project: %s"),get_project_name($projectid)) . "\r\n";
    echo "\r\n";
    echo strip_tags($page_text) . "\r\n";
    echo "\r\n";
    echo_page_instruction_text( "good", $format );
    echo "\r\n";
    echo_download_text( $projectid, $format );
    echo "\r\n";
    echo strip_tags($time_cutoff_text) . "\r\n";
    echo _("Format: [word] - [frequency in text] - [frequency suggested]") . "\r\n";
    echo "\r\n";

    // print out the complete list first
    echo _("All rounds") . "\r\n";
    foreach( $all_suggestions_w_freq as $word => $freq )
        echo "$word - $freq - " . $all_suggestions_w_occurrences[$word] . "\r\n";
    echo "\r\n";

    // now per round
    if(count($rounds)) {
        foreach($rounds as $round) {
            $round_string=sprintf(_("Round %s"),$round);
            $page_num_string=sprintf(_("Number of pages with data: %d"), $round_page_count[$round]);
            echo "$round_string\r\n";
            echo "$page_num_string\r\n"; 
            foreach( $round_suggestions_w_freq[$round] as $word => $freq)
                echo "$word - $freq - " . $round_suggestions_w_occurrences[$round][$word] . "\r\n";
            echo "\r\n";
        }
    }

    // we're done here, exit
    exit;
} elseif($format == "html") {
    // fall-through
} else {
    assert(false);
}

// how many instances (ie: frequency sections) are there?
$instances = count( $rounds ) + 1;
// what are the cutoff options?
$cutoffOptions = array(1,2,3,4,5,10,25,50);

output_header($title, NO_STATSBAR, array("js_data" => get_cutoff_script($cutoffOptions,$instances)));
echo_page_header($title,$projectid);

// what is the initial cutoff frequency?
$initialFreq=getInitialCutoff($freqCutoff,$cutoffOptions,$all_suggestions_w_freq);

echo "<p>$page_text</p>";

echo_page_instruction_text( "good", $format );

echo_any_warnings_errors( $messages );

echo "<form action='show_good_word_suggestions.php' method='get'>";
echo "<p>$time_cutoff_text ";
echo _("This setting also controls the words that will be included in the downloaded file.");
echo " ";
echo "<input type='hidden' name='freqCutoff' value='$initialFreq' id='freqCutoffValue'>";
echo "<input type='hidden' name='projectid' value='$projectid'>";
echo "<input type='hidden' name='format' value='$format'>";
echo _("Change the results to show:") . " ";
echo "<select name='timeCutoff'>";
echo "<option value='0' "; if($timeCutoff==0) echo "selected"; echo ">" . _("All suggestions") . "</option>";
echo "<option value='$fileObject->mod_time'"; if($timeCutoff==$fileObject->mod_time) echo "selected"; echo ">" . _("Suggestions since Good Words List was saved") . "</option>";
$timeCutoffOptions=array(1,2,3,4,5,6,7,14,21);
foreach($timeCutoffOptions as $timeCutoffOption) {
    $timeCutoffValue = ceil((time() - 24*60*60*$timeCutoffOption)/100)*100;
    echo "<option value='$timeCutoffValue'";
    if($timeCutoff==$timeCutoffValue) echo "selected";
    echo ">" . sprintf(_("Suggestions in the past %d days"),$timeCutoffOption) . "</option>";
}
echo "</select>";
echo "<input type='submit' value='Submit'>";
echo "</p></form>";

// if there are no suggestions available (probably because they are all already
// on the Good Words List) stop here
if(count($all_suggestions_w_occurrences)==0) {
    echo "<p>" . _("There are no suggestions in the given time frame that aren't already on the Good Words List.") . "</p>";
    exit;
}

echo_download_text( $projectid, $format, "timeCutoff=$timeCutoff" );

echo_cutoff_text( $initialFreq,$cutoffOptions );

$submit_label=_("Add selected words to Good Words List");

$checkbox_form["projectid"]=$projectid;
$checkbox_form["freqCutoff"]=$freqCutoff;
$checkbox_form["timeCutoff"]=$timeCutoff;
echo_checkbox_form_start($checkbox_form);

$context_array["[[TITLE]]"]=_("Show Context");

// see how many rounds actually have data in them
$roundsWithData=0;
foreach($rounds as $round) {
    if(count($round_suggestions_w_occurrences[$round])>0)
        $roundsWithData++;
}

// print out the complete list first but only if
// we have more than one round with data
if($roundsWithData>1) {
    foreach($all_suggestions_w_occurrences as $word => $occur) {
        $encWord = encode_word($word);
        $context_array[$word]=recycle_window_link("show_good_word_suggestions_detail.php?projectid=$projectid&amp;word=$encWord",_("Context"),"context");
    }
    $all_suggestions_w_occurrences["[[TITLE]]"]=_("Times Suggested");
    $all_suggestions_w_occurrences["[[STYLE]]"]="text-align: right;";

    echo "<h2>" . _("All rounds") . "</h2>";
    $word_checkbox = build_checkbox_array($all_suggestions_w_freq,'all');
    echo_checkbox_selects(count($all_suggestions_w_freq),'all');
    echo_checkbox_form_submit($submit_label);

    printTableFrequencies($initialFreq,$cutoffOptions,$all_suggestions_w_freq,$instances--,array($all_suggestions_w_occurrences,$context_array), $word_checkbox);

    echo_checkbox_form_submit($submit_label);
}

// now per round
foreach($rounds as $round) {
    // if there are no words for this round, skip it
    if(count($round_suggestions_w_occurrences[$round])==0)
        continue;

    foreach($round_suggestions_w_occurrences[$round] as $word => $occur) {
        $encWord = encode_word($word);
        $context_array[$word]=recycle_window_link("show_good_word_suggestions_detail.php?projectid=$projectid&amp;word=$encWord",_("Context"),"context");
    }
    $round_suggestions_w_occurrences[$round]["[[TITLE]]"]=_("Times Suggested");
    $round_suggestions_w_occurrences[$round]["[[STYLE]]"]="text-align: right;";

    $round_string=sprintf(_("Round %s"),$round);
    $page_num_string=sprintf(_("Number of pages with suggestions: %d"), $round_page_count[$round]);
    echo "<h2>$round_string</h2>";
    echo "<p>$page_num_string</p>";

    if(count($round_suggestions_w_freq[$round])==0)
    {
        echo "<p>" . _("None of the suggested words remain in the saved text for this round.") . "</p>";
        continue;
    }

    $word_checkbox = build_checkbox_array($round_suggestions_w_freq[$round],$round);
    echo_checkbox_selects(count($round_suggestions_w_freq[$round]),$round);
    echo_checkbox_form_submit($submit_label);

    printTableFrequencies( $initialFreq,$cutoffOptions,$round_suggestions_w_freq[$round],$instances--,array($round_suggestions_w_occurrences[$round],$context_array),$word_checkbox );

    echo_checkbox_form_submit($submit_label);
}

echo_checkbox_form_end();


//---------------------------------------------------------------------------
// supporting page functions

function _get_word_list($projectid,$timeCutoff) {
    $messages = array();

    // load the suggestions
    $suggestions = load_project_good_word_suggestions($projectid,$timeCutoff);
    if(!is_array($suggestions)) {
        $messages[] = sprintf(_("Unable to load suggestions: %s"),$suggestions);
        return array( array(), array(), array(), array(), array(), array(), $messages);
    }

    if(count($suggestions)==0) {
        return array( array(), array(), array(), array(), array(), array(), $messages);
    }

    // load project good words
    $project_good_words = load_project_good_words($projectid);

    // load project bad words
    $project_bad_words = load_project_bad_words($projectid);

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

        // remove any words already on the project's good or bad words lists
        $round_suggestions = array_diff( $round_suggestions, array_merge($project_good_words,$project_bad_words) );

        // get the suggestion occurrences
        $round_suggestions_w_occurrences[$round] = generate_frequencies($round_suggestions);

        // get suggestion with project word frequency
        $round_suggestions_w_freq[$round] = array_intersect_key( $all_words_w_freq, array_flip( $round_suggestions ) );

        // multisort screws up all-numeric words so we need to preprocess first
        prep_numeric_keys_for_multisort( $round_suggestions_w_freq[$round] );

        // sort the list by frequency, then by word
        array_multisort( array_values( $round_suggestions_w_freq[$round]), SORT_DESC, array_map( 'strtolower', array_keys( $round_suggestions_w_freq[$round] )), SORT_ASC, $round_suggestions_w_freq[$round] );
    }

    // now, remove any words that are already on the project's good or bad words lists
    $all_suggestions = array_diff( $all_suggestions, array_merge($project_good_words,$project_bad_words) );

    // get the number of suggestion occurrences
    $all_suggestions_w_occurrences = generate_frequencies( $all_suggestions );

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

    return array($all_suggestions_w_freq, $all_suggestions_w_occurrences, $round_suggestions_w_freq, $round_suggestions_w_occurrences,$rounds, $round_page_count, $messages);
}

// vim: sw=4 ts=4 expandtab
?>
