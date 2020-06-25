<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'stages.inc');
include_once('common.inc');

require_login();

$graph = new Graph(800, 500, get_image_cache_filename(), 60);

$n_pages_ = array();
$n_available_pages_ = array();
$res = mysqli_query(DPDatabase::get_connection(), "
    SELECT state, SUM(n_pages), SUM(n_available_pages)
    FROM projects
    WHERE state != 'proj_submit_pgposted' AND state != 'project_delete'
    GROUP BY state
") or die(DPDatabase::log_error());
while (list($state,$sum_n_pages,$sum_n_available_pages) = mysqli_fetch_row($res) )
{
    $n_pages_[$state] = $sum_n_pages;
    $n_available_pages_[$state] = $sum_n_available_pages;
}

$stage_labels = array();
$unavail_n_pages=array();
$waiting_n_pages=array();
$available_n_pages=array();
$progordone_n_pages=array();

// ---------------

$stage_labels[] = 'New';
$unavail_n_pages[] = $n_pages_[PROJ_NEW];
$waiting_n_pages[] = 0;
$available_n_pages[] = 0;
$progordone_n_pages[] = 0;

foreach ( $Round_for_round_id_ as $round )
{
    $stage_labels[] = $round->id;
    $unavail_n_pages[] = @$n_pages_[$round->project_unavailable_state] + @$n_pages_[$round->project_bad_state];
    $waiting_n_pages[] = @$n_pages_[$round->project_waiting_state];
    $available_n_pages[] = @$n_available_pages_[$round->project_available_state];
    $progordone_n_pages[] =
        @$n_pages_[$round->project_available_state]
        - @$n_available_pages_[$round->project_available_state]
        + @$n_pages[$round->project_complete_state];
}

$stage_labels[] = 'PP';
$unavail_n_pages[] = @$n_pages_[PROJ_POST_FIRST_UNAVAILABLE];
$waiting_n_pages[] = 0;
$available_n_pages[] = @$n_pages_[PROJ_POST_FIRST_AVAILABLE];
$progordone_n_pages[] = @$n_pages_[PROJ_POST_FIRST_CHECKED_OUT];


$stage_labels[] = 'PPV';
$unavail_n_pages[] = 0;
$waiting_n_pages[] = 0;
$available_n_pages[] = @$n_pages_[PROJ_POST_SECOND_AVAILABLE];
$progordone_n_pages[] = @$n_pages_[PROJ_POST_SECOND_CHECKED_OUT];

// ---------------

$graph->SetScale("textlin");

$graph->SetShadow();
$graph->img->SetMargin(75, 50, 50, 100);

// ------------------------

// Create the bar plots
$unavail_plot = new BarPlot($unavail_n_pages);
$unavail_plot->SetLegend(_('unavailable'));

$waiting_plot = new BarPlot($waiting_n_pages);
$waiting_plot->SetLegend(_('waiting (to be available)'));

$available_plot = new BarPlot($available_n_pages);
$available_plot->SetLegend(_('available'));

$progordone_plot = new BarPlot($progordone_n_pages);
$progordone_plot->SetLegend(_('in progress or done'));

// Create the grouped bar plot
$gbplot = new GroupBarPlot(array($unavail_plot,$waiting_plot,$available_plot,$progordone_plot));

// ...and add it to the graph
$graph->Add($gbplot);

// ------------------------

$graph->title->Set(_("Number of pages in various states"));
$graph->xaxis->title->Set(_("stage"));
// $graph->yaxis->title->Set(_("# pages"));

$graph->xaxis->SetTickLabels($stage_labels);

$graph->title->SetFont($jpgraph_FF,$jpgraph_FS);
$graph->yaxis->title->SetFont($jpgraph_FF,$jpgraph_FS);
$graph->xaxis->title->SetFont($jpgraph_FF,$jpgraph_FS);

// Display the graph
$graph->Stroke();

// vim: sw=4 ts=4 expandtab
