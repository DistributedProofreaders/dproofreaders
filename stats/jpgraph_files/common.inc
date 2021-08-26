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
