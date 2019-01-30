<?php
include_once($relPath.'base.inc');

// DP API v1
function v1_serverdaytime_GET($params)
{
    global $utf8_site;

    $now = time();
    $day = strftime("%a", $now);
    if(!$utf8_site)
    {
        $day = utf8_encode($day);
    }
    return array(
        "day" => $day,
        "hour" => strftime("%H", $now),
        "minute" => strftime("%M", $now)
    );
}
