<?
$relPath="./../../pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'dp_main.inc');
include_once($relPath.'wordcheck_engine.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'links.inc');
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

// load the events
$earliestTimestamp = time();
$events = load_wordcheck_events($projectid);
if(is_array($events)) {
    // parse the events complex array
    foreach( $events as $event) {
        list($time,$round,$page,$proofer,$words,$corrections)=$event;
        $page_usage[$page][$round]++;
        if($earliestTimestamp > $time) $earliestTimestamp = $time;
    }
}

if(0) {
// Optimal "get it all at once" query that needs an index:
//   select image, username, round_id from page_events where projectid='projectID45b672b501229' and event_type='saveAsDone' order by image, timestamp;
// Index needed: (projectid, event_type)

// if the page has been marked saveAsDone, see who did that action last
$query = "SELECT image, username, round_id FROM page_events WHERE projectid='$projectid' AND event_type='saveAsDone' ORDER BY image, timestamp";

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
    $query = "SELECT username, round_id FROM page_events WHERE projectid='$projectid' AND image='$page' AND event_type='saveAsDone' ORDER BY timestamp";

    $res = mysql_query( $query ) or die(mysql_error());
    while($result = mysql_fetch_assoc($res))
        @$page_event[$page][$result["round_id"]]=$result["username"];
    mysql_free_result($res);
}

} // end sub-optimal "one at a time" way

echo "<p>" . _("The following table lists the number of times WordCheck was run against a page in each round and the last user to work on the page. Click the proofer's username to compose a private message to them.") . "</p>";

echo "<p><b>" . _("Legend") . "</b></p>";
echo "<ul>";
echo "<li>" . _("Page has not been saved in the given round.") . "</li>";
echo "<li><span class='WC'>" . _("Page has had WordCheck run on it and is saved in the given round.") . "</span></li>";
echo "<li><span class='noWC'>" . _("Page has not had WordCheck run on it and is saved in the given round.") . "</span></li>";
echo "</ul>";

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
    echo "<td>" . recycle_window_link("displayimage.php?project=$projectid&amp;imagefile=$page",$page,"pageView") . "</td>";
    foreach($roundIDs as $roundID) {
        $timesChecked = $val[$roundID];
        $lastProofer = $page_event[$page][$roundID];
        if(!empty($lastProofer))
            $lastProoferLink = private_message_link($lastProofer);
        
        if(!empty($lastProofer) && $timesChecked==0) {
            $class = "class='noWC'";
            $data = $lastProoferLink;
        } elseif(!empty($lastProofer) && $timesChecked>0) {
            $class = "class='WC'";
            $data = "$timesChecked: $lastProoferLink";
        } else { 
            $class = "";
            $data = "";
        }

        echo "<td $class>$data</td>";
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

    table.wordlisttable td { }

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
