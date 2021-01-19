<?php
include_once($relPath."CharSuites.inc");

use voku\helper\UTF8;

$charsuite = new CharSuite("semitic-and-indic", _("Semitic and Indic transcriptions"));
$charsuite->description = _("Characters used in Romanized forms of languages such as Arabic, Hebrew, and Sanskrit.");
$charsuite->codepoints = [
    # https://www.pgdp.net/wiki/Semitic_and_Indic_transcriptions
    'U+0100-U+0101',
    'U+0112-U+0113',
    'U+012a-U+012b',
    'U+014c-U+014d',
    'U+015a-U+015b',
    'U+0160-U+0161',
    'U+016a-U+016b',
    'U+02be-U+02bf',
    'U+1e0c-U+1e0d',
    'U+1e24-U+1e25',
    'U+1e2a-U+1e2b',
    'U+1e32-U+1e33',
    'U+1e37',
    'U+1e39',
    'U+1e40-U+1e47',
    'U+1e5a-U+1e5d',
    'U+1e62-U+1e63',
    'U+1e6c-U+1e6d',
    'U+1e92-U+1e96',
    'U+0053>U+0324',
    'U+0054>U+0324',
    'U+005a>U+0324',
    'U+0073>U+0324',
    'U+0074>U+0324',
    'U+007a>U+0324',
];

$charsuite->reference_urls = [
    "https://www.pgdp.net/wiki/Semitic_and_Indic_transcriptions",
];

$pickerset = new PickerSet();

$pickerset->add_subset(UTF8::hex_to_chr("U+1e0c"), [
    [ 'U+1e0c', 'U+1e24', 'U+1e32', NULL, NULL, 'U+1e42', 'U+1e40',
      'U+1e46', 'U+1e44', 'U+1e5a', 'U+1e5c', 'U+1e62', 'U+1e6c', 'U+1e92' ],
    [ 'U+1e0d', 'U+1e25', 'U+1e33', 'U+1e37', 'U+1e39', 'U+1e43', 'U+1e41',
      'U+1e47', 'U+1e45', 'U+1e5b', 'U+1e5d', 'U+1e63', 'U+1e6d', 'U+1e93' ],
], _("Letters with dot"));

$pickerset->add_subset(UTF8::hex_to_chr("U+0100"), [
    [ 'U+0100', 'U+0112', 'U+012a', 'U+014c', 'U+016a', NULL,
      'U+1e2a', 'U+015a', 'U+0160', 'U+0053>U+0324', 'U+0054>U+0324',
      'U+005a>U+0324', 'U+1e94', 'U+02bf' ],
    [ 'U+0101', 'U+0113', 'U+012b', 'U+014d', 'U+016b', 'U+1e96',
      'U+1e2b', 'U+015b', 'U+0161', 'U+0073>U+0324', 'U+0074>U+0324',
      'U+007a>U+0324', 'U+1e95', 'U+02be' ],
], _("Vowels with macron and miscellaneous"));

$charsuite->pickerset = $pickerset;

CharSuites::add($charsuite);

