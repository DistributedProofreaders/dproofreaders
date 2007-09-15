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

$title=_("WordCheck Project Usage");
$no_stats = 1;
theme($title,"header");
echo_word_freq_style();
echo_stylesheet();


echo "<h1>$title</h1>";
echo "<h2>" . get_project_name($projectid) . "</h2>";

// load the events
$events = load_wordcheck_events($projectid);
if(is_array($events)) {
    // parse the events complex array
    foreach( $events as $event) {
        list($time,$roundID,$page,$proofer,$words,$corrections)=$event;
        @$wordcheck_usage[$page][$roundID]++;
    }
}

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
    <td class='label'><? echo _("State"); ?></td>
<?
    foreach($Round_for_round_id_ as $round)
        echo "<td class='label'>$round->id</td>";
?>
</tr>
<?

// build a list of user column names for the select statement
// this avoids "select *" which unnecessarily pulls in the large
// text fields
$roundColumns=array();
foreach($Round_for_round_id_ as $round) {
    $roundColumns[]=$round->user_column_name;
}
$roundColumns=implode(", ",$roundColumns);

// cycle through all pages, creating rows as we go
$res = mysql_query( "SELECT image, state, $roundColumns FROM $projectid ORDER BY image ASC" ) or die(mysql_error());
while($result = mysql_fetch_assoc($res)) {
    $page = $result["image"];
    echo "<tr>";
    echo "<td>" . recycle_window_link("displayimage.php?project=$projectid&amp;imagefile=$page",$page,"pageView") . "</td>";
    echo "<td>" . $result["state"] . "</td>";

    // get the current round for the page
    $currentRound = get_Round_for_page_state($result["state"]);

    // foreach round print out the available info
    foreach($Round_for_round_id_ as $round) {
        $roundID = $round->id;

        // the ' + 0' forces timesChecked to be numeric
        $timesChecked = $wordcheck_usage[$page][$roundID] + 0;
        $lastProofer = $result[$round->user_column_name];

        // determine if the page has been marked 'Done' or not
        // If one of the following is true, its done:
        // * if the page is in a previous round
        // * if the page is in the current round in the done state
        $pageIsDoneInRound =
            $currentRound->round_number > $round->round_number ||
            $currentRound->page_save_state == $result["state"];

        if(!empty($lastProofer))
            $lastProoferLink = private_message_link($lastProofer);
        
        $class = $data = "";
        // if there is no proofer's name, the round was likely skipped
        // or the page has never been worked on
        if(empty($lastProofer)) {
            // placeholder
        }
        // if the page is finished and WordCheck'd
        elseif($pageIsDoneInRound && $timesChecked>0) {
            $class = "class='WC'";
            $data = "$timesChecked: $lastProoferLink";
        }
        // if the page is finished but was not ran through WordCheck
        elseif($pageIsDoneInRound && $timesChecked==0) {
            $class = "class='noWC'";
            $data = "$timesChecked: $lastProoferLink";
        }
        // the page has been worked on, but not finished
        else {
            $class = "";
            $data = "$timesChecked: $lastProoferLink";
        }

        echo "<td $class>$data</td>";
    }

    echo "</tr>\n";
}
mysql_free_result($res);

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

// vim: sw=4 ts=4 expandtab
?>
