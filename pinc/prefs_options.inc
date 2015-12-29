<?php
define( 'BROWSER_DEFAULT_STR', _("Browser default") );

# Care should be taken when changing the following arrays. The user
# preferences (stored in user_profiles) stores the array index
# and not the array value.
# The order listed here is the order they will appear in drop-downs.

# The 0 => '' entry is special and used to indicate the browser default.
# Due to how this is used in both PHP and JS code, the value should be
# empty and replaced with BROWSER_DEFAULT_STR before displaying to the
# user.

$proofreading_font_faces = array(
    0 => '', // browser default
    5 => 'monospace',
    3 => 'Arial',
    1 => 'Courier',
    6 => 'DPCustomMono2',
    4 => 'Lucida',
    7 => 'Lucida Console',
    8 => 'Monaco',
    2 => 'Times',
);

$proofreading_font_sizes = array (
    0 => '', // browser default
    1 => '8pt',
    2 => '9pt',
    3 => '10pt',
    4 => '11pt',
    5 => '12pt',
    6 => '13pt',
    7 => '14pt',
    8 => '15pt',
    9 => '16pt',
    10 => '18pt',
    11 => '20pt',
);

// $u_n = show rank neighbors
$u_n= array('0', '2', '4', '6', '8', '10', '12', '14', '16', '18', '20');

define('PRIVACY_PUBLIC',    0);
define('PRIVACY_ANONYMOUS', 1);
define('PRIVACY_PRIVATE',   2);
// See ../faq/pophelp/prefs/set_privacy.html
// for definitions of these privacy categories.
