<?php
include_once($relPath."CharSuites.inc");

use voku\helper\UTF8;

$charsuite = new CharSuite("math-symbols", _("Math symbols"));

$charsuite->description = _("Commonly used mathematical symbols not in Basic Latin");

$charsuite->codepoints = [
    'U+2202-U+2203',
    'U+2207-U+2209',
    'U+221a',
    'U+221e',
    'U+2220',
    'U+2229-U+222b',
    'U+2234-U+2235',
    'U+2237',
    'U+2248',
    'U+2260-U+2261',
    'U+2264-U+2265',
    'U+2282-U+2283',
    'U+2295',
    'U+2297',
];

$charsuite->reference_urls = [
    "https://www.pgdp.net/wiki/Math_symbols",
];

$pickerset = new PickerSet();

$pickerset->add_subset(UTF8::hex_to_chr("U+222b"), [
    [
        'U+2248', 'U+2260', 'U+2264-U+2265', 'U+2261', null, 'U+222b',
        'U+2202', 'U+2207', 'U+221e', 'U+221a', 'U+2220', 'U+2237',
    ],
    [
        'U+2203', 'U+2208-U+2209', 'U+2282-U+2283', 'U+2229-U+222a', null,
        'U+2234-U+2235', null, 'U+2295', 'U+2297',
    ],
], _("Math symbols"));

$charsuite->pickerset = $pickerset;

CharSuites::add($charsuite);
