<?PHP
include_once($relPath.'site_vars.php'); // $wikihiero_dir

$utf8_site = (strcasecmp($charset,"UTF-8") == 0);

// "PIT" stands for "Proofing Interface Toolbox" (ctrl_frame.php)

// -----------------------------------------------------------------------------

// PIT Popup Link:
// A link that, when clicked, opens a new window containing some tool.

$all_pit_popup_links = array();

function define_pit_popup_link( $id, $label, $url, $window_name, $width, $height )
{
    $window_features = "width=$width,height=$height,directories=0,location=0,menubar=0,resizable,scrollbars,status=0,toolbar=0";
    $js = "window.open('$url','$window_name','$window_features'); return false;";
    $element = "<a href='#' onClick=\"$js\"\n>$label</a>\n";

    global $all_pit_popup_links;
    $all_pit_popup_links[$id] = $element;
}

function echo_popup_links( $ids )
{
    global $all_pit_popup_links;
    echo implode('&nbsp;|&nbsp;', array_extract($all_pit_popup_links, $ids));
}

define_pit_popup_link(
    'search_and_replace',
    _('Search/Replace'),
    'srchrep.php',
    'dp_searchrepl',
    300, 250
);
define_pit_popup_link(
    'table_maker',
    _('Table Maker'),
    'mktable.php',
    'dp_mktable',
    600, 500
);
define_pit_popup_link(
    'greek_transliterator',
    _('Greek Transliterator'),
    'greek2ascii.php',
    'gkasciiWin',
    640, 210
);
if ($wikihiero_dir)
{
    define_pit_popup_link(
        'hieroglyph_transliterator',
        _("Hieroglyphs"),
        'hiero/index.php',
        'dp_hiero',
        600, 400
    );
}

// -----------------------------------------------------------------------------

// PIT Tool Button:
// A button that, when pressed, alters the text in some way.

$all_pit_tool_buttons = array();

function define_pit_tool_button( $id, $accesskey, $title, $label, $onclick )
{
    # IE, not surprisingly, differs from all other browsers
    # in rendering the buttons. The difference is in the centering
    # of the labels, and only Opera gets it wrong if pretending to be IE
    $spc = stristr(@$_SERVER['HTTP_USER_AGENT'],"msie") ? "" : "&nbsp;";

    global $all_pit_tool_buttons;
    $all_pit_tool_buttons[$id] = <<<EOS
<button
    onclick="$onclick"
    accesskey="$accesskey"
    class='proofbutton'
    style='height: 22px; width: 22px; padding-left: 2px; padding-right: 2px;'
    align="top"
    title="$title"
>$spc$label</button>

EOS;
}

function echo_tool_buttons( $ids )
{
    global $all_pit_tool_buttons;
    echo implode('', array_extract($all_pit_tool_buttons, $ids));
}

define_pit_tool_button(
    'italic',
    'i',
    _('Italics'),
    '<i>i</i>',
    "top.surroundSelection('<i>','</i>')"
);
define_pit_tool_button(
    'bold',
    'b',
    _('Bold'),
    '<b>B</b>',
    "top.surroundSelection('<b>','</b>')"
);
define_pit_tool_button(
    'small_caps',
    's',
    _('Small Caps markup'),
    "<span style='font-variant: small-caps;'>Sc</span>",
    "top.surroundSelection('<sc>','</sc>')"
);
define_pit_tool_button(
    'gesperrt',
    'g',
    _('Gesperrt (spaced out)'),
    'g',
    "top.surroundSelection('<g>','</g>')"
);
define_pit_tool_button(
    'antiqua',
    'f',
    _('Antiqua'),
    'f',
    "top.surroundSelection('<f>','</f>')"
);
define_pit_tool_button(
    'remove_markup',
    'm',
    _('Remove markup from selection'),
    "<span style='text-decoration: line-through;'>&lt;x&gt;</span>",
    "top.transformText('remove_markup')"
);
define_pit_tool_button(
    'upper_case',
    'u',
    _('Uppercase selection'),
    'ABC',
    "top.transformText('upper-case')"
);
define_pit_tool_button(
    'title_case',
    't',
    _('Title case selection'),
    'Abc',
    "top.transformText('title-case')"
);
define_pit_tool_button(
    'lower_case',
    'l',
    _('Lowercase selection'),
    'abc',
    "top.transformText('lower-case')"
);

// -----------------------------------------------------------------------------

// PIT Tool Link
// A link that, when clicked, alters the text in some way.
// (It's not clear whether it's useful to have both
// tool buttons and tool links in the toolbox.)

$all_pit_tool_links = array();

function define_pit_tool_link( $id, $label, $title, $onclick )
{
    $label   = htmlspecialchars($label, ENT_NOQUOTES);
    $title   = htmlspecialchars($title);
    $onclick = htmlspecialchars($onclick);

    global $all_pit_tool_links;
    $all_pit_tool_links[$id] =
        "<a href='#' onclick=\"$onclick\" title=\"$title\">$label</a>";
}

function echo_tool_links( $ids )
{
    global $all_pit_tool_links;
    echo implode("\n&nbsp;\n", array_extract($all_pit_tool_links, $ids));
}

define_pit_tool_link(
    'greek',
    '[Greek: ]',
    '[Greek:',
    "top.surroundSelection('[Greek: ',']')"
);
define_pit_tool_link(
    'sidenote',
    '[Sidenote: ]',
    '[Sidenote:',
    "top.surroundSelection('[Sidenote: ',']')"
);
define_pit_tool_link(
    'illustration',
    '[Illustration: ]',
    '[Illustration:',
    "top.surroundSelection('[Illustration: ',']')"
);
define_pit_tool_link(
    'note',
    '[** ]',
    'Note',
    "top.surroundSelection('[** ',']')"
);
define_pit_tool_link(
    'brackets',
    '[ ]',
    '[ ]',
    "top.surroundSelection('[',']')"
);
define_pit_tool_link(
    'braces',
    '{ }',
    '{ }',
    "top.surroundSelection('{','}')"
);
define_pit_tool_link(
    'footnote',
    '[Footnote #: ]',
    '[Footnote #:',
    "top.surroundSelection('[Footnote #: ',']')"
);
define_pit_tool_link(
    'slash_star',
    '/* */',
    _('Start poetry/table'),
    "top.surroundSelection('/*\\n','\\n*/')"
);
define_pit_tool_link(
    'slash_number',
    '/# #/',
    _('Start block quote'),
    "top.surroundSelection('/#\\n','\\n#/')"
);
define_pit_tool_link(
    'thought_break',
    '<tb>',
    _('Thought Break'),
    "top.surroundSelection('\\n<tb>\\n','')"
);
define_pit_tool_link(
    'blank_page',
    '[Blank Page]',
    '[Blank Page]',
    "top.replaceAllText('[Blank Page]')"
);

// -----------------------------------------------------------------------------

function echo_character_selector( $id, $title, $option_args )
{
    global $utf8_site;

    echo '<td align="right">';
    echo <<<EOS
<select
    name="tChars{$id}"
    ID="tChars{$id}"
    title="{$title}"
    onchange="if (this.options[selectedIndex].value !=0){top.insertCharacter(this.options[selectedIndex].value);}"
    class="dropchars"
>

EOS;

    foreach ( $option_args as $arg )
    {
        if ( is_string($arg) )
        {
            echo "<option value='0'>{$arg}</option>\n";
        }
        elseif ( is_array($arg) )
        {
            foreach ( $arg as $codepoint )
            {
                if ( $codepoint <= 255 || $utf8_site )
                {
                    echo "<option value='$codepoint'>&#{$codepoint};</option>\n";
                }
            }
        }
        else
        {
            assert( 0 );
        }
    }

    echo "</select>";
    echo "</td>\n";
}

function echo_character_selectors_block()
{
    echo "<table border='0' cellpadding='0' cellspacing='0'>\n";

    // START OF ROW 1
    echo "<tr>\n";

    echo_character_selector(
        'A',
        'A',
        array(
            'A',
            '--',
            array( 192, 224, 193, 225, 194, 226, 195, 227, 196, 228, 197, 229, 198, 230 ),
            array( 256, 257, 258, 259, 260, 261 ),
        )
    );

    echo_character_selector(
        'E',
        'E',
        array(
            'E',
            '--',
            array( 200, 232, 201, 233, 202, 234, 203, 235 ),
            array( 274, 275, 276, 277, 278, 279, 280, 281, 282, 283 ),
        )
    );

    echo_character_selector(
        'I',
        'I',
        array(
            'I',
            '--',
            array( 204, 236, 205, 237, 206, 238, 207, 239 ),
            array( 296, 297, 298, 299, 300, 301, 302, 303, 304, 305 ),
        )
    );

    echo_character_selector(
        'O',
        'O',
        array(
            'O',
            '--',
            array( 210, 242, 211, 243, 212, 244, 213, 245, 214, 246, 216, 248 ),
            array( 332, 333, 334, 335, 336, 337, 338, 339 ),
        )
    );

    echo_character_selector(
        'U',
        'U',
        array(
            'U',
            '--',
            array( 217, 249, 218, 250, 219, 251, 220, 252, 181 ),
            array( 362, 363, 368, 369 ),
        )
    );

    echo_character_selector(
        'M',
        'More',
        array(
            '+',
            '--',
            array( 36, 162, 163, 164, 165, 161, 191, 169, 174 ),
            '--',
            array( 171, 187 ),
            array( 8222, 8220 ),
            '--',
            array( 222, 254, 223 ),
            array( 502, 405 ),
            '--',
            'Y',
            '--',
            array( 221, 253, 255 ),
            '--',
            'C',
            '--',
            array( 199, 231 ),
            '--',
            'D',
            '--',
            array( 208, 240 ),
            '--',
            'N',
            '--',
            array( 209, 241 ),
            '--',
            array( 166, 167, 168, 170, 172, 173, 175, 176, 177, 178, 179, 180, 182, 183, 184, 185, 186, 188, 189, 190, 215, 247 ),
        )
    );

    global $utf8_site;

    ?>
        <td valign="top" <? if($utf8_site) echo "rowspan='2'"; ?>>
            <input
                TYPE="text"
                VALUE=""
                accesskey="\"
                name="markBoxChar"
                class="dropnormal"
                size="1"
                onclick="this.select()"
            >
        </td>
    <?

    echo "</tr>\n";
    // END OF ROW 1

    if ($utf8_site)
    {
        // START OF ROW 2
        echo "<tr>\n";

        echo_character_selector(
            'C',
            'CD',
            array(
                'CD',
                '--',
                array( 199, 231, 262, 263, 264, 265, 266, 267, 268, 269, 390, 391, 208, 240, 270, 271, 272, 273, 393, 394 ),
            )
        );

        echo_character_selector(
            'D',
            'LN',
            array(
                'LN',
                '--',
                array( 313, 314, 315, 316, 317, 318, 319, 320, 321, 322, 209, 241, 323, 324, 325, 326, 327, 328, 329, 330, 331 ),
            )
        );

        echo_character_selector(
            'S',
            'RS',
            array(
                'RS',
                '--',
                array( 340, 341, 342, 343, 344, 345, 223, 346, 347, 348, 349, 350, 351, 352, 353 ),
            )
        );

        echo_character_selector(
            'Z',
            'TZ',
            array(
                'TZ',
                '--',
                array( 354, 355, 356, 357, 358, 359, 377, 378, 379, 380, 381, 382 ),
            )
        );

        echo_character_selector(
            'Cyr',
            'Cyrillic',
            array(
                '&#1035;',
                '--',
                array( 1026, 1106, 1027, 1107, 1024, 1104, 1025, 1105, 1028, 1108, 1029, 1109, 1037, 1117, 1030, 1110, 1031, 1111, 1049, 1081, 1032, 1112, 1033, 1113, 1034, 1114, 1035, 1115, 1036, 1116, 1038, 1118, 1039, 1119, 1065, 1097, 1066, 1098, 1067, 1099, 1068, 1100, 1069, 1101, 1070, 1102, 1071, 1103 ),
            )
        );

        echo_character_selector(
            'OCyr',
            'OldCyrillic',
            array(
                '&#1122;',
                '--',
                array( 1120, 1121, 1122, 1123, 1124, 1125, 1126, 1127, 1128, 1129, 1130, 1131, 1132, 1133, 1134, 1135, 1136, 1137, 1138, 1139, 1140, 1141, 1142, 1143, 1144, 1145, 1146, 1147, 1148, 1149, 1150, 1151, 1152, 1153, 1154 ),
            )
        );

        echo "</tr>";
        // END OF ROW 2
    }

    echo "</table>\n";
}

// -----------------------------------------------------------------------------

function array_extract( $source, $keys )
{
    if ( $keys == 'ALL' )
    {
        return $source;
    }

    $result = array();
    foreach ( $keys as $key )
    {
        if ( array_key_exists($key, $source) )
        {
            $result[$key] = $source[$key];
        }
        else
        {
            // It's tempting to do an assert(0), but $key could be for a tool
            // that *can* exist, but happens not to in this installation.
            // (i.e., currently, hieroglyph_transliterator)
        }
    }
    return $result;
}

// vim: sw=4 ts=4 expandtab
?>
