<?php

// Various utilities for the scripts in this directory.

include_once($relPath.'misc.inc');
include_once($relPath.'site_vars.php');
include_once($jpgraph_dir.'/src/jpgraph.php');
include_once($jpgraph_dir.'/src/jpgraph_line.php');
include_once($jpgraph_dir.'/src/jpgraph_bar.php');
include_once($jpgraph_dir.'/src/jpgraph_canvas.php');
include_once($jpgraph_dir.'/src/jpgraph_pie.php');

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function get_image_cache_filename()
// Returns a filename to pass into jpgraph's Graph constructor for caching
// purposes.
// jpgraph allows a value of "auto" to be passed which will base the image
// filename off the PHP_SELF value from Apache. This can't be used with
// gettext() support because we need to cache a per-language version of the
// graph. Instead we leverage jpgraph's GenImgName() function (which is
// ultimately used if "auto" is passed into the constructor) and prepend the
// user's language.
{
    return get_desired_language() . "-" . GenImgName();
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function get_number_of_days_in_current_month()
{
    $current = getdate();
    // The last day of this month is the "zeroth" day of next month.
    $last_day_timestamp =
        mktime(0, 0, 0, $current['mon'] + 1, 0, $current['year']);
    $last_day = getdate($last_day_timestamp);
    return $last_day['mday'];
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function array_subtract_first_from_each($arr)
// Return the array defined by:
//     $result[$i] = $arr[$i] - $arr[0]
{
    $result = [];
    foreach ($arr as $key => $value) {
        if (count($result) == 0) {
            $first_value = $value;
        }
        $result[$key] = $value - $first_value;
    }
    return $result;
}

function array_successive_differences($arr)
// Return the array defined by:
//     $result[$i] = $arr[$i+1] - $arr[$i]
{
    $result = [];
    foreach ($arr as $key => $value) {
        if (isset($prev_key)) {
            $result[$prev_key] = $value - $prev_value;
        }
        $prev_key = $key;
        $prev_value = $value;
    }
    return $result;
}

function array_accumulate($arr)
// Return the array defined by:
//     $result[$i] = sum( $arr[$j] for $j up to and including $i )
{
    $result = [];
    $sum = 0;
    foreach ($arr as $key => $value) {
        $sum += $value;
        $result[$key] = $sum;
    }
    return $result;
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function init_pages_graph($cache_timeout)
{
    return new Graph(640, 400, get_image_cache_filename(), $cache_timeout);
}

function draw_pages_graph(
    $graph,
    $x_dates,
    $y_actual_n_pages,
    $y_page_goal,
    $y_average,
    $y_average_label,
    $x_frequency,
    $cumulative_or_increments,
    $title
)
// Draw various kinds of 'pages completed vs. time' graphs.
{
    // check to see that $graph is actually a Graph object
    if (!is_a($graph, "Graph")) {
        assert(false);
    }

    if (array_get($_GET, 'echo_graph_data', false)) {
        echo "$title\n\n";
        echo_graph_data($x_dates, $y_actual_n_pages, $y_page_goal, $y_average);
        return;
    }

    global $jpgraph_FF, $jpgraph_FS;

    //var_dump($x_dates); echo "<br>"; var_dump($y_actual_n_pages);
    $n_datapoints = max(count($y_actual_n_pages), count($y_page_goal));
    // assert( count(x_dates$) == $n_datapoints );
    // assert( is_null($y_page_goal) || count($y_page_goal) == $n_datapoints );

    assert($cumulative_or_increments == 'cumulative' || $cumulative_or_increments == 'increments');

    $x_text_tick_interval = calculate_text_tick_interval($x_frequency, $n_datapoints);

    $graph->SetScale("textint");
    $graph->graph_theme = null;

    // Set background to white
    $graph->SetMarginColor('white');

    // Add a drop shadow
    $graph->SetShadow();

    // Adjust the margin a bit to make more room for titles
    // left, right, top, bottom
    $graph->img->SetMargin(70, 30, 20, 100);

    // Set title
    $graph->title->Set($title);
    $graph->title->SetFont($jpgraph_FF, $jpgraph_FS);

    // Set X axis
    $graph->xaxis->SetTickLabels($x_dates);
    $graph->xaxis->SetLabelAngle(90);
    $graph->xaxis->title->Set("");
    $graph->xaxis->title->SetFont($jpgraph_FF, $jpgraph_FS);
    $graph->xaxis->SetTextTickInterval($x_text_tick_interval);

    // Set Y axis
    $graph->yaxis->title->Set(_('Pages'));
    $graph->yaxis->title->SetFont($jpgraph_FF, $jpgraph_FS);
    $graph->yaxis->SetTitleMargin(45);
    $graph->yaxis->scale->SetAutoMin(0);

    // Create the plot for actual pages
    // Note we can't draw line graphs with only one point
    // so if they requested a cumulative chart but our data
    // has one point or fewer, make it a bar graph instead
    if ($cumulative_or_increments == 'increments' || count($y_actual_n_pages) <= 1) {
        $pages_plot = new BarPlot($y_actual_n_pages);
        $pages_plot->SetColor("blue");
    } elseif ($cumulative_or_increments == 'cumulative') {
        $pages_plot = new LinePlot($y_actual_n_pages);
        $pages_plot->SetColor("blue");
        $pages_plot->SetWeight(1);
        $pages_plot->SetFillColor("blue");

        $graph->img->SetAntiAliasing();
    } else {
        assert(false);
    }
    $graph->Add($pages_plot);

    if (!is_null($y_page_goal) || !is_null($y_average)) {
        // Add a legend for the 'actual' plot.
        $pages_plot->SetLegend(_("Actual"));

        $graph->legend->SetFont($jpgraph_FF, $jpgraph_FS, 9);
        $graph->legend->SetPos(0.15, 0.65, "left", "bottom");
        $graph->legend->SetColumns(1);
        $graph->legend->SetShadow();
    }

    if (!is_null($y_page_goal)) {
        // Create the plot for the page goals.
        $goal_plot = new LinePlot($y_page_goal);
        $goal_plot->SetColor("lime");
        $goal_plot->SetWeight(2);
        $goal_plot->SetLegend(_('Goal'));
        $graph->Add($goal_plot);
    }

    if (!is_null($y_average)) {
        // Create the plot for the average.
        $average_plot = new LinePlot($y_average);
        $average_plot->SetColor("red");
        $average_plot->SetWeight(3);
        $average_plot->SetLegend($y_average_label);
        $graph->Add($average_plot);
    }

    add_graph_timestamp($graph);

    // Display the graph
    $graph->Stroke();
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function init_projects_graph($cache_timeout)
{
    return new Graph(640, 400, get_image_cache_filename(), $cache_timeout);
}

function draw_projects_graph(
    $graph,
    $x_dates,
    $y_num_projects,
    $cumulative_or_increments,
    $color,
    $title
)
// Draw various kinds of 'number of projects vs. time' graphs.
{
    // check to see that $graph is actually a Graph object
    if (!is_a($graph, "Graph")) {
        assert(false);
    }

    if (array_get($_GET, 'echo_graph_data', false)) {
        echo "$title\n\n";
        echo_graph_data($x_dates, $y_num_projects);
        return;
    }

    global $jpgraph_FF, $jpgraph_FS;

    $n_datapoints = count($y_num_projects);
    /*
    if ( count($x_dates) != $n_datapoints )
    {
        echo "$n_datapoints Y values, but ". count($x_dates) ." X values.<br>";
        die('aborting');
    }
    */

    assert($cumulative_or_increments == 'cumulative' || $cumulative_or_increments == 'increments');

    $x_text_tick_interval = calculate_text_tick_interval('daily', $n_datapoints);

    $graph->SetScale("textint");
    $graph->graph_theme = null;

    // Set background to white
    $graph->SetMarginColor('white');

    // Add a drop shadow
    $graph->SetShadow();

    // Adjust the margin a bit to make more room for titles
    // left, right, top, bottom
    $graph->img->SetMargin(70, 30, 20, 100);

    // Set title
    $graph->title->Set($title);
    $graph->title->SetFont($jpgraph_FF, $jpgraph_FS);

    // Set X axis
    $graph->xaxis->SetTickLabels($x_dates);
    $graph->xaxis->SetLabelAngle(90);
    $graph->xaxis->title->Set("");
    $graph->xaxis->title->SetFont($jpgraph_FF, $jpgraph_FS);
    $graph->xaxis->SetTextTickInterval($x_text_tick_interval);

    // Set Y axis
    $graph->yaxis->title->Set(_('Projects'));
    $graph->yaxis->title->SetFont($jpgraph_FF, $jpgraph_FS);
    $graph->yaxis->SetTitleMargin(45);

    // Create the plot
    if ($cumulative_or_increments == 'increments') {
        //Create the bar plot
        $plot = new BarPlot($y_num_projects);
        $plot->SetFillColor($color);
    } elseif ($cumulative_or_increments == 'cumulative') {
        //Create the line plot
        $plot = new LinePlot($y_num_projects);
        $plot->SetColor($color);
        $plot->SetWeight(1);

        // Only add colour to the part that we have data for.

        // This should work:
        // $plot->SetFillColor($color);
        // Unfortunately, that has a bug that is triggered by the
        // "cumulative projects Xed in the current month" graphs.
        // The y-values for future days are the empty string
        // (the code for "don't plot"), but SetFillColor behaves
        // as if the last y-value were an explicit zero.
        // So instead...

        for ($i = count($y_num_projects) - 1; $i >= 0; $i--) {
            if ($y_num_projects[$i] !== '') {
                break;
            }
        }
        $plot->AddArea(0, $i, LP_AREA_FILLED, $color);

        $graph->img->SetAntiAliasing();
    } else {
        assert(false);
    }
    $graph->Add($plot);

    add_graph_timestamp($graph);

    // Display the graph
    $graph->Stroke();
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function init_pie_graph($width, $height, $cache_timeout)
{
    return new PieGraph($width, $height, get_image_cache_filename(), $cache_timeout);
}

function draw_pie_graph(
    $graph,
    $datax,
    $datay,
    $title
) {
    // check to see that $graph is actually a Graph object
    if (!is_a($graph, "Graph")) {
        assert(false);
    }

    if (array_get($_GET, 'echo_graph_data', false)) {
        echo "$title\n\n";
        echo_graph_data($datax, $datay);
        return;
    }

    global $jpgraph_FF, $jpgraph_FS;

    // Set background to white
    $graph->SetMarginColor('white');

    // Add a drop shadow
    $graph->SetShadow();

    // Adjust the margin a bit to make more room for titles
    // left, right, top, bottom
    $graph->img->SetMargin(70, 30, 20, 100);

    // Add the plot
    $plot = new PiePlot($datay);
    $plot->SetSize(0.34);
    // SetCenter() doesn't work because the theme overrides it:
    // https://stackoverflow.com/questions/27745187/jpgraph-setcenter-does-not-work
    // $plot->SetCenter(0.4);
    $plot->SetLegends($datax);
    if (0) {
        $plot->SetLabelType(PIE_VALUE_ABS);
        $plot->value->SetFormat('%d');
    } else {
        $plot->SetLabelType(PIE_VALUE_PER);
        $plot->value->SetFormat('%.1f%%');
    }
    $plot->value->SetFont($jpgraph_FF, $jpgraph_FS);
    $plot->value->Show();

    $graph->Add($plot);

    // Setup the title
    $graph->title->Set($title);

    $graph->title->SetFont($jpgraph_FF, $jpgraph_FS);
    $graph->legend->SetFont($jpgraph_FF, $jpgraph_FS);
    $graph->legend->SetPos(0.01, 0.05, 'right', 'top');
    $graph->legend->SetColumns(1);
    $graph->legend->SetShadow();

    add_graph_timestamp($graph);

    // Display the graph
    $graph->Stroke();
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function init_simple_bar_graph($width, $height, $cache_timeout)
{
    return new Graph($width, $height, get_image_cache_filename(), $cache_timeout);
}

function draw_simple_bar_graph(
    $graph,
    $datax,
    $datay,
    $x_label_interval,
    $title,
    $y_title
) {
    // check to see that $graph is actually a Graph object
    if (!is_a($graph, "Graph")) {
        assert(false);
    }

    if (array_get($_GET, 'echo_graph_data', false)) {
        echo "$title\n\n";
        echo_graph_data($datax, $datay);
        return;
    }

    global $jpgraph_FF, $jpgraph_FS;

    $graph->SetScale("textint");

    // Set background to white
    $graph->SetMarginColor('white');

    // Add a drop shadow
    $graph->SetShadow();

    // Adjust the margin a bit to make more room for titles
    // left, right, top, bottom
    $graph->img->SetMargin(70, 30, 20, 110);

    // Set title
    $graph->title->Set($title);
    $graph->title->SetFont($jpgraph_FF, $jpgraph_FS);

    // Set X axis
    $graph->xaxis->SetTickLabels($datax);
    $graph->xaxis->SetLabelAngle(90);
    $graph->xaxis->title->Set("");
    $graph->xaxis->title->SetFont($jpgraph_FF, $jpgraph_FS);
    $graph->xaxis->SetTextTickInterval($x_label_interval);

    // Set Y axis
    $graph->yaxis->title->Set($y_title);
    $graph->yaxis->title->SetFont($jpgraph_FF, $jpgraph_FS);
    $graph->yaxis->SetTitleMargin(45);

    // Create a bar plot
    $plot = new BarPlot($datay);
    if (count($datax) > 60) {
        // The bar outlines will overwhelm the bar interiors
        // turning the graph black. So turn off the bar outlines:
        $plot->SetColor("lightblue");
    }
    $graph->Add($plot);

    add_graph_timestamp($graph);

    // Display the graph
    $graph->Stroke();
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// Calculate tick interval based on number of datapoints
// Once we have more than about 30 labels, the axis is getting too crowded
// x_frequency = the frequency of the data points either:
//    'hourly', 'daily' or 'monthly'
function calculate_text_tick_interval($x_frequency, $n_datapoints)
{
    if ($x_frequency == 'hourly') {
        if ($n_datapoints < 30) {
            // one label per hour
            $x_text_tick_interval = 1;
        } elseif ($n_datapoints < (30 * 168)) {
            // one label per week
            $x_text_tick_interval = 168;
        } elseif ($n_datapoints < (30 * 168 * 4)) {
            // one label per 4 weeks (pseudo-month)
            $x_text_tick_interval = 168 * 4;
        } elseif ($n_datapoints < (30 * 168 * 13)) {
            // one label per quarter
            $x_text_tick_interval = 168 * 13;
        } else {
            // one label per year
            $x_text_tick_interval = 168 * 52;
        }
    } elseif ($x_frequency == 'daily') {
        if ($n_datapoints <= 31) {
            // one label per day
            $x_text_tick_interval = 1;
        } elseif ($n_datapoints < (30 * 7)) {
            // one label per week
            $x_text_tick_interval = 7;
        } elseif ($n_datapoints < (30 * 7 * 4)) {
            // one label per 4 weeks (pseudo-month)
            $x_text_tick_interval = 7 * 4;
        } elseif ($n_datapoints < (30 * 7 * 13)) {
            // one label per quarter
            $x_text_tick_interval = 7 * 13;
        } else {
            // one label per year
            $x_text_tick_interval = 7 * 52;
        }
    } elseif ($x_frequency == 'monthly') {
        if ($n_datapoints < 30) {
            // one label per month
            $x_text_tick_interval = 1;
        } elseif ($n_datapoints < (30 * 3)) {
            // one label per quarter
            $x_text_tick_interval = 3;
        } else {
            // one label per year
            $x_text_tick_interval = 12;
        }
    } else {
        assert(false);
    }

    return $x_text_tick_interval;
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function echo_graph_data()
{
    $args = func_get_args();
    $args = array_filter($args);
    assert(count($args) >= 2);

    $max_length = 0;
    foreach ($args as $column) {
        $max_length = max($max_length, count($column));
    }

    for ($i = 0; $i < $max_length; $i++) {
        echo "($i)\t";
        foreach ($args as $column) {
            echo($column[$i] ?? 'unset'), "\t";
        }
        echo "\n";
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// This function adds a timestamp to the graph - this is helpful for the user
// to know when the graph was created if caching is enabled.
// $graph - the graph object; note that it is passed by reference
function add_graph_timestamp(&$graph)
{
    // TRANSLATORS: This is an strftime-formatted string
    $graph_timestamp_format = _("graph generated at %Y-%m-%d %T");

    $text = new Text(strftime($graph_timestamp_format));

    // determine where to put the text
    // Vertical:
    //   start with the height of the graph, move up 10 pixels (to account for the
    //   shadow and border) and then up the height of the text
    $text_vert_pos = $graph->img->height - 10 - $text->GetTextHeight($graph->img);

    // There are various options for horizontal placement: center, left, right
    // To center the text:
    //$text->Center(0, $graph->img->width, $text_vert_pos);

    // To make the text flush left:
    //$text->SetPos(10, $text_vert_pos);

    // To make the text flush right:
    //   start with the width of the graph, move left 10 pixels (to account for the
    //   shadow and border) and then left the width of the text
    $text_horiz_pos = $graph->img->width - 10 - $text->GetWidth($graph->img);
    $text->SetPos($text_horiz_pos, $text_vert_pos);

    $graph->AddText($text);
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function dpgraph_error($error, $width = 660, $height = 400)
{
    global $jpgraph_FF, $jpgraph_FS;

    $graph = new CanvasGraph($width, $height, get_image_cache_filename());
    $graph->SetMargin(12, 22, 12, 22);
    $graph->SetShadow();
    $graph->SetMarginColor("red");

    // Stroke the plot-area and margin first to avoid overwriting the text.
    $graph->InitFrame();

    $text = new Text($error, 200, 10);
    $text->SetFont($jpgraph_FF, $jpgraph_FS);

    $fontheight = $text->GetFontHeight($graph->img);

    $text->Center(0, $width, (($height / 2) - ($fontheight * 2)));
    $text->ParagraphAlign('center');

    // fill, border, shadow
    $text->SetBox("white", "red", "gray");

    $text->Stroke($graph->img);

    $graph->Stroke();
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function get_round_backlog_stats($interested_phases)
{
    // Pull the stats data out of the database
    $stats = [];
    foreach ($interested_phases as $phase) {
        $where_state = _get_project_state_selector($phase, ["available", "waiting"]);
        $sql = "SELECT SUM(n_pages) as num_pages
                FROM projects
                WHERE $where_state";
        $res = mysqli_query(DPDatabase::get_connection(), $sql);
        while ($result = mysqli_fetch_assoc($res)) {
            $stats[$phase] = $result["num_pages"];
        }
        mysqli_free_result($res);
    }

    // Pop off any trailing phases that have no pages.
    // This prevents new sites from showing rounds
    // that haven't started yet.
    $reverse_phases = array_reverse($interested_phases);
    foreach ($reverse_phases as $phase) {
        if ($stats[$phase] > 0) {
            break;
        }
        array_pop($stats);
    }

    return $stats;
}

function _get_project_state_selector($which, $desired_states = null)
// This function returns an SQL selector that can be used in a WHERE clause.
// $which is a word denoting a round ID or a phase (NEW, PP, GB)
{
    global $project_state_phase_;

    if (null == $desired_states) {
        $desired_states = ["unavailable", "waiting", "bad", "available", "complete"];
    } elseif (!is_array($desired_states)) {
        $desired_states = [$desired_states];
    }

    $sql = "";

    // see if it's a round
    $round = get_Round_for_round_id($which);
    if ($round != null) {
        $states = [];
        foreach ($desired_states as $desired_state) {
            $state_variable_name = "project_{$desired_state}_state";
            $states[] = $round->$state_variable_name;
        }
        $sql = "state IN ('" . implode("','", $states) . "')";
        return $sql;
    }

    // it may be a pool or stage so look at the stage's phase
    $states = [];
    foreach ($project_state_phase_ as $state => $phase) {
        if ($phase == $which) {
            array_push($states, $state);
        }
    }
    if (count($states)) {
        $sql = "state IN ('" . implode("','", $states) . "')";
        return $sql;
    }

    die("bad value for 'which': '$which'");
}
