<?PHP
$relPath="./../../pinc/";
include($relPath.'doctype.inc');
echo $docType. "\r\n";
?>
<html>

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
<td colspan="2">
<img Src="gfx/greek.png" height="80" width="600" usemap="#charmap" border="0">
</td>
</tr>

<tr>
<td valign="top">
<form name="greek" action="none">
<input type="text" name="textbox" length="80" size="72">
</td>
<td align="right" valign="top">
<input type=button value="Clear" onClick="clearBox();">
</td>
</form>
</tr>

<tr>
<td colspan="3">
<p class="info">
Diacritical marks may be ignored except for the rough-breathing mark,
denoted as <IMG SRC="gfx/greekrough.png" height="12" width="10"> above the
letter.<br>
For these, put an 'h' before the letter <emp>unless</emp> the word
begins with 'r', in which case the 'h' goes <emp>after</emp> the 'r'.
</p>
</td>
</tr>

<tr>
<td colspan="3">
For further information, see the Project Gutenberg 
<a HREF="http://www.gutenberg.net/vol/greek.html" target="_new">
Greek-ASCII Primer</a>.
<br>
<a HREF="#" onclick="window.close()"><b>Close</b></a>
</td>
</tr>

</table>

<map name="charmap">

<!-- Uppercase -->
<area shape="rect" coords="  5,8, 21,25" href="javascript:addChar('A')" alt="Alpha">
<area shape="rect" coords=" 21,8, 40,25" href="javascript:addChar('B')" alt="Beta">
<area shape="rect" coords=" 41,8, 58,25" href="javascript:addChar('G')" alt="Gamma">
<area shape="rect" coords=" 59,8, 76,25" href="javascript:addChar('D')" alt="Delta">
<area shape="rect" coords=" 76,8, 92,25" href="javascript:addChar('E')" alt="Epsilon">
<area shape="rect" coords=" 93,8,110,25" href="javascript:addChar('Z')" alt="Zeta">
<area shape="rect" coords="111,8,130,25" href="javascript:addChar('Ae')" alt="Eta">
<area shape="rect" coords="131,8,158,25" href="javascript:addChar('Th')" alt="Theta">
<area shape="rect" coords="159,8,170,25" href="javascript:addChar('I')" alt="Iota">
<area shape="rect" coords="171,8,188,25" href="javascript:addChar('K')" alt="Kappa">
<area shape="rect" coords="189,8,206,25" href="javascript:addChar('L')" alt="Lambda">
<area shape="rect" coords="207,8,228,25" href="javascript:addChar('M')" alt="Mu">
<area shape="rect" coords="229,8,246,25" href="javascript:addChar('N')" alt="Nu">
<area shape="rect" coords="247,8,264,25" href="javascript:addChar('X')" alt="Xi">
<area shape="rect" coords="265,8,282,25" href="javascript:addChar('O')" alt="Omicron">
<area shape="rect" coords="283,8,303,25" href="javascript:addChar('P')" alt="Pi">
<area shape="rect" coords="304,8,320,25" href="javascript:addChar('R')" alt="Rho">
<area shape="rect" coords="321,8,350,25" href="javascript:addChar('S')" alt="Sigma">
<area shape="rect" coords="351,8,369,25" href="javascript:addChar('T')" alt="Tau">
<area shape="rect" coords="370,8,393,25" href="javascript:addChar('U')" alt="Upsilon">
<area shape="rect" coords="394,8,421,25" href="javascript:addChar('Ph')" alt="Phi">
<area shape="rect" coords="422,8,442,25" href="javascript:addChar('Ch')" alt="Chi">
<area shape="rect" coords="443,8,462,25" href="javascript:addChar('Ps')" alt="Psi">
<area shape="rect" coords="463,8,480,25" href="javascript:addChar('O')" alt="Omega">

<!-- Lowercase -->
<area shape="rect" coords="  5,27, 21,48" href="javascript:addChar('a')" alt="alpha">
<area shape="rect" coords=" 21,27, 40,48" href="javascript:addChar('b')" alt="beta">
<area shape="rect" coords=" 41,27, 58,48" href="javascript:addChar('g')" alt="gamma">
<area shape="rect" coords=" 59,27, 76,48" href="javascript:addChar('d')" alt="delta">
<area shape="rect" coords=" 76,27, 92,48" href="javascript:addChar('e')" alt="epsilon">
<area shape="rect" coords=" 93,27,110,48" href="javascript:addChar('z')" alt="zeta">
<area shape="rect" coords="111,27,130,48" href="javascript:addChar('ae')" alt="eta">
<area shape="rect" coords="131,27,158,48" href="javascript:addChar('th')" alt="theta">
<area shape="rect" coords="159,27,170,48" href="javascript:addChar('i')" alt="iota">
<area shape="rect" coords="171,27,188,48" href="javascript:addChar('k')" alt="kappa">
<area shape="rect" coords="189,27,206,48" href="javascript:addChar('l')" alt="lambda">
<area shape="rect" coords="207,27,228,48" href="javascript:addChar('m')" alt="mu">
<area shape="rect" coords="229,27,246,48" href="javascript:addChar('n')" alt="nu">
<area shape="rect" coords="247,27,264,48" href="javascript:addChar('x')" alt="xi">
<area shape="rect" coords="265,27,282,48" href="javascript:addChar('o')" alt="omicron">
<area shape="rect" coords="283,27,303,48" href="javascript:addChar('p')" alt="pi">
<area shape="rect" coords="304,27,320,48" href="javascript:addChar('r')" alt="rho">
<area shape="rect" coords="321,27,350,48" href="javascript:addChar('s')" alt="sigma">
<area shape="rect" coords="351,27,369,48" href="javascript:addChar('t')" alt="tau">
<area shape="rect" coords="370,27,393,48" href="javascript:addChar('u')" alt="upsilon">
<area shape="rect" coords="394,27,421,48" href="javascript:addChar('ph')" alt="phi">
<area shape="rect" coords="422,27,442,48" href="javascript:addChar('ch')" alt="chi">
<area shape="rect" coords="443,27,462,48" href="javascript:addChar('ps')" alt="psi">
<area shape="rect" coords="463,27,480,48" href="javascript:addChar('o')" alt="omega">

<!-- Gamma exceptions -->
<area shape="rect" coords="488,27,511,48" href="javascript:addChar('ng')" alt="gamma (ng)">
<area shape="rect" coords="512,27,538,48" href="javascript:addChar('nk')" alt="gamma (nk)">
<area shape="rect" coords="539,27,564,48" href="javascript:addChar('nx')" alt="gamma (nx)">
<area shape="rect" coords="565,27,594,48" href="javascript:addChar('nch')" alt="gamma (nch)">

<area shape="default" nohref>
</map>

</body>
</html>

