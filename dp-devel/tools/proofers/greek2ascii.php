<?PHP
$relPath="./../../pinc/";
include($relPath.'doctype.inc');
include($relPath.'pg.inc');
echo $docType. "\r\n";
?>
<html>
<?
	$greek_contents = @$_GET['textbox'];
?>

<!-- Graphics and html/javascript for Greek text conversion
     by D Garcia 12/24/02 for Distributed Proofreaders
     -->

<head>
<title>Greek to ASCII Transliteration</title>
<script language="JavaScript">
<!--
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
//-->
</script>
<style type="text/css">
p.info {
	font-size: 12px;
	}
</style>
</head>

<body>

<table border="0" cellspacing="0" cellpadding="0" width="600">

<tr>
<td colspan="3">

<img name="greekimg" 

<?
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
<td valign="top">
<form name="greek" action="<? echo $_SERVER["PHP_SELF"] ?>">
<input type="text" name="textbox" length="65" size="65"
<? if ($greek_contents != "") { echo "value=\"$greek_contents\""; } ?>
>
</td>
<td align="right" valign="top">
<input type=button value="Clear" title="Clear" onClick="clearBox();">
</td>
<td align="right" valign="top">

<!-- Pretty little mode buttons, all in a row -->
<table cellspacing="0" cellpadding="0" border=0 >
<tr><td>
<input type="image" name="normal" border="0" src="gfx/icon-n.png" alt="Normal" title="Normal Glyphs">
</td><td>
<input type="image" name="italic" border="0" src="gfx/icon-i.png" alt="Italic" title="Italic Glyphs">
</td></tr>
</table>

</form>
</td>
</tr>

<tr>
<td colspan="3">
<p class="info">
The Greek glyphs above are <b>clickable</b>.<br>
Diacritical marks may be ignored except for the rough-breathing mark,
(<img src="gfx/greekrough.png" height="12" width="10">) above the
letter.<br>
For these, put 'h' before the letter <emp>unless</emp> the word
begins with 'r'. For those, put 'h' <emp>after</emp> the 'r'.
</p>
</td>
</tr>

<tr>
<td colspan="3">
Please read the Project Gutenberg 
<a href="<? echo $PG_greek_howto_url; ?>" target="_new">
Greek HOWTO</a> for more information.
<br>
<a href="#" onclick="window.close()"><b>Close</b></a>
</td>
</tr>

</table>

<map name="charmap">

<!-- Uppercase -->
<area shape="rect" coords="  5,8, 21,25" href="javascript:addChar('A')" title="Alpha">
<area shape="rect" coords=" 21,8, 40,25" href="javascript:addChar('B')" title="Beta">
<area shape="rect" coords=" 41,8, 58,25" href="javascript:addChar('G')" title="Gamma">
<area shape="rect" coords=" 59,8, 76,25" href="javascript:addChar('D')" title="Delta">
<area shape="rect" coords=" 76,8, 92,25" href="javascript:addChar('E')" title="Epsilon">
<area shape="rect" coords=" 93,8,110,25" href="javascript:addChar('Z')" title="Zeta">
<area shape="rect" coords="111,8,130,25" href="javascript:addChar('Ê')" title="Eta">
<area shape="rect" coords="131,8,158,25" href="javascript:addChar('Th')" title="Theta">
<area shape="rect" coords="159,8,170,25" href="javascript:addChar('I')" title="Iota">
<area shape="rect" coords="171,8,188,25" href="javascript:addChar('K')" title="Kappa">
<area shape="rect" coords="189,8,206,25" href="javascript:addChar('L')" title="Lambda">
<area shape="rect" coords="207,8,228,25" href="javascript:addChar('M')" title="Mu">
<area shape="rect" coords="229,8,246,25" href="javascript:addChar('N')" title="Nu">
<area shape="rect" coords="247,8,264,25" href="javascript:addChar('X')" title="Xi">
<area shape="rect" coords="265,8,282,25" href="javascript:addChar('O')" title="Omicron">
<area shape="rect" coords="283,8,303,25" href="javascript:addChar('P')" title="Pi">
<area shape="rect" coords="304,8,320,25" href="javascript:addChar('R')" title="Rho">
<area shape="rect" coords="321,8,350,25" href="javascript:addChar('S')" title="Sigma">
<area shape="rect" coords="351,8,369,25" href="javascript:addChar('T')" title="Tau">
<area shape="rect" coords="370,8,393,25" href="javascript:addChar('U')" title="Upsilon">
<area shape="rect" coords="394,8,421,25" href="javascript:addChar('Ph')" title="Phi">
<area shape="rect" coords="422,8,442,25" href="javascript:addChar('Ch')" title="Chi">
<area shape="rect" coords="443,8,462,25" href="javascript:addChar('Ps')" title="Psi">
<area shape="rect" coords="463,8,480,25" href="javascript:addChar('Ô')" title="Omega">

<!-- Lowercase -->
<area shape="rect" coords="  5,27, 21,48" href="javascript:addChar('a')" title="alpha">
<area shape="rect" coords=" 21,27, 40,48" href="javascript:addChar('b')" title="beta">
<area shape="rect" coords=" 41,27, 58,48" href="javascript:addChar('g')" title="gamma">
<area shape="rect" coords=" 59,27, 76,48" href="javascript:addChar('d')" title="delta">
<area shape="rect" coords=" 76,27, 92,48" href="javascript:addChar('e')" title="epsilon">
<area shape="rect" coords=" 93,27,110,48" href="javascript:addChar('z')" title="zeta">
<area shape="rect" coords="111,27,130,48" href="javascript:addChar('ê')" title="eta">
<area shape="rect" coords="131,27,158,48" href="javascript:addChar('th')" title="theta">
<area shape="rect" coords="159,27,170,48" href="javascript:addChar('i')" title="iota">
<area shape="rect" coords="171,27,188,48" href="javascript:addChar('k')" title="kappa">
<area shape="rect" coords="189,27,206,48" href="javascript:addChar('l')" title="lambda">
<area shape="rect" coords="207,27,228,48" href="javascript:addChar('m')" title="mu">
<area shape="rect" coords="229,27,246,48" href="javascript:addChar('n')" title="nu">
<area shape="rect" coords="247,27,264,48" href="javascript:addChar('x')" title="xi">
<area shape="rect" coords="265,27,282,48" href="javascript:addChar('o')" title="omicron">
<area shape="rect" coords="283,27,303,48" href="javascript:addChar('p')" title="pi">
<area shape="rect" coords="304,27,320,48" href="javascript:addChar('r')" title="rho">
<area shape="rect" coords="321,27,350,48" href="javascript:addChar('s')" title="sigma">
<area shape="rect" coords="351,27,369,48" href="javascript:addChar('t')" title="tau">
<area shape="rect" coords="370,27,393,48" href="javascript:addChar('u')" title="upsilon">
<area shape="rect" coords="394,27,421,48" href="javascript:addChar('ph')" title="phi">
<area shape="rect" coords="422,27,442,48" href="javascript:addChar('ch')" title="chi">
<area shape="rect" coords="443,27,462,48" href="javascript:addChar('ps')" title="psi">
<area shape="rect" coords="463,27,480,48" href="javascript:addChar('ô')" title="omega">

<!-- Gamma exceptions -->
<area shape="rect" coords="488,27,511,48" href="javascript:addChar('ng')" title="gamma gamma">
<area shape="rect" coords="512,27,538,48" href="javascript:addChar('nk')" title="gamma kappa">
<area shape="rect" coords="539,27,564,48" href="javascript:addChar('nx')" title="gamma xi">
<area shape="rect" coords="565,27,594,48" href="javascript:addChar('nch')" title="gamma chi">

<area shape="default" nohref>
</map>

</body>
</html>
