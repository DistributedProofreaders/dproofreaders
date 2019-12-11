<?php

class CharacterSelector
{
    function __construct ()
    {
        global $utf8_site;

        if($utf8_site)
        {
            $this->picker_set =
            [
                [
                    "code" => "A",
                    "title" => "A",
                    "upper" => [0xc0, 0xc1, 0xc2, 0xc3, 0xc4, 0xc5, 0xc6, 0x100, 0x102, 0x104],
                    "lower" => [0xe0, 0xe1, 0xe2, 0xe3, 0xe4, 0xe5, 0xe6, 0x101, 0x103, 0x105],
                ],
                [
                    "code" => "E",
                    "title" => "E",
                    "upper" => [200, 201, 202, 203, 274, 276, 278, 280, 282],
                    "lower" => [232, 233, 234, 235, 275, 277, 279, 281, 283],
                ],
                [
                    "code" => "I",
                    "title" => "I",
                    "upper" => [204, 205, 206, 207, 296, 298, 300, 302, 304],
                    "lower" => [236, 237, 238, 239, 297, 299, 301, 303, 305],
                ],
                [
                    "code" => "O",
                    "title" => "O",
                    "upper" => [210, 211, 212, 213, 214, 216, 332, 334, 336, 338],
                    "lower" => [242, 243, 244, 245, 246, 248, 333, 335, 337, 339],
                ],
                [
                    "code" => "U",
                    "title" => "U",
                    "upper" => [217, 218, 219, 220, 362, 368],
                    "lower" => [249, 250, 251, 252, 363, 369],
                ],
                [
                    "code" => "+",
                    "title" => _('More'),
                    "upper" => [0x24, 0xa3, 0xa5, 0xa1, 0xa9, 0xab, 0x201e, 0x20, 0x1f6, 0xdd, 0x20, 0xc7, 0xd0, 0xd1, 0xde, 0xa6, 0xaa, 0xba, 0xb4, 0xa8, 0xaf, 0xb8, 0xb0, 0xb9, 0xb2, 0xb3],
                    "lower" => [0xa2, 0xa4, 0x20, 0xbf, 0xae, 0xbb, 0x201c, 0xdf, 0x195, 0xfd, 0xff, 0xe7, 0xf0, 0xf1, 0xfe, 0xa7, 0xbc, 0xbd, 0xbe, 0xb6, 0xac, 0xb7, 0xb1, 0xd7, 0xf7, 0xb5],
                ],
                [
                    "code" => 'CD',
                    "title" => 'CD',
                    "upper" => [199, 262, 264, 266, 268, 390, 391, 208, 270, 272, 393, 394],
                    "lower" => [231, 263, 265, 267, 269,  32, 392, 240, 271, 273,  32,  32],
                ],
                [
                    "code" => 'LN',
                    "title" => 'LN',
                    "upper" => [313, 315, 317, 319, 321, 209, 323, 325, 327,  32, 330],
                    "lower" => [314, 316, 318, 320, 322, 241, 324, 326, 328, 329, 331],
                ],
                [
                    "code" => 'RS',
                    "title" => 'RS',
                    "upper" => [340, 342, 344,  32, 346, 348, 350, 352],
                    "lower" => [341, 343, 345, 223, 347, 349, 351, 353],
                ],
                [
                    "code" => 'TZ',
                    "title" => 'TZ',
                    "upper" => [354, 356, 358, 377, 379, 381],
                    "lower" => [355, 357, 359, 378, 380, 382],
                ],
                [
                    "code" => "Cyr",
                    "title" => _('Cyrillic'),
                    "upper" => [1026, 1027, 1024, 1025, 1028, 1029, 1037, 1030, 1031, 1049, 1032, 1033, 1034, 1035, 1036, 1038, 1039, 1065, 1066, 1067, 1068, 1069, 1070, 1071],
                    "lower" => [1106, 1107, 1104, 1105, 1108, 1109, 1117, 1110, 1111, 1081, 1112, 1113, 1114, 1115, 1116, 1118, 1119, 1097, 1098, 1099, 1100, 1101, 1102, 1103],
                ],
                [
                    "code" => "OCyr",
                    "title" => _('OldCyrillic'),
                    "upper" => [1120, 1122, 1124, 1126, 1128, 1130, 1132, 1134, 1136, 1138, 1140, 1142, 1144, 1146, 1148, 1150, 1152, 1154],
                    "lower" => [1121, 1123, 1125, 1127, 1129, 1131, 1133, 1135, 1137, 1139, 1141, 1143, 1145, 1147, 1149, 1151, 1153,   32],
                ],
            ];
        }
        else
        {
            $this->picker_set =
            [
                [
                    "code" => "A",
                    "title" => _("A accents"),
                    "upper" => [0xc0, 0xc1, 0xc2, 0xc3, 0xc4, 0xc5, 0xc6],
                    "lower" => [0xe0, 0xe1, 0xe2, 0xe3, 0xe4, 0xe5, 0xe6],
                ],
                [
                    "code" => "E",
                    "title" => _("E accents"),
                    "upper" => [200, 201, 202, 203],
                    "lower" => [232, 233, 234, 235],
                ],
                [
                    "code" => "I",
                    "title" => _("I accents"),
                    "upper" => [204, 205, 206, 207],
                    "lower" => [236, 237, 238, 239],
                ],
                [
                    "code" => "O",
                    "title" => _("O accents"),
                    "upper" => [210, 211, 212, 213, 214, 216],
                    "lower" => [242, 243, 244, 245, 246, 248],
                ],
                [
                    "code" => "U",
                    "title" => _("U accents"),
                    "upper" => [217, 218, 219, 220],
                    "lower" => [249, 250, 251, 252],
                ],
                [
                    "code" => "$",
                    "title" => _('More letters and punctuation'),
                    "upper" => [0x24, 0xa3, 0xa5, 0xa1, 0xab, 0x20, 0xdd, 0x20, 0xc7, 0xd0, 0xd1, 0xde],
                    "lower" => [0xa2, 0xa4, 0x20, 0xbf, 0xbb, 0xdf, 0xfd, 0xff, 0xe7, 0xf0, 0xf1, 0xfe],
                ],
                [
                    "code" => "¶",
                    "title" => _('More symbols'),
                    "upper" => [0xa9, 0xa6, 0xaa, 0xba, 0xb4, 0xa8, 0xaf, 0xb8, 0xb0, 0xb9, 0xb2, 0xb3],
                    "lower" => [0xae, 0xa7, 0xbc, 0xbd, 0xbe, 0xb6, 0xac, 0xb7, 0xb1, 0xd7, 0xf7, 0xb5],
                ],
            ];
        }
    }

    function draw()
    {
        $selector_string = "<div id='selector_row'>";
        $row_string = "";


        foreach ($this->picker_set as $picker)
        {
            $code = maybe_utf8_decode($picker["code"]);
            $safe_code = bin2hex($code);
            $title = maybe_utf8_decode($picker['title']);
            $selector_string .= "<button type='button' id='_$safe_code' class='selector_button' title='$title'>" . html_safe($code) . "</button>";
            $row_string .= "<div class='_$safe_code key-block'>\n";
            $row_string .= $this->draw_row($picker["upper"]);
            $row_string .= $this->draw_row($picker["lower"]);
            $row_string .= "</div>\n";
        }

        $selector_string .= "</div>\n";

        echo "<div class='char_box'><input type='text' readonly class='big_text' id='large_char'></div>\n";
        echo "<div id='char-selector' class='nowrap'>$selector_string$row_string</div>\n";
    }

    function draw_row($chars)
    {

        $row = "<div class='table-row'>";

        foreach($chars as $character)
        {
            $row .= "<div class='table-cell'>";
            if (0x20 == $character)
            {
                $row .= "<button type='button' class='picker invisible'></button>";
            }
            else
            {
                $title_string = attr_safe(IntlChar::charName($character));
                $row .= "<button type='button' class='picker' title='$title_string'>" . html_safe(maybe_utf8_decode(IntlChar::chr($character))) . "</button>";
            }
            $row .= "</div>";
        }
        return $row . "</div>\n";
    }
}

function maybe_utf8_decode($char)
{
    global $utf8_site;

    if(!$utf8_site)
    {
        $char = utf8_decode($char);
    }
    return $char;
}

// vim: sw=4 ts=4 expandtab
