<?php
define( 'BROWSER_DEFAULT_STR', _("Browser default") );

# The return values from get_available_proofreading_font_faces() and
# get_available_proofreading_font_sizes() are used to set the font family
# and size in the proofing interface. These strings are both shown to the
# user *and* passed in the HTML to the browser and thus must be valid values
# for font-family and font-size respectively.

# Care should be taken when changing the following arrays. The user
# preferences (stored in user_profiles) stores the array index
# and not the array value.
# The order listed here is the order they will appear in drop-downs.

# The 0 => '' entry is special and used to indicate the browser default.
# Due to how this is used in both PHP and JS code, the value should be
# empty and replaced with BROWSER_DEFAULT_STR before displaying to the
# user.

function get_available_proofreading_font_faces()
{
    return [
        0 => '', // browser default
        5 => 'monospace',
        3 => 'Arial',
        1 => 'Courier',
        6 => 'DPCustomMono2',
        4 => 'Lucida',
        7 => 'Lucida Console',
        8 => 'Monaco',
        2 => 'Times',
    ];
}

function get_available_proofreading_font_sizes()
{
    return [
         0 => '', // browser default
         1 => '11px',
         2 => '12px',
         3 => '13px',
        12 => '14px',
         4 => '15px',
         5 => '16px',
         6 => '17px',
        13 => '18px',
         7 => '19px',
        14 => '20px',
         8 => '21px',
         9 => '22px',
        10 => '24px',
        11 => '26px',
    ];
}

// And a function to return user's current proofreading font
function get_user_proofreading_font()
{
    global $userP;
    $proofreading_font_faces = get_available_proofreading_font_faces();
    $proofreading_font_sizes = get_available_proofreading_font_sizes();

    if ( $userP['i_layout']==1 )   // "vertical"
    {
        $font_size_i = $userP['v_fnts'];
        $font_style_i = $userP['v_fntf'];
    }
    else   // "horizontal"
    {
        $font_size_i = $userP['h_fnts'];
        $font_style_i = $userP['h_fntf'];
    }

    $font_size = $proofreading_font_sizes[$font_size_i];
    $font_style = $proofreading_font_faces[$font_style_i];

    return array($font_style, $font_size);
}

// This string is appended to the user's proofreading font for a fallback
function get_proofreading_font_family_fallback()
{
    return "monospace";
}

// $u_n = show rank neighbors
$u_n= array('0', '2', '4', '6', '8', '10', '12', '14', '16', '18', '20');

define('PRIVACY_PUBLIC',    0);
define('PRIVACY_ANONYMOUS', 1);
define('PRIVACY_PRIVATE',   2);
// See ../faq/pophelp/prefs/set_privacy.html
// for definitions of these privacy categories.
