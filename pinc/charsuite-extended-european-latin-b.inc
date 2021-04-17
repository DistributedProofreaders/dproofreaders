<?php
include_once($relPath."CharSuites.inc");

use voku\helper\UTF8;

$charsuite = new CharSuite("extended-european-latin-b", _("Extended European Latin B"));
$charsuite->description = _("Supplementary characters for Slovenian, Estonian, Czech, Slovak, and Latvian");
$charsuite->codepoints = [
    // https://en.wikipedia.org/wiki/Latin_Extended-A
    'U+0100-U+0101',
    'U+010c-U+010f',
    'U+0112-U+0113',
    'U+011a-U+011b',
    'U+0122-U+0123',
    'U+012a-U+012b',
    'U+0136-U+0137',
    'U+0139-U+013e',
    'U+0145-U+0148',
    'U+014c-U+014d',
    'U+0154-U+0159',
    'U+0160-U+0161',
    'U+0164-U+0165',
    'U+016a-U+016b',
    'U+016e-U+016f',
    'U+017d-U+017e',
];
$charsuite->reference_urls = [
    'https://en.wikipedia.org/wiki/Latin_Extended-A',
];

$pickerset = new PickerSet();
//  with diacriticals
$pickerset->add_subset(UTF8::hex_to_chr("U+010c"), [
    [
        'U+010c', 'U+010e', 'U+011a', 'U+013d', 'U+0147', 'U+0158', 'U+0160',
        'U+0164', 'U+017d', 'U+0139', 'U+0154', 'U+016e',
    ],
    [
        'U+010d', 'U+010f', 'U+011b', 'U+013e', 'U+0148', 'U+0159', 'U+0161',
        'U+0165', 'U+017e', 'U+013a', 'U+0155', 'U+016f',
    ],
], _("Letters with caron"));

//  with diacriticals
$pickerset->add_subset(UTF8::hex_to_chr("U+0122"), [
    [
        'U+0122', 'U+0136', 'U+013b', 'U+0145', 'U+0156', 'U+0100', 'U+0112',
        'U+012a', 'U+014c', 'U+016a',
    ],
    [
        'U+0123', 'U+0137', 'U+013c', 'U+0146', 'U+0157', 'U+0101', 'U+0113',
        'U+012b', 'U+014d', 'U+016b',
    ],
], _("Letters with cedilla or macron"));


$charsuite->pickerset = $pickerset;

CharSuites::add($charsuite);
