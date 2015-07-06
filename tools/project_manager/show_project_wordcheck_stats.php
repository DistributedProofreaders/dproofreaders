<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'wordcheck_engine.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'links.inc');
include_once($relPath.'theme.inc');
include_once('./post_files.inc');
include_once("./word_freq_table.inc");

require_login();

set_time_limit(0); // no time limit

$projectid = validate_projectID('projectid', @$_GET['projectid']);
$project = new Project($projectid);

enforce_edit_authorization($projectid);

$title=_("Project WordCheck Statistics");
output_header($title, NO_STATSBAR);
echo_word_freq_style();
echo_stylesheet();


echo "<h1>$title</h1>";
echo "<h2>" . get_project_name($projectid) . "</h2>";

echo "<p>" . _("The following statistics are generated from the most recently saved text of each page and the site and project's Good and Bad Word Lists.") . "</p>"; 

// load bad words for project
$proj_bad_words = load_project_bad_words($projectid);

// load bad words for the site for this project
$site_bad_words = load_site_bad_words_given_project($projectid);

// get the latest possible round
$last_possible_round = get_Round_for_round_number(MAX_NUM_PAGE_EDITING_ROUNDS);
$pages_res = page_info_query($projectid,$last_possible_round->id,'LE');

// get the entire text
$page_texts = get_page_texts($pages_res);
mysql_free_result($pages_res);

// now run it through WordCheck
list($bad_words_w_freq,$languages,$messages) =
    get_bad_words_for_text($page_texts,$projectid,'all','',array(),'FREQS');

// see how many of the words are on the site and project bad word list
$total["proj_bad_words"]=0;
$total["site_bad_words"]=0;
$total["flagged"]=0;
foreach($bad_words_w_freq as $word => $freq) {
    // is this word in the project's Bad list?
    if(in_array($word,$proj_bad_words))
        $total["proj_bad_words"]+=$freq;

    // is this word in the site's Bad list?
    if(in_array($word,$site_bad_words))
        $total["site_bad_words"]+=$freq;

    // add total flagged words
    $total["flagged"]+=$freq;
}
$total["num_pages"]=$project->n_pages;


// now run it again except we're going to count the words per page
// this time through
$pages_res = page_info_query($projectid,$last_possible_round->id,'LE');
$page_stats = array();
// iterate through all the pages gathering stats
while( list($page_text,$page,$proofer_names) = page_info_fetch($pages_res) ) {
    // find which words would be flagged for this page
    $page_words_w_freq = get_distinct_words_in_text($page_text);

    $page_stats[$page]["flagged"]=0;
    // cycle through the words and count things
    foreach($page_words_w_freq as $word => $freq) {
        // is this word flagged?
        if(isset($bad_words_w_freq[$word]))
            $page_stats[$page]["flagged"]+=$freq;
    }
}
mysql_free_result($pages_res);

$total["flagged_min"]=1000000;
$total["flagged_max"]=0;
$mode=array();
$graph_x=array();
$graph_y=array();
// we have per-page stats, lets aggregate them together
foreach($page_stats as $page => $data) {
    $flagged=$data["flagged"];

    if($total["flagged_max"]<$flagged) {
        $total["flagged_max"]=$flagged;
        $total["flagged_max_page"]=$page;
    }
    $total["flagged_min"]=min($total["flagged_min"],$flagged);
    @$mode[$flagged]++;

    // push data into our graph data
    array_push($graph_x,$page);
    array_push($graph_y,$flagged);
}

// store the data in the session for the graphing script to read
$_SESSION["graph_flags_per_page"][$projectid]["graph_x"]=$graph_x;
$_SESSION["graph_flags_per_page"][$projectid]["graph_y"]=$graph_y;

// calculate the mode
arsort($mode);
$total["flagged_mode"]=array_shift(array_keys($mode));
$total["flagged_mode_num"]=$mode[$total["flagged_mode"]];

// initialize for empty projects
$flags_n_pages=array();

// use the $mode array to prepare the graph_pages_per_number_of_flags data
for($numFlags=$total["flagged_min"];$numFlags<=$total["flagged_max"];$numFlags++) {
   if(isset($mode[$numFlags])) $flags_n_pages[$numFlags]=$mode[$numFlags];
   else $flags_n_pages[$numFlags]=0;
}

// store the mode for graphing
$_SESSION["graph_pages_per_number_of_flags"][$projectid]["graph_x"]=array_keys($flags_n_pages);
$_SESSION["graph_pages_per_number_of_flags"][$projectid]["graph_y"]=array_values($flags_n_pages);

// calculate averages
$total["flagged_avg"]=$total["proj_bad_words_avg"]=$total["site_bad_words_avg"]=0;
if($total["num_pages"]>0) {
    $total["flagged_avg"] = $total["flagged"]/$total["num_pages"];
    $total["proj_bad_words_avg"] = $total["proj_bad_words"]/$total["num_pages"];
    $total["site_bad_words_avg"] = $total["site_bad_words"]/$total["num_pages"];
} else {
    $total["flagged_min"]=0;
}

?>
<table class='wordlisttable'>
<tr>
    <td class='label'><?php echo _("Number of pages in project"); ?></td>
    <td style='text-align: right;'><?php echo $total["num_pages"]; ?></td>
</tr>
<tr>
    <td class='label'><?php echo _("Number of flagged words in project"); ?></td>
    <td style='text-align: right;'><?php echo $total["flagged"]; ?></td>
</tr>
<tr>
    <td class='label'><?php echo _("Number of flagged words from site's Bad Word List"); ?></td>
    <td style='text-align: right;'><?php echo $total["site_bad_words"]; ?></td>
</tr>
<tr>
    <td class='label'><?php echo _("Number of flagged words from project's Bad Word List"); ?></td>
    <td style='text-align: right;'><?php echo $total["proj_bad_words"]; ?></td>
</tr>
<tr>
    <td class='label'><?php echo _("Mean flagged words per page"); ?></td>
    <td style='text-align: right;'><?php echo number_format($total["flagged_avg"],3); ?></td>
</tr>
<tr>
    <td class='label'><?php echo _("Mean of words from site's Bad Word List flagged per page"); ?></td>
    <td style='text-align: right;'><?php echo number_format($total["site_bad_words_avg"],3); ?></td>
</tr>
<tr>
    <td class='label'><?php echo _("Mean of words from project's Bad Word List flagged per page"); ?></td>
    <td style='text-align: right;'><?php echo number_format($total["proj_bad_words_avg"],3); ?></td>
</tr>
<tr>
    <td class='label'><?php echo _("Maximum flagged words per page"); ?></td>
    <td style='text-align: right;'><?php echo $total["flagged_max"]; ?> on <?php echo new_window_link("displayimage.php?project=$projectid&amp;imagefile=" . $total["flagged_max_page"], $total["flagged_max_page"]); ?></a></td>
</tr>
<tr>
    <td class='label'><?php echo _("Minimum flagged words per page"); ?></td>
    <td style='text-align: right;'><?php echo $total["flagged_min"]; ?></td>
</tr>
<tr>
    <td class='label'><?php echo _("Mode of flagged words per page"); ?></td>
    <td style='text-align: right;'><?php echo $total["flagged_mode"] . " (" . $total["flagged_mode_num"] . ")"; ?></td>
</tr>
</table>

<h2><?php echo _("Flagged words distribution"); ?></h2>

<p><img src="graph_flags_per_page.php?projectid=<?php echo $projectid; ?>" alt="<?php echo _("Graph showing the number of flagged words per page"); ?>"></p>

<p><img src="graph_pages_per_number_of_flags.php?projectid=<?php echo $projectid; ?>" alt="<?php echo _("Graph showing the number of pages with a given number of flagged words"); ?>"></p>

<?php


function echo_stylesheet() {
?>
    <style type="text/css">
    table.wordlisttable {
        padding: 5px;
        border-collapse: collapse;
    }
    table.wordlisttable td {
        padding: 2px;
        border: thin solid #000;
    }
    table.wordlisttable td.label {
        background-color: #CCC;
        font-weight: bold;
    }
    table.wordlisttable .mono { font-family: monospace; }
    table.wordlisttable textarea { width: 100%; }
    </style>
<?php
}

// vim: sw=4 ts=4 expandtab
?>
