<?php
include_once($relPath."CharSuites.inc");

$charsuite = new CharSuite("extended-european-latin", _("Extended European Latin"));
$charsuite->codepoints = [
    # https://en.wikipedia.org/wiki/Latin_Extended-A
    'U+0100-U+0148',
    'U+014A-U+017F',
];
$charsuite->reference_urls = [
    'https://en.wikipedia.org/wiki/Latin_Extended-A',
];

$pickerset = new PickerSet();
# A-E with diacriticals
$pickerset->add_subset(utf8_chr("\u0100"), [
    [ 'U+0100', 'U+0102', 'U+0104', 'U+0106', 'U+0108', 'U+010a', 'U+010c',
      'U+010e', 'U+0110', 'U+0112', 'U+0114', 'U+0116', 'U+0118', 'U+011a' ],
    [ 'U+0101', 'U+0103', 'U+0105', 'U+0107', 'U+0109', 'U+010b', 'U+010d',
      'U+010f', 'U+0111', 'U+0113', 'U+0115', 'U+0117', 'U+0119', 'U+011b' ],
]);
# G-K with diacriticals
$pickerset->add_subset(utf8_chr("\u011C"), [
    [ 'U+011c', 'U+011e', 'U+0120', 'U+0122', 'U+0124', 'U+0126', 'U+0128',
      'U+012a', 'U+012c', 'U+012e', 'U+0130', 'U+0132', 'U+0134', 'U+0136' ],
    [ 'U+011d', 'U+011f', 'U+0121', 'U+0123', 'U+0125', 'U+0127', 'U+0129',
      'U+012b', 'U+012d', 'U+012f', 'U+0131', 'U+0133', 'U+0135', 'U+0137', 'U+0138' ],
]);
# L-O with diacriticals
$pickerset->add_subset(utf8_chr("\u0139"), [
    [ 'U+0139', 'U+013b', 'U+013d', 'U+013f', 'U+0141', 'U+0143', 'U+0145',
      'U+0147', 'U+0149', 'U+014a', 'U+014c', 'U+014e', 'U+0150', 'U+0152'  ],
    [ 'U+013a', 'U+013c', 'U+013e', 'U+0140', 'U+0142', 'U+0144', 'U+0146',
      'U+0148', 'U+014a', 'U+014b', 'U+014d', 'U+014f', 'U+0151', 'U+0153' ],
]);
# R-T with diacriticals
$pickerset->add_subset(utf8_chr("\u0154"), [
    [ 'U+0154', 'U+0156', 'U+0158', 'U+015a', 'U+015c', 'U+015e', 'U+0160',
      'U+0162', 'U+0164', 'U+0166' ],
    [ 'U+0155', 'U+0157', 'U+0159', 'U+015b', 'U+015d', 'U+015f', 'U+0161',
      'U+0163', 'U+0165', 'U+0167' ],
]);
# U-Z with diacriticals
$pickerset->add_subset(utf8_chr("\u0168"), [
    [ 'U+0168', 'U+016a', 'U+016c', 'U+016e', 'U+0170', 'U+0172', 'U+0174',
      'U+0176', 'U+0178', 'U+0179', 'U+017b', 'U+017d' ],
    [ 'U+0169', 'U+016b', 'U+016d', 'U+016f', 'U+0171', 'U+0173', 'U+0175',
      'U+0177', 'U+0179', 'U+017a', 'U+017c', 'U+017e' ],
]);
$charsuite->pickerset = $pickerset;

CharSuites::add($charsuite);

