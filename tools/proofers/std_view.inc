<?php
function get_std_view_data()
{
    $user = User::load_current();

    $split_vertical = ($user->profile->i_layout == 1);

    if ($split_vertical) {
        $text_percent = $user->profile->v_tframe;
    } else {
        $text_percent = $user->profile->h_tframe;
    }

    $view_data = json_encode([
        "splitVertical" => $split_vertical,
        "splitPercent" => 100 - $text_percent,
    ]);

    return "var viewData = $view_data;";
}

function echo_std_view()
{
    echo "<div id='container' style='height: 100%;'>\n";
    echo "<div id='image-view' class='page-interface'></div>\n";
}
