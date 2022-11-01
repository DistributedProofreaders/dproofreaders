<?php

$page_image_size_limit = 100; // kb
$page_image_minimum_size = 1000; // pixels

function get_image_size_error($image_size)
{
    global $page_image_size_limit;
    $result = null;

    if ($image_size > ($page_image_size_limit * 1024)) {
        $result = sprintf(_("Image > %dkb: %dkb"), $page_image_size_limit, $image_size / 1024);
    }
    return $result;
}

function get_image_size_small_error($image_dimensions)
{
    global $page_image_minimum_size;
    $result = null;

    if ($image_dimensions[0] < $page_image_minimum_size || $image_dimensions[1] < $page_image_minimum_size) {
        $result = _("The image may be too small: the shortest dimension of the images is less than $page_image_minimum_size pixels.");
    }
    return $result;
}
