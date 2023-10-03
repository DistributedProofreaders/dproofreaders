<?php
include_once($relPath."CharSuites.inc");

use voku\helper\UTF8;

$charsuite = new CharSuite("basic-cyrillic", _("Basic Cyrillic"));

$charsuite->codepoints = [
    // https://www.pgdp.net/phpBB3/viewtopic.php?p=1321508#p1321508
    'U+0401',
    'U+0406',
    'U+0410-U+044f',
    'U+0451',
    'U+0456',
    'U+0462-U+0463',
    'U+0472-U+0473',
];
$charsuite->reference_urls = [
    "https://www.pgdp.net/wiki/Basic_Cyrillic",

];

$pickerset = new PickerSet();

$pickerset->add_subset(UTF8::hex_to_chr('U+0411'), [
    // upper case А - Т
    [
        'U+0410-U+0415', 'U+0401', 'U+0416-U+0422',
    ],
    // lowercase а - т
    [
        'U+0430-U+0435', 'U+0451', 'U+0436-U+0442',
    ],
], _("Letters А - Т"));

$pickerset->add_subset(UTF8::hex_to_chr('U+042f'), [
    // upper case У - Я
    [
        'U+0423-U+042f', 'U+0406', 'U+0462', 'U+0472',
    ],
    // lowercase у - я
    [
        'U+0443-U+044f', 'U+0456', 'U+0463', 'U+0473',
    ],
], _("Letters У - Я and old Russian orthography"));

$charsuite->pickerset = $pickerset;

CharSuites::add($charsuite);
