<?php
include_once($relPath."CharSuites.inc");

use voku\helper\UTF8;

$charsuite = new CharSuite("basic-greek", _("Basic Greek"));
$charsuite->codepoints = [
    // https://en.wikipedia.org/wiki/Greek_and_Coptic
    'U+0391-U+03a1',
    'U+03a3-U+03a9',
    'U+03b1-U+03c9',
];
$charsuite->reference_urls = [
    "https://en.wikipedia.org/wiki/Greek_and_Coptic",
];

$pickerset = new PickerSet();
$pickerset->add_subset(UTF8::hex_to_chr('U+0391') . '-' . UTF8::hex_to_chr('U+03a9'), [
    // capital alpha through omega
    ['U+0391-U+03a1', null, 'U+03a3-U+03a9'],
    // lowercase alpha through omega
    ['U+03b1-U+03c9'],
]);
$charsuite->pickerset = $pickerset;

CharSuites::add($charsuite);
