<?php
include_once($relPath."CharSuites.inc");

use voku\helper\UTF8;

$charsuite = new CharSuite("medievalist-supplement", _("Medievalist supplement"));
$charsuite->description = _("Characters used for transcription and analysis of medieval writings");
$charsuite->codepoints = [
    // https://www.pgdp.net/wiki/Medievalist_supplement
    'U+0100-U+0103',
    'U+0111-U+0115',
    'U+0118-U+0119',
    'U+0127',
    'U+012a-U+012d',
    'U+014c-U+014f',
    'U+016a-U+016d',
    'U+017f',
    'U+0180',
    'U+01bf',
    'U+01e2',
    'U+01e3',
    'U+01ea-U+01eb',
    'U+01f7',
    'U+01fc-U+01fd',
    'U+021c-U+021d',
    'U+0232-U+0233',
    'U+204a',
    'U+a734-U+a735',
    'U+a751',
    'U+a753',
    'U+a755',
    'U+a75d',
    'U+a765',
    'U+a76b',
    'U+a76d',
    'U+a770',
];

$charsuite->reference_urls = [
    "https://www.pgdp.net/wiki/Medievalist_supplement",
];

$pickerset = new PickerSet();

$pickerset->add_subset(UTF8::hex_to_chr("U+0101"), [
    [
        'U+0100', 'U+0112', 'U+012a', 'U+014c', 'U+016a', 'U+0102', 'U+0114',
        'U+012c', 'U+014e', 'U+016c',
    ],
    [
        'U+0101', 'U+0113', 'U+012b', 'U+014d', 'U+016b', 'U+0103', 'U+0115',
        'U+012d', 'U+014f', 'U+016d',
    ],
], _("Letters with macron or breve"));

$pickerset->add_subset(UTF8::hex_to_chr("U+01fd"), [
    [
        'U+01fc', 'U+01e2', 'U+a734', 'U+0118', 'U+01ea', null, 'U+01f7',
        'U+021c', 'U+0232',
    ],
    [
        'U+01fd', 'U+01e3', 'U+a735', 'U+0119', 'U+01eb', 'U+017f', 'U+01bf',
        'U+021d', 'U+0233',
    ],
], _("Medieval letters"));

$pickerset->add_subset(UTF8::hex_to_chr("U+0180"), [
    [
        'U+a76b', 'U+204a', 'U+a76d', 'U+a75d', 'U+a770',
    ],
    [
        'U+0180', 'U+0111', 'U+0127', 'U+a751', 'U+a753', 'U+a755', 'U+a765',
    ],
], _("Medieval abbreviations"));

$charsuite->pickerset = $pickerset;

CharSuites::add($charsuite);
