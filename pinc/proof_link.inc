<?php

function url_for_any_page($projectid, $round)
{
    global $code_url;
    return "$code_url/tools/proofers/uproof.php?round=$round->id&amp;projectid=$projectid";
}

function url_for_given_page($projectid, $round, $imagefile)
{
    global $code_url;
    return "$code_url/tools/proofers/uproof.php?projectid=$projectid&amp;imagefile=$imagefile&amp;round=$round->id";
}
