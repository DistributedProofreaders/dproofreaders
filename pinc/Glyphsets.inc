<?php
include_once($relPath."unicode.inc");

class PickerSet
{
    function __construct($name)
    {
        $this->name = $name;
    }

    public function add_subset($name, $codepoints, $title=NULL)
    {
        $this->subsets[$name] = $codepoints;

        if($title)
            $this->titles[$name] = $title;
        else
            $this->titles[$name] = $name;
    }

    public function get_subsets()
    {
        return $this->subsets;
    }

    public function get_title($name)
    {
        return $this->titles[$name];
    }
}

class Glyphset
{
    function __construct($name, $codepoints=NULL, $pickerset=NULL)
    {
        $this->name = $name;
        $this->codepoints = $codepoints;
        $this->pickerset = $pickerset;
    }
}

class Glyphsets
{
    static private $_available_glyphsets = [];

    public static function add_glyphset($glyphset)
    {
        self::$_available_glyphsets[$glyphset->name] = $glyphset;
    }

    public static function get_glyphsets()
    {
        return self::$_available_glyphsets;
    }

    public static function get_glyphset($name)
    {
        if(!isset(self::$_available_glyphsets[$name]))
        {
            throw new UnexpectedValueException("$name is not a valid glyphset");
        }
        return self::$_available_glyphsets[$name];
    }
}

#----------------------------------------------------------------------------

// For now these are hard-coded here in this file. They could be abstracted
// into a database or another data structure.

$basic_latin = new Glyphset("Basic Latin");
$basic_latin->codepoints = [
    'U+0020-U+007E', # https://en.wikipedia.org/wiki/Basic_Latin_(Unicode_block)
    'U+00A1-U+00FF', # https://en.wikipedia.org/wiki/Latin-1_Supplement_(Unicode_block)'
];
$basic_latin->reference_urls = [
    "https://en.wikipedia.org/wiki/Basic_Latin_(Unicode_block)",
    "https://en.wikipedia.org/wiki/Latin-1_Supplement_(Unicode_block)",
];

$basic_latin_pickerset = new PickerSet("Basic Latin");
$basic_latin_pickerset->add_subset("A-Z", [
    [ 'U+0041-U+005a' ], # A-Z
    [ 'U+0061-U+007a' ], # a-z
]);
$basic_latin_pickerset->add_subset(utf8_chr("\u00c0"), [
    [ 'U+00c0-U+00c7', 'U+00d0', 'U+00c8-U+00cf', 'U+00d1' ],
    [ 'U+00e0-U+00e7', 'U+00f0', 'U+00e8-U+00ef', 'U+00f1' ],
]);
$basic_latin_pickerset->add_subset(utf8_chr("\u00d2"), [
    [ 'U+00d2-U+00d6', 'U+00d8-U+00df' ],
    [ 'U+00f2-U+00f6', 'U+00f8-U+00ff' ],
]);
$basic_latin_pickerset->add_subset("!", [
    [ 'U+0021-U+0022', 'U+0026-U+002f', 'U+003a-U+0040', 'U+00b4-U+00b8' ],
    [ 'U+00a1', 'U+005b-U+0060', 'U+007b-U+007e', 'U+00a6-U+00a9', 'U+00ab', 'U+00bb', 'U+00ae-U+00b0', 'U+00bf' ],
], _("Symbols"));
$basic_latin_pickerset->add_subset("1", [
    [ 'U+0030-U+0039', 'U+0023', 'U+0025', 'U+0024', 'U+00a2-U+00a5' ],
    [ 'U+00b9', 'U+00b2-U+00b3', 'U+00ba', 'U+00aa', 'U+00bc-U+00be', 'U+00d7', 'U+00f7', 'U+00b1', 'U+00ac' ],
], _("Numbers and Currency"));
$basic_latin->pickerset = $basic_latin_pickerset;

Glyphsets::add_glyphset($basic_latin);
