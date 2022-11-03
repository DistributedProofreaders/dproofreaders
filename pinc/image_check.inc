<?php

$page_image_size_limit = 100; // kb
$page_image_minimum_dimension = 5000; // pixels

function get_image_size_error($image_size)
{
    global $page_image_size_limit;
    $result = null;

    if ($image_size > ($page_image_size_limit * 1024)) {
        $result = sprintf(_("Image > %dkb: %dkb"), $page_image_size_limit, $image_size / 1024);
    }
    return $result;
}

function get_image_small_dimension_error($width, $height)
{
    global $page_image_minimum_dimension;
    $result = null;

    if ($width < $page_image_minimum_dimension || $height < $page_image_minimum_dimension) {
        $result = sprintf(_("The image may be too small: the shortest dimension of the images is less than %d pixels."), $page_image_minimum_dimension);
    }
    return $result;
}
