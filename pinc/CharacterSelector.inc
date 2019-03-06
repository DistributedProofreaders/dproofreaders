<?php
include_once($relPath.'site_vars.php'); // $wikihiero_dir $utf8_site
include_once($relPath.'misc.inc'); // attr_safe(), html_safe()

class CharacterSelector
{
    public function __construct()
    {
    }
    
    private function echo_selector( $id, $title, $option_args )
    {
        global $utf8_site;
    
        echo '<td class="right-align">';
        $title_safe = attr_safe($title);
        echo <<<EOS
    <select
        name="tChars{$id}"
        ID="tChars{$id}"
        title="{$title_safe}"
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
    
    public function echo_block()
    {
        echo "<table style='margin-left: auto; margin-right: 0;'>\n";
    
        // START OF ROW 1
        echo "<tr>\n";
    
        $this->echo_selector(
            'A',
            'A',
            array(
                'A',
                '--',
                array( 192, 224, 193, 225, 194, 226, 195, 227, 196, 228, 197, 229, 198, 230 ),
                array( 256, 257, 258, 259, 260, 261 ),
            )
        );
    
        $this->echo_selector(
            'E',
            'E',
            array(
                'E',
                '--',
                array( 200, 232, 201, 233, 202, 234, 203, 235 ),
                array( 274, 275, 276, 277, 278, 279, 280, 281, 282, 283 ),
            )
        );
    
        $this->echo_selector(
            'I',
            'I',
            array(
                'I',
                '--',
                array( 204, 236, 205, 237, 206, 238, 207, 239 ),
                array( 296, 297, 298, 299, 300, 301, 302, 303, 304, 305 ),
            )
        );
    
        $this->echo_selector(
            'O',
            'O',
            array(
                'O',
                '--',
                array( 210, 242, 211, 243, 212, 244, 213, 245, 214, 246, 216, 248 ),
                array( 332, 333, 334, 335, 336, 337, 338, 339 ),
            )
        );
    
        $this->echo_selector(
            'U',
            'U',
            array(
                'U',
                '--',
                array( 217, 249, 218, 250, 219, 251, 220, 252 ),
                array( 362, 363, 368, 369 ),
            )
        );
    
        $this->echo_selector(
            'M',
            _('More'),
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
                array( 166, 167, 168, 170, 172, 175, 176, 177, 178, 179, 180, 182, 183, 184, 185, 186, 188, 189, 190, 215, 247, 181 ),
            )
        );
    
        global $utf8_site;
    
        ?>
            <td class="top-align" <?php if($utf8_site) echo "rowspan='2'"; ?>>
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
        <?php
    
        echo "</tr>\n";
        // END OF ROW 1
    
        if ($utf8_site)
        {
            // START OF ROW 2
            echo "<tr>\n";
    
            $this->echo_selector(
                'C',
                'CD',
                array(
                    'CD',
                    '--',
                    array( 199, 231, 262, 263, 264, 265, 266, 267, 268, 269, 390, 391, 208, 240, 270, 271, 272, 273, 393, 394 ),
                )
            );
    
            $this->echo_selector(
                'D',
                'LN',
                array(
                    'LN',
                    '--',
                    array( 313, 314, 315, 316, 317, 318, 319, 320, 321, 322, 209, 241, 323, 324, 325, 326, 327, 328, 329, 330, 331 ),
                )
            );
    
            $this->echo_selector(
                'S',
                'RS',
                array(
                    'RS',
                    '--',
                    array( 340, 341, 342, 343, 344, 345, 223, 346, 347, 348, 349, 350, 351, 352, 353 ),
                )
            );
    
            $this->echo_selector(
                'Z',
                'TZ',
                array(
                    'TZ',
                    '--',
                    array( 354, 355, 356, 357, 358, 359, 377, 378, 379, 380, 381, 382 ),
                )
            );
    
            $this->echo_selector(
                'Cyr',
                _('Cyrillic'),
                array(
                    '&#1035;',
                    '--',
                    array( 1026, 1106, 1027, 1107, 1024, 1104, 1025, 1105, 1028, 1108, 1029, 1109, 1037, 1117, 1030, 1110, 1031, 1111, 1049, 1081, 1032, 1112, 1033, 1113, 1034, 1114, 1035, 1115, 1036, 1116, 1038, 1118, 1039, 1119, 1065, 1097, 1066, 1098, 1067, 1099, 1068, 1100, 1069, 1101, 1070, 1102, 1071, 1103 ),
                )
            );
    
            $this->echo_selector(
                'OCyr',
                _('OldCyrillic'),
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
}
    
// vim: sw=4 ts=4 expandtab
