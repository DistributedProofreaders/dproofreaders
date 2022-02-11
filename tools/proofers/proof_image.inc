<?php

function get_image_data($ppage)
{
    $user = User::load_current();
    $split_vertical = ($user->profile->i_layout == 1);

    if ($user->profile->i_type == 1) {
        $storage_key = "proof-enh";
        $align = "L";
    } else {
        $storage_key = "proof-std";
        $align = "C";
    }
    $storage_key .= ($split_vertical ? "-v" : "-h");

    $image_data = json_encode([
        "imageUrl" => $ppage->url_for_image(),
        "storageKey" => $storage_key,
        "align" => $align,
    ]);

    return "var imageData = $image_data;";
}
