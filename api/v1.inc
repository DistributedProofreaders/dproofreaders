<?php
include_once($relPath.'base.inc');

// DP API v1
function v1_serverdaytime_GET($params)
{
    global $utf8_site;

    $time_string = strftime("%a %R");
    if(!$utf8_site)
    {
        $time_string = utf8_encode($time_string);
    }
    return array(
        "daytime" => $time_string
    );
}
