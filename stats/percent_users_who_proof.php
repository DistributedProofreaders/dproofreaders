<?php
$relPath = "./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'page_tally.inc');
include_once($relPath.'graph_data.inc');
include_once($relPath.'slim_header.inc');

[$users_ELR_page_tallyboard, ] = get_ELR_tallyboards();

[$joined_with_user_ELR_page_tallies, $user_ELR_page_tally_column] =
    $users_ELR_page_tallyboard->get_sql_joinery_for_current_tallies('u_id');

///////////////////////////////////////////////////
// For each month in which someone joined,
// get the number who joined,
// and the number of those who have proofread at least one page.
//
$result = DPDatabase::query("
    SELECT
        FROM_UNIXTIME(date_created, '%Y-%m')
          AS month,
        COUNT(*)
          AS num_who_joined,
        SUM($user_ELR_page_tally_column > 0)
          AS num_who_proofed
    FROM users $joined_with_user_ELR_page_tallies
    GROUP BY month
    ORDER BY month
");

// If there was a month when nobody joined,
// then the results will not include a row for that month.
// This may lead to a misleading graph,
// depending on its style.

while ($row = mysqli_fetch_object($result)) {
    $datax[] = $row->month;
    $data1y[] = 100 * $row->num_who_proofed / $row->num_who_joined;
}

$width = 640;
$height = 400;

$title = _('Percentage of New Users Who Went on to Proofread By Month');
$js_data = '$(function(){barChart("percent_users_who_proof",' . json_encode([
    "title" => $title,
    "data" => [
        // xgettext:no-php-format
        _('% of newly Joined Users who Proofread') => [
            "x" => $datax,
            "y" => $data1y,
        ],
    ],
    "width" => $width,
    "height" => $height,
]) . ');});';

slim_header($title, [
    "body_attributes" => "style='margin: 0'",
    "js_files" => get_graph_js_files(),
    "js_data" => $js_data,

]);

echo "<div id='percent_users_who_proof' style='width:" . $width . "px;height:" . $height . "px;'></div>";
