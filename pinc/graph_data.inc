<?php

include_once($relPath.'dpsql.inc');
include_once($relPath.'project_states.inc');

function get_graph_js_files()
{
    global $code_url;
    return [
        "$code_url/pinc/3rdparty/d3/d3.min.js",
        "$code_url/scripts/stackedAreaChart.js",
    ];
}

function cumulative_total_proj_summary_graph()
{
    global $project_status_descriptors;
    $max_num_data = 0;
    $data = [];

    foreach ($project_status_descriptors as $which) {
        $psd = get_project_status_descriptor($which);

        //query db and put results into arrays
        $result = mysqli_query(DPDatabase::get_connection(), "
            SELECT date, SUM(num_projects)
            FROM project_state_stats
            WHERE $psd->state_selector
            GROUP BY DATE
            ORDER BY date ASC
        ");

        [$datax, $datay] = dpsql_fetch_columns($result);

        if (empty($datay)) {
            $datay[0] = 0;
        }

        if (count($datay) > $max_num_data) {
            $max_num_data = count($datay);
        }

        $data[$which] = ["x" => $datax, "y" => $datay];
    }

    return [
        "data" => $data,
        "title" => _("Total Projects Created, Proofread, Post-Processed and Posted"),
    ];
}
