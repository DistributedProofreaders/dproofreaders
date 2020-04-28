<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'misc.inc'); // attr_safe(), html_safe()
include_once($relPath.'Project.inc');
include_once($relPath.'wordcheck_engine.inc');
include_once($relPath.'misc.inc'); // get_integer_param(), get_enumerated_param(), attr_safe()
include_once('./post_files.inc');
include_once('./word_freq_table.inc');

require_login();

set_time_limit(0); // no time limit

$projectid  = validate_projectID('projectid', @$_REQUEST['projectid']);
$freqCutoff = get_integer_param($_REQUEST, 'freqCutoff', 5, 0, null);

$queryWordText = array_get($_POST, "queryWordText", "");
// do some cleanup on the input string
$queryWordText = str_replace("\r","",$queryWordText);
$queryWords = explode("\n",$queryWordText);
// do some cleanup on the resulting words
$queryWords = array_map('ltrim', $queryWords);
$queryWords = preg_replace('/\s+.*$/','',$queryWords);
$queryWords = array_unique($queryWords);
// remove any empty words
$queryWords = array_diff($queryWords,array(''));
// now reset the input string to our sanitized values
$queryWordText = implode("\r\n",$queryWords);

enforce_edit_authorization($projectid);

// $format determins what is presented from this page:
//    'html' - page is rendered with frequencies included
// 'update' - update the list
$format         = get_enumerated_param($_REQUEST, 'format', 'html', array('html', 'update'));
$wordListTarget = get_enumerated_param($_POST, 'wordlisttarget', 'bad', array('good', 'bad'));

if($format=="update") {
     $postedWords = parse_posted_words($_POST);

     if($wordListTarget=="good") {
          $words = load_project_good_words($projectid);
          $words = array_merge($words,$postedWords);
          save_project_good_words($projectid,$words);
     } elseif($wordListTarget=="bad") {
          $words = load_project_bad_words($projectid);
          $words = array_merge($words,$postedWords);
          save_project_bad_words($projectid,$words);
     }

     $format="html";
}

$title = _("Ad Hoc Word Details");
$page_text = _("Insert words, one per line, in the box below and click the Show details button to get frequency and context details for them.");
$page_text2 = _("The results list below shows how many times each word occurs in the most recent project text.");

// how many instances (ie: frequency sections) are there?
$instances = 1;
// what are the cutoff options?
$cutoffOptions = array(1,2,3,4,5,10,25,50);

output_header($title, NO_STATSBAR, array("js_data" => get_cutoff_script($cutoffOptions,$instances)));

echo_page_header($title,$projectid);

echo "<p>$page_text</p>";
echo "<form action='show_adhoc_word_details.php' method='post'>";
echo "<input type='hidden' name='projectid' value='$projectid'>";
echo "<p><textarea cols='20' rows='6' name='queryWordText'>$queryWordText</textarea></p>";
echo "<input type='submit' value='", attr_safe(_("Show details")), "'>";
echo "</form>";

if(count($queryWords)) {
    echo "<hr>";

    list($words_w_freq ,$messages) = _get_word_list($projectid, $queryWords);

    // what is the intial cutoff frequecny?
    $initialFreq=getInitialCutoff($freqCutoff,$cutoffOptions,$words_w_freq);

    echo_any_warnings_errors( $messages );

    echo "<p>" . $page_text2 . "</p>";

    echo_cutoff_text( $initialFreq,$cutoffOptions );

    $context_array=build_context_array_links($words_w_freq,$projectid);

    // load the project and site bad words to include in the Notes column
    $site_bad_words = load_site_bad_words_given_project($projectid);

    // load project good and bad words
    $project_bad_words = load_project_bad_words($projectid);
    $project_good_words = load_project_good_words($projectid);

    $word_notes=array();
    foreach($words_w_freq as $word => $freq) {
        $notes=array();
        if(in_array($word,$site_bad_words))
            $notes[]=_("On site BWL");
        if(in_array($word,$project_bad_words))
            $notes[]=_("On project BWL");
        if(in_array($word,$project_good_words))
            $notes[]=_("On project GWL");
        if(count($notes))
            $word_notes[$word]=implode(", ",$notes);
    }

    $context_array["[[TITLE]]"]=_("Show Context");
    $word_notes["[[TITLE]]"]=_("Notes");

    $word_checkbox = build_checkbox_array($words_w_freq);

    $checkbox_form["projectid"]=$projectid;
    $checkbox_form["freqCutoff"]=$freqCutoff;
    $checkbox_form["queryWordText"]=html_safe($queryWordText);
    echo_checkbox_form_start($checkbox_form);

    echo "<p>" . _("Words can be added to either the Good or the Bad word list. Select which of the project's lists to add the words to.") . "</p>";
    echo "<input type='radio' name='wordlisttarget' value='good'"; if($wordListTarget=="good") echo " checked"; echo "> ";
    echo _("Good Words List") . "<br>";
    echo "<input type='radio' name='wordlisttarget' value='bad'"; if($wordListTarget=="bad") echo " checked"; echo "> ";
    echo _("Bad Words List") . "<br>";

    echo_checkbox_selects(count($words_w_freq));

    echo_checkbox_form_submit(_("Add selected words"), GOOD_WORDS_LIST);

    printTableFrequencies($initialFreq,$cutoffOptions,$words_w_freq,$instances--,array($context_array,$word_notes), $word_checkbox);

    echo_checkbox_form_submit(_("Add selected words"), GOOD_WORDS_LIST);
    echo_checkbox_form_end();
}

//---------------------------------------------------------------------------
// supporting page functions

function _get_word_list($projectid, $queryWords) {
    $messages = array();

    // get the latest project text of all pages up to last possible round
    $last_possible_round = get_Round_for_round_number(MAX_NUM_PAGE_EDITING_ROUNDS);
    $pages_res = page_info_query($projectid,$last_possible_round->id,'LE');
    $page_texts = get_page_texts($pages_res);

    // now run it through WordCheck
    $all_words_w_freq=get_distinct_words_in_text($page_texts);

    $words_w_freq=array();
    foreach($queryWords as $word) {
        if(@$all_words_w_freq[$word]) {
              $words_w_freq[$word]=$all_words_w_freq[$word];
        }
    }

    // multisort screws up all-numeric words so we need to preprocess first
    prep_numeric_keys_for_multisort($words_w_freq);

    // sort the list by frequency, then by word
    array_multisort(array_values($words_w_freq), SORT_DESC, array_map( 'strtolower', array_keys($words_w_freq) ), SORT_ASC, $words_w_freq);

    return array($words_w_freq,$messages);
}

// vim: sw=4 ts=4 expandtab
?>
