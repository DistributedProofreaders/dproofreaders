<?php
include_once($relPath."Glyphsets.inc");

$glyphset = new Glyphset("polytonic-greek", _("Polytonic Greek"));
$glyphset->codepoints = [
    # https://en.wikipedia.org/wiki/Greek_and_Coptic
    'U+02B9',
    'U+0375',
    'U+003B',
    'U+00B7',
    'U+0391-U+03A1',
    'U+03A3-U+03A9',
    'U+03AA-U+03AB',
    'U+03B1-U+03C9',
    'U+03CA',
    'U+03CB',
    'U+03DB',
    'U+03DC',
    'U+03DD',
    'U+03F2',
    'U+03F9',
    # tonos accented characters
    'U+0386',
    'U+0388',
    'U+0389',
    'U+038A',
    'U+038C',
    'U+038E',
    'U+038F',
    'U+0390',
    'U+03AC',
    'U+03AD',
    'U+03AE',
    'U+03AF',
    'U+03B0',
    'U+03CC',
    'U+03CD',
    'U+03CE',
    # polytonic characters
    'U+1F00-U+1F15',
    'U+1F18-U+1F1D',
    'U+1F20-U+1F45',
    'U+1F48-U+1F4D',
    'U+1F50-U+1F57',
    'U+1F59',
    'U+1F5B',
    'U+1F5D',
    'U+1F5F',
    'U+1F60-U+1F70',
    # skip oxia U+1F71
    'U+1F72',
    # skip oxia U+1F73
    'U+1F74',
    # skip oxia U+1F75
    'U+1F76',
    # skip oxia U+1F77
    'U+1F78',
    # skip oxia U+1F79
    'U+1F7A',
    # skip oxia U+1F7B
    'U+1F7C',
    # skip oxia U+1F7D
    'U+1F80-U+1FB4',
    'U+1FB6-U+1FBA',
    # skip oxia U+1FBB
    'U+1FBC',
    'U+1FC2-U+1FC4',
    'U+1FC6-U+1FC8',
    # skip oxia U+1FC9
    'U+1FCA',
    # skip oxia U+1FCB
    'U+1FCC',
    'U+1FD0-U+1FD2',
    # skip oxia U+1FD3
    'U+1FD6-U+1FDA',
    # skip oxia U+1FDB
    'U+1FE0-U+1FE2',
    # skip oxia U+1FE3
    'U+1FE4-U+1FEA',
    # skip oxia U+1FEB
    'U+1FEC',
    'U+1FF2-U+1FF4',
    'U+1FF6-U+1FF8',
    # skip oxia U+1FF9
    'U+1FFA',
    # skip oxia U+1FFB
    'U+1FFC',
];
$glyphset->reference_urls = [
    "https://www.pgdp.net/wiki/DP_Code_-_Unicode/Greek",
    "https://en.wikipedia.org/wiki/Greek_and_Coptic",
    "https://en.wikipedia.org/wiki/Greek_Extended",
];

$pickerset = new PickerSet();
$pickerset->add_subset(utf8_chr('U+0391') . '-' . utf8_chr('U+03A9'), [
    [ 'U+0391-U+03A1', NULL, 'U+03A3-U+03A9' ], # capital alpha through omega
    [ 'U+03B1-U+03C9' ], # lowercase alpha through omega
]);
$pickerset->add_subset(utf8_chr('U+0386'), [
    [ 'U+0386', 'U+1fba', NULL, 'U+1f08', 'U+1f09', 'U+1f0c', 'U+1f0d', 'U+1f0a', 
      'U+1f0b', 'U+1f0e', 'U+1f0f', 'U+1fbc', NULL, NULL, NULL, 'U+1f88', 'U+1f89',
      'U+1f8c', 'U+1f8d', 'U+1f8a', 'U+1f8b', 'U+1f8e', 'U+1f8f'  ],
    [ 'U+03AC', 'U+1f70', 'U+1fb6', 'U+1f00', 'U+1f01', 'U+1f04', 'U+1f05', 'U+1f02', 
      'U+1f03', 'U+1f06', 'U+1f07', 'U+1fb3', 'U+1fb4', 'U+1fb2', 'U+1fb7', 'U+1f80', 
      'U+1f81', 'U+1f84', 'U+1f85', 'U+1f82', 'U+1f83', 'U+1f86', 'U+1f87' ],
]);
$pickerset->add_subset(utf8_chr('U+0388'), [
    [ 'U+0388', 'U+1fc8', 'U+1f18', 'U+1f19', 'U+1f1c', 'U+1f1d', 'U+1f1a', 'U+1f1b' ],
    [ 'U+03AD', 'U+1f72', 'U+1f10', 'U+1f11', 'U+1f14', 'U+1f15', 'U+1f12', 'U+1f13' ],
]);
$pickerset->add_subset(utf8_chr('U+0389'), [
    [ 'U+0389', 'U+1fca', NULL, 'U+1f28', 'U+1f29', 'U+1f2c', 'U+1f2d', 'U+1f2a', 
      'U+1f2b', 'U+1f2e', 'U+1f2f', 'U+1fcc', NULL, NULL, NULL, 'U+1f98', 'U+1f99',
      'U+1f9c', 'U+1f9d', 'U+1f9a', 'U+1f9b', 'U+1f9e', 'U+1f9f', ],
    [ 'U+03AE', 'U+1f74', 'U+1fc6', 'U+1f20', 'U+1f21', 'U+1f24', 'U+1f25', 'U+1f22',
      'U+1f23', 'U+1f26', 'U+1f27', 'U+1fc3', 'U+1fc4', 'U+1fc2', 'U+1fc7', 'U+1f90',
      'U+1f91', 'U+1f94', 'U+1f95', 'U+1f92', 'U+1f93', 'U+1f96', 'U+1f97' ],
]);
$pickerset->add_subset(utf8_chr('U+038A'), [
    [ 'U+038A', 'U+1fda', NULL, 'U+1f38', 'U+1f39', 'U+1f3c', 'U+1f3d', 'U+1f3a', 
      'U+1f3b', 'U+1f3e', 'U+1f3f', 'U+03aa' ],
    [ 'U+03AF', 'U+1f76', 'U+1fd6', 'U+1f30', 'U+1f31', 'U+1f34', 'U+1f35', 'U+1f32',
      'U+1f33', 'U+1f36', 'U+1f37', 'U+03ca', 'U+0390', 'U+1fd2', 'U+1fd7' ],
]);
$pickerset->add_subset(utf8_chr('U+038C'), [
    [ 'U+038C', 'U+1ff8', 'U+1f48', 'U+1f49', 'U+1f4c', 'U+1f4d', 'U+1f4a', 'U+1f4b' ],
    [ 'U+03CC', 'U+1f78', 'U+1f40', 'U+1f41', 'U+1f44', 'U+1f45', 'U+1f42', 'U+1f43' ],
]);
$pickerset->add_subset(utf8_chr('U+1fec'), [
    [ NULL, 'U+1fec' ],
    [ 'U+1fe4', 'U+1fe5' ],
]);
$pickerset->add_subset(utf8_chr('U+038E'), [
    [ 'U+038E', 'U+1fea', NULL, NULL, 'U+1f59', NULL, 'U+1f5d', NULL,
      'U+1f5b',NULL, 'U+1f5f', 'U+03ab' ],
    [ 'U+03CD', 'U+1f7a', 'U+1fe6', 'U+1f50', 'U+1f51', 'U+1f54', 'U+1f55', 'U+1f52',
      'U+1f53', 'U+1f56', 'U+1f57', 'U+03cb', 'U+03b0', 'U+1fe2', 'U+1fe7'  ],
]);
$pickerset->add_subset(utf8_chr('U+038F'), [
    [ 'U+038F', 'U+1ffa', NULL, 'U+1f68', 'U+1f69', 'U+1f6c', 'U+1f6d', 'U+1f6a',
     'U+1f6b', 'U+1f6e', 'U+1f6f', 'U+1ffc', NULL, NULL, NULL, 'U+1fa8', 'U+1fa9',
     'U+1fac', 'U+1fad', 'U+1faa', 'U+1fab', 'U+1fae', 'U+1faf' ],
    [ 'U+03CE', 'U+1f7c', 'U+1ff6', 'U+1f60', 'U+1f61', 'U+1f64', 'U+1f65', 'U+1f62',
      'U+1f63', 'U+1f66', 'U+1f67', 'U+1ff3', 'U+1ff4', 'U+1ff2', 'U+1ff7', 'U+1fa0',
      'U+1fa1', 'U+1fa4', 'U+1fa5', 'U+1fa2', 'U+1fa3', 'U+1fa6', 'U+1fa7' ],
]);
$glyphset->pickerset = $pickerset;

Glyphsets::add($glyphset);
