<?php
include_once($relPath."CharSuites.inc");

$charsuite = new CharSuite("extended-european-latin", _("Extended European Latin"));
$charsuite->codepoints = [
    # https://en.wikipedia.org/wiki/Latin_Extended-A
    'U+0100-U+011d',
    'U+0120-U+0127',
    'U+012a-U+012f',
    'U+0134-U+0137',
    'U+0139-U+013e',
    'U+0141-U+0148',
    'U+014a-U+0151',
    'U+0154-U+015d',
    'U+0160-U+0161',
    'U+0164-U+0167',
    'U+016a-U+0177',
    'U+0179-U+017e',
];
$charsuite->reference_urls = [
    'https://en.wikipedia.org/wiki/Latin_Extended-A',
];

$pickerset = new PickerSet();
#  with diacriticals
$pickerset->add_subset(utf8_chr("U+0100"), [
    [ 'U+0100', 'U+0112', 'U+012a', 'U+014c', 'U+016a', 'U+0102', 'U+0114',
      'U+012c', 'U+014e', 'U+016c' ],
    [ 'U+0101', 'U+0113', 'U+012b', 'U+014d', 'U+016b', 'U+0103', 'U+0115',
      'U+012d', 'U+014f', 'U+016d' ],
], _("Letters with macron or breve"));
#  with diacriticals
$pickerset->add_subset(utf8_chr("U+0104"), [
    [ 'U+0104', 'U+0118', 'U+012e', 'U+0172', 'U+0106', 'U+0139', 'U+0143',
      'U+0154', 'U+015a', 'U+0179', 'U+010a', 'U+0116', 'U+0120', 'U+017b' ],
    [ 'U+0105', 'U+0119', 'U+012f', 'U+0173', 'U+0107', 'U+013a', 'U+0144',
      'U+0155', 'U+015b', 'U+017a', 'U+010b', 'U+0117', 'U+0121', 'U+017c' ],
], _("Letters with ogonek, acute or dot"));
#  with diacriticals
$pickerset->add_subset(utf8_chr("U+010c"), [
    [ 'U+010c', 'U+010e', 'U+011a', 'U+013d', 'U+0147', 'U+0158', 'U+0160',
      'U+0164', 'U+017d', 'U+016e', 'U+0122', 'U+0136', 'U+013b', 'U+0145', 'U+0156' ],
    [ 'U+010d', 'U+010f', 'U+011b', 'U+013e', 'U+0148', 'U+0159', 'U+0161',
      'U+0165', 'U+017e', 'U+016f', 'U+0123', 'U+0137', 'U+013c', 'U+0146', 'U+0157' ],
], _("Letters with caron, ring or cedilla"));
#  with diacriticals
$pickerset->add_subset(utf8_chr("U+0108"), [
    [ 'U+0108', 'U+011c', 'U+0124', 'U+0134', 'U+015c', 'U+0174', 'U+0176',
      'U+0110', 'U+0126', 'U+0141', 'U+0166', 'U+014a', 'U+0150', 'U+0170' ],
    [ 'U+0109', 'U+011d', 'U+0125', 'U+0135', 'U+015d', 'U+0175', 'U+0177',
      'U+0111', 'U+0127', 'U+0142', 'U+0167', 'U+014b', 'U+0151', 'U+0171' ],
], _("Letters with circumflex or stroke, and miscellaneous"));


$charsuite->pickerset = $pickerset;

CharSuites::add($charsuite);

