<?

// Various utilities for the scripts in this directory.

$relPath="./../../pinc/";
include_once($relPath.'misc.inc');
include_once($relPath.'v_site.inc');
include_once($jpgraph_dir.'/src/jpgraph.php');
include_once($jpgraph_dir.'/src/jpgraph_line.php');
include_once($jpgraph_dir.'/src/jpgraph_bar.php');

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function draw_pages_graph(
    $x_dates,
    $y_actual_n_pages,
    $y_page_goal,
    $x_frequency,
    $cumulative_or_increments,
    $title,
    $cache_timeout
)
// Draw various kinds of 'pages completed vs. time' graphs.
{
    global $jpgraph_FF, $jpgraph_FS;

    //var_dump($x_dates); echo "<br>"; var_dump($y_actual_n_pages);
    $n_datapoints = count($x_dates);
    // assert( count($y_actual_n_pages) == $n_datapoints );
    // assert( is_null($y_page_goal) || count($y_page_goal) == $n_datapoints );

    assert( $cumulative_or_increments == 'cumulative' || $cumulative_or_increments == 'increments' );

    // Calculate tick interval based on number of datapoints
    // Once we have more than about 30 labels, the axis is getting too crowded
    if ($x_frequency == 'daily')
    {
        if ($n_datapoints <= 31 ) {
                $x_text_tick_interval = 1;            // one label per day
        } else if ($n_datapoints < (30 * 7)) {
                $x_text_tick_interval = 7;            // one label per week
        } else if ($n_datapoints < (30 * 7 * 4)) {
                $x_text_tick_interval = 7 * 4;        // one label per 4 weeks (pseudo-month)
        } else if ($n_datapoints < (30 * 7 * 13)) {
                $x_text_tick_interval = 7 * 13;       // one label per quarter
        } else {
                $x_text_tick_interval = 7  * 52;       // one label per year
        }
    }
    else if ($x_frequency == 'monthly')
    {
        if ($n_datapoints < 30 ) {
                $x_text_tick_interval = 1;            // one label per month
        } else if ($n_datapoints < (30 * 3)) {
                $x_text_tick_interval = 3;            // one label per quarter
        } else {
                $x_text_tick_interval = 12;           // one label per year
        }
    }
    else
    {
        assert( FALSE );
    }

    // Create the graph. These two calls are always required
    // Last value controls how long the graph is cached for in minutes
    $graph = new Graph(640,400,"auto",$cache_timeout);
    $graph->SetScale("textint");

    // Set background to white
    $graph->SetMarginColor('white');

    // Add a drop shadow
    $graph->SetShadow();

    // Adjust the margin a bit to make more room for titles
    // left, right, top, bottom
    $graph->img->SetMargin(70,30,20,100);

    // Set title
    $graph->title->Set($title);
    $graph->title->SetFont($jpgraph_FF,$jpgraph_FS);

    // Set X axis
    $graph->xaxis->SetTickLabels($x_dates);
    $graph->xaxis->SetLabelAngle(90);
    $graph->xaxis->title->Set("");
    $graph->xaxis->title->SetFont($jpgraph_FF,$jpgraph_FS);
    $graph->xaxis->SetTextTickInterval($x_text_tick_interval);

    // Set Y axis
    $graph->yaxis->title->Set(_('Pages'));
    $graph->yaxis->title->SetFont($jpgraph_FF,$jpgraph_FS);
    $graph->yaxis->SetTitleMargin(45);

    // Create the plot for actual pages
    if ( $cumulative_or_increments == 'increments' )
    {
        $pages_plot = new BarPlot($y_actual_n_pages);
        $pages_plot->SetColor("blue");
    }
    else if ( $cumulative_or_increments == 'cumulative' )
    {
        $pages_plot = new LinePlot($y_actual_n_pages);
        $pages_plot->SetColor("blue");
        $pages_plot->SetWeight(1);
        $pages_plot->SetFillColor("blue");

        $graph->img->SetAntiAliasing(); 
    }
    else
    {
        assert( FALSE );
    }
    $graph->Add($pages_plot);

    if (!is_null($y_page_goal))
    {
        // Create the plot for the page goals.
        $goal_plot = new LinePlot($y_page_goal);
        $goal_plot->SetColor("lime");
        $goal_plot->SetWeight(2);
        $goal_plot->SetLegend(_('Goal'));
        $graph->Add($goal_plot);

        // Add a legend for the 'actual' plot too.
        $pages_plot->SetLegend(_("Actual"));

        $graph->legend->SetFont($jpgraph_FF,$jpgraph_FS);
        $graph->legend->Pos(0.05,0.5,"right" ,"top");
    }

    // Display the graph
    $graph->Stroke();
}

?>
