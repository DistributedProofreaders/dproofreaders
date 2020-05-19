<?php

$page_image_size_limit = 100; // kb

function get_image_size_error($image_size)
{
    global $page_image_size_limit;
    $result = NULL;

    if($image_size > ($page_image_size_limit * 1024))
    {
        $result = sprintf(_("Image > %dkb: %dkb"), $page_image_size_limit, $image_size / 1024);
    }
    return $result;
}
