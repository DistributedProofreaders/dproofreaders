<?php
include_once($relPath."Glyphsets.inc");

$glyphset = new Glyphset("basic-latin", _("Basic Latin"));
$glyphset->codepoints = [
    # https://en.wikipedia.org/wiki/Basic_Latin_(Unicode_block)
    'U+0020-U+007E',
    # https://en.wikipedia.org/wiki/Latin-1_Supplement_(Unicode_block)
    # without the soft hyphen
    'U+00A1-U+00AC',
    'U+00AE-U+00FF',
    # codepoints that map to Windows-1252
    'U+0152', # OE ligature
    'U+0153', # oe ligature
    'U+0160', # [vS]
    'U+0161', # [vs]
    'U+017D', # [vZ]
    'U+017E', # [vz]
    'U+0178', # [:Y]
    'U+2039', # open single guillemet
    'U+203A', # close single guillemet
];
$glyphset->reference_urls = [
    "https://en.wikipedia.org/wiki/Basic_Latin_(Unicode_block)",
    "https://en.wikipedia.org/wiki/Latin-1_Supplement_(Unicode_block)",
];

$pickerset = new PickerSet();
$pickerset->add_subset("A-Z", [
    [ 'U+0041-U+005a' ], # A-Z
    [ 'U+0061-U+007a' ], # a-z
]);
$pickerset->add_subset(utf8_chr("U+00c0"), [
    [ 'U+00c0-U+00c7', 'U+00d0', 'U+00c8-U+00cf', 'U+00d1' ],
    [ 'U+00e0-U+00e7', 'U+00f0', 'U+00e8-U+00ef', 'U+00f1' ],
]);
$pickerset->add_subset(utf8_chr("U+00d2"), [
    [ 'U+00d2-U+00d6', 'U+00d8', 'U+0152', 'U+0160', NULL, 'U+00d9-U+00dd', 'U+00de-U+00de', 'U+0178', 'U+017D' ],
    [ 'U+00f2-U+00f6', 'U+00f8', 'U+0153', 'U+0161', 'U+00df', 'U+00f9-U+00fd', 'U+00fe-U+00ff', 'U+017E' ],
]);
$pickerset->add_subset("!", [
    [ 'U+0021', 'U+003f', 'U+002e', 'U+002c', 'U+0026', 'U+0022', 'U+0028-U+0029', 'U+005b', 'U+005d', 'U+003c', 'U+003e', 'U+005c', 'U+002a-U+002b', 'U+003d' ],
    [ 'U+00a1', 'U+00bf', 'U+003a', 'U+003b', 'U+0040', 'U+0027', 'U+00ab', 'U+00bb', 'U+2039', 'U+203A', 'U+007b', 'U+007d', 'U+002f', 'U+005e', 'U+002d' ],
], _("Punctuation"));
$pickerset->add_subset(utf8_chr("U+00b6"), [
    [ 'U+00b6', 'U+00b7', 'U+00b0', 'U+007c', 'U+00a6', 'U+00b4', 'U+00a2-U+00a5' ],
    [ 'U+00a7', 'U+007e', 'U+00b5', 'U+00a9', 'U+00ae', 'U+0060', 'U+005f', 'U+00af', 'U+00a8', 'U+00b8', ],
], _("Symbols"));
$pickerset->add_subset("1", [
    [ 'U+0030-U+0039', 'U+0023', 'U+0025', 'U+0024' ],
    [ 'U+00b9', 'U+00b2-U+00b3', 'U+00ba', 'U+00aa', 'U+00bc-U+00be', 'U+002b', 'U+002d', 'U+003d', 'U+00d7', 'U+00f7', 'U+00b1', 'U+00ac' ],
], _("Numbers and Currency"));
$glyphset->pickerset = $pickerset;

Glyphsets::add($glyphset);

