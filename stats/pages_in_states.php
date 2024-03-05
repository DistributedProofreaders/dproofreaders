<?php
$relPath = "./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'graph_data.inc');
include_once($relPath.'slim_header.inc');

require_login();

$width = 800;
$height = 500;

$n_pages_ = [];
$n_available_pages_ = [];
$res = DPDatabase::query("
    SELECT state, SUM(n_pages), SUM(n_available_pages)
    FROM projects
    WHERE state != '" . PROJ_SUBMIT_PG_POSTED . "' AND state != '" . PROJ_DELETE . "'
    GROUP BY state
");
while ([$state, $sum_n_pages, $sum_n_available_pages] = mysqli_fetch_row($res)) {
    $n_pages_[$state] = $sum_n_pages;
    $n_available_pages_[$state] = $sum_n_available_pages;
}

$stage_labels = [];
$unavail_n_pages = [];
$waiting_n_pages = [];
$available_n_pages = [];
$progordone_n_pages = [];

// ---------------

$stage_labels[] = 'New';
$unavail_n_pages[] = $n_pages_[PROJ_NEW] ?? 0;
$waiting_n_pages[] = 0;
$available_n_pages[] = 0;
$progordone_n_pages[] = 0;

foreach (Rounds::get_all() as $round) {
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

// ------------------------

$title = _("Number of pages in various states");
$graphs = [
    ["barLineGraph", "pages_in_states", [
        "title" => $title,
        "data" => [
            _('unavailable') => [
                "x" => $stage_labels,
                "y" => $unavail_n_pages,
            ],
            _('waiting (to be available)') => [
                "x" => $stage_labels,
                "y" => $waiting_n_pages,
            ],
            _('available') => [
                "x" => $stage_labels,
                "y" => $available_n_pages,
            ],
            _('in progress or done') => [
                "x" => $stage_labels,
                "y" => $progordone_n_pages,
            ],
        ],
        "width" => $width,
        "height" => $height,
        "groupBars" => true,
        "legendAdjustment" => [
            "x" => 500,
            "y" => 0,
        ],
    ]],
];

slim_header($title, [
    "body_attributes" => "style='margin: 0'",
    "js_files" => get_graph_js_files(),
    "js_data" => build_svg_graph_inits($graphs),
]);

echo "<div id='pages_in_states' style='width:" . $width . "px;height:" . $height . "px;'></div>";
