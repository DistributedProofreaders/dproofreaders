<?php
include_once($relPath."CharSuites.inc");

use voku\helper\UTF8;

$charsuite = new CharSuite("basic-latin", _("Basic Latin"));
$charsuite->codepoints = [
    // https://en.wikipedia.org/wiki/Basic_Latin_(Unicode_block)
    'U+0020-U+007e',
    // https://en.wikipedia.org/wiki/Latin-1_Supplement_(Unicode_block)
    // without the soft hyphen
    'U+00a1-U+00ac',
    'U+00ae-U+00ff',
    // codepoints that map to Windows-1252 + extras
    'U+0128', // Ĩ - I with tilde (not in Windows-1252)
    'U+0129', // ĩ - i with tilde (not in Windows-1252)
    'U+0152', // Œ - OE ligature
    'U+0153', // œ - oe ligature
    'U+0160', // Š - S with caron
    'U+0161', // š - s with caron
    'U+0168', // Ũ - U with tilde (not in Windows-1252)
    'U+0169', // Ũ - u with tilde (not in Windows-1252)
    'U+0178', // Ÿ - Y with diaeresis
    'U+017d', // Ž - Z with caron
    'U+017e', // ž - z with caron
    'U+0192', // ƒ - latin small letter f with hook florin
    'U+1ebc', // Ẽ - E with tilde (not in Windows-1252)
    'U+1ebd', // ẽ - e with tilde (not in Windows-1252)
    'U+2039', // ‹ - single left-pointing guillemet
    'U+203a', // › - single right-pointing guillemet
];
$charsuite->reference_urls = [
    "https://en.wikipedia.org/wiki/Basic_Latin_(Unicode_block)",
    "https://en.wikipedia.org/wiki/Latin-1_Supplement_(Unicode_block)",
];

$pickerset = new PickerSet();
$pickerset->add_subset("A-Z", [
    ['U+0041-U+005a'], // A-Z
    ['U+0061-U+007a'], // a-z
]);
$pickerset->add_subset("ˆ", [
    [
        'U+00c1', 'U+00c9', 'U+00cd', 'U+00d3', 'U+00da', 'U+00dd', 'U+00c0',
        'U+00c8', 'U+00cc', 'U+00d2', 'U+00d9', 'U+00c2', 'U+00ca', 'U+00ce',
        'U+00d4', 'U+00db',
    ],
    [
        'U+00e1', 'U+00e9', 'U+00ed', 'U+00f3', 'U+00fa', 'U+00fd', 'U+00e0',
        'U+00e8', 'U+00ec', 'U+00f2', 'U+00f9', 'U+00e2', 'U+00ea', 'U+00ee',
        'U+00f4', 'U+00fb',
    ],
], _("Letters with acute, grave, and circumflex"));

$pickerset->add_subset("~", [
    [
        'U+00c3', 'U+1ebc', 'U+0128', 'U+00d1', 'U+00d5', 'U+0168', 'U+00c4',
        'U+00cb', 'U+00cf', 'U+00d6', 'U+00dc', 'U+0178', 'U+00c5-U+00c7',
        'U+00d0', 'U+00d8', 'U+0152', null, 'U+00de',
    ],
    [
        'U+00e3', 'U+1ebd', 'U+0129', 'U+00f1', 'U+00f5', 'U+0169', 'U+00e4',
        'U+00eb', 'U+00ef', 'U+00f6', 'U+00fc', 'U+00ff', 'U+00e5-U+00e7',
        'U+00f0', 'U+00f8', 'U+0153', 'U+00df', 'U+00fe',
    ],
], _("Letters with tilde and diaeresis; miscellaneous"));

$pickerset->add_subset("!", [
    [
        'U+0021', 'U+003f', 'U+002e', 'U+002c', 'U+003a', 'U+003b', 'U+0028-U+0029',
        'U+005b', 'U+005d', 'U+003c', 'U+003e', 'U+005c', 'U+002a',
    ],
    [
        'U+00a1', 'U+00bf', 'U+0022', 'U+0027', 'U+0026', 'U+0040', 'U+00ab',
        'U+00bb', 'U+2039', 'U+203a', 'U+007b', 'U+007d', 'U+002f', 'U+002d',
    ],
], _("Punctuation"));

$pickerset->add_subset(UTF8::hex_to_chr("U+00b6"), [
    [
        'U+00b6', 'U+00b7', 'U+0024', 'U+00a2-U+00a5', 'U+0192',
    ],
    [
        'U+007c', 'U+00a6', 'U+00a7', 'U+00a9', 'U+00ae', 'U+005f',
        'U+005e', 'U+007e',
    ],
], _("Symbols and Currency"));

$pickerset->add_subset("1", [
    [
        'U+0030-U+0039',
    ],
    [
        'U+0023', 'U+0025', 'U+00b0', 'U+002b', 'U+002d', 'U+003d',
        'U+00d7', 'U+00f7', 'U+00b1', 'U+00ac',
    ],
], _("Numbers and Math"));

$charsuite->pickerset = $pickerset;

CharSuites::add($charsuite);
