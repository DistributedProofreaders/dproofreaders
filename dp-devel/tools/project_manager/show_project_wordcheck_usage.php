<?
$relPath="./../../pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'dp_main.inc');
include_once($relPath.'wordcheck_engine.inc');
include_once($relPath.'theme.inc');
include_once("./word_freq_table.inc");

set_time_limit(0); // no time limit

$projectid = $_GET["projectid"];

enforce_edit_authorization($projectid);

$roundIDs=_getRoundIDs();

$title=_("WordCheck Project Usage");
$no_stats = 1;
theme($title,"header");
echo_word_freq_style();
echo_stylesheet();


echo "<h1>$title</h1>";
echo "<h2>" . get_project_name($projectid) . "</h2>";

// get the list of all pages
$res = mysql_query( "SELECT image FROM $projectid ORDER BY image ASC") or die(mysql_error());
while($result = mysql_fetch_assoc($res)) {
    $page_usage[$result["image"]]=array();
}
mysql_free_result($res);

// load the suggestions
$earliestTimestamp = time();
$suggestions = load_project_good_word_suggestions($projectid,0,TRUE);
if(is_array($suggestions)) {
    // parse the suggestions complex array
    // it was pulled in the raw format
    foreach( $suggestions as $suggestion) {
        list($time,$round,$page,$proofer,$words)=$suggestion;
        $page_usage[$page][$round]++;
        if($earliestTimestamp > $time) $earliestTimestamp = $time;
    }
}

if(0) {
// Optimal "get it all at once" query that needs an index:
//   select image, username, round_id from page_events where projectid='projectID45b672b501229' and event_type='saveAsDone' order by image, timestamp;
// Index needed: (projectid, event_type)

// if the page has been marked saveAsDone, see who did that action last
$query = "select image, username, round_id from page_events where projectid='$projectid' and event_type='saveAsDone' order by image, timestamp";

$res = mysql_query( $query ) or die(mysql_error());
while($result = mysql_fetch_assoc($res)) {
    @$page_event[$result["image"]][$result["round_id"]]=$result["username"];
}
mysql_free_result($res);

} // optimal "all at once" way

if(1) {
// Query using an existing index but requires us to query per-image:
//   select username, round_id from page_events where projectid='projectID45b672b501229' and image='000.png' and event_type='saveAsDone' order by timestamp;
// Index used: (projectid, image)

foreach($page_usage as $page => $val ) {
    $query = "select username, round_id from page_events where projectid='$projectid' and image='$page' and event_type='saveAsDone' order by timestamp";

    $res = mysql_query( $query ) or die(mysql_error());
    while($result = mysql_fetch_assoc($res))
        @$page_event[$page][$result["round_id"]]=$result["username"];
    mysql_free_result($res);
}

} // end sub-optimal "one at a time" way


echo "<p>" . _("The following table lists the number of times WordCheck was run against a page in a specific round. Pages that are Done and have had WordCheck run on them will be marked <span class='WC'>like this</span>. Pages that are Done and WordCheck has not been run on them are marked <span class='noWC'>like this</span> and list the last user to save the page. Pages without a background color have not yet been saved as Done and they may or may not have had WordCheck run against them.") . "</p>";

// now build the table
?>
<table class='wordlisttable'>
<tr>
    <td class='label'><? echo _("Page"); ?></td>
<?
    foreach($roundIDs as $roundID)
        echo "<td class='label'>$roundID</td>";
?>
</tr>
<?
foreach($page_usage as $page => $val) {
    echo "<tr>";
    echo "<td>$page</td>";
    foreach($roundIDs as $roundID) {
        $data = $val[$roundID];
        if(!empty($page_event[$page][$roundID]) && $val[$roundID]==0) {
            $class = " class='noWC'";
            $data .= $page_event[$page][$roundID];
        } elseif(!empty($page_event[$page][$roundID]) && $val[$roundID]>0) {
            $class = " class='WC'";
        } else { 
            $class = "";
        }
        echo "<td$class>$data</td>";
    }
    echo "</tr>\n";
}
?>
</table>
<?

theme('',"footer");


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

    table.wordlisttable td { text-align: center; }

    .noWC { background-color: yellow; }
    .WC { background-color: #0F0; }
    </style>
<?
}

function _getRoundIDs() {
    global $Round_for_round_number_;

    $roundNames = array();
    foreach($Round_for_round_number_ as $round) {
        $roundNames[] = $round->id;
    }

    return $roundNames;
}


// vim: sw=4 ts=4 expandtab
?>
