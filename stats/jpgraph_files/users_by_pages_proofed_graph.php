<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'page_tally.inc');
include_once("common.inc");

// Create the graph. We do this before everything else
// to make use of the jpgraph cache if enabled.
// Last value controls how long the graph is cached for in minutes.
$graph = new Graph(640,400,get_image_cache_filename(),1440);

///////////////////////////////////////////////////////////////////
//Number of users who have done X pages, and how recently logged in
//(time scales used: ever, last 90 days, last 28 days, last 7 days)


// define threshold timestamps
$seconds_per_day = 24 * 60 * 60;
$now = time();
$t_90_days_ago = $now - (90 * $seconds_per_day);
$t_28_days_ago = $now - (28 * $seconds_per_day);
$t_7_days_ago  = $now - ( 7 * $seconds_per_day);

list($users_ELR_page_tallyboard, ) = get_ELR_tallyboards();

list($joined_with_user_ELR_page_tallies, $user_ELR_page_tally_column) =
    $users_ELR_page_tallyboard->get_sql_joinery_for_current_tallies('u_id');

// how many bars in the graph?
$result0 = mysqli_query(DPDatabase::get_connection(), "
    SELECT max($user_ELR_page_tally_column) as maxpages
    FROM users $joined_with_user_ELR_page_tallies
");
$row = mysqli_fetch_assoc($result0);
$maxpages = $row["maxpages"];



//query db and put results into arrays
$result = mysqli_query(DPDatabase::get_connection(), "
    SELECT
        $user_ELR_page_tally_column        AS pagescompleted,
        COUNT(*)                         AS n_all,
        SUM(last_login > $t_90_days_ago) AS n_90d,
        SUM(last_login > $t_28_days_ago) AS n_28d,
        SUM(last_login > $t_7_days_ago)  AS n_7d
    FROM users $joined_with_user_ELR_page_tallies
    GROUP BY pagescompleted
    ORDER BY pagescompleted ASC
");


// Initialize the data arrays for all 'possible' values of pagescompleted
// (many of which won't be the current value for any particular user).
//
for ($pagescompleted_i = 0; $pagescompleted_i <= $maxpages; $pagescompleted_i++ )
{
    $datax[$pagescompleted_i] = $pagescompleted_i;

    $datayAll[$pagescompleted_i] = 0;
    $datay90[ $pagescompleted_i] = 0;
    $datay28[ $pagescompleted_i] = 0;
    $datay7[  $pagescompleted_i] = 0;
}


// For each pagescompleted value in the result-set,
// add the corresponding n_* values to the arrays of Y-axis data.
//
while ($row = mysqli_fetch_object($result))
{
    if ($row->pagescompleted >= 0)
    {
        $datayAll[$row->pagescompleted] = $row->n_all;
        $datay90[ $row->pagescompleted] = $row->n_90d;
        $datay28[ $row->pagescompleted] = $row->n_28d;
        $datay7[  $row->pagescompleted] = $row->n_7d;
    }
}




$graph->SetScale("textint");
$graph->graph_theme = null;

//set X axis
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->SetLabelAngle(90);
$graph->xaxis->title->Set(_("Pages Proofread"));
// Only draw labels on every 100th tick mark
$graph->xaxis->SetTextTickInterval(1000);

//Set Y axis
$graph->yaxis->title->Set(_("Number of Proofreaders"));
$graph->yaxis->SetTitleMargin(45);

//Set background to white
$graph->SetMarginColor('white');

// Add a drop shadow
$graph->SetShadow();

// Adjust the margin a bit to make more room for titles
//left, right , top, bottom
$graph->img->SetMargin(70,30,20,100);


// Create the bar pots
$bplotAll = new BarPlot($datayAll);
$bplotAll ->SetFillColor ("cadetblue1");
$bplotAll->SetLegend(_("All Registered Users"));

$bplot90 = new BarPlot($datay90);
$bplot90 ->SetFillColor ("mediumseagreen");
$bplot90->SetLegend(sprintf(_("Logged on in last %d days"), 90));


$bplot28 = new BarPlot($datay28);
$bplot28 ->SetFillColor ("lime");
$bplot28->SetLegend(sprintf(_("Logged on in last %d days"), 28));


$bplot7 = new BarPlot($datay7);
$bplot7 ->SetFillColor ("yellow");
$bplot7->SetLegend(sprintf(_("Logged on in last %d days"), 7));


// Create the grouped bar plot
$gbplot = new GroupBarPlot (array($bplotAll ,$bplot90,$bplot28 ,$bplot7 ));

// ...and add it to the graPH
$graph->Add( $gbplot);


// Setup the title
$graph->title->Set(
    _("Numbers of Proofreaders who have Proofread X pages")
);
$graph->subtitle->Set(
    _("and How Recently Logged In")
);



$graph->title->SetFont($jpgraph_FF,$jpgraph_FS);
$graph->yaxis->title->SetFont($jpgraph_FF,$jpgraph_FS);
$graph->xaxis->title->SetFont($jpgraph_FF,$jpgraph_FS);

add_graph_timestamp($graph);

// Display the graph
$graph->Stroke();

