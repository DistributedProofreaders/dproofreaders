<?

// Various utilities for the scripts in this directory.

$relPath="./../../pinc/";
include_once($relPath.'misc.inc');
include_once($relPath.'v_site.inc');
include_once($jpgraph_dir.'/src/jpgraph.php');
include_once($jpgraph_dir.'/src/jpgraph_line.php');
include_once($jpgraph_dir.'/src/jpgraph_bar.php');

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function get_number_of_days_in_current_month()
{
    $current = getdate();
    // The last day of this month is the "zeroth" day of next month.
    $last_day_timestamp =
        mktime( 0, 0, 0, $current['mon'] + 1, 0, $current['year'] );
    $last_day = getdate($last_day_timestamp);
    return $last_day['mday'];
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function get_project_status_descriptor( $which )
// $which is a word denoting a possible status of a project
// (created, proofed, PPd, or posted).
// Return an object whose attributes hold various constants that
// are useful for plotting a graph of projects having that status.
// (Somewhat ad hoc, but useful.)
{
    $obj = new stdClass;

    switch ( $which )
    {
        case 'created':
            $obj->state_selector = "
                state NOT LIKE 'proj_new%'
            ";
            $obj->color = 'green';
            $obj->per_day_title = _('Projects Created Each Day');
            $obj->cumulative_title = _('Cumulative Projects Created');
            break;

        case 'proofed':
            $obj->state_selector = "
                state LIKE 'proj_submit%'
                OR state LIKE 'proj_correct%'
                OR state LIKE 'proj_post%'
            ";
            $obj->color = 'blue';
            $obj->per_day_title = _('Projects Proofed Each Day');
            $obj->cumulative_title = _('Cumulative Projects Proofed');
            break;

        case 'PPd':
            $obj->state_selector = "
                state LIKE 'proj_submit%'
                OR state LIKE 'proj_correct%'
                OR state LIKE 'proj_post_second%'
            ";
            $obj->color = 'silver';
            $obj->per_day_title = _('Projects Post-Processed Each Day');
            $obj->cumulative_title = _('Cumulative Projects PPd');
            break;

        case 'posted':
            $obj->state_selector = "
                state LIKE 'proj_submit%'
                OR state LIKE 'proj_correct%'
            ";
            $obj->color = 'gold';
            $obj->per_day_title = _('Projects Posted to PG Each Day');
            $obj->cumulative_title = _('Cumulative Projects Posted to PG');
            break;

        default:
            die("bad value for 'which': '$which'");
    }
    return $obj;
}

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
    $n_datapoints = max( count($y_actual_n_pages), count($y_page_goal) );
    // assert( count(x_dates$) == $n_datapoints );
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

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function draw_projects_graph(
    $x_dates,
    $y_num_projects,
    $cumulative_or_increments,
    $color,
    $title,
    $cache_timeout
)
// Draw various kinds of 'number of projects vs. time' graphs.
{
    global $jpgraph_FF, $jpgraph_FS;

    $n_datapoints = count($y_num_projects);
    /*
    if ( count($x_dates) != $n_datapoints )
    {
        echo "$n_datapoints Y values, but ". count($x_dates) ." X values.<br>";
        die('aborting');
    }
    */

    assert( $cumulative_or_increments == 'cumulative' || $cumulative_or_increments == 'increments' );

    // Calculate tick interval based on number of datapoints
    // Once we have more than about 30 labels, the axis is getting too crowded
    // $x_frequency == 'daily'
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
    $graph->yaxis->title->Set(_('Projects'));
    $graph->yaxis->title->SetFont($jpgraph_FF,$jpgraph_FS);
    $graph->yaxis->SetTitleMargin(45);

    // Create the plot
    if ( $cumulative_or_increments == 'increments' )
    {
        //Create the bar plot
        $plot = new BarPlot($y_num_projects);
        $plot->SetFillColor($color);
    }
    else if ( $cumulative_or_increments == 'cumulative' )
    {
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

        for ( $i = count($y_num_projects)-1; $i >= 0; $i-- )
        {
            if ( $y_num_projects[$i] !== '' )
            {
                break;
            }
        }
        $plot->AddArea(0,$i,LP_AREA_FILLED,$color);

        $graph->img->SetAntiAliasing(); 
    }
    else
    {
        assert( FALSE );
    }
    $graph->Add($plot);

    // Display the graph
    $graph->Stroke();
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function draw_simple_bar_graph(
    $datax,
    $datay,
    $x_label_interval,
    $title,
    $y_title,
    $width, $height,
    $cache_timeout
)
{
    global $jpgraph_FF, $jpgraph_FS;

    // Create the graph. These two calls are always required
    // Last value controls how long the graph is cached for in minutes
    $graph = new Graph($width,$height,"auto",$cache_timeout);
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
    $graph->xaxis->SetTickLabels($datax);
    $graph->xaxis->SetLabelAngle(90);
    $graph->xaxis->title->Set("");
    $graph->xaxis->title->SetFont($jpgraph_FF,$jpgraph_FS);
    $graph->xaxis->SetTextTickInterval($x_label_interval);

    // Set Y axis
    $graph->yaxis->title->Set($y_title);
    $graph->yaxis->title->SetFont($jpgraph_FF,$jpgraph_FS);
    $graph->yaxis->SetTitleMargin(45);

    // Create a bar plot
    $plot = new BarPlot($datay);
    if ( count($datax) > 60 )
    {
        // The bar outlines will overwhelm the bar interiors
        // turning the graph black. So turn off the bar outlines:
        $plot->SetColor("lightblue");
    }
    $graph->Add($plot);

    // Display the graph
    $graph->Stroke();
}

// vim: sw=4 ts=4 expandtab
?>
