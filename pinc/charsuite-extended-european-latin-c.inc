<?php
include_once($relPath."CharSuites.inc");

use voku\helper\UTF8;

$charsuite = new CharSuite("extended-european-latin-c", _("Extended European Latin C"));
$charsuite->description = _("Supplementary characters for Polish, Croatian, Lithuanian, and Maltese");
$charsuite->codepoints = [
    # https://en.wikipedia.org/wiki/Latin_Extended-A
    'U+0104-U+0107',
    'U+010a-U+010d',
    'U+0110-U+0111',
    'U+0116-U+0119',
    'U+0120-U+0121',
    'U+0126-U+0127',
    'U+012e-U+012f',
    'U+0141-U+0144',
    'U+015a-U+015b',
    'U+0160-U+0161',
    'U+016a-U+016b',
    'U+0172-U+0173',
    'U+0179-U+017e',
];
$charsuite->reference_urls = [
    'https://en.wikipedia.org/wiki/Latin_Extended-A',
];

$pickerset = new PickerSet();
//  with diacriticals
$pickerset->add_subset(UTF8::hex_to_chr("U+0104"), [
    [
        'U+0104', 'U+0118', 'U+012e', 'U+0172', 'U+010a', 'U+0116', 'U+0120',
        'U+017b', 'U+016a',
    ],
    [
        'U+0105', 'U+0119', 'U+012f', 'U+0173', 'U+010b', 'U+0117', 'U+0121',
        'U+017c', 'U+016b',
    ],
], _("Letters with ogonek or dot"));

//  with diacriticals
$pickerset->add_subset(UTF8::hex_to_chr("U+0106"), [
    [
        'U+0106', 'U+0143', 'U+015a', 'U+0179', 'U+010c', 'U+0160', 'U+017d',
        'U+0110', 'U+0126', 'U+0141',
    ],
    [
        'U+0107', 'U+0144', 'U+015b', 'U+017a', 'U+010d', 'U+0161', 'U+017e',
        'U+0111', 'U+0127', 'U+0142',
    ],
], _("Letters with acute, caron or stroke"));

$charsuite->pickerset = $pickerset;

CharSuites::add($charsuite);
