<?php
function get_proofreading_interface_data_js()
{
    $font_data = [
        "faces" => get_font_styles(),
        "faceFamilies" => get_full_font_families(),
        "sizes" => array_values(get_available_proofreading_font_sizes()),
    ];

    $proof_int_data = json_encode([
        "font" => $font_data,
    ]);

    return "var proofIntData = $proof_int_data;";
}
