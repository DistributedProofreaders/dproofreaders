<?php

function get_pophelp_json(string $category): string
{
    global $code_url, $code_dir;

    if ($category == "prefs") {
        include("$code_dir/faq/pophelp/prefs/prefs_pophelp.inc");
        $help = $prefs_pophelp;
    } elseif ($category == "teams") {
        include("$code_dir/faq/pophelp/teams/teams_pophelp.inc");
        $help = $teams_pophelp;
    } else {
        throw new ValueError("Invalid category");
    }

    return json_encode($help);
}
