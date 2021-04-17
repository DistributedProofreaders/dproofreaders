<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'stages.inc');
include_once('common.inc');

// This image shows the same data as round_backlog.php but in a trend
// format, one bar per stage per week over the past 4 weeks.
//
// see also: http://www.pgdp.net/wiki/Round_backlog_graphs

$weeks = 4;

// Start with creating the Graph, this enables the use of the cache
// where possisble
$width = 300;
$height = 200;
$cache_timeout = 59; // in minutes
$graph = new Graph($width, $height, get_image_cache_filename(), $cache_timeout);

// Pull all interested phases, primarily all the rounds and PP
$interested_phases = array_keys($Round_for_round_id_);
$interested_phases[] = "PP";

// Pull the stats data out of the database
$stats = get_round_backlog_stats($interested_phases);

// get the total of all phases
$stats_total = array_sum($stats);

// If this is a new system there won't be any stats so don't divide by zero
if ($stats_total == 0) {
    dpgraph_error(_("No pages found."), $width, $height);
}

// Generate the date ranges
$date_ranges = [];
for ($days_ago = 0; $days_ago < ($weeks * 7); $days_ago += 7) {
    $before = time() - (60 * 60 * 24 * $days_ago);
    $after = time() - (60 * 60 * 24 * ($days_ago + 7));
    $date_ranges[] = [$before, $after];
}

// Need to reverse the date ranges so they go from past to present
$date_ranges = array_reverse($date_ranges);

// Get page completed trend information
$today = time();
$week_ago = $today - (60 * 60 * 24 * $days_ago);
foreach ($stats as $phase => $pages) {
    $where_state = _get_project_state_selector($phase, ["available", "waiting"]);

    foreach ($date_ranges as $range) {
        [$before, $after] = $range;
        $sql = "SELECT SUM(num_pages)
                FROM project_state_stats
                WHERE $where_state AND
                date >= FROM_UNIXTIME($after) AND date < FROM_UNIXTIME($before)";
        $res = mysqli_query(DPDatabase::get_connection(), $sql);
        [$pages] = mysqli_fetch_row($res);
        mysqli_free_result($res);

        @$pages_per_days_ago[$before][$phase] = $pages / 7;
    }
}


// colors
$normalBarColors = ["#bfbfff", "#8080ff", "#4040ff", "#0000ff"];
$warningBarColors = ["#ffbfbf", "#ff8080", "#ff4040", "#ff0000"];

// calculate the goal percent as 100 / number_of_phases
$goal_percent = ceil(100 / count($stats));

$plots = [];

$barColorIndex = 0;
foreach ($pages_per_days_ago as $days_ago => $stats) {
    // calculate the percentage of work remaining in each round
    // and the color for each bar
    $barColors = [];
    foreach ($stats as $phase => $num_pages) {
        $stats_percentage[$phase] = ceil(($num_pages / $stats_total) * 100);
        if ($stats_percentage[$phase] > $goal_percent) {
            $barColors[] = $warningBarColors[$barColorIndex];
        } else {
            $barColors[] = $normalBarColors[$barColorIndex];
        }
    }

    // Create a bar plot for each day
    $plot = new BarPlot(array_values($stats));
    $plot->SetFillColor($barColors);
    $plots[] = $plot;

    $barColorIndex++;
}

// Some graph variables
$datax = array_keys($stats);
// TRANSLATORS: This string is the title of a graph with very little room.
//              Try to keep the translation the same length as the English text.
$title = sprintf(_("Total page trend over last %s weeks"), $weeks);

//$graph->SetScale("textint");
$graph->SetScale("textlin", 0, max($stats) * 1.05);
$graph->graph_theme = null;

// Set background to white
$graph->SetMarginColor('white');

// Add a drop shadow
$graph->SetShadow();

// Adjust the margin a bit to make more room for titles
// left, right, top, bottom
$graph->img->SetMargin(50, 20, 30, 30);

// Set title
$graph->title->Set($title);
$graph->title->SetFont($jpgraph_FF, FS_BOLD, 10);

// Set X axis
$graph->xaxis->SetTickLabels($datax);

// Create a bar plot
$plot = new GroupBarPlot($plots);
$plot->SetWidth(0.8);
$graph->Add($plot);

// Display the graph
$graph->Stroke();
