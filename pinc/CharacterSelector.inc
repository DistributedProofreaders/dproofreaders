<?php

include_once($relPath.'prefs_options.inc'); // get_user_proofreading_font()

class CharacterSelector
{
    function __construct ()
    {
        $this->picker_set =
        [
            [
                "code" => "A",
                "upper" => "ÀÁÂÃÄÅÆ",
                "lower" => "àáâãäåæ",
            ],
            [
                "code" => "E",
                "upper" => "ÈÉÊË",
                "lower" => "èéêë",
            ],
            [
                "code" => "I",
                "upper" => "ÌÍÎÏ",
                "lower" => "ìíîï",
            ],
            [
                "code" => "O",
                "upper" => "ÒÓÔÕÖØ",
                "lower" => "òóôõöø",
            ],
            [
                "code" => "U",
                "upper" => "ÙÚÛÜ",
                "lower" => "ùúûü",
            ],
            [
                "code" => "+",
                "upper" => '$£¥¡©« Ý ÇÐÑÞ¦ªº´¨¯¸°¹²³',
                "lower" => "¢¤ ¿®»ßýÿçðñþ§¼½¾¶¬·±×÷µ",
            ],
        ];

        $this->key_titles =
        [
            '¶' => _('pilcrow'),
            'µ' => _('micro sign'),
            '°' => _('degree sign'),
            'º' => _('masculine ordinal'),
            'ª' => _('feminine ordinal'),
            '·' => _('mid-dot'),
            '£' => _('pound sign'),
            '¤' => _('currency sign'),
            '¦' => _('broken bar'),
            'Ð' => _('capital eth'),
            'Þ' => _('capital thorn'),
            'ß' => _('sharp s'),
            'ð' => _('small eth'),
            'þ' => _('small thorn'),
        ];
    }

    function draw()
    {
        list($font_face, $font_size) = get_user_proofreading_font();
        if (!$font_face)
        {
            // browser default - this should then be same as proofreading textarea
            $font_face = "monospace";
        }

        $selector_string = "<div id='selector_row'>";
        $row_string = "";


        foreach ($this->picker_set as $picker)
        {
            $code = maybe_utf8_decode($picker["code"]);
            $safe_code = bin2hex($code);
            $selector_string .= "<button type='button' id='_$safe_code' class='selector_button'>" . html_safe($code) . "</button>";
            $row_string .= "<div class='_$safe_code key-block'>\n";
            $row_string .= $this->draw_row($picker["upper"]);
            $row_string .= $this->draw_row($picker["lower"]);
            $row_string .= "</div>\n";
        }

        $selector_string .= "</div>\n";

        echo "<div class='char_box' style='font-family: $font_face;'><input type='text' readonly class='big_text' id='large_char'></div>\n";
        echo "<div id='char-selector' class='nowrap' style='font-family: $font_face;'>$selector_string$row_string</div>\n";
    }

    function draw_row($char_row)
    {

        $row = "<div>";

        // this gives an empty array for an empty string
        $chars = preg_split('//u', $char_row, -1, PREG_SPLIT_NO_EMPTY);

        foreach($chars as $character)
        {
            if (" " == $character)
            {
                $row .= "<button type='button' class='picker invisible'></button>";
            }
            else
            {
                if (array_key_exists($character, $this->key_titles))
                {
                    $title = $this->key_titles[$character];
                    $title_string = " title='" . attr_safe($title) . "'";
                }
                else
                {
                    $title_string = "";
                }
                $row .= "<button type='button' class='picker'$title_string>" . html_safe(maybe_utf8_decode($character)) . "</button>";
            }
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
