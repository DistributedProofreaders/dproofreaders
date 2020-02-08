<?php

$page_image_size_limit = 100; // kb

function get_image_size_error($imageSize)
{
    global $page_image_size_limit;
    $result = NULL;

    if($imageSize > ($page_image_size_limit * 1024))
    {
        $result = sprintf(_("Image > %dkb: %dkb"), $page_image_size_limit, $imageSize / 1024);
    }
    return $result;
}
