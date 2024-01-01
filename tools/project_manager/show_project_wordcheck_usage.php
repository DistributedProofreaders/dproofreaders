<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'wordcheck_engine.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'links.inc');
include_once("./word_freq_table.inc");

require_login();

set_time_limit(0); // no time limit

$projectid = get_projectID_param($_GET, 'projectid');

enforce_edit_authorization($projectid);

$title = _("WordCheck Project Usage");
output_header($title, NO_STATSBAR);


echo "<h1>$title</h1>";
echo "<h2>" . get_project_name($projectid) . "</h2>";

echo "<p>" . _("The following table lists the number of times WordCheck was run against a page in each round and the last user to work on the page. Click the proofreader's username to compose a private message to them.") . "</p>";

echo "<p><b>" . _("Legend") . "</b></p>";
echo "<ul>";
echo "<li>" . _("Page has not been saved in the given round.") . "</li>";
echo "<li><span class='WC'>" . _("Page has had WordCheck run on it and is saved in the given round.") . "</span></li>";
echo "<li><span class='noWC'>" . _("Page has not had WordCheck run on it and is saved in the given round.") . "</span></li>";
echo "<li><span class='preWC'>" . _("Page was saved in the given round before WordCheck was rolled out on this site.") . "</span></li>";
echo "</ul>";

// now build the table
?>
<table class='basic'>
<tr>
    <th class='label'><?php echo _("Page"); ?></th>
    <th class='label'><?php echo pgettext("page state", "State"); ?></th>
<?php
    foreach (Rounds::get_all() as $round) {
        echo "<th class='label'>$round->id</td>";
    }
?>
</tr>
<?php

// identifying pages that were proofread pre-WordCheck requires
// $t_wordcheck_start being defined in site_vars.php
// with its value set to the timestamp of when WordCheck was
// deployed on the site

// build the SQL to return the fields and WordCheck status for each round
$round_fields_select = "";
foreach (Rounds::get_all() as $round) {
    $rn = $round->round_number;
    $round_fields_select .= "
        $round->user_column_name,
        $round->time_column_name,
        (
            SELECT count(*)
            FROM wordcheck_events
            WHERE projectid = '$projectid' AND
                image = $projectid.image AND
                username = $round->user_column_name AND
                round_id = '$round->id'
        ) as wordcheck_status$rn,
    ";
}

$sql = "
    SELECT
        $projectid.image AS image,
        $round_fields_select
        state
    FROM $projectid
    ORDER BY image ASC
";
$res = DPDatabase::query($sql);
while ($result = mysqli_fetch_assoc($res)) {
    $page = $result["image"];
    echo "<tr>";
    echo "<td>" . recycle_window_link("../page_browser.php?project=$projectid&amp;imagefile=$page", $page, "pageView") . "</td>";
    echo "<td>" . $result["state"] . "</td>";

    // get the current round for the page
    $currentRound = get_Round_for_page_state($result["state"]);

    // foreach round print out the available info
    foreach (Rounds::get_all() as $round) {
        $roundID = $round->id;
        $rn = $round->round_number;

        // the ' + 0' forces timesChecked to be numeric
        $timesChecked = $result["wordcheck_status$rn"];
        $lastProofer = $result[$round->user_column_name];
        $timeProofed = $result[$round->time_column_name];

        // determine if the page has been marked 'Done' in $round.
        // If one of the following is true, it's done:
        // * $round is prior to the page's current round, or
        // * $round is the page's current round and the page is in that round's done state.
        $pageIsDoneInRound =
            $currentRound->round_number > $round->round_number ||
            $round->page_save_state == $result["state"];

        if (!empty($lastProofer)) {
            $lastProoferLink = private_message_link($lastProofer);
        }

        $class = $data = "";
        // if there is no proofreader's name, the round was likely skipped
        // or the page has never been worked on
        if (empty($lastProofer)) {
            // placeholder
        }
        // if the page is finished and WordCheck'd
        elseif ($pageIsDoneInRound && $timesChecked > 0) {
            $class = "class='WC'";
            $data = "$timesChecked: $lastProoferLink";
        }
        // if the page was finished before WordCheck was deployed on the site
        elseif ($pageIsDoneInRound && $timesChecked == 0 && $timeProofed < @$t_wordcheck_start) {
            $class = "class='preWC'";
            $data = $lastProoferLink;
        }
        // if the page is finished but was not ran through WordCheck
        elseif ($pageIsDoneInRound && $timesChecked == 0) {
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
mysqli_free_result($res);

?>
</table>
<?php
