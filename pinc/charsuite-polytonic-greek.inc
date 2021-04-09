<?php
include_once($relPath."CharSuites.inc");

use voku\helper\UTF8;

$charsuite = new CharSuite("polytonic-greek", _("Polytonic Greek"));
$charsuite->codepoints = [
    # https://en.wikipedia.org/wiki/Greek_and_Coptic
    'U+02b9',
    'U+0375',
    'U+0391-U+03a1',
    'U+03a3-U+03a9',
    'U+03aa-U+03ab',
    'U+03b1-U+03c9',
    'U+03ca',
    'U+03cb',
    'U+03db',
    'U+03dc',
    'U+03dd',
    'U+03f2',
    'U+03f9',
    # tonos accented characters
    'U+0386',
    'U+0388',
    'U+0389',
    'U+038a',
    'U+038c',
    'U+038e',
    'U+038f',
    'U+0390',
    'U+03ac',
    'U+03ad',
    'U+03ae',
    'U+03af',
    'U+03b0',
    'U+03cc',
    'U+03cd',
    'U+03ce',
    # polytonic characters
    'U+1f00-U+1f15',
    'U+1f18-U+1f1d',
    'U+1f20-U+1f45',
    'U+1f48-U+1f4d',
    'U+1f50-U+1f57',
    'U+1f59',
    'U+1f5b',
    'U+1f5d',
    'U+1f5f',
    'U+1f60-U+1f70',
    # skip oxia U+1f71
    'U+1f72',
    # skip oxia U+1f73
    'U+1f74',
    # skip oxia U+1f75
    'U+1f76',
    # skip oxia U+1f77
    'U+1f78',
    # skip oxia U+1f79
    'U+1f7a',
    # skip oxia U+1f7b
    'U+1f7c',
    # skip oxia U+1f7d
    'U+1f80-U+1fb4',
    'U+1fb6-U+1fba',
    # skip oxia U+1fbb
    'U+1fbc',
    'U+1fc2-U+1fc4',
    'U+1fc6-U+1fc8',
    # skip oxia U+1fc9
    'U+1fca',
    # skip oxia U+1fcb
    'U+1fcc',
    'U+1fd0-U+1fd2',
    # skip oxia U+1fd3
    'U+1fd6-U+1fda',
    # skip oxia U+1fdb
    'U+1fe0-U+1fe2',
    # skip oxia U+1fe3
    'U+1fe4-U+1fea',
    # skip oxia U+1feb
    'U+1fec',
    'U+1ff2-U+1ff4',
    'U+1ff6-U+1ff8',
    # skip oxia U+1ff9
    'U+1ffa',
    # skip oxia U+1ffb
    'U+1ffc',
];
$charsuite->reference_urls = [
    "https://www.pgdp.net/wiki/DP_Code_-_Unicode/Greek",
    "https://en.wikipedia.org/wiki/Greek_and_Coptic",
    "https://en.wikipedia.org/wiki/Greek_Extended",
];

$pickerset = new PickerSet();

$pickerset->add_subset(UTF8::hex_to_chr('U+0391') . '-' . UTF8::hex_to_chr('U+03a9'), [
    // capital alpha through omega
    ['U+0391-U+03a1', null, 'U+03a3-U+03a9'],
    // lowercase alpha through omega
    ['U+03b1-U+03c9'],
]);

$pickerset->add_subset(UTF8::hex_to_chr('U+03ac'), [
    [
        null, null, null, 'U+1f08', 'U+1f09', 'U+1f0c', 'U+1f0d', 'U+1f0a',
        'U+1f0b', 'U+1f0e', 'U+1f0f',
    ],
    [
        'U+03ac', 'U+1f70', 'U+1fb6', 'U+1f00', 'U+1f01', 'U+1f04', 'U+1f05', 'U+1f02',
        'U+1f03', 'U+1f06', 'U+1f07', 'U+1fb3', 'U+1fb4', 'U+1fb2', 'U+1fb7', 'U+1f80',
        'U+1f81', 'U+1f84', 'U+1f85', 'U+1f82', 'U+1f83', 'U+1f86', 'U+1f87',
    ],
]);

$pickerset->add_subset(UTF8::hex_to_chr('U+03ad'), [
    [
        null, null, 'U+1f18', 'U+1f19', 'U+1f1c', 'U+1f1d', 'U+1f1a', 'U+1f1b',
    ],
    [
        'U+03ad', 'U+1f72', 'U+1f10', 'U+1f11', 'U+1f14', 'U+1f15', 'U+1f12', 'U+1f13',
    ],
]);

$pickerset->add_subset(UTF8::hex_to_chr('U+03ae'), [
    [
        null, null, null, 'U+1f28', 'U+1f29', 'U+1f2c', 'U+1f2d', 'U+1f2a',
        'U+1f2b', 'U+1f2e', 'U+1f2f',
    ],
    [
        'U+03ae', 'U+1f74', 'U+1fc6', 'U+1f20', 'U+1f21', 'U+1f24', 'U+1f25', 'U+1f22',
        'U+1f23', 'U+1f26', 'U+1f27', 'U+1fc3', 'U+1fc4', 'U+1fc2', 'U+1fc7', 'U+1f90',
        'U+1f91', 'U+1f94', 'U+1f95', 'U+1f92', 'U+1f93', 'U+1f96', 'U+1f97',
    ],
]);

$pickerset->add_subset(UTF8::hex_to_chr('U+03af'), [
    [
        null, null, null, 'U+1f38', 'U+1f39', 'U+1f3c', 'U+1f3d', 'U+1f3a',
        'U+1f3b', 'U+1f3e', 'U+1f3f', 'U+03aa',
    ],
    [
        'U+03af', 'U+1f76', 'U+1fd6', 'U+1f30', 'U+1f31', 'U+1f34', 'U+1f35', 'U+1f32',
        'U+1f33', 'U+1f36', 'U+1f37', 'U+03ca', 'U+0390', 'U+1fd2', 'U+1fd7',
    ],
]);

$pickerset->add_subset(UTF8::hex_to_chr('U+03cc'), [
    [
        null, null, 'U+1f48', 'U+1f49', 'U+1f4c', 'U+1f4d', 'U+1f4a', 'U+1f4b',
    ],
    [
        'U+03cc', 'U+1f78', 'U+1f40', 'U+1f41', 'U+1f44', 'U+1f45', 'U+1f42', 'U+1f43',
    ],
]);

$pickerset->add_subset(UTF8::hex_to_chr('U+1fe4'), [
    [null, 'U+1fec'],
    ['U+1fe4', 'U+1fe5'],
]);

$pickerset->add_subset(UTF8::hex_to_chr('U+03cd'), [
    [
        null, null, null, null, 'U+1f59', null, 'U+1f5d', null,
        'U+1f5b', null, 'U+1f5f', 'U+03ab',
    ],
    [
        'U+03cd', 'U+1f7a', 'U+1fe6', 'U+1f50', 'U+1f51', 'U+1f54', 'U+1f55', 'U+1f52',
        'U+1f53', 'U+1f56', 'U+1f57', 'U+03cb', 'U+03b0', 'U+1fe2', 'U+1fe7',
    ],
]);

$pickerset->add_subset(UTF8::hex_to_chr('U+03ce'), [
    [
        null, null, null, 'U+1f68', 'U+1f69', 'U+1f6c', 'U+1f6d', 'U+1f6a',
        'U+1f6b', 'U+1f6e', 'U+1f6f',
    ],
    [
        'U+03ce', 'U+1f7c', 'U+1ff6', 'U+1f60', 'U+1f61', 'U+1f64', 'U+1f65', 'U+1f62',
        'U+1f63', 'U+1f66', 'U+1f67', 'U+1ff3', 'U+1ff4', 'U+1ff2', 'U+1ff7', 'U+1fa0',
        'U+1fa1', 'U+1fa4', 'U+1fa5', 'U+1fa2', 'U+1fa3', 'U+1fa6', 'U+1fa7',
    ],
]);

$charsuite->pickerset = $pickerset;

CharSuites::add($charsuite);
