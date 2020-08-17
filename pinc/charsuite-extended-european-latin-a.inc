<?php
include_once($relPath."CharSuites.inc");

$charsuite = new CharSuite("extended-european-latin-a", _("Extended European Latin A"));
$charsuite->description = _("Supplementary characters for Esperanto, Welsh, Sami, Romanian and Hungarian");
$charsuite->codepoints = [
    # https://en.wikipedia.org/wiki/Latin_Extended-A
    'U+0102-U+0103',
    'U+0108-U+0109',
    'U+011c-U+011d',
    'U+014a-U+014b',
    'U+015c-U+015d',
    'U+016c-U+016d',
    'U+0124-U+0125',
    'U+0134-U+0135',
    'U+0150-U+0151',
    'U+0166-U+0167',
    'U+0170-U+0171',
    'U+0174-U+0177',
    'U+0218-U+021b',
];
$charsuite->reference_urls = [
    'https://en.wikipedia.org/wiki/Latin_Extended-A',
    'https://en.wikipedia.org/wiki/Latin_Extended-B',
];

$pickerset = new PickerSet();
#  with diacriticals
$pickerset->add_subset(utf8_chr("U+0108"), [
    [ 'U+0108', 'U+011c', 'U+0124', 'U+0134', 'U+015c', 'U+0174', 'U+0176', 'U+014a',
      'U+0166', 'U+0218', 'U+021a', 'U+0102', 'U+016c', 'U+0150', 'U+0170' ],
    [ 'U+0109', 'U+011d', 'U+0125', 'U+0135', 'U+015d', 'U+0175', 'U+0177', 'U+014b',
      'U+0167', 'U+0219', 'U+021b', 'U+0103', 'U+016d', 'U+0151', 'U+0171' ],
], _("Letters with circumflex and miscellaneous"));


$charsuite->pickerset = $pickerset;

CharSuites::add($charsuite);

