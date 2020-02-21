<?php

// This file 'exports' 3 functions:
//     find_bad_byte_sequences_in_text()
//     get_remarks_for_bad_byte_sequence()
//     string_to_hex()
// Other globally-declared identifiers are prefixed with an underscore.

include_once($relPath.'misc.inc'); // startswith

error_reporting(-1);

$_character_data = array(

// basic latin
0x0000 => array('name' => "null"                                   , 'cc'=>1),
0x0001 => array('name' => "start of heading"                       , 'cc'=>1),
0x0002 => array('name' => "start of text"                          , 'cc'=>1),
0x0003 => array('name' => "end of text"                            , 'cc'=>1),
0x0004 => array('name' => "end of transmission"                    , 'cc'=>1),
0x0005 => array('name' => "enquiry"                                , 'cc'=>1),
0x0006 => array('name' => "acknowledge"                            , 'cc'=>1),
0x0007 => array('name' => "bell"                                   , 'cc'=>1),
0x0008 => array('name' => "backspace"                              , 'cc'=>1),
0x0009 => array('name' => "character tabulation [tab]"             , 'cc'=>1),
0x000a => array('name' => "line feed"),
0x000b => array('name' => "line tabulation"                        , 'cc'=>1),
0x000c => array('name' => "form feed"                              , 'cc'=>1),
0x000d => array('name' => "carriage return"),
0x000e => array('name' => "shift out"                              , 'cc'=>1),
0x000f => array('name' => "shift in"                               , 'cc'=>1),
0x0010 => array('name' => "data link escape"                       , 'cc'=>1),
0x0011 => array('name' => "device control one"                     , 'cc'=>1),
0x0012 => array('name' => "device control two"                     , 'cc'=>1),
0x0013 => array('name' => "device control three"                   , 'cc'=>1),
0x0014 => array('name' => "device control four"                    , 'cc'=>1),
0x0015 => array('name' => "negative acknowledge"                   , 'cc'=>1),
0x0016 => array('name' => "synchronous idle"                       , 'cc'=>1),
0x0017 => array('name' => "end of transmission block"              , 'cc'=>1),
0x0018 => array('name' => "cancel"                                 , 'cc'=>1),
0x0019 => array('name' => "end of medium"                          , 'cc'=>1),
0x001a => array('name' => "substitute"                             , 'cc'=>1),
0x001b => array('name' => "escape"                                 , 'cc'=>1),
0x001c => array('name' => "information separator four"             , 'cc'=>1),
0x001d => array('name' => "information separator three"            , 'cc'=>1),
0x001e => array('name' => "information separator two"              , 'cc'=>1),
0x001f => array('name' => "information separator one"              , 'cc'=>1),

0x0026 => array('name' => "ampersand"                                        , 'e'=>"amp"),
0x003e => array('name' => "greater-than sign"                                , 'e'=>"gt"),
0x003c => array('name' => "less-than sign"                                   , 'e'=>"lt"),
0x007f => array('name' => "delete"                                 , 'cc'=>1),

// latin-1 supplement
0x0080 => array('name' => "<control>"                              , 'cc'=>1),
0x0081 => array('name' => "<control>"                              , 'cc'=>1),
0x0082 => array('name' => "break permitted here"                   , 'cc'=>1),
0x0083 => array('name' => "no break here"                          , 'cc'=>1),
0x0084 => array('name' => "<control>"                              , 'cc'=>1),
0x0085 => array('name' => "next line"                              , 'cc'=>1),
0x0086 => array('name' => "start of selected area"                 , 'cc'=>1),
0x0087 => array('name' => "end of selected area"                   , 'cc'=>1),
0x0088 => array('name' => "character tabulation set"               , 'cc'=>1),
0x0089 => array('name' => "character tabulation with justification", 'cc'=>1),
0x008a => array('name' => "line tabulation set"                    , 'cc'=>1),
0x008b => array('name' => "partial line forward"                   , 'cc'=>1),
0x008c => array('name' => "partial line backward"                  , 'cc'=>1),
0x008d => array('name' => "reverse line feed"                      , 'cc'=>1),
0x008e => array('name' => "single shift two"                       , 'cc'=>1),
0x008f => array('name' => "single shift three"                     , 'cc'=>1),
0x0090 => array('name' => "device control string"                  , 'cc'=>1),
0x0091 => array('name' => "private use one"                        , 'cc'=>1),
0x0092 => array('name' => "private use two"                        , 'cc'=>1),
0x0093 => array('name' => "set transmit state"                     , 'cc'=>1),
0x0094 => array('name' => "cancel character"                       , 'cc'=>1),
0x0095 => array('name' => "message waiting"                        , 'cc'=>1),
0x0096 => array('name' => "start of guarded area"                  , 'cc'=>1),
0x0097 => array('name' => "end of guarded area"                    , 'cc'=>1),
0x0098 => array('name' => "start of string"                        , 'cc'=>1),
0x0099 => array('name' => "<control>"                              , 'cc'=>1),
0x009a => array('name' => "single character introducer"            , 'cc'=>1),
0x009b => array('name' => "control sequence introducer"            , 'cc'=>1),
0x009c => array('name' => "string terminator"                      , 'cc'=>1),
0x009d => array('name' => "operating system command"               , 'cc'=>1),
0x009e => array('name' => "privacy message"                        , 'cc'=>1),
0x009f => array('name' => "application program command"            , 'cc'=>1),
0x00a0 => array('name' => "no-break space"                         , 'cc'=>1, 'e'=>"nbsp"),
0x00a1 => array('name' => "inverted exclamation mark"),
0x00a2 => array('name' => "cent sign"                                       , 'e'=>"cent"),
0x00a3 => array('name' => "pound sign"                                      , 'utf8x2'=>1),
0x00a4 => array('name' => "currency sign"),
0x00a5 => array('name' => "yen sign"),
0x00a6 => array('name' => "broken bar"),
0x00a7 => array('name' => "section sign"),
0x00a8 => array('name' => "diaeresis"),
0x00a9 => array('name' => "copyright sign"                                  , 'utf8x2'=>1),
0x00aa => array('name' => "feminine ordinal indicator"),
0x00ab => array('name' => "left-pointing double angle quotation mark"       , 'utf8x2'=>1),
0x00ac => array('name' => "not sign"),
0x00ad => array('name' => "soft hyphen"                            , 'cc'=>1),
0x00ae => array('name' => "registered sign"                                 , 'utf8x2'=>1),
0x00af => array('name' => "macron"),
0x00b0 => array('name' => "degree sign"                                     , 'utf8x2'=>1),
0x00b1 => array('name' => "plus-minus sign"),
0x00b2 => array('name' => "superscript two"),
0x00b3 => array('name' => "superscript three"),
0x00b4 => array('name' => "acute accent"),
0x00b5 => array('name' => "micro sign"),
0x00b6 => array('name' => "pilcrow sign"),
0x00b7 => array('name' => "middle dot"),
0x00b8 => array('name' => "cedilla"),
0x00b9 => array('name' => "superscript one"),
0x00ba => array('name' => "masculine ordinal indicator"),
0x00bb => array('name' => "right-pointing double angle quotation mark"      , 'utf8x2'=>1),
0x00bc => array('name' => "vulgar fraction one quarter"),
0x00bd => array('name' => "vulgar fraction one half"),
0x00be => array('name' => "vulgar fraction three quarters"),
0x00bf => array('name' => "inverted question mark"                          , 'e'=>"iquest"),
0x00c0 => array('name' => "latin capital letter a with grave"),
0x00c1 => array('name' => "latin capital letter a with acute"),
0x00c2 => array('name' => "latin capital letter a with circumflex"          , 'utf8x2'=>1),
0x00c3 => array('name' => "latin capital letter a with tilde"),
0x00c4 => array('name' => "latin capital letter a with diaeresis"),
0x00c5 => array('name' => "latin capital letter a with ring above"),
0x00c6 => array('name' => "latin capital ligature ae"),
0x00c7 => array('name' => "latin capital letter c with cedilla"),
0x00c8 => array('name' => "latin capital letter e with grave"),
0x00c9 => array('name' => "latin capital letter e with acute"),
0x00ca => array('name' => "latin capital letter e with circumflex"),
0x00cb => array('name' => "latin capital letter e with diaeresis"),
0x00cc => array('name' => "latin capital letter i with grave"),
0x00cd => array('name' => "latin capital letter i with acute"),
0x00ce => array('name' => "latin capital letter i with circumflex"),
0x00cf => array('name' => "latin capital letter i with diaeresis"),
0x00d0 => array('name' => "latin capital letter eth"),
0x00d1 => array('name' => "latin capital letter n with tilde"),
0x00d2 => array('name' => "latin capital letter o with grave"),
0x00d3 => array('name' => "latin capital letter o with acute"),
0x00d4 => array('name' => "latin capital letter o with circumflex"),
0x00d5 => array('name' => "latin capital letter o with tilde"),
0x00d6 => array('name' => "latin capital letter o with diaeresis"),
0x00d7 => array('name' => "multiplication sign"),
0x00d8 => array('name' => "latin capital letter o with stroke"),
0x00d9 => array('name' => "latin capital letter u with grave"),
0x00da => array('name' => "latin capital letter u with acute"),
0x00db => array('name' => "latin capital letter u with circumflex"),
0x00dc => array('name' => "latin capital letter u with diaeresis"),
0x00dd => array('name' => "latin capital letter y with acute"),
0x00de => array('name' => "latin capital letter thorn"),
0x00df => array('name' => "latin small letter sharp s"),
0x00e0 => array('name' => "latin small letter a with grave"),
0x00e1 => array('name' => "latin small letter a with acute"),
0x00e2 => array('name' => "latin small letter a with circumflex"),
0x00e3 => array('name' => "latin small letter a with tilde"),
0x00e4 => array('name' => "latin small letter a with diaeresis"),
0x00e5 => array('name' => "latin small letter a with ring above"),
0x00e6 => array('name' => "latin small ligature ae"),
0x00e7 => array('name' => "latin small letter c with cedilla"),
0x00e8 => array('name' => "latin small letter e with grave"),
0x00e9 => array('name' => "latin small letter e with acute"),
0x00ea => array('name' => "latin small letter e with circumflex"),
0x00eb => array('name' => "latin small letter e with diaeresis"),
0x00ec => array('name' => "latin small letter i with grave"),
0x00ed => array('name' => "latin small letter i with acute"),
0x00ee => array('name' => "latin small letter i with circumflex"            , 'e'=>"icirc"),
0x00ef => array('name' => "latin small letter i with diaeresis"),
0x00f0 => array('name' => "latin small letter eth"),
0x00f1 => array('name' => "latin small letter n with tilde"),
0x00f2 => array('name' => "latin small letter o with grave"),
0x00f3 => array('name' => "latin small letter o with acute"),
0x00f4 => array('name' => "latin small letter o with circumflex"),
0x00f5 => array('name' => "latin small letter o with tilde"),
0x00f6 => array('name' => "latin small letter o with diaeresis"),
0x00f7 => array('name' => "division sign"),
0x00f8 => array('name' => "latin small letter o with stroke"                , 'e'=>"oslash"),
0x00f9 => array('name' => "latin small letter u with grave"),
0x00fa => array('name' => "latin small letter u with acute"),
0x00fb => array('name' => "latin small letter u with circumflex"),
0x00fc => array('name' => "latin small letter u with diaeresis"),
0x00fd => array('name' => "latin small letter y with acute"),
0x00fe => array('name' => "latin small letter thorn"),
0x00ff => array('name' => "latin small letter y with diaeresis"),


// latin extended-a
0x0100 => array('name' => "latin capital letter a with macron"),
0x0101 => array('name' => "latin small letter a with macron"),
0x0102 => array('name' => "latin capital letter a with breve"),
0x0103 => array('name' => "latin small letter a with breve"),
0x0104 => array('name' => "latin capital letter a with ogonek"),
0x0105 => array('name' => "latin small letter a with ogonek"),
0x0106 => array('name' => "latin capital letter c with acute"),
0x0107 => array('name' => "latin small letter c with acute"),
0x0108 => array('name' => "latin capital letter c with circumflex"),
0x0109 => array('name' => "latin small letter c with circumflex"),
0x010a => array('name' => "latin capital letter c with dot above"),
0x010b => array('name' => "latin small letter c with dot above"),
0x010c => array('name' => "latin capital letter c with caron"),
0x010d => array('name' => "latin small letter c with caron"),
0x010e => array('name' => "latin capital letter d with caron"),
0x010f => array('name' => "latin small letter d with caron"),
0x0110 => array('name' => "latin capital letter d with stroke"),
0x0111 => array('name' => "latin small letter d with stroke"),
0x0112 => array('name' => "latin capital letter e with macron"),
0x0113 => array('name' => "latin small letter e with macron"),
0x0114 => array('name' => "latin capital letter e with breve"),
0x0115 => array('name' => "latin small letter e with breve"),
0x0116 => array('name' => "latin capital letter e with dot above"),
0x0117 => array('name' => "latin small letter e with dot above"),
0x0118 => array('name' => "latin capital letter e with ogonek"),
0x0119 => array('name' => "latin small letter e with ogonek"),
0x011a => array('name' => "latin capital letter e with caron"),
0x011b => array('name' => "latin small letter e with caron"),
0x011c => array('name' => "latin capital letter g with circumflex"),
0x011d => array('name' => "latin small letter g with circumflex"),
0x011e => array('name' => "latin capital letter g with breve"),
0x011f => array('name' => "latin small letter g with breve"),
0x0120 => array('name' => "latin capital letter g with dot above"),
0x0121 => array('name' => "latin small letter g with dot above"),
0x0122 => array('name' => "latin capital letter g with cedilla"),
0x0123 => array('name' => "latin small letter g with cedilla"),
0x0124 => array('name' => "latin capital letter h with circumflex"),
0x0125 => array('name' => "latin small letter h with circumflex"),
0x0126 => array('name' => "latin capital letter h with stroke"),
0x0127 => array('name' => "latin small letter h with stroke"),
0x0128 => array('name' => "latin capital letter i with tilde"),
0x0129 => array('name' => "latin small letter i with tilde"),
0x012a => array('name' => "latin capital letter i with macron"),
0x012b => array('name' => "latin small letter i with macron"),
0x012c => array('name' => "latin capital letter i with breve"),
0x012d => array('name' => "latin small letter i with breve"),
0x012e => array('name' => "latin capital letter i with ogonek"),
0x012f => array('name' => "latin small letter i with ogonek"),
0x0130 => array('name' => "latin capital letter i with dot above"),
0x0131 => array('name' => "latin small letter dotless i"),
0x0132 => array('name' => "latin capital ligature ij"),
0x0133 => array('name' => "latin small ligature ij"),
0x0134 => array('name' => "latin capital letter j with circumflex"),
0x0135 => array('name' => "latin small letter j with circumflex"),
0x0136 => array('name' => "latin capital letter k with cedilla"),
0x0137 => array('name' => "latin small letter k with cedilla"),
0x0138 => array('name' => "latin small letter kra"),
0x0139 => array('name' => "latin capital letter l with acute"),
0x013a => array('name' => "latin small letter l with acute"),
0x013b => array('name' => "latin capital letter l with cedilla"),
0x013c => array('name' => "latin small letter l with cedilla"),
0x013d => array('name' => "latin capital letter l with caron"),
0x013e => array('name' => "latin small letter l with caron"),
0x013f => array('name' => "latin capital letter l with middle dot"),
0x0140 => array('name' => "latin small letter l with middle dot"),
0x0141 => array('name' => "latin capital letter l with stroke"),
0x0142 => array('name' => "latin small letter l with stroke"),
0x0143 => array('name' => "latin capital letter n with acute"),
0x0144 => array('name' => "latin small letter n with acute"),
0x0145 => array('name' => "latin capital letter n with cedilla"),
0x0146 => array('name' => "latin small letter n with cedilla"),
0x0147 => array('name' => "latin capital letter n with caron"),
0x0148 => array('name' => "latin small letter n with caron"),
0x0149 => array('name' => "latin small letter n preceded by apostrophe"),
0x014a => array('name' => "latin capital letter eng"),
0x014b => array('name' => "latin small letter eng"),
0x014c => array('name' => "latin capital letter o with macron"),
0x014d => array('name' => "latin small letter o with macron"),
0x014e => array('name' => "latin capital letter o with breve"),
0x014f => array('name' => "latin small letter o with breve"),
0x0150 => array('name' => "latin capital letter o with double acute"),
0x0151 => array('name' => "latin small letter o with double acute"),
0x0152 => array('name' => "latin capital ligature oe",                  'w'=>0x8c),
0x0153 => array('name' => "latin small ligature oe",                    'w'=>0x9c),
0x0154 => array('name' => "latin capital letter r with acute"),
0x0155 => array('name' => "latin small letter r with acute"),
0x0156 => array('name' => "latin capital letter r with cedilla"),
0x0157 => array('name' => "latin small letter r with cedilla"),
0x0158 => array('name' => "latin capital letter r with caron"),
0x0159 => array('name' => "latin small letter r with caron"),
0x015a => array('name' => "latin capital letter s with acute"),
0x015b => array('name' => "latin small letter s with acute"),
0x015c => array('name' => "latin capital letter s with circumflex"),
0x015d => array('name' => "latin small letter s with circumflex"),
0x015e => array('name' => "latin capital letter s with cedilla"),
0x015f => array('name' => "latin small letter s with cedilla"),
0x0160 => array('name' => "latin capital letter s with caron",          'w'=>0x8a),
0x0161 => array('name' => "latin small letter s with caron",            'w'=>0x9a),
0x0162 => array('name' => "latin capital letter t with cedilla"),
0x0163 => array('name' => "latin small letter t with cedilla"),
0x0164 => array('name' => "latin capital letter t with caron"),
0x0165 => array('name' => "latin small letter t with caron"),
0x0166 => array('name' => "latin capital letter t with stroke"),
0x0167 => array('name' => "latin small letter t with stroke"),
0x0168 => array('name' => "latin capital letter u with tilde"),
0x0169 => array('name' => "latin small letter u with tilde"),
0x016a => array('name' => "latin capital letter u with macron"),
0x016b => array('name' => "latin small letter u with macron"),
0x016c => array('name' => "latin capital letter u with breve"),
0x016d => array('name' => "latin small letter u with breve"),
0x016e => array('name' => "latin capital letter u with ring above"),
0x016f => array('name' => "latin small letter u with ring above"),
0x0170 => array('name' => "latin capital letter u with double acute"),
0x0171 => array('name' => "latin small letter u with double acute"),
0x0172 => array('name' => "latin capital letter u with ogonek"),
0x0173 => array('name' => "latin small letter u with ogonek"),
0x0174 => array('name' => "latin capital letter w with circumflex"),
0x0175 => array('name' => "latin small letter w with circumflex"),
0x0176 => array('name' => "latin capital letter y with circumflex"),
0x0177 => array('name' => "latin small letter y with circumflex"),
0x0178 => array('name' => "latin capital letter y with diaeresis",      'w'=>0x9f),
0x0179 => array('name' => "latin capital letter z with acute"),
0x017a => array('name' => "latin small letter z with acute"),
0x017b => array('name' => "latin capital letter z with dot above"),
0x017c => array('name' => "latin small letter z with dot above"),
0x017d => array('name' => "latin capital letter z with caron",          'w'=>0x8e),
0x017e => array('name' => "latin small letter z with caron",            'w'=>0x9e),
0x017f => array('name' => "latin small letter long s"),

// latin extended-b
0x0180 => array('name' => "latin small letter b with stroke"),
0x0186 => array('name' => "latin capital letter open o"),
0x0192 => array('name' => "latin small letter f with hook",             'w'=>0x83),
0x019a => array('name' => "latin small letter l with bar"),
0x01b7 => array('name' => "latin capital letter ezh"),
0x01dd => array('name' => "latin small letter turned e"),
0x01eb => array('name' => "latin small letter o with ogonek"),
0x0217 => array('name' => "latin small letter u with inverted breve"),
0x0223 => array('name' => "latin small letter ou"),

// ipa extensions block
0x0259 => array('name' => "latin small letter schwa"),
0x028a => array('name' => "latin small letter upsilon"),
0x0299 => array('name' => "latin letter small capital b"),

// spacing modifier letters
0x02c6 => array('name' => "modifier letter circumflex accent",          'w'=>0x88),
0x02c7 => array('name' => "caron"),
0x02d8 => array('name' => "breve"),
0x02d9 => array('name' => "dot above"),
0x02da => array('name' => "ring above"),
0x02dc => array('name' => "small tilde",                                'w'=>0x98),
0x02dd => array('name' => "double acute accent"),

// combining diacritical marks
0x0300 => array('name' => "combining grave accent"),
0x0301 => array('name' => "combining acute accent"),
0x0368 => array('name' => "combining latin small letter c"),

// greek and coptic
0x0386 => array('name' => "greek capital letter alpha with tonos"),
0x0387 => array('name' => "greek ano teleia"),
0x0391 => array('name' => "greek capital letter alpha"),
0x0392 => array('name' => "greek capital letter beta"),
0x0393 => array('name' => "greek capital letter gamma"),
0x0394 => array('name' => "greek capital letter delta"),
0x0395 => array('name' => "greek capital letter epsilon"),
0x0396 => array('name' => "greek capital letter zeta"),
0x0397 => array('name' => "greek capital letter eta"),
0x0398 => array('name' => "greek capital letter theta"),
0x0399 => array('name' => "greek capital letter iota"),
0x039a => array('name' => "greek capital letter kappa"),
0x039b => array('name' => "greek capital letter lamda"),
0x039c => array('name' => "greek capital letter mu"),
0x039d => array('name' => "greek capital letter nu"),
0x039e => array('name' => "greek capital letter xi"),
0x039f => array('name' => "greek capital letter omicron"),
0x03a0 => array('name' => "greek capital letter pi"),
0x03a1 => array('name' => "greek capital letter rho"),
0x03a3 => array('name' => "greek capital letter sigma"),
0x03a4 => array('name' => "greek capital letter tau"),
0x03a5 => array('name' => "greek capital letter upsilon"),
0x03a6 => array('name' => "greek capital letter phi"),
0x03a7 => array('name' => "greek capital letter chi"),
0x03a8 => array('name' => "greek capital letter psi"),
0x03a9 => array('name' => "greek capital letter omega"),

0x03ac => array('name' => "greek small letter alpha with tonos"),
0x03ad => array('name' => "greek small letter epsilon with tonos"),
0x03ae => array('name' => "greek small letter eta with tonos"),
0x03af => array('name' => "greek small letter iota with tonos"),
0x03b0 => array('name' => "greek small letter upsilon with dialytika and tonos"),

0x03b1 => array('name' => "greek small letter alpha"     , 'e'=>"alpha"),
0x03b2 => array('name' => "greek small letter beta"      , 'e'=>"beta"),
0x03b3 => array('name' => "greek small letter gamma"     , 'e'=>"gamma"),
0x03b4 => array('name' => "greek small letter delta"     , 'e'=>"delta"),
0x03b5 => array('name' => "greek small letter epsilon"   , 'e'=>"epsilon"),
0x03b6 => array('name' => "greek small letter zeta"),
0x03b7 => array('name' => "greek small letter eta"),
0x03b8 => array('name' => "greek small letter theta"),
0x03b9 => array('name' => "greek small letter iota"),
0x03ba => array('name' => "greek small letter kappa"     , 'e'=>"kappa"),
0x03bb => array('name' => "greek small letter lamda"     , 'e'=>"lambda"),
0x03bc => array('name' => "greek small letter mu"),
0x03bd => array('name' => "greek small letter nu"),
0x03be => array('name' => "greek small letter xi"),
0x03bf => array('name' => "greek small letter omicron"   , 'e'=>"omicron"),
0x03c0 => array('name' => "greek small letter pi"),
0x03c1 => array('name' => "greek small letter rho"),
0x03c2 => array('name' => "greek small letter final sigma"),
0x03c3 => array('name' => "greek small letter sigma"),
0x03c4 => array('name' => "greek small letter tau"),
0x03c5 => array('name' => "greek small letter upsilon"),
0x03c6 => array('name' => "greek small letter phi"),
0x03c7 => array('name' => "greek small letter chi"),
0x03c8 => array('name' => "greek small letter psi"),
0x03c9 => array('name' => "greek small letter omega"),

0x03cc => array('name' => "greek small letter omicron with tonos"),
0x03cd => array('name' => "greek small letter upsilon with tonos"),
0x03ce => array('name' => "greek small letter omega with tonos"),

// cyrillic
0x0416 => array('name' => "cyrillic capital letter zhe"),
0x0427 => array('name' => "cyrillic capital letter che"),
0x042d => array('name' => "cyrillic capital letter e"),
0x0432 => array('name' => "cyrillic small letter ve"),
0x0434 => array('name' => "cyrillic small letter de"),
0x0435 => array('name' => "cyrillic small letter ie"),
0x0436 => array('name' => "cyrillic small letter zhe"),
0x0437 => array('name' => "cyrillic small letter ze"),
0x0439 => array('name' => "cyrillic small letter short i"),
0x043b => array('name' => "cyrillic small letter el"),
0x043d => array('name' => "cyrillic small letter en"),
0x043e => array('name' => "cyrillic small letter o"),
0x043f => array('name' => "cyrillic small letter pe"),
0x0440 => array('name' => "cyrillic small letter er"),
0x0441 => array('name' => "cyrillic small letter es"),
0x0442 => array('name' => "cyrillic small letter te"),
0x0443 => array('name' => "cyrillic small letter u"),
0x0445 => array('name' => "cyrillic small letter ha"),
0x0447 => array('name' => "cyrillic small letter che"),
0x044f => array('name' => "cyrillic small letter ya"),

// hebrew
0x05b0 => array('name' => "hebrew point sheva"),
0x05b1 => array('name' => "hebrew point hataf segol"),
0x05b2 => array('name' => "hebrew point hataf patah"),
0x05b3 => array('name' => "hebrew point hataf qamats"),
0x05b4 => array('name' => "hebrew point hiriq"),
0x05b5 => array('name' => "hebrew point tsere"),
0x05b6 => array('name' => "hebrew point segol"),
0x05b7 => array('name' => "hebrew point patah"),
0x05b8 => array('name' => "hebrew point qamats"),
0x05b9 => array('name' => "hebrew point holam"),
0x05ba => array('name' => "hebrew point holam haser for vav"),
0x05bb => array('name' => "hebrew point qubuts"),
0x05bc => array('name' => "hebrew point dagesh or mapiq"),
0x05bd => array('name' => "hebrew point meteg"),
0x05c1 => array('name' => "hebrew point shin dot"),
0x05c2 => array('name' => "hebrew point sin dot"),
0x05d0 => array('name' => "hebrew letter alef"),
0x05d1 => array('name' => "hebrew letter bet"),
0x05d2 => array('name' => "hebrew letter gimel"),
0x05d3 => array('name' => "hebrew letter dalet"),
0x05d4 => array('name' => "hebrew letter he"),
0x05d5 => array('name' => "hebrew letter vav"),
0x05d6 => array('name' => "hebrew letter zayin"),
0x05d7 => array('name' => "hebrew letter het"),
0x05d8 => array('name' => "hebrew letter tet"),
0x05d9 => array('name' => "hebrew letter yod"),
0x05da => array('name' => "hebrew letter final kaf"),
0x05db => array('name' => "hebrew letter kaf"),
0x05dc => array('name' => "hebrew letter lamed"),
0x05dd => array('name' => "hebrew letter final mem"),
0x05de => array('name' => "hebrew letter mem"),
0x05df => array('name' => "hebrew letter final nun"),
0x05e0 => array('name' => "hebrew letter nun"),
0x05e1 => array('name' => "hebrew letter samekh"),
0x05e2 => array('name' => "hebrew letter ayin"),
0x05e3 => array('name' => "hebrew letter final pe"),
0x05e4 => array('name' => "hebrew letter pe"),
0x05e5 => array('name' => "hebrew letter final tsadi"),
0x05e6 => array('name' => "hebrew letter tsadi"),
0x05e7 => array('name' => "hebrew letter qof"),
0x05e8 => array('name' => "hebrew letter resh"),
0x05e9 => array('name' => "hebrew letter shin"),
0x05ea => array('name' => "hebrew letter tav"),

// phonetic extensions
0x1d04 => array('name' => "latin letter small capital c"),

// latin extended additional
0x1ebd => array('name' => "latin small letter e with tilde"),

// greek extended
0x1f00 => array('name' => "greek small letter alpha with psili"),
0x1f01 => array('name' => "greek small letter alpha with dasia"),
0x1f02 => array('name' => "greek small letter alpha with psili and varia"),
0x1f04 => array('name' => "greek small letter alpha with psili and oxia"),
0x1f05 => array('name' => "greek small letter alpha with dasia and oxia"),
0x1f06 => array('name' => "greek small letter alpha with psili and perispomeni"),
0x1f07 => array('name' => "greek small letter alpha with dasia and perispomeni"),
0x1f08 => array('name' => "greek capital letter alpha with psili"),
0x1f10 => array('name' => "greek small letter epsilon with psili"),
0x1f14 => array('name' => "greek small letter epsilon with psili and oxia"),
0x1f22 => array('name' => "greek small letter eta with psili and varia"),
0x1f31 => array('name' => "greek small letter iota with dasia"),
0x1f38 => array('name' => "greek capital letter iota with psili"),
0x1f41 => array('name' => "greek small letter omicron with dasia"),
0x1f45 => array('name' => "greek small letter omicron with dasia and oxia"),
0x1f61 => array('name' => "greek small letter omega with dasia"),
0x1f70 => array('name' => "greek small letter alpha with varia"),
0x1f71 => array('name' => "greek small letter alpha with oxia"),
0x1f73 => array('name' => "greek small letter epsilon with oxia"),
0x1f75 => array('name' => "greek small letter eta with oxia"),
0x1f76 => array('name' => "greek small letter iota with varia"),
0x1f77 => array('name' => "greek small letter iota with oxia"),
0x1f78 => array('name' => "greek small letter omicron with varia"),
0x1f79 => array('name' => "greek small letter omicron with oxia"),
0x1f7a => array('name' => "greek small letter upsilon with varia"),
0x1fb6 => array('name' => "greek small letter alpha with perispomeni"),
0x1fd6 => array('name' => "greek small letter iota with perispomeni"),
0x1fe6 => array('name' => "greek small letter upsilon with perispomeni"),
0x1ff3 => array('name' => "greek small letter omega with ypogegrammeni"),

// general punctuation
0x2013 => array('name' => "en dash",                                    'w'=>0x96),
0x2014 => array('name' => "em dash",                                    'w'=>0x97, 'utf8x2'=>1),
0x2018 => array('name' => "left single quotation mark",                 'w'=>0x91),
0x2019 => array('name' => "right single quotation mark",                'w'=>0x92),
0x201a => array('name' => "single low-9 quotation mark",                'w'=>0x82),
0x201c => array('name' => "left double quotation mark",                 'w'=>0x93),
0x201d => array('name' => "right double quotation mark",                'w'=>0x94),
0x201e => array('name' => "double low-9 quotation mark",                'w'=>0x84, 'utf8x2'=>1),
0x2020 => array('name' => "dagger",                                     'w'=>0x86, 'e'=>"dagger"),
0x2021 => array('name' => "double dagger",                              'w'=>0x87),
0x2022 => array('name' => "bullet",                                     'w'=>0x95, 'utf8x2'=>1),
0x2026 => array('name' => "horizontal ellipsis",                        'w'=>0x85),
0x2027 => array('name' => "hyphenation point"),
0x2030 => array('name' => "per mille sign",                             'w'=>0x89),
0x2033 => array('name' => "double prime"),
0x2039 => array('name' => "single left-pointing angle quotation mark",  'w'=>0x8b, 'e'=>"lsaquo"),
0x203a => array('name' => "single right-pointing angle quotation mark", 'w'=>0x9b, 'e'=>"rsaquo"),
0x206a => array('name' => "inhibit symmetric swapping"),

// superscripts and subscripts
0x2074 => array('name' => "superscript four"),
0x2075 => array('name' => "superscript five"),
0x2076 => array('name' => "superscript six"),
0x2077 => array('name' => "superscript seven"),
0x2078 => array('name' => "superscript eight"),
0x2079 => array('name' => "superscript nine"),

// currency symbols
0x20ac => array('name' => "euro sign",                                  'w'=>0x80),

// letterlike symbols
0x211e => array('name' => "prescription take"),
0x2122 => array('name' => "trade mark sign",                            'w'=>0x99),
0x2147 => array('name' => "double-struck italic small e",               'e'=>'ee'),

// number forms
0x215b => array('name' => "vulgar fraction one eighth"),
0x2160 => array('name' => "roman numeral one"),
0x216e => array('name' => "roman numeral five hundred"),

// arrows
0x2190 => array('name' => "leftwards arrow"),
0x2192 => array('name' => "rightwards arrow"),

// mathematical operators
0x2202 => array('name' => "partial differential"),
0x220d => array('name' => "small contains as member"),
0x220f => array('name' => "n-ary product"),
0x221a => array('name' => "square root"),
0x221e => array('name' => "infinity"),
0x222a => array('name' => "union"),
0x2260 => array('name' => "not equal to"),
0x2264 => array('name' => "less-than or equal to"),
0x2265 => array('name' => "greater-than or equal to"),
0x2299 => array('name' => "circled dot operator"),
0x22bd => array('name' => "nor"),

// geometic shapes
0x25a0 => array('name' => "black square"),
0x25ba => array('name' => "black right-pointing pointer"),
0x25cf => array('name' => "black circle"),

// miscellaneous symbols
0x261e => array('name' => "white right pointing index"),
0x2640 => array('name' => "female sign"),
0x2642 => array('name' => "male sign"),
0x2649 => array('name' => "taurus"),
0x264d => array('name' => "virgo"),
0x2666 => array('name' => "black diamond suit"),

// dingbats
0x2720 => array('name' => "maltese cross"),

// cjk symbols and punctuation
0x3000 => array('name' => "ideographic space"),
0x300b => array('name' => "right double angle bracket"),

// hiragana
0x3072 => array('name' => "hiragana letter hi"),

// cjk unified ideographs
0x4e00 => array('name' => "<cjk ideograph, first>"),
0x4e0d => array('name' => "no, not; un-; negative prefix"),
0x4e94 => array('name' => "five; surname"),
0x4eba => array('name' => "man; people; mankind; someone else"),
0x4f5c => array('name' => "make; work; compose, write; act, perform"),
0x50d5 => array('name' => "slave, servant, i"),
0x5149 => array('name' => "light, brilliant, shine; only"),
0x516c => array('name' => "fair, equitable; public; duke"),
0x5185 => array('name' => "inside"),
0x5207 => array('name' => "cut, mince, slice, carve"),
0x5236 => array('name' => "system; establish; overpower"),
0x5916 => array('name' => "out, outside, external; foreign"),
0x5bfa => array('name' => "court, office; temple, monastery"),
0x5c0a => array('name' => "respect, revere, venerate; honor"),
0x5d47 => array('name' => "mountain in henan; surname"),
0x6728 => array('name' => "tree; wood, lumber; wooden"),
0x677e => array('name' => "pine tree; fir tree"),
0x6a29 => array('name' => "power, right, authority"),
0x6c5f => array('name' => "large river; yangzi; surname"),
0x6ec4 => array('name' => "blue, dark green; cold"),
0x703e => array('name' => "overflowing; waves, ripples"),
0x767b => array('name' => "rise, mount, board, climb"),
0x80fd => array('name' => "to be able; can, permitted to; ability"),
0x8457 => array('name' => "manifest; (cant.) to wear"),
0x88fd => array('name' => "make, manufacture; produce"),
0x8907 => array('name' => "repeat, double, overlap"),
0x8a31 => array('name' => "allow, permit; promise; betroth"),
0x8acb => array('name' => "ask, request; invite; please"),
0x9332 => array('name' => "copy"),
0x9593 => array('name' => "interval, space; place, between"),

// alphabetic presentation forms
0xfb2a => array('name' => "hebrew letter shin with shin dot"),
0xfb35 => array('name' => "hebrew letter vav with dagesh"),

// arabic presentation forms-b
0xfeff => array('name' => "zero width no-break space [bom]"),

// halfwidth and fullwidth forms
0xff03 => array('name' => "fullwidth number sign"),
0xff3b => array('name' => "fullwidth left square bracket"),
0xff3d => array('name' => "fullwidth right square bracket"),

// specials
0xfffd => array('name' => "replacement character"),
);

function define_bad_byte_sequences()
{
    global $_bad_byte_sequences, $_character_data;

    $_bad_byte_sequences = array();

    foreach( $_character_data as $code_point => $info )
    {
        $char_name = $info['name'];

        @$entity_name = $info['e'];
        if ($entity_name)
        {
            $entity_ref = "&$entity_name;";
            _install_bad_sequence( $entity_ref, $code_point, "HTML character entity reference" );
        }

        // multibyte UTF-8 encoding
        @$windows_1252_byte = $info['w'];
        if ($windows_1252_byte)
        {
            // double-UTF8-encoding
            if (@$info['utf8x2'] == 1)
            {
                $utf8_enc = _utf8_encode_code_point($code_point);
                $double_enc = _utf8_encode_string($utf8_enc);
                _install_bad_sequence( $double_enc, $code_point, "double-UTF8-encoded" );

                $midways = array(
                    "\x94" => "\x22", // Windows-1252-encoded right double quotation mark => quotation mark
                    "\x9e" => "\x7a", // Windows-1252-encoded latin small letter z with caron => latin small letter z
                );
                foreach ($midways as $before => $after)
                {
                    if (strpos($utf8_enc, $before))
                    {
                        $description_m = sprintf("double-UTF8-encoded with midway conversion of x%02x to x%02x",
                            ord($before), ord($after));
                        $utf8_enc_m = str_replace($before, $after, $utf8_enc);
                        $double_enc_m = _utf8_encode_string($utf8_enc_m);
                        _install_bad_sequence( $double_enc_m, $code_point, $description_m );
                    }
                }
            }
        }
    }

    ksort($_bad_byte_sequences);

    if (0)
    {
        foreach ($_bad_byte_sequences as $raw => $remarks)
        {
            list($code_point, $why_bad) = $remarks;
            echo sprintf("%s: U+%04x %s\n",
                string_to_hex($raw), $code_point, $why_bad);
        }
    }
}

// =============================================================================

function _utf8_encode_string($seq)
// Interpreting each byte in the string as a code point.
// (I.e., interpreting it as encoded in ISO-8859-1?)
{
    $result = "";
    foreach ( str_split($seq) as $byte )
    {
        $result .= _utf8_encode_code_point(ord($byte));
    }
    return $result;
}

function _utf8_encode_code_point($cp)
{
    if ($cp <= 0x007f)
    {
        // utf8 encodes as 1 byte
        return chr($cp);
    }
    elseif ($cp <= 0x07ff)
    {
        // utf8 encodes as 2 bytes
        $byte1 = 0xC0 | (($cp & 0x7C0) >> 6);
        $byte2 = 0x80 | (($cp & 0x03F) >> 0);

        $sa = chr($byte1) . chr($byte2);
        $sb = sprintf("%c%c", $byte1, $byte2);
        assert( $sa == $sb );
        return $sa;
    }
    elseif ($cp <= 0xffff)
    {
        // utf8 encodes as 3 bytes
        $byte1 = 0xE0 | (($cp & 0xF000) >> 12);
        $byte2 = 0x80 | (($cp & 0x0FC0) >>  6);
        $byte3 = 0x80 | (($cp & 0x003F) >>  0);

        $sa = chr($byte1) . chr($byte2) . chr($byte3);
        $sb = sprintf("%c%c%c", $byte1, $byte2, $byte3);
        assert( $sa == $sb );
        return $sa;
    }
    else
    {
        // utf8 encodes as 4 or more bytes
        assert(FALSE);
    }
}

function _install_bad_sequence($bytes, $code_point, $why_bad)
{
    global $_anchored_bad_bytes_regex, $_bad_byte_sequences;
 
    // echo $why_bad, "\n";
 
    if (!preg_match($_anchored_bad_bytes_regex, $bytes))
    {
        $hex = string_to_hex($bytes);
        die("$hex ($code_point $why_bad) is not covered by \$_bad_bytes_pattern\n");
    }

    $_bad_byte_sequences[$bytes] = array($code_point, $why_bad);
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

function find_bad_byte_sequences_in_text($text, $projectid)
{
    global $_unanchored_bad_bytes_regex, $_bad_byte_sequences;

    $occurrences = array();

    // find any characters in the text that are not valid for the project
    if(utf8_string_has_bom($text))
    {
        $occurrences['BOM'] = 1;
        $text = utf8_str_replace(utf8_bom(), '', $text);
    }

    $project = new Project($projectid);
    $invalid_chars = get_invalid_characters($text, $project->get_valid_codepoints());
    foreach($invalid_chars as $char => $num)
    {
        $occurrences[$char] = $num;
    }

    $n_matches = preg_match_all($_unanchored_bad_bytes_regex, $text, $matches);
    if ($n_matches === FALSE)
    {
        echo "preg_match_all returned FALSE!\n";
        return $occurrences;
    }

    if ($n_matches == 0)
    {
        // no matches
        // i.e. page text doesn't have any bad byte-sequences, yay!
        return $occurrences;
    }

    foreach ($matches[0] as $match)
    {
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

        if (startswith($match, '&#'))
        {
            @$occurrences[$match] += 1;
            // So if a page has (say) both &#62; and &#x3e;,
            // those will show up separately in $occurrences.
            // But that seems unlikely, and not a big deal if it does happen.

        }
        elseif (array_key_exists($match, $_bad_byte_sequences))
        {
            // Yes it is.
            @$occurrences[$match] += 1;
        }
        elseif (startswith($match, '&'))
        {
            // It satisfies the syntax of an HTML character entity reference,
            // but there's no entry for it in $_bad_byte_sequences.
            // So we assume it's meant literally.
            // (E.g., &c; is meant to represent the 3 characters
            // ampersand, 'c', semicolon.)
            // So we don't count it as bad.
        }
        else
        {
            // $match isn't a bad-bytes-sequence.
            // However, if it's a multi-byte sequence,
            // then (some of) its constituent bytes might be bad.

            foreach (str_split($match) as $byte)
            {
                if (array_key_exists($byte, $_bad_byte_sequences))
                {
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

    if (startswith($raw, '&#'))
    {
        // This is a numeric character reference.

        if (startswith($raw, '&#x'))
        {
            $hex = substr($raw, 3, -1);
            $code_point = intval($hex, 16);
        }
        else
        {
            $dec = substr($raw, 2, -1);
            $code_point = intval($dec, 10);
        }

        $why_bad = "HTML numeric character reference";
    }
    elseif(extended_strlen($raw) == 1 && is_utf8($raw))
    {
        $why_bad = "Character not valid for project";
        $intended_character = "";
    }
    else
    {
        list($code_point, $why_bad) = $_bad_byte_sequences[$raw];
    }

    if(!isset($intended_character))
    {
        @$info = $_character_data[$code_point];
        if ($info)
        {
            $char_name = $info['name'];
        }
        else
        {
            // $_character_data doesn't have an entry for that code point
            $char_name = '???';
        }
        $intended_character = sprintf("U+%04x %s", $code_point, $char_name);
    }

    return array($intended_character, $why_bad);
}

function string_to_hex($str)
{
    $hex = "";
    foreach (str_split($str) as $byte)
    {
        if ($hex != "") $hex .= " ";
        $hex .= sprintf("%02x", ord($byte));
    }
    return $hex;
}

// vim: sw=4 ts=4 expandtab
