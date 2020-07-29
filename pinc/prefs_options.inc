<?php
include_once($relPath."misc.inc"); // array_get()

define( 'BROWSER_DEFAULT_STR', _("Browser default") );

# The return values from get_available_proofreading_font_faces() and
# get_available_proofreading_font_sizes() are used to set the font family
# and size in the proofing interface. These strings are both shown to the
# user *and* passed in the HTML to the browser and thus must be valid values
# for font-family and font-size respectively.

# Care should be taken when changing the following arrays. The user
# preferences (stored in user_profiles) stores the array index
# and not the array value.
# The order listed here is the order they will appear.

# The 0 => '' entry is special and used to indicate the browser default.
# Due to how this is used in both PHP and JS code, the value should be
# empty and replaced with BROWSER_DEFAULT_STR before displaying to the
# user.
# The 1 => '' entry is also special, as it indicates that the user wants
# to use the value in x_fntf_other instead.

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

// And a function to return user's current proofreading font and size
// This returns [ $font_face, $font_size, $font_family_string, $font_size_string ]
//
// $full_font_family is the current selection from get_full_font_families() below.
function get_user_proofreading_font($interface=NULL)
{
    $proofreading_font_faces = get_available_proofreading_font_faces();
    $proofreading_font_sizes = get_available_proofreading_font_sizes();

    $user = User::load_current();
    if($interface === NULL)
    {
        $interface = $user ? $user->profile->i_layout : NULL;
    }

    if( !$user ) {
        $font_size_i = 0;
        $font_style_i = 0;
    }
    elseif ( $interface == 1 )   // "vertical"
    {
        $font_size_i = $user->profile->v_fnts;
        $font_style_i = $user->profile->v_fntf;
    }
    else   // "horizontal"
    {
        $font_size_i = $user->profile->h_fnts;
        $font_style_i = $user->profile->h_fntf;
    }

    if($font_style_i == 1) // other
    {
        $font_style = get_user_proofreading_font_other($interface);
    }
    else
    {
        $font_style = array_get($proofreading_font_faces, $font_style_i, '');
    }
    $font_size = array_get($proofreading_font_sizes, $font_size_i, '');

    $full_font_family = get_full_font_families($interface)[$font_style_i];
    $font_size_family = $font_size ? $font_size : 'unset';

    return array($font_style, $font_size, $full_font_family, $font_size_family);
}

// return an array of strings which the user sees in the font selector
// The array is indexed like get_available_proofreading_font_faces()
// this is used in the format preview font selector.
function get_font_styles($interface = NULL)
{
    $font_styles = get_available_proofreading_font_faces();
    $font_styles[0] = BROWSER_DEFAULT_STR;
    $font_styles[1] = get_user_proofreading_font_other($interface);
    if(!$font_styles[1])
    {
        // remove an empty string
        unset($font_styles[1]);
    }
    return $font_styles;
}

// return an array of font-family strings to use to render text
// in the user's proofreading font plus the font fallback. Font faces
// with spaces in them are esacped in _single quotes_
// The array is indexed like get_available_proofreading_font_faces()
// This is used above and to select fonts in the format preview.
function get_full_font_families($interface = NULL)
{
    $full_font_family = [];
    $proofreading_font_faces = get_available_proofreading_font_faces();
    $fallback_font = get_proofreading_font_family_fallback();
    $proofreading_font_faces[1] = get_user_proofreading_font_other($interface);
    foreach($proofreading_font_faces as $index => $font_style)
    {
        if($font_style)
        {
            if(stripos($font_style, ' ') !== FALSE)
            {
                $full_font_family[$index] = "'$font_style', $fallback_font";
            }
            else
            {
                $full_font_family[$index] = "$font_style, $fallback_font";
            }
        }
        else
        {
            $full_font_family[$index] = $fallback_font;
        }
    }
    return $full_font_family;
}

// return an array of strings which the user sees in the font size selector
// The array is indexed like get_available_proofreading_font_sizes()
// this is used in the format preview font selector.
function get_font_sizes()
{
    $font_sizes = get_available_proofreading_font_sizes();
    $font_sizes[0] = BROWSER_DEFAULT_STR;
    return $font_sizes;
}

// return an array of strings to use to render text
// The array is indexed like get_available_proofreading_font_sizes()
function get_font_sizes_css()
{
    $font_sizes = get_available_proofreading_font_sizes();
    $font_sizes[0] = 'unset';
    return $font_sizes;
}

function get_user_proofreading_font_other($interface=NULL)
{
    $user = User::load_current();

    // If we don't have a current user, they can't have an other font
    if(!$user)
    {
        return "";
    }

    if($interface === NULL)
    {
        $interface = $user->profile->i_layout;
    }

    if ( $interface == 1 )   // "vertical"
    {
        return $user->profile->v_fntf_other;
    }
    else   // "horizontal"
    {
        return $user->profile->h_fntf_other;
    }
}

// This string is appended to the user's proofreading font for a fallback
function get_proofreading_font_family_fallback()
{
    return "monospace";
}

function get_rank_neighbor_options()
{
    return ['0', '2', '4', '6', '8', '10', '12', '14', '16', '18', '20'];
}

define('PRIVACY_PUBLIC',    0);
define('PRIVACY_ANONYMOUS', 1);
define('PRIVACY_PRIVATE',   2);
// See ../faq/pophelp/prefs/set_privacy.html
// for definitions of these privacy categories.
