<?php
$relPath="./../../pinc/";
include_once($relPath.'http_headers.inc');
//include_once($relPath.'dp_main.inc');
include_once($relPath.'v_site.inc');
include_once($relPath.'stages.inc');
include_once('toolbox.inc');

$round_id = @$_GET['round_id'];
assert( !empty($round_id) );
$round = get_Round_for_round_id($round_id);
assert( !is_null($round) );

/*
include_once($relPath.'v_resolution.inc');
$i_r= $i_resolutions;
$wSize=explode("x",$i_r[$userP['i_res']*1]);
$menuWidth=$wSize[0]<=800?'99%':'820';
*/

$utf8_site=!strcasecmp($charset,"UTF-8");

include_once($relPath.'slim_header.inc');
slim_header("Control Frame",TRUE,FALSE);

function echo_character_selector( $id, $title, $option_args )
{
    global $utf8_site;

    echo '<td align="right">';
    echo <<<EOS
<select
    name="tChars{$id}"
    ID="tChars{$id}"
    title="{$title}"
    onchange="if (this.options[selectedIndex].value !=0){top.new_iMUc(this.options[selectedIndex].value);}"
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

?>
<style type="text/css">
<!--
table { margin: 0; padding: 0; }
body {
  font-family: verdana, arial, helvetica, sans-serif;
  font-size: 12px;
  color:#000000;
  background-color:#CDC0B0;
  padding:0px;
  text-align:center;
  }
A:link {
  color:#000000;
  text-decoration : none;
  }
A:visited {
  color:#000000;
  text-decoration : none;
  }
A:hover {
  color:#003300;
  text-decoration : none;
  }
A:active {
  color:#000033;
  text-decoration : none;
  }
.dropsmall {
  font-size: 75%;
  background-color:#FFF8DC;
  }
.dropnormal {
  background-color:#FFF8DC;
  }
.dropchars {
  background-color:#EEDFCC;
//#CDCDC1;
//#EEDFCC;
  }
.proofbutton {
border:1px solid black;
text-align: center;
background: #FFF8DC;
display:inline;
margin: 0 1px 1px 0;
<? if(!stristr($_SERVER['HTTP_USER_AGENT'],"msie"))
     echo "line-height:140%;\n"; ?>
padding-top: 1px;
cursor: pointer;
}

-->
</style>
</head>
<body>
<a
	href="#"
	accesskey="="
	onfocus="top.focusText()"
></a><form
	name="markform"
	id="markform"
	onsubmit="return(false);"
	action="ctrl_frame.php"
><table
	cellpadding="0"
	cellspacing="0"
	align="center"
	width="99%"
	border="0"
><tr><td
	valign="top"
	<? if($utf8_site) echo "rowspan=\"2\""; ?>
><table
	border="0"
	cellpadding="0"
	cellspacing="0"
><tr><?

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

?><td valign="top" <? if($utf8_site) echo "rowspan='2'"; ?>><input
	TYPE="text"
	VALUE=""
	accesskey="\"
	name="markBoxChar"
	class="dropnormal"
	size="1"
	onclick="this.select()"
></td></tr>
<?
if ($utf8_site)
{
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
}
?></table>
<center><font size=-1><?

echo "<i>", _('Pop-up tools'), ":</i>\n";
echo_popup_links( $round->pi_tools['popup_links'] );

?></font></center>
</td><td
	valign="top"
	align="center"
><INPUT
	accesskey="["
	TYPE="text"
	VALUE=""
	name="markBox"
	class="dropnormal"
	size="9"
	onclick="this.select();"
><INPUT
	accesskey="]"
	TYPE="text"
	VALUE=""
	name="markBoxEnd"
	class="dropnormal"
	size="9"
	onclick="this.select()"
>
<?

echo_tool_buttons( $round->pi_tools['tool_buttons'] );

/* temp disabled
<a
href="#" onclick="top.iMU(23)"><img
src="gfx/tags/underline.png" width="22" height="22" border="0" align="top" title="underline" alt="underline"></a><a
href="#" onclick="top.iMU(24)"><img
src="gfx/tags/caps.png" width="42" height="22" border="0" align="top" title="caps" alt="caps"></a><a
href="#" onclick="top.iMU(25)"><img
src="gfx/tags/sup.png" width="22" height="22" border="0" align="top" title="superscript" alt="superscript"></a><a
href="#" onclick="top.iMU(26)"><img
src="gfx/tags/sub.png" width="22" height="22" border="0" align="top" title="subscript" alt="subscript"></a>
*/

?></td><td
	align="right"
	valign="top">
<?
echo "<b><font color='red'>"._("HELP")."---&gt;</font></b>";
?>
      <a
	href="../../faq/<? echo lang_dir(); ?>prooffacehelp.php"
	accesskey="1"
	target="helpNewWin"
><img
	src="gfx/tags/help.png"
	width="18"
	height="18"
	border="0"
	align="top"
	alt="<? echo _("Help"); ?>"
	title="<? echo _("Help"); ?>"
></a>
</td></tr><tr><td
	valign="top"
	colspan="3"
	align="center">
<?PHP 
echo "<font size=\"-1\"><i>"._("Markup shortcuts").":</i> </font>";
echo "<font size='-1'>\n";
echo_tool_links( $round->pi_tools['tool_links'] );
echo "</font>\n";
?>
<br>
<font size="-1">
<? 
echo "<i>"._("Reference Information").":</i> ";
$url = "$code_url/faq/".lang_dir().$round->document;
echo "<a style=\"color:#0000FF; text-decoration: underline;\" href='$url' target='_blank'>";
echo _('Guidelines');
echo "</a>\n";

echo "<i>"._("Proofreading Diagrams:")."</i>"; ?> [<a style="color:#0000FF; text-decoration: underline;" href='<?php echo $code_url; ?>/faq/ProofingDiagram_HighRes.gif' target='_blank'><? echo _("High Res"); ?></a>] 
[<a style="color:#0000FF; text-decoration: underline;" href='<? echo $code_url; ?>/faq/ProofingDiagram_MedRes.gif' target='_blank'><? echo _("Medium Res"); ?></a>] 
[<a style="color:#0000FF; text-decoration: underline;" href='<? echo $code_url; ?>/faq/ProofingDiagram_LowRes.gif' target='_blank'><? echo _("Low Res"); ?></a>]</font>
</td>
</tr>
</table>
</form>

</body>
</html>
