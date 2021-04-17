<?php

// SITE-SPECIFIC
// Functions that deal with the walkthrough housed in $dyn_dir

function get_walkthrough_url($langcode = null)
{
    global $dyn_dir, $dyn_url;

    // if no langcode was passed in, try to get the user's language
    if (!$langcode) {
        $langcode = substr(get_desired_language(), 0, 2);
    }

    // first try $langcode, then English
    foreach ([$langcode, "en"] as $langcode) {
        if (is_dir("$dyn_dir/walkthrough/$langcode")) {
            return "$dyn_url/walkthrough/$langcode";
        }
    }

    return "";
}
