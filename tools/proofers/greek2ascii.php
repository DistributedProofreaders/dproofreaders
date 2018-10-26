<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'pg.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'faq.inc');
include_once($relPath.'misc.inc'); // attr_safe()

require_login();

$greek_contents = @$_GET['textbox'];

$title = _("Greek to Latin-1 Transliteration");
$header_args = array(
    "js_data" => "
        function clearBox() {
            document.greek.textbox.value = '';
            document.greek.textbox.focus();
        }

        function addChar(myChar) {
            document.greek.textbox.value += myChar;
            document.greek.textbox.focus();
            // Following line is Opera focus+highlight workaround
            document.greek.textbox.value += '';
        }
    ",
);
slim_header($title, $header_args);
?>
<table border="0" cellspacing="0" cellpadding="0" width="600">

<tr>
<td colspan="3">

<img name="greekimg" 

<?php
// Use the form x/y coords for image button to see which one was pressed.
// Later, we can add other glyphsets and image maps, for alternate glyphs.

if ( @$_REQUEST['italic_y'] != 0 || @$_REQUEST['italic_x'] != 0 )
 { echo "src=\"gfx/igreek.png\""; }
else
 { echo "src=\"gfx/greek.png\""; }
?>

 height="80" width="600" usemap="#charmap" border="0">
</td>
</tr>

<tr>
<td class="top-align">
<form name="greek" action="<?php echo attr_safe($_SERVER['PHP_SELF']); ?>">
<input type="text" name="textbox" length="65" size="65"
<?php echo "value=\"" . attr_safe($greek_contents) . "\""; ?>
>
</td>
<td class="right-align top-align">
<input type=button value="<?php echo attr_safe(_("Clear"))?>" title="<?php
echo attr_safe(_("Clear"))?>" onClick="clearBox();">
</td>
<td class="right-align top-align">

<!-- Pretty little mode buttons, all in a row -->
<table cellspacing="0" cellpadding="0" border=0 >
<tr><td>
<input type="image" name="normal" border="0" src="gfx/icon-n.png" alt="<?php
echo attr_safe(_("Normal"))?>" title="<?php echo attr_safe(_("Normal Glyphs"))?>">
</td><td>
<input type="image" name="italic" border="0" src="gfx/icon-i.png" alt="<?php
echo attr_safe(_("Italic"))?>" title="<?php echo attr_safe(_("Italic Glyphs"))?>">
</td></tr>
</table>

</form>
</td>
</tr>

<tr>
<td colspan="3">
<?php
echo _("The Greek glyphs above are <b>clickable</b>.") . "<br>";
// TRANSLATORS: %s is an image of a rough-breathing mark.
echo sprintf(_("Diacritical marks may be ignored except for the rough-breathing mark, (%s) above the letter."),
    "<img src='gfx/greekrough.png' height='12' width='10'>") . "<br>";
echo _("For these, put '<code>h</code>' before the letter <em>unless</em> the word begins with '<code>r</code>'. For those, put '<code>h</code>' <em>after</em> the '<code>r</code>'.");
?>
</td>
</tr>

<tr>
<td colspan="3">
<?php
$url = get_faq_url('transliterating-greek');

// In case get_faq_url fails to find the url, default to the Gutenberg page
if (!$url)
    echo sprintf( _("Please read the Project Gutenberg <a href='%s' target='_new'>Greek HOWTO</a> for more information."), $PG_greek_howto_url);
else
    echo sprintf( _("Please read the <a href='%s' target='_new'>Transliterating Greek</a> documentation for more information."), $url);
?>
<br>
<a href="#" onclick="window.close()"><b><?php echo _("Close"); ?></b></a>
</td>
</tr>

</table>

<map name="charmap">

<!-- Uppercase -->
<area shape="rect" coords="  5,8, 21,25" href="javascript:addChar('A')" title="<?php echo attr_safe(_("Alpha")); ?>">
<area shape="rect" coords=" 21,8, 40,25" href="javascript:addChar('B')" title="<?php echo attr_safe(_("Beta")); ?>">
<area shape="rect" coords=" 41,8, 58,25" href="javascript:addChar('G')" title="<?php echo attr_safe(_("Gamma")); ?>">
<area shape="rect" coords=" 59,8, 76,25" href="javascript:addChar('D')" title="<?php echo attr_safe(_("Delta")); ?>">
<area shape="rect" coords=" 76,8, 92,25" href="javascript:addChar('E')" title="<?php echo attr_safe(_("Epsilon")); ?>">
<area shape="rect" coords=" 93,8,110,25" href="javascript:addChar('Z')" title="<?php echo attr_safe(_("Zeta")); ?>">
<area shape="rect" coords="111,8,130,25" href="javascript:addChar('Ê')" title="<?php echo attr_safe(_("Eta")); ?>">
<area shape="rect" coords="131,8,158,25" href="javascript:addChar('Th')" title="<?php echo attr_safe(_("Theta")); ?>">
<area shape="rect" coords="159,8,170,25" href="javascript:addChar('I')" title="<?php echo attr_safe(_("Iota")); ?>">
<area shape="rect" coords="171,8,188,25" href="javascript:addChar('K')" title="<?php echo attr_safe(_("Kappa")); ?>">
<area shape="rect" coords="189,8,206,25" href="javascript:addChar('L')" title="<?php echo attr_safe(_("Lambda")); ?>">
<area shape="rect" coords="207,8,228,25" href="javascript:addChar('M')" title="<?php echo attr_safe(_("Mu")); ?>">
<area shape="rect" coords="229,8,246,25" href="javascript:addChar('N')" title="<?php echo attr_safe(_("Nu")); ?>">
<area shape="rect" coords="247,8,264,25" href="javascript:addChar('X')" title="<?php echo attr_safe(_("Xi")); ?>">
<area shape="rect" coords="265,8,282,25" href="javascript:addChar('O')" title="<?php echo attr_safe(_("Omicron")); ?>">
<area shape="rect" coords="283,8,303,25" href="javascript:addChar('P')" title="<?php echo attr_safe(_("Pi")); ?>">
<area shape="rect" coords="304,8,320,25" href="javascript:addChar('R')" title="<?php echo attr_safe(_("Rho")); ?>">
<area shape="rect" coords="321,8,350,25" href="javascript:addChar('S')" title="<?php echo attr_safe(_("Sigma")); ?>">
<area shape="rect" coords="351,8,369,25" href="javascript:addChar('T')" title="<?php echo attr_safe(_("Tau")); ?>">
<area shape="rect" coords="370,8,393,25" href="javascript:addChar('U')" title="<?php echo attr_safe(_("Upsilon")); ?>">
<area shape="rect" coords="394,8,421,25" href="javascript:addChar('Ph')" title="<?php echo attr_safe(_("Phi")); ?>">
<area shape="rect" coords="422,8,442,25" href="javascript:addChar('Ch')" title="<?php echo attr_safe(_("Chi")); ?>">
<area shape="rect" coords="443,8,462,25" href="javascript:addChar('Ps')" title="<?php echo attr_safe(_("Psi")); ?>">
<area shape="rect" coords="463,8,480,25" href="javascript:addChar('Ô')" title="<?php echo attr_safe(_("Omega")); ?>">

<!-- Lowercase -->
<area shape="rect" coords="  5,27, 21,48" href="javascript:addChar('a')" title="<?php echo attr_safe(_("alpha")); ?>">
<area shape="rect" coords=" 21,27, 40,48" href="javascript:addChar('b')" title="<?php echo attr_safe(_("beta")); ?>">
<area shape="rect" coords=" 41,27, 58,48" href="javascript:addChar('g')" title="<?php echo attr_safe(_("gamma")); ?>">
<area shape="rect" coords=" 59,27, 76,48" href="javascript:addChar('d')" title="<?php echo attr_safe(_("delta")); ?>">
<area shape="rect" coords=" 76,27, 92,48" href="javascript:addChar('e')" title="<?php echo attr_safe(_("epsilon")); ?>">
<area shape="rect" coords=" 93,27,110,48" href="javascript:addChar('z')" title="<?php echo attr_safe(_("zeta")); ?>">
<area shape="rect" coords="111,27,130,48" href="javascript:addChar('ê')" title="<?php echo attr_safe(_("eta")); ?>">
<area shape="rect" coords="131,27,158,48" href="javascript:addChar('th')" title="<?php echo attr_safe(_("theta")); ?>">
<area shape="rect" coords="159,27,170,48" href="javascript:addChar('i')" title="<?php echo attr_safe(_("iota")); ?>">
<area shape="rect" coords="171,27,188,48" href="javascript:addChar('k')" title="<?php echo attr_safe(_("kappa")); ?>">
<area shape="rect" coords="189,27,206,48" href="javascript:addChar('l')" title="<?php echo attr_safe(_("lambda")); ?>">
<area shape="rect" coords="207,27,228,48" href="javascript:addChar('m')" title="<?php echo attr_safe(_("mu")); ?>">
<area shape="rect" coords="229,27,246,48" href="javascript:addChar('n')" title="<?php echo attr_safe(_("nu")); ?>">
<area shape="rect" coords="247,27,264,48" href="javascript:addChar('x')" title="<?php echo attr_safe(_("xi")); ?>">
<area shape="rect" coords="265,27,282,48" href="javascript:addChar('o')" title="<?php echo attr_safe(_("omicron")); ?>">
<area shape="rect" coords="283,27,303,48" href="javascript:addChar('p')" title="<?php echo attr_safe(_("pi")); ?>">
<area shape="rect" coords="304,27,320,48" href="javascript:addChar('r')" title="<?php echo attr_safe(_("rho")); ?>">
<area shape="rect" coords="321,27,350,48" href="javascript:addChar('s')" title="<?php echo attr_safe(_("sigma")); ?>">
<area shape="rect" coords="351,27,369,48" href="javascript:addChar('t')" title="<?php echo attr_safe(_("tau")); ?>">
<area shape="rect" coords="370,27,393,48" href="javascript:addChar('u')" title="<?php echo attr_safe(_("upsilon")); ?>">
<area shape="rect" coords="394,27,421,48" href="javascript:addChar('ph')" title="<?php echo attr_safe(_("phi")); ?>">
<area shape="rect" coords="422,27,442,48" href="javascript:addChar('ch')" title="<?php echo attr_safe(_("chi")); ?>">
<area shape="rect" coords="443,27,462,48" href="javascript:addChar('ps')" title="<?php echo attr_safe(_("psi")); ?>">
<area shape="rect" coords="463,27,480,48" href="javascript:addChar('ô')" title="<?php echo attr_safe(_("omega")); ?>">

<!-- Gamma exceptions -->
<area shape="rect" coords="488,27,511,48" href="javascript:addChar('ng')" title="<?php echo attr_safe(_("gamma")." "._("gamma")); ?>">
<area shape="rect" coords="512,27,538,48" href="javascript:addChar('nk')" title="<?php echo attr_safe(_("gamma")." "._("kappa")); ?>">
<area shape="rect" coords="539,27,564,48" href="javascript:addChar('nx')" title="<?php echo attr_safe(_("gamma")." "._("xi")); ?>">
<area shape="rect" coords="565,27,594,48" href="javascript:addChar('nch')" title="<?php echo attr_safe(_("gamma")." "._("chi")); ?>">

<area shape="default" nohref>
</map>
<?php
// vim: sw=4 ts=4 expandtab
