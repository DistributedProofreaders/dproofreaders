<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'wordcheck_engine.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'misc.inc'); // attr_safe()
include_once($relPath.'Stopwatch.inc');
include_once($relPath.'misc.inc'); // array_get(), get_integer_param(), surround_and_join()
include_once('./post_files.inc');
include_once("./word_freq_table.inc"); // echo_cutoff_text()
include_once($relPath.'page_controls.inc');

require_login();

// TRANSLATORS: This is a strftime-formatted string for the date with year and time
$datetime_format = _("%A, %B %e, %Y at %X");

$watch = new Stopwatch;
$watch->start();

set_time_limit(0); // no time limit

$freqCutoff = get_integer_param($_REQUEST, 'freqCutoff', 5, 0, null);
$timeCutoff = get_integer_param($_REQUEST, 'timeCutoff', -1, -1, null);

// load the PM
$pm = array_get($_REQUEST,"pm",$pguser);
if ( !user_is_a_sitemanager() && !user_is_proj_facilitator() ) {
    $pm = $pguser;
}

$action = get_enumerated_param($_REQUEST, 'action', 'view', array('view', 'update'));

if($action=="update") {
    $newProjectWords=array();
    foreach($_POST as $key => $val) {
        if(preg_match("/cb_(projectID[0-9a-f]{13})_(\d+)/",$key,$matches)) {
            $projectid=$matches[1];
            $word=decode_word($val);
            if(!is_array(@$newProjectWords[$projectid]))
                $newProjectWords[$projectid]=array();
            array_push($newProjectWords[$projectid],$word);
        }
    }

    foreach($newProjectWords as $projectid => $projectWords) {
        $words = load_project_good_words($projectid);
        $words = array_merge($words,$projectWords);
        save_project_good_words($projectid,$words);
    }
}
/*
// $frame determines which frame we're operating from
//     none - we're the master frame
//   'left' - we're the left frame with the text
//  'right' - we're the right frame for the context info
$frame = get_enumerated_param($_REQUEST, 'frame', 'master', array('master', 'left', 'right'));

if($frame=="master") {
    slim_header_frameset(_("Manage Suggestions"));
    $frameSpec='cols="40%,60%"';
    if(@$_REQUEST["timecutoff"])
        $timeCutoffSpec="timeCutoff=$timeCutoff&amp;";
    else $timeCutoffSpec="";
?>
<frameset <?php echo $frameSpec; ?>>
<frame src="<?php echo attr_safe($_SERVER['PHP_SELF']); ?>?pm=<?php echo urlencode($pm); ?>&amp;freqCutoff=<?php echo $freqCutoff; ?>&amp;<?php echo $timeCutoffSpec; ?>frame=left">
<frame name="detailframe" src="<?php echo attr_safe($_SERVER['PHP_SELF']); ?>?frame=right">
</frameset>
<noframes>
<?php echo _("Your browser currently does not display frames!"); ?>
</noframes>
<?php
    exit;
}

// now load data in the left frame
if($frame=="left") {
*/

// get all projects for this PM
$projects = _get_projects_for_pm($pm);

$submitLabel = _("Add selected words to Good Words List");

// how many instances (ie: frequency sections) are there?
$instances=count( $projects ) + 1;
// what are the cutoff options?
$cutoffOptions = array(1,2,3,4,5,10,25,50);
// what is the initial cutoff frequency?
$initialFreq=getInitialCutoff($freqCutoff,$cutoffOptions);

$header_args = [
    "js_files" => [
        "$code_url/scripts/splitControl.js",
        "$code_url/scripts/page_browse.js",
        "./show_all_good_word_suggestions.js",
    ],
    "js_data" => get_proofreading_interface_data_js() .
        get_cutoff_script($cutoffOptions,$instances),

    "body_attributes" => 'class="no-margin overflow-hidden" style="height: 100vh; width: 100vw"',
];

slim_header(_("Manage Suggestions"), $header_args);

echo "<div id='suggestions_container' style='flex: auto;width: 100%; height: 100%'>";
echo "<div class='overflow-auto'>";
echo "<div style='padding: 0.5em'>";

echo "<h1>" . _("Manage Suggestions") . "</h1>";

// TRANSLATORS: PM = project manager
echo "<p><a href='$code_url/tools/project_manager/projectmgr.php' target='_TOP'>" . _("Return to the PM page") . "</a></p>";

echo "<form action='" . attr_safe($_SERVER['PHP_SELF']) . "' method='get'>";
echo "<input type='hidden' name='frame' value='left'>";
echo "<p>";
if ( user_is_a_sitemanager() || user_is_proj_facilitator() ) {
    echo _("View projects for user:") . " <input type='text' name='pm' value='" . attr_safe($pm) . "' size='10'><br>";
}

echo _("Show") . ": ";
echo "<select name='timeCutoff'>";
echo "<option value='0'"; if($timeCutoff==0) echo "selected"; echo ">" . _("All suggestions") . "</option>";
echo "<option value='-1'"; if($timeCutoff==-1) echo "selected"; echo ">" . _("Suggestions since Good Words List was saved") . "</option>";
$timeCutoffOptions=array(1,2,3,4,5,6,7,14,21);
foreach($timeCutoffOptions as $timeCutoffOption) {
    $timeCutoffValue = ceil((time() - 24*60*60*$timeCutoffOption)/100)*100;
    echo "<option value='$timeCutoffValue'";
    if($timeCutoff==$timeCutoffValue) echo "selected";
    echo ">" . sprintf(_("Suggestions made in the past %d days"),$timeCutoffOption) . "</option>";
}
echo "</select>";
echo "<br>";

echo "<input type='submit' value='Submit'></p>";
echo "</form>";

if($timeCutoff==-1)
    $time_cutoff_text = _("Suggestions since the project's Good Words List was last modified are included.");
elseif($timeCutoff==0)
    $time_cutoff_text = _("<b>All proofreader suggestions</b> are included in the results.");
else
    $time_cutoff_text = sprintf(_("Only proofreader suggestions made <b>after %s</b> are included in the results."),strftime($datetime_format,$timeCutoff));

echo "<p>" . $time_cutoff_text . "</p>";

echo "<p>" . sprintf(_("Selecting any '%s' button will add all selected words to their corresponding project word list, not just the words in the section for the button itself."),$submitLabel) . "</p>";

echo_cutoff_text( $initialFreq,$cutoffOptions );

$t_before = $watch->read();

echo "<form action='" . attr_safe($_SERVER['PHP_SELF']) . "' method='post'>";
echo "<input type='hidden' name='action' value='update'>";
echo "<input type='hidden' name='frame' value='left'>";
echo "<input type='hidden' name='pm' value='" . attr_safe($pm) . "'>";
echo "<input type='hidden' name='timeCutoff' value='$timeCutoff'>";

$projectsNeedingAttention=0;
// loop through the projects
foreach($projects as $projectid=>$projectdata) {
    list($projectname,$projectstate)=$projectdata;
    $goodFileObject = get_project_word_file($projectid,"good");

    // set the timeCutoff
    if($timeCutoff==-1) $timeCutoffActual=$goodFileObject->mod_time;
    else $timeCutoffActual=$timeCutoff;

    // load suggestions since cutoff
    $suggestions = load_project_good_word_suggestions($projectid,$timeCutoffActual);

    // if there are no suggestions since the cutoff, skip it
    if(!count($suggestions)) continue;

    // get the data
    list($suggestions_w_freq,$suggestions_w_occurrences,$messages) =
        _get_word_list($projectid,$suggestions);

    // if no words are returned (probably because something was
    // suggested but is no longer in the text) skip this project
    if(count($suggestions_w_freq)==0) continue;

    $projectsNeedingAttention++;

    echo "<hr>";
    echo "<h3>$projectname</h3>";
    echo "<p><b>" . pgettext("project state", "State") . ":</b> $projectstate</p>";

    echo_checkbox_selects(count($suggestions_w_freq),$projectid);

    echo_any_warnings_errors( $messages );

    $count=0;
    foreach($suggestions_w_freq as $word => $freq) {
        $encWord = encode_word($word);
        $context_array[$word]="<a href='show_good_word_suggestions_detail.php?projectid=$projectid&amp;word=$encWord&amp;timeCutoff=$timeCutoffActual' target='detailframe'>" . _("Context") . "</a>";
        $word_checkbox[$word]="<input type='checkbox' id='cb_{$projectid}_{$count}' name='cb_{$projectid}_{$count}' value='$encWord'>";
        $count++;
    }
    $suggestions_w_occurrences['[[TITLE]]']=_("Sugg");
    $suggestions_w_occurrences['[[STYLE]]']="text-align: right;";
    $context_array['[[TITLE]]']=_("Show Context");

    printTableFrequencies($initialFreq,$cutoffOptions,$suggestions_w_freq,$instances--,array($suggestions_w_occurrences,$context_array),$word_checkbox);

    echo "<p><input type='submit' value='$submitLabel'></p>";
}

if($projectsNeedingAttention==0) {
    echo "<p>" . _("No projects have proofreader suggestions for the given timeframe.") . "</p>";
} else {
    echo "<hr>";
}

echo "</form>";

$t_after = $watch->read();
$t_to_generate_data = $t_after - $t_before;

echo_page_footer($t_to_generate_data);

echo "</div></div>";

echo "<div>";
echo "<iframe name='detailframe' width='100%' height='100%' style='border:none;'></iframe>";
echo "</div></div>";

//---------------------------------------------------------------------------
// supporting page functions

function _get_word_list($projectid,$suggestions) {
    $messages = array();

    // check that there are suggestions
    if(count($suggestions)==0) {
        return array( array(), array(), $messages);
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

    // parse the suggestions complex array
    // it is in the format: $suggestions[$round][$pagenum]=$wordsArray
    foreach( $suggestions as $round => $pageArray ) {
        foreach( $pageArray as $page => $words) {
            // add the words to the combined array too
            $all_suggestions = array_merge($all_suggestions,$words);
        }
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

    return array($all_suggestions_w_freq, $all_suggestions_w_occurrences, $messages);
}

function _get_projects_for_pm($pm) {
    $returnArray=array();

    $states = _get_project_states_in_order();
    $stateString = surround_and_join( $states, "'", "'", ',' );
    $where = "state IN ($stateString)";
    $collator = "FIELD(state,$stateString)";
    $query = sprintf("
        SELECT projectid, state, nameofwork
        FROM projects
        WHERE username='%s' AND $where
        ORDER BY $collator, nameofwork
    ", mysqli_real_escape_string(DPDatabase::get_connection(), $pm));

    $res = mysqli_query(DPDatabase::get_connection(), $query);
    while($ar = mysqli_fetch_array($res)) {
        $returnArray[$ar["projectid"]]=array($ar["nameofwork"],$ar["state"]);
    }
    return $returnArray;

}

function _get_project_states_in_order() {
    global $Round_for_round_id_;

    $projectStates = array();

    foreach($Round_for_round_id_ as $round_id => $round) {
        array_push($projectStates,$round->project_unavailable_state);
        array_push($projectStates,$round->project_waiting_state);
        array_push($projectStates,$round->project_bad_state);
        array_push($projectStates,$round->project_available_state);
        array_push($projectStates,$round->project_complete_state);
    }

    return $projectStates;
}

// vim: sw=4 ts=4 expandtab
?>
