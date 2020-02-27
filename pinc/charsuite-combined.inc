<?php
include_once($relPath."CharSuites.inc");

$charsuite = new CharSuite("combined", _("Combined"));
$charsuite->codepoints= [
    'U+004E',
    'U+004E>U+0305',
    'U+004E>U+0305>U+0306',
    'U+004E>U+0306',
    'U+004E>U+0307',
    'U+004E>U+0308',
    'U+004E>U+0309',
    'U+004E>U+030A',
    'U+004E>U+030B',
    'U+004E>U+030C',
];
$pickerset = new PickerSet();
$pickerset->add_subset(utf8_combined_chr("U+004E>U+0305"), [
    [
    'U+004E>U+0305',
    'U+004E>U+0305>U+0306',
    'U+004E>U+0306',
    'U+004E>U+0307',
    'U+004E>U+0308',
    'U+004E>U+0309',
    'U+004E>U+030A',
    'U+004E>U+030B',
    'U+004E>U+030C',
],
    [],
]);
$charsuite->pickerset = $pickerset;

CharSuites::add($charsuite);

