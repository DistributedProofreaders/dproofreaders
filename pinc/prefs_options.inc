<?php

define('BROWSER_DEFAULT_STR', _("Browser default"));

// The return values from get_available_proofreading_font_faces() and
// get_available_proofreading_font_sizes() are used as available fonts
// in the proofing interface. These strings are both shown to the
// user *and* passed in the HTML to the browser and thus must be valid values
// for font-family and font-size respectively.

// Care should be taken when changing the following arrays. The user
// preferences (stored in user_profiles) stores the array index
// and not the array value.
// The order listed here is the order they will appear.

// The 0 => '' entry is special and used to indicate the browser default.
// Due to how this is used in both PHP and JS code, the value should be
// empty and replaced with BROWSER_DEFAULT_STR before displaying to the
// user.
// The 1 => '' entry is also special, as it indicates that the user wants
// to use the value in x_fntf_other instead.

function get_available_proofreading_font_faces()
{
    return [
        0 => '', // browser default
        // Insert web fonts provided by the DP CSS here
        6 => 'DP Sans Mono',
        2 => 'DejaVu Sans Mono',
        // Leave "Other" at the end
        1 => '', // user-specified 'other'
    ];
}

function get_rank_neighbor_options()
{
    return ['0', '2', '4', '6', '8', '10', '12', '14', '16', '18', '20'];
}

define('PRIVACY_PRIVATE', 0);
define('PRIVACY_ANONYMOUS', 1);
// See ../faq/pophelp/prefs/set_privacy.html
// for definitions of these privacy categories.
