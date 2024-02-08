<?php

// This file 'exports' 3 functions:
//     find_bad_byte_sequences_in_text()
//     get_remarks_for_bad_byte_sequence()
//     string_to_hex()
// Other globally-declared identifiers are prefixed with an underscore.

use voku\helper\UTF8;

$_character_data = [

    // basic latin
    0x0000 => ['name' => "null", 'cc' => 1],
    0x0001 => ['name' => "start of heading", 'cc' => 1],
    0x0002 => ['name' => "start of text", 'cc' => 1],
    0x0003 => ['name' => "end of text", 'cc' => 1],
    0x0004 => ['name' => "end of transmission", 'cc' => 1],
    0x0005 => ['name' => "enquiry", 'cc' => 1],
    0x0006 => ['name' => "acknowledge", 'cc' => 1],
    0x0007 => ['name' => "bell", 'cc' => 1],
    0x0008 => ['name' => "backspace", 'cc' => 1],
    0x0009 => ['name' => "character tabulation [tab]", 'cc' => 1],
    0x000a => ['name' => "line feed"],
    0x000b => ['name' => "line tabulation", 'cc' => 1],
    0x000c => ['name' => "form feed", 'cc' => 1],
    0x000d => ['name' => "carriage return"],
    0x000e => ['name' => "shift out", 'cc' => 1],
    0x000f => ['name' => "shift in", 'cc' => 1],
    0x0010 => ['name' => "data link escape", 'cc' => 1],
    0x0011 => ['name' => "device control one", 'cc' => 1],
    0x0012 => ['name' => "device control two", 'cc' => 1],
    0x0013 => ['name' => "device control three", 'cc' => 1],
    0x0014 => ['name' => "device control four", 'cc' => 1],
    0x0015 => ['name' => "negative acknowledge", 'cc' => 1],
    0x0016 => ['name' => "synchronous idle", 'cc' => 1],
    0x0017 => ['name' => "end of transmission block", 'cc' => 1],
    0x0018 => ['name' => "cancel", 'cc' => 1],
    0x0019 => ['name' => "end of medium", 'cc' => 1],
    0x001a => ['name' => "substitute", 'cc' => 1],
    0x001b => ['name' => "escape", 'cc' => 1],
    0x001c => ['name' => "information separator four", 'cc' => 1],
    0x001d => ['name' => "information separator three", 'cc' => 1],
    0x001e => ['name' => "information separator two", 'cc' => 1],
    0x001f => ['name' => "information separator one", 'cc' => 1],

    0x0026 => ['name' => "ampersand", 'e' => "amp"],
    0x003e => ['name' => "greater-than sign", 'e' => "gt"],
    0x003c => ['name' => "less-than sign", 'e' => "lt"],
    0x007f => ['name' => "delete", 'cc' => 1],

    // latin-1 supplement
    0x0080 => ['name' => "<control>", 'cc' => 1],
    0x0081 => ['name' => "<control>", 'cc' => 1],
    0x0082 => ['name' => "break permitted here", 'cc' => 1],
    0x0083 => ['name' => "no break here", 'cc' => 1],
    0x0084 => ['name' => "<control>", 'cc' => 1],
    0x0085 => ['name' => "next line", 'cc' => 1],
    0x0086 => ['name' => "start of selected area", 'cc' => 1],
    0x0087 => ['name' => "end of selected area", 'cc' => 1],
    0x0088 => ['name' => "character tabulation set", 'cc' => 1],
    0x0089 => ['name' => "character tabulation with justification", 'cc' => 1],
    0x008a => ['name' => "line tabulation set", 'cc' => 1],
    0x008b => ['name' => "partial line forward", 'cc' => 1],
    0x008c => ['name' => "partial line backward", 'cc' => 1],
    0x008d => ['name' => "reverse line feed", 'cc' => 1],
    0x008e => ['name' => "single shift two", 'cc' => 1],
    0x008f => ['name' => "single shift three", 'cc' => 1],
    0x0090 => ['name' => "device control string", 'cc' => 1],
    0x0091 => ['name' => "private use one", 'cc' => 1],
    0x0092 => ['name' => "private use two", 'cc' => 1],
    0x0093 => ['name' => "set transmit state", 'cc' => 1],
    0x0094 => ['name' => "cancel character", 'cc' => 1],
    0x0095 => ['name' => "message waiting", 'cc' => 1],
    0x0096 => ['name' => "start of guarded area", 'cc' => 1],
    0x0097 => ['name' => "end of guarded area", 'cc' => 1],
    0x0098 => ['name' => "start of string", 'cc' => 1],
    0x0099 => ['name' => "<control>", 'cc' => 1],
    0x009a => ['name' => "single character introducer", 'cc' => 1],
    0x009b => ['name' => "control sequence introducer", 'cc' => 1],
    0x009c => ['name' => "string terminator", 'cc' => 1],
    0x009d => ['name' => "operating system command", 'cc' => 1],
    0x009e => ['name' => "privacy message", 'cc' => 1],
    0x009f => ['name' => "application program command", 'cc' => 1],
    0x00a0 => ['name' => "no-break space", 'cc' => 1, 'e' => "nbsp"],
    0x00a1 => ['name' => "inverted exclamation mark"],
    0x00a2 => ['name' => "cent sign", 'e' => "cent"],
    0x00a3 => ['name' => "pound sign", 'utf8x2' => 1],
    0x00a4 => ['name' => "currency sign"],
    0x00a5 => ['name' => "yen sign"],
    0x00a6 => ['name' => "broken bar"],
    0x00a7 => ['name' => "section sign"],
    0x00a8 => ['name' => "diaeresis"],
    0x00a9 => ['name' => "copyright sign", 'utf8x2' => 1],
    0x00aa => ['name' => "feminine ordinal indicator"],
    0x00ab => ['name' => "left-pointing double angle quotation mark", 'utf8x2' => 1],
    0x00ac => ['name' => "not sign"],
    0x00ad => ['name' => "soft hyphen", 'cc' => 1],
    0x00ae => ['name' => "registered sign", 'utf8x2' => 1],
    0x00af => ['name' => "macron"],
    0x00b0 => ['name' => "degree sign", 'utf8x2' => 1],
    0x00b1 => ['name' => "plus-minus sign"],
    0x00b2 => ['name' => "superscript two"],
    0x00b3 => ['name' => "superscript three"],
    0x00b4 => ['name' => "acute accent"],
    0x00b5 => ['name' => "micro sign"],
    0x00b6 => ['name' => "pilcrow sign"],
    0x00b7 => ['name' => "middle dot"],
    0x00b8 => ['name' => "cedilla"],
    0x00b9 => ['name' => "superscript one"],
    0x00ba => ['name' => "masculine ordinal indicator"],
    0x00bb => ['name' => "right-pointing double angle quotation mark", 'utf8x2' => 1],
    0x00bc => ['name' => "vulgar fraction one quarter"],
    0x00bd => ['name' => "vulgar fraction one half"],
    0x00be => ['name' => "vulgar fraction three quarters"],
    0x00bf => ['name' => "inverted question mark", 'e' => "iquest"],
    0x00c0 => ['name' => "latin capital letter a with grave"],
    0x00c1 => ['name' => "latin capital letter a with acute"],
    0x00c2 => ['name' => "latin capital letter a with circumflex", 'utf8x2' => 1],
    0x00c3 => ['name' => "latin capital letter a with tilde"],
    0x00c4 => ['name' => "latin capital letter a with diaeresis"],
    0x00c5 => ['name' => "latin capital letter a with ring above"],
    0x00c6 => ['name' => "latin capital ligature ae"],
    0x00c7 => ['name' => "latin capital letter c with cedilla"],
    0x00c8 => ['name' => "latin capital letter e with grave"],
    0x00c9 => ['name' => "latin capital letter e with acute"],
    0x00ca => ['name' => "latin capital letter e with circumflex"],
    0x00cb => ['name' => "latin capital letter e with diaeresis"],
    0x00cc => ['name' => "latin capital letter i with grave"],
    0x00cd => ['name' => "latin capital letter i with acute"],
    0x00ce => ['name' => "latin capital letter i with circumflex"],
    0x00cf => ['name' => "latin capital letter i with diaeresis"],
    0x00d0 => ['name' => "latin capital letter eth"],
    0x00d1 => ['name' => "latin capital letter n with tilde"],
    0x00d2 => ['name' => "latin capital letter o with grave"],
    0x00d3 => ['name' => "latin capital letter o with acute"],
    0x00d4 => ['name' => "latin capital letter o with circumflex"],
    0x00d5 => ['name' => "latin capital letter o with tilde"],
    0x00d6 => ['name' => "latin capital letter o with diaeresis"],
    0x00d7 => ['name' => "multiplication sign"],
    0x00d8 => ['name' => "latin capital letter o with stroke"],
    0x00d9 => ['name' => "latin capital letter u with grave"],
    0x00da => ['name' => "latin capital letter u with acute"],
    0x00db => ['name' => "latin capital letter u with circumflex"],
    0x00dc => ['name' => "latin capital letter u with diaeresis"],
    0x00dd => ['name' => "latin capital letter y with acute"],
    0x00de => ['name' => "latin capital letter thorn"],
    0x00df => ['name' => "latin small letter sharp s"],
    0x00e0 => ['name' => "latin small letter a with grave"],
    0x00e1 => ['name' => "latin small letter a with acute"],
    0x00e2 => ['name' => "latin small letter a with circumflex"],
    0x00e3 => ['name' => "latin small letter a with tilde"],
    0x00e4 => ['name' => "latin small letter a with diaeresis"],
    0x00e5 => ['name' => "latin small letter a with ring above"],
    0x00e6 => ['name' => "latin small ligature ae"],
    0x00e7 => ['name' => "latin small letter c with cedilla"],
    0x00e8 => ['name' => "latin small letter e with grave"],
    0x00e9 => ['name' => "latin small letter e with acute"],
    0x00ea => ['name' => "latin small letter e with circumflex"],
    0x00eb => ['name' => "latin small letter e with diaeresis"],
    0x00ec => ['name' => "latin small letter i with grave"],
    0x00ed => ['name' => "latin small letter i with acute"],
    0x00ee => ['name' => "latin small letter i with circumflex", 'e' => "icirc"],
    0x00ef => ['name' => "latin small letter i with diaeresis"],
    0x00f0 => ['name' => "latin small letter eth"],
    0x00f1 => ['name' => "latin small letter n with tilde"],
    0x00f2 => ['name' => "latin small letter o with grave"],
    0x00f3 => ['name' => "latin small letter o with acute"],
    0x00f4 => ['name' => "latin small letter o with circumflex"],
    0x00f5 => ['name' => "latin small letter o with tilde"],
    0x00f6 => ['name' => "latin small letter o with diaeresis"],
    0x00f7 => ['name' => "division sign"],
    0x00f8 => ['name' => "latin small letter o with stroke", 'e' => "oslash"],
    0x00f9 => ['name' => "latin small letter u with grave"],
    0x00fa => ['name' => "latin small letter u with acute"],
    0x00fb => ['name' => "latin small letter u with circumflex"],
    0x00fc => ['name' => "latin small letter u with diaeresis"],
    0x00fd => ['name' => "latin small letter y with acute"],
    0x00fe => ['name' => "latin small letter thorn"],
    0x00ff => ['name' => "latin small letter y with diaeresis"],


    // latin extended-a
    0x0100 => ['name' => "latin capital letter a with macron"],
    0x0101 => ['name' => "latin small letter a with macron"],
    0x0102 => ['name' => "latin capital letter a with breve"],
    0x0103 => ['name' => "latin small letter a with breve"],
    0x0104 => ['name' => "latin capital letter a with ogonek"],
    0x0105 => ['name' => "latin small letter a with ogonek"],
    0x0106 => ['name' => "latin capital letter c with acute"],
    0x0107 => ['name' => "latin small letter c with acute"],
    0x0108 => ['name' => "latin capital letter c with circumflex"],
    0x0109 => ['name' => "latin small letter c with circumflex"],
    0x010a => ['name' => "latin capital letter c with dot above"],
    0x010b => ['name' => "latin small letter c with dot above"],
    0x010c => ['name' => "latin capital letter c with caron"],
    0x010d => ['name' => "latin small letter c with caron"],
    0x010e => ['name' => "latin capital letter d with caron"],
    0x010f => ['name' => "latin small letter d with caron"],
    0x0110 => ['name' => "latin capital letter d with stroke"],
    0x0111 => ['name' => "latin small letter d with stroke"],
    0x0112 => ['name' => "latin capital letter e with macron"],
    0x0113 => ['name' => "latin small letter e with macron"],
    0x0114 => ['name' => "latin capital letter e with breve"],
    0x0115 => ['name' => "latin small letter e with breve"],
    0x0116 => ['name' => "latin capital letter e with dot above"],
    0x0117 => ['name' => "latin small letter e with dot above"],
    0x0118 => ['name' => "latin capital letter e with ogonek"],
    0x0119 => ['name' => "latin small letter e with ogonek"],
    0x011a => ['name' => "latin capital letter e with caron"],
    0x011b => ['name' => "latin small letter e with caron"],
    0x011c => ['name' => "latin capital letter g with circumflex"],
    0x011d => ['name' => "latin small letter g with circumflex"],
    0x011e => ['name' => "latin capital letter g with breve"],
    0x011f => ['name' => "latin small letter g with breve"],
    0x0120 => ['name' => "latin capital letter g with dot above"],
    0x0121 => ['name' => "latin small letter g with dot above"],
    0x0122 => ['name' => "latin capital letter g with cedilla"],
    0x0123 => ['name' => "latin small letter g with cedilla"],
    0x0124 => ['name' => "latin capital letter h with circumflex"],
    0x0125 => ['name' => "latin small letter h with circumflex"],
    0x0126 => ['name' => "latin capital letter h with stroke"],
    0x0127 => ['name' => "latin small letter h with stroke"],
    0x0128 => ['name' => "latin capital letter i with tilde"],
    0x0129 => ['name' => "latin small letter i with tilde"],
    0x012a => ['name' => "latin capital letter i with macron"],
    0x012b => ['name' => "latin small letter i with macron"],
    0x012c => ['name' => "latin capital letter i with breve"],
    0x012d => ['name' => "latin small letter i with breve"],
    0x012e => ['name' => "latin capital letter i with ogonek"],
    0x012f => ['name' => "latin small letter i with ogonek"],
    0x0130 => ['name' => "latin capital letter i with dot above"],
    0x0131 => ['name' => "latin small letter dotless i"],
    0x0132 => ['name' => "latin capital ligature ij"],
    0x0133 => ['name' => "latin small ligature ij"],
    0x0134 => ['name' => "latin capital letter j with circumflex"],
    0x0135 => ['name' => "latin small letter j with circumflex"],
    0x0136 => ['name' => "latin capital letter k with cedilla"],
    0x0137 => ['name' => "latin small letter k with cedilla"],
    0x0138 => ['name' => "latin small letter kra"],
    0x0139 => ['name' => "latin capital letter l with acute"],
    0x013a => ['name' => "latin small letter l with acute"],
    0x013b => ['name' => "latin capital letter l with cedilla"],
    0x013c => ['name' => "latin small letter l with cedilla"],
    0x013d => ['name' => "latin capital letter l with caron"],
    0x013e => ['name' => "latin small letter l with caron"],
    0x013f => ['name' => "latin capital letter l with middle dot"],
    0x0140 => ['name' => "latin small letter l with middle dot"],
    0x0141 => ['name' => "latin capital letter l with stroke"],
    0x0142 => ['name' => "latin small letter l with stroke"],
    0x0143 => ['name' => "latin capital letter n with acute"],
    0x0144 => ['name' => "latin small letter n with acute"],
    0x0145 => ['name' => "latin capital letter n with cedilla"],
    0x0146 => ['name' => "latin small letter n with cedilla"],
    0x0147 => ['name' => "latin capital letter n with caron"],
    0x0148 => ['name' => "latin small letter n with caron"],
    0x0149 => ['name' => "latin small letter n preceded by apostrophe"],
    0x014a => ['name' => "latin capital letter eng"],
    0x014b => ['name' => "latin small letter eng"],
    0x014c => ['name' => "latin capital letter o with macron"],
    0x014d => ['name' => "latin small letter o with macron"],
    0x014e => ['name' => "latin capital letter o with breve"],
    0x014f => ['name' => "latin small letter o with breve"],
    0x0150 => ['name' => "latin capital letter o with double acute"],
    0x0151 => ['name' => "latin small letter o with double acute"],
    0x0152 => ['name' => "latin capital ligature oe",                  'w' => 0x8c],
    0x0153 => ['name' => "latin small ligature oe",                    'w' => 0x9c],
    0x0154 => ['name' => "latin capital letter r with acute"],
    0x0155 => ['name' => "latin small letter r with acute"],
    0x0156 => ['name' => "latin capital letter r with cedilla"],
    0x0157 => ['name' => "latin small letter r with cedilla"],
    0x0158 => ['name' => "latin capital letter r with caron"],
    0x0159 => ['name' => "latin small letter r with caron"],
    0x015a => ['name' => "latin capital letter s with acute"],
    0x015b => ['name' => "latin small letter s with acute"],
    0x015c => ['name' => "latin capital letter s with circumflex"],
    0x015d => ['name' => "latin small letter s with circumflex"],
    0x015e => ['name' => "latin capital letter s with cedilla"],
    0x015f => ['name' => "latin small letter s with cedilla"],
    0x0160 => ['name' => "latin capital letter s with caron",          'w' => 0x8a],
    0x0161 => ['name' => "latin small letter s with caron",            'w' => 0x9a],
    0x0162 => ['name' => "latin capital letter t with cedilla"],
    0x0163 => ['name' => "latin small letter t with cedilla"],
    0x0164 => ['name' => "latin capital letter t with caron"],
    0x0165 => ['name' => "latin small letter t with caron"],
    0x0166 => ['name' => "latin capital letter t with stroke"],
    0x0167 => ['name' => "latin small letter t with stroke"],
    0x0168 => ['name' => "latin capital letter u with tilde"],
    0x0169 => ['name' => "latin small letter u with tilde"],
    0x016a => ['name' => "latin capital letter u with macron"],
    0x016b => ['name' => "latin small letter u with macron"],
    0x016c => ['name' => "latin capital letter u with breve"],
    0x016d => ['name' => "latin small letter u with breve"],
    0x016e => ['name' => "latin capital letter u with ring above"],
    0x016f => ['name' => "latin small letter u with ring above"],
    0x0170 => ['name' => "latin capital letter u with double acute"],
    0x0171 => ['name' => "latin small letter u with double acute"],
    0x0172 => ['name' => "latin capital letter u with ogonek"],
    0x0173 => ['name' => "latin small letter u with ogonek"],
    0x0174 => ['name' => "latin capital letter w with circumflex"],
    0x0175 => ['name' => "latin small letter w with circumflex"],
    0x0176 => ['name' => "latin capital letter y with circumflex"],
    0x0177 => ['name' => "latin small letter y with circumflex"],
    0x0178 => ['name' => "latin capital letter y with diaeresis",      'w' => 0x9f],
    0x0179 => ['name' => "latin capital letter z with acute"],
    0x017a => ['name' => "latin small letter z with acute"],
    0x017b => ['name' => "latin capital letter z with dot above"],
    0x017c => ['name' => "latin small letter z with dot above"],
    0x017d => ['name' => "latin capital letter z with caron",          'w' => 0x8e],
    0x017e => ['name' => "latin small letter z with caron",            'w' => 0x9e],
    0x017f => ['name' => "latin small letter long s"],

    // latin extended-b
    0x0180 => ['name' => "latin small letter b with stroke"],
    0x0186 => ['name' => "latin capital letter open o"],
    0x0192 => ['name' => "latin small letter f with hook",             'w' => 0x83],
    0x019a => ['name' => "latin small letter l with bar"],
    0x01b7 => ['name' => "latin capital letter ezh"],
    0x01dd => ['name' => "latin small letter turned e"],
    0x01eb => ['name' => "latin small letter o with ogonek"],
    0x0217 => ['name' => "latin small letter u with inverted breve"],
    0x0223 => ['name' => "latin small letter ou"],

    // ipa extensions block
    0x0259 => ['name' => "latin small letter schwa"],
    0x028a => ['name' => "latin small letter upsilon"],
    0x0299 => ['name' => "latin letter small capital b"],

    // spacing modifier letters
    0x02c6 => ['name' => "modifier letter circumflex accent",          'w' => 0x88],
    0x02c7 => ['name' => "caron"],
    0x02d8 => ['name' => "breve"],
    0x02d9 => ['name' => "dot above"],
    0x02da => ['name' => "ring above"],
    0x02dc => ['name' => "small tilde",                                'w' => 0x98],
    0x02dd => ['name' => "double acute accent"],

    // combining diacritical marks
    0x0300 => ['name' => "combining grave accent"],
    0x0301 => ['name' => "combining acute accent"],
    0x0368 => ['name' => "combining latin small letter c"],

    // greek and coptic
    0x0386 => ['name' => "greek capital letter alpha with tonos"],
    0x0387 => ['name' => "greek ano teleia"],
    0x0391 => ['name' => "greek capital letter alpha"],
    0x0392 => ['name' => "greek capital letter beta"],
    0x0393 => ['name' => "greek capital letter gamma"],
    0x0394 => ['name' => "greek capital letter delta"],
    0x0395 => ['name' => "greek capital letter epsilon"],
    0x0396 => ['name' => "greek capital letter zeta"],
    0x0397 => ['name' => "greek capital letter eta"],
    0x0398 => ['name' => "greek capital letter theta"],
    0x0399 => ['name' => "greek capital letter iota"],
    0x039a => ['name' => "greek capital letter kappa"],
    0x039b => ['name' => "greek capital letter lamda"],
    0x039c => ['name' => "greek capital letter mu"],
    0x039d => ['name' => "greek capital letter nu"],
    0x039e => ['name' => "greek capital letter xi"],
    0x039f => ['name' => "greek capital letter omicron"],
    0x03a0 => ['name' => "greek capital letter pi"],
    0x03a1 => ['name' => "greek capital letter rho"],
    0x03a3 => ['name' => "greek capital letter sigma"],
    0x03a4 => ['name' => "greek capital letter tau"],
    0x03a5 => ['name' => "greek capital letter upsilon"],
    0x03a6 => ['name' => "greek capital letter phi"],
    0x03a7 => ['name' => "greek capital letter chi"],
    0x03a8 => ['name' => "greek capital letter psi"],
    0x03a9 => ['name' => "greek capital letter omega"],

    0x03ac => ['name' => "greek small letter alpha with tonos"],
    0x03ad => ['name' => "greek small letter epsilon with tonos"],
    0x03ae => ['name' => "greek small letter eta with tonos"],
    0x03af => ['name' => "greek small letter iota with tonos"],
    0x03b0 => ['name' => "greek small letter upsilon with dialytika and tonos"],

    0x03b1 => ['name' => "greek small letter alpha", 'e' => "alpha"],
    0x03b2 => ['name' => "greek small letter beta", 'e' => "beta"],
    0x03b3 => ['name' => "greek small letter gamma", 'e' => "gamma"],
    0x03b4 => ['name' => "greek small letter delta", 'e' => "delta"],
    0x03b5 => ['name' => "greek small letter epsilon", 'e' => "epsilon"],
    0x03b6 => ['name' => "greek small letter zeta"],
    0x03b7 => ['name' => "greek small letter eta"],
    0x03b8 => ['name' => "greek small letter theta"],
    0x03b9 => ['name' => "greek small letter iota"],
    0x03ba => ['name' => "greek small letter kappa", 'e' => "kappa"],
    0x03bb => ['name' => "greek small letter lamda", 'e' => "lambda"],
    0x03bc => ['name' => "greek small letter mu"],
    0x03bd => ['name' => "greek small letter nu"],
    0x03be => ['name' => "greek small letter xi"],
    0x03bf => ['name' => "greek small letter omicron", 'e' => "omicron"],
    0x03c0 => ['name' => "greek small letter pi"],
    0x03c1 => ['name' => "greek small letter rho"],
    0x03c2 => ['name' => "greek small letter final sigma"],
    0x03c3 => ['name' => "greek small letter sigma"],
    0x03c4 => ['name' => "greek small letter tau"],
    0x03c5 => ['name' => "greek small letter upsilon"],
    0x03c6 => ['name' => "greek small letter phi"],
    0x03c7 => ['name' => "greek small letter chi"],
    0x03c8 => ['name' => "greek small letter psi"],
    0x03c9 => ['name' => "greek small letter omega"],

    0x03cc => ['name' => "greek small letter omicron with tonos"],
    0x03cd => ['name' => "greek small letter upsilon with tonos"],
    0x03ce => ['name' => "greek small letter omega with tonos"],

    // cyrillic
    0x0416 => ['name' => "cyrillic capital letter zhe"],
    0x0427 => ['name' => "cyrillic capital letter che"],
    0x042d => ['name' => "cyrillic capital letter e"],
    0x0432 => ['name' => "cyrillic small letter ve"],
    0x0434 => ['name' => "cyrillic small letter de"],
    0x0435 => ['name' => "cyrillic small letter ie"],
    0x0436 => ['name' => "cyrillic small letter zhe"],
    0x0437 => ['name' => "cyrillic small letter ze"],
    0x0439 => ['name' => "cyrillic small letter short i"],
    0x043b => ['name' => "cyrillic small letter el"],
    0x043d => ['name' => "cyrillic small letter en"],
    0x043e => ['name' => "cyrillic small letter o"],
    0x043f => ['name' => "cyrillic small letter pe"],
    0x0440 => ['name' => "cyrillic small letter er"],
    0x0441 => ['name' => "cyrillic small letter es"],
    0x0442 => ['name' => "cyrillic small letter te"],
    0x0443 => ['name' => "cyrillic small letter u"],
    0x0445 => ['name' => "cyrillic small letter ha"],
    0x0447 => ['name' => "cyrillic small letter che"],
    0x044f => ['name' => "cyrillic small letter ya"],

    // hebrew
    0x05b0 => ['name' => "hebrew point sheva"],
    0x05b1 => ['name' => "hebrew point hataf segol"],
    0x05b2 => ['name' => "hebrew point hataf patah"],
    0x05b3 => ['name' => "hebrew point hataf qamats"],
    0x05b4 => ['name' => "hebrew point hiriq"],
    0x05b5 => ['name' => "hebrew point tsere"],
    0x05b6 => ['name' => "hebrew point segol"],
    0x05b7 => ['name' => "hebrew point patah"],
    0x05b8 => ['name' => "hebrew point qamats"],
    0x05b9 => ['name' => "hebrew point holam"],
    0x05ba => ['name' => "hebrew point holam haser for vav"],
    0x05bb => ['name' => "hebrew point qubuts"],
    0x05bc => ['name' => "hebrew point dagesh or mapiq"],
    0x05bd => ['name' => "hebrew point meteg"],
    0x05c1 => ['name' => "hebrew point shin dot"],
    0x05c2 => ['name' => "hebrew point sin dot"],
    0x05d0 => ['name' => "hebrew letter alef"],
    0x05d1 => ['name' => "hebrew letter bet"],
    0x05d2 => ['name' => "hebrew letter gimel"],
    0x05d3 => ['name' => "hebrew letter dalet"],
    0x05d4 => ['name' => "hebrew letter he"],
    0x05d5 => ['name' => "hebrew letter vav"],
    0x05d6 => ['name' => "hebrew letter zayin"],
    0x05d7 => ['name' => "hebrew letter het"],
    0x05d8 => ['name' => "hebrew letter tet"],
    0x05d9 => ['name' => "hebrew letter yod"],
    0x05da => ['name' => "hebrew letter final kaf"],
    0x05db => ['name' => "hebrew letter kaf"],
    0x05dc => ['name' => "hebrew letter lamed"],
    0x05dd => ['name' => "hebrew letter final mem"],
    0x05de => ['name' => "hebrew letter mem"],
    0x05df => ['name' => "hebrew letter final nun"],
    0x05e0 => ['name' => "hebrew letter nun"],
    0x05e1 => ['name' => "hebrew letter samekh"],
    0x05e2 => ['name' => "hebrew letter ayin"],
    0x05e3 => ['name' => "hebrew letter final pe"],
    0x05e4 => ['name' => "hebrew letter pe"],
    0x05e5 => ['name' => "hebrew letter final tsadi"],
    0x05e6 => ['name' => "hebrew letter tsadi"],
    0x05e7 => ['name' => "hebrew letter qof"],
    0x05e8 => ['name' => "hebrew letter resh"],
    0x05e9 => ['name' => "hebrew letter shin"],
    0x05ea => ['name' => "hebrew letter tav"],

    // phonetic extensions
    0x1d04 => ['name' => "latin letter small capital c"],

    // latin extended additional
    0x1ebd => ['name' => "latin small letter e with tilde"],

    // greek extended
    0x1f00 => ['name' => "greek small letter alpha with psili"],
    0x1f01 => ['name' => "greek small letter alpha with dasia"],
    0x1f02 => ['name' => "greek small letter alpha with psili and varia"],
    0x1f04 => ['name' => "greek small letter alpha with psili and oxia"],
    0x1f05 => ['name' => "greek small letter alpha with dasia and oxia"],
    0x1f06 => ['name' => "greek small letter alpha with psili and perispomeni"],
    0x1f07 => ['name' => "greek small letter alpha with dasia and perispomeni"],
    0x1f08 => ['name' => "greek capital letter alpha with psili"],
    0x1f10 => ['name' => "greek small letter epsilon with psili"],
    0x1f14 => ['name' => "greek small letter epsilon with psili and oxia"],
    0x1f22 => ['name' => "greek small letter eta with psili and varia"],
    0x1f31 => ['name' => "greek small letter iota with dasia"],
    0x1f38 => ['name' => "greek capital letter iota with psili"],
    0x1f41 => ['name' => "greek small letter omicron with dasia"],
    0x1f45 => ['name' => "greek small letter omicron with dasia and oxia"],
    0x1f61 => ['name' => "greek small letter omega with dasia"],
    0x1f70 => ['name' => "greek small letter alpha with varia"],
    0x1f71 => ['name' => "greek small letter alpha with oxia"],
    0x1f73 => ['name' => "greek small letter epsilon with oxia"],
    0x1f75 => ['name' => "greek small letter eta with oxia"],
    0x1f76 => ['name' => "greek small letter iota with varia"],
    0x1f77 => ['name' => "greek small letter iota with oxia"],
    0x1f78 => ['name' => "greek small letter omicron with varia"],
    0x1f79 => ['name' => "greek small letter omicron with oxia"],
    0x1f7a => ['name' => "greek small letter upsilon with varia"],
    0x1fb6 => ['name' => "greek small letter alpha with perispomeni"],
    0x1fd6 => ['name' => "greek small letter iota with perispomeni"],
    0x1fe6 => ['name' => "greek small letter upsilon with perispomeni"],
    0x1ff3 => ['name' => "greek small letter omega with ypogegrammeni"],

    // general punctuation
    0x2013 => ['name' => "en dash",                                    'w' => 0x96],
    0x2014 => ['name' => "em dash",                                    'w' => 0x97, 'utf8x2' => 1],
    0x2018 => ['name' => "left single quotation mark",                 'w' => 0x91],
    0x2019 => ['name' => "right single quotation mark",                'w' => 0x92],
    0x201a => ['name' => "single low-9 quotation mark",                'w' => 0x82],
    0x201c => ['name' => "left double quotation mark",                 'w' => 0x93],
    0x201d => ['name' => "right double quotation mark",                'w' => 0x94],
    0x201e => ['name' => "double low-9 quotation mark",                'w' => 0x84, 'utf8x2' => 1],
    0x2020 => ['name' => "dagger",                                     'w' => 0x86, 'e' => "dagger"],
    0x2021 => ['name' => "double dagger",                              'w' => 0x87],
    0x2022 => ['name' => "bullet",                                     'w' => 0x95, 'utf8x2' => 1],
    0x2026 => ['name' => "horizontal ellipsis",                        'w' => 0x85],
    0x2027 => ['name' => "hyphenation point"],
    0x2030 => ['name' => "per mille sign",                             'w' => 0x89],
    0x2033 => ['name' => "double prime"],
    0x2039 => ['name' => "single left-pointing angle quotation mark",  'w' => 0x8b, 'e' => "lsaquo"],
    0x203a => ['name' => "single right-pointing angle quotation mark", 'w' => 0x9b, 'e' => "rsaquo"],
    0x206a => ['name' => "inhibit symmetric swapping"],

    // superscripts and subscripts
    0x2074 => ['name' => "superscript four"],
    0x2075 => ['name' => "superscript five"],
    0x2076 => ['name' => "superscript six"],
    0x2077 => ['name' => "superscript seven"],
    0x2078 => ['name' => "superscript eight"],
    0x2079 => ['name' => "superscript nine"],

    // currency symbols
    0x20ac => ['name' => "euro sign",                                  'w' => 0x80],

    // letterlike symbols
    0x211e => ['name' => "prescription take"],
    0x2122 => ['name' => "trade mark sign",                            'w' => 0x99],
    0x2147 => ['name' => "double-struck italic small e",               'e' => 'ee'],

    // number forms
    0x215b => ['name' => "vulgar fraction one eighth"],
    0x2160 => ['name' => "roman numeral one"],
    0x216e => ['name' => "roman numeral five hundred"],

    // arrows
    0x2190 => ['name' => "leftwards arrow"],
    0x2192 => ['name' => "rightwards arrow"],

    // mathematical operators
    0x2202 => ['name' => "partial differential"],
    0x220d => ['name' => "small contains as member"],
    0x220f => ['name' => "n-ary product"],
    0x221a => ['name' => "square root"],
    0x221e => ['name' => "infinity"],
    0x222a => ['name' => "union"],
    0x2260 => ['name' => "not equal to"],
    0x2264 => ['name' => "less-than or equal to"],
    0x2265 => ['name' => "greater-than or equal to"],
    0x2299 => ['name' => "circled dot operator"],
    0x22bd => ['name' => "nor"],

    // geometic shapes
    0x25a0 => ['name' => "black square"],
    0x25ba => ['name' => "black right-pointing pointer"],
    0x25cf => ['name' => "black circle"],

    // miscellaneous symbols
    0x261e => ['name' => "white right pointing index"],
    0x2640 => ['name' => "female sign"],
    0x2642 => ['name' => "male sign"],
    0x2649 => ['name' => "taurus"],
    0x264d => ['name' => "virgo"],
    0x2666 => ['name' => "black diamond suit"],

    // dingbats
    0x2720 => ['name' => "maltese cross"],

    // cjk symbols and punctuation
    0x3000 => ['name' => "ideographic space"],
    0x300b => ['name' => "right double angle bracket"],

    // hiragana
    0x3072 => ['name' => "hiragana letter hi"],

    // cjk unified ideographs
    0x4e00 => ['name' => "<cjk ideograph, first>"],
    0x4e0d => ['name' => "no, not; un-; negative prefix"],
    0x4e94 => ['name' => "five; surname"],
    0x4eba => ['name' => "man; people; mankind; someone else"],
    0x4f5c => ['name' => "make; work; compose, write; act, perform"],
    0x50d5 => ['name' => "slave, servant, i"],
    0x5149 => ['name' => "light, brilliant, shine; only"],
    0x516c => ['name' => "fair, equitable; public; duke"],
    0x5185 => ['name' => "inside"],
    0x5207 => ['name' => "cut, mince, slice, carve"],
    0x5236 => ['name' => "system; establish; overpower"],
    0x5916 => ['name' => "out, outside, external; foreign"],
    0x5bfa => ['name' => "court, office; temple, monastery"],
    0x5c0a => ['name' => "respect, revere, venerate; honor"],
    0x5d47 => ['name' => "mountain in henan; surname"],
    0x6728 => ['name' => "tree; wood, lumber; wooden"],
    0x677e => ['name' => "pine tree; fir tree"],
    0x6a29 => ['name' => "power, right, authority"],
    0x6c5f => ['name' => "large river; yangzi; surname"],
    0x6ec4 => ['name' => "blue, dark green; cold"],
    0x703e => ['name' => "overflowing; waves, ripples"],
    0x767b => ['name' => "rise, mount, board, climb"],
    0x80fd => ['name' => "to be able; can, permitted to; ability"],
    0x8457 => ['name' => "manifest; (cant.) to wear"],
    0x88fd => ['name' => "make, manufacture; produce"],
    0x8907 => ['name' => "repeat, double, overlap"],
    0x8a31 => ['name' => "allow, permit; promise; betroth"],
    0x8acb => ['name' => "ask, request; invite; please"],
    0x9332 => ['name' => "copy"],
    0x9593 => ['name' => "interval, space; place, between"],

    // alphabetic presentation forms
    0xfb2a => ['name' => "hebrew letter shin with shin dot"],
    0xfb35 => ['name' => "hebrew letter vav with dagesh"],

    // arabic presentation forms-b
    0xfeff => ['name' => "zero width no-break space [bom]"],

    // halfwidth and fullwidth forms
    0xff03 => ['name' => "fullwidth number sign"],
    0xff3b => ['name' => "fullwidth left square bracket"],
    0xff3d => ['name' => "fullwidth right square bracket"],

    // specials
    0xfffd => ['name' => "replacement character"],
];

function define_bad_byte_sequences()
{
    global $_bad_byte_sequences, $_character_data;

    $_bad_byte_sequences = [];

    foreach ($_character_data as $code_point => $info) {
        $char_name = $info['name'];

        @$entity_name = $info['e'];
        if ($entity_name) {
            $entity_ref = "&$entity_name;";
            _install_bad_sequence($entity_ref, $code_point, "HTML character entity reference");
        }

        // multibyte UTF-8 encoding
        @$windows_1252_byte = $info['w'];
        if ($windows_1252_byte) {
            // double-UTF8-encoding
            if (@$info['utf8x2'] == 1) {
                $utf8_enc = _utf8_encode_code_point($code_point);
                $double_enc = _utf8_encode_string($utf8_enc);
                _install_bad_sequence($double_enc, $code_point, "double-UTF8-encoded");

                $midways = [
                    "\x94" => "\x22", // Windows-1252-encoded right double quotation mark => quotation mark
                    "\x9e" => "\x7a", // Windows-1252-encoded latin small letter z with caron => latin small letter z
                ];
                foreach ($midways as $before => $after) {
                    if (strpos($utf8_enc, $before)) {
                        $description_m = sprintf(
                            "double-UTF8-encoded with midway conversion of x%02x to x%02x",
                            ord($before),
                            ord($after)
                        );
                        $utf8_enc_m = str_replace($before, $after, $utf8_enc);
                        $double_enc_m = _utf8_encode_string($utf8_enc_m);
                        _install_bad_sequence($double_enc_m, $code_point, $description_m);
                    }
                }
            }
        }
    }

    ksort($_bad_byte_sequences);

    if (0) {
        foreach ($_bad_byte_sequences as $raw => $remarks) {
            [$code_point, $why_bad] = $remarks;
            echo sprintf(
                "%s: U+%04x %s\n",
                string_to_hex($raw),
                $code_point,
                $why_bad
            );
        }
    }
}

// =============================================================================

/**
 * Interpret each byte in the string as a code point.
 *
 * For example, interpreting it as encoded in ISO-8859-1?
 */
function _utf8_encode_string($seq)
{
    $result = "";
    foreach (str_split($seq) as $byte) {
        $result .= _utf8_encode_code_point(ord($byte));
    }
    return $result;
}

function _utf8_encode_code_point($cp)
{
    if ($cp <= 0x007f) {
        // utf8 encodes as 1 byte
        return chr($cp);
    } elseif ($cp <= 0x07ff) {
        // utf8 encodes as 2 bytes
        $byte1 = 0xC0 | (($cp & 0x7C0) >> 6);
        $byte2 = 0x80 | (($cp & 0x03F) >> 0);

        $sa = chr($byte1) . chr($byte2);
        $sb = sprintf("%c%c", $byte1, $byte2);
        assert($sa == $sb);
        return $sa;
    } elseif ($cp <= 0xffff) {
        // utf8 encodes as 3 bytes
        $byte1 = 0xE0 | (($cp & 0xF000) >> 12);
        $byte2 = 0x80 | (($cp & 0x0FC0) >> 6);
        $byte3 = 0x80 | (($cp & 0x003F) >> 0);

        $sa = chr($byte1) . chr($byte2) . chr($byte3);
        $sb = sprintf("%c%c%c", $byte1, $byte2, $byte3);
        assert($sa == $sb);
        return $sa;
    } else {
        // utf8 encodes as 4 or more bytes
        throw new UnexpectedValueException("Unexpected codepoint $cp");
    }
}

function _install_bad_sequence($bytes, $code_point, $why_bad)
{
    global $_anchored_bad_bytes_regex, $_bad_byte_sequences;

    // echo $why_bad, "\n";

    if (!preg_match($_anchored_bad_bytes_regex, $bytes)) {
        $hex = string_to_hex($bytes);
        die("$hex ($code_point $why_bad) is not covered by \$_bad_bytes_pattern\n");
    }

    $_bad_byte_sequences[$bytes] = [$code_point, $why_bad];
}

// This just has to match a superset of the bad sequences.
// On the other hand, we don't want *too* many false positives.
$_bad_bytes_pattern = '
    & [a-z]+ ;             # html entities
    |
    & \# ( [0-9]+ | x [0-9A-Fa-f]+ ) ;  # numeric character references
    |
    \xc3\xa2 \xc2\x80 (\x7a|\x22|\xc2[\x94\x9e\xa2]) # 6-byte double-UTF8-encodings
    |
    \xc3\x82 \xc2[\xa3\xa9\xab\xae\xb0\xbb] # 4-byte double-UTF8-encoding
    |
    \xc3\x83 \xc2\x82 # ditto
';

$_anchored_bad_bytes_regex = "/^($_bad_bytes_pattern)$/x";
$_unanchored_bad_bytes_regex = "/$_bad_bytes_pattern/x";

define_bad_byte_sequences();

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

define("BOM", "\u{feff}");

// note that this can change $text
function find_bad_byte_sequences_in_text(&$text)
{
    global $_unanchored_bad_bytes_regex, $_bad_byte_sequences;

    $occurrences = [];

    if (UTF8::string_has_bom($text)) {
        $occurrences[BOM] = 1;
        //remove it so not reported again as an invalid character
        $text = UTF8::str_replace(UTF8::bom(), '', $text);
    }

    $n_matches = preg_match_all($_unanchored_bad_bytes_regex, $text, $matches);
    if ($n_matches === false) {
        echo "preg_match_all returned FALSE!\n";
        return $occurrences;
    }

    if ($n_matches == 0) {
        // no matches
        // i.e. page text doesn't have any bad byte-sequences, yay!
        return $occurrences;
    }

    foreach ($matches[0] as $match) {
        // $match contains a match for the bad_bytes *pattern*,
        // but that's just a first cut.
        // Check if it actually is a bad byte-sequence.

        // Note that the bad bytes pattern
        // matches numeric character references,
        // but $_bad_byte_sequences doesn't have any entries for them,
        // because there's too much variability to make exact matches.
        //     E.g., the greater-than sign (U+003E) could be represented by:
        //     &#x3e; &#x03e &#x003e ...
        //     &#x3E; &#x03E &#x003E ...
        //     &#62;  &#062; &#0062  ...
        // Thus, we handle such cases differently.

        if (startswith($match, '&#')) {
            @$occurrences[$match] += 1;
            // So if a page has (say) both &#62; and &#x3e;,
            // those will show up separately in $occurrences.
            // But that seems unlikely, and not a big deal if it does happen.
        } elseif (array_key_exists($match, $_bad_byte_sequences)) {
            // Yes it is.
            @$occurrences[$match] += 1;
        } elseif (startswith($match, '&')) {
            // It satisfies the syntax of an HTML character entity reference,
            // but there's no entry for it in $_bad_byte_sequences.
            // So we assume it's meant literally.
            // (E.g., &c; is meant to represent the 3 characters
            // ampersand, 'c', semicolon.)
            // So we don't count it as bad.
        } else {
            // $match isn't a bad-bytes-sequence.
            // However, if it's a multi-byte sequence,
            // then (some of) its constituent bytes might be bad.

            foreach (str_split($match) as $byte) {
                if (array_key_exists($byte, $_bad_byte_sequences)) {
                    @$occurrences[$byte] += 1;
                }
            }
        }
    }

    return $occurrences;
}

function get_remarks_for_bad_byte_sequence($raw)
{
    global $_bad_byte_sequences, $_character_data;

    if (BOM === $raw) {
        return ["", "Byte Order Mark"];
    }
    if (startswith($raw, '&#')) {
        // This is a numeric character reference.

        if (startswith($raw, '&#x')) {
            $hex = substr($raw, 3, -1);
            $code_point = intval($hex, 16);
        } else {
            $dec = substr($raw, 2, -1);
            $code_point = intval($dec, 10);
        }

        $why_bad = "HTML numeric character reference";
    } else {
        [$code_point, $why_bad] = $_bad_byte_sequences[$raw];
    }

    $char_name = $_character_data[$code_point]['name'] ?? '???';

    $intended_character = sprintf("U+%04x %s", $code_point, $char_name);

    return [$intended_character, $why_bad];
}

function string_to_hex($str)
{
    $hex = "";
    foreach (str_split($str) as $byte) {
        if ($hex != "") {
            $hex .= " ";
        }
        $hex .= sprintf("%02x", ord($byte));
    }
    return $hex;
}
