<?php
$relPath="./../../pinc/";
include_once($relPath.'dp_main.inc');
include_once($relPath.'doctype.inc');
echo $docType."\r\n";

include_once($relPath.'v_resolution.inc');
$i_r= $i_resolutions;
$wSize=explode("x",$i_r[$userP['i_res']*1]);
$menuWidth=$wSize[0]<=800?'99%':'780';

?>
<html><head><title>Control Frame</title>
<script language="JavaScript" src="dp_proof.js" type="text/javascript"></script>
<script language="JavaScript" src="dp_scroll.js" type="text/javascript"></script>
<style type="text/css">
<!--
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
  font-weight: bold;
  text-decoration : none; 
  }
A:active {
  color:#000033;
  font-weight: bold;
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
-->
</style></head><body><table cellpadding="0" cellspacing="0" 
align="center" width="<?PHP echo $menuWidth; ?>" border="0"><tr><form 
name="charform" id="charform" onsubmit="return(false);"><td valign="top"><select 
name="tCharsA" ID="tCharsA" title="A" onchange="if (this.value !=0){iMUc(this.value);}" class="dropchars">
<option value="0">A</option>
<option value="0">--</option>
<option value="33">&#192;</option>
<option value="65">&#224;</option>
<option value="34">&#193;</option>
<option value="66">&#225;</option>
<option value="35">&#194;</option>
<option value="67">&#226;</option>
<option value="36">&#195;</option>
<option value="68">&#227;</option>
<option value="37">&#196;</option>
<option value="69">&#228;</option>
<option value="38">&#197;</option>
<option value="70">&#229;</option>
<option value="39">&#198;</option>
<option value="71">&#230;</option>
</select><select 
name="tCharsE" ID="tCharsE" title="E" onchange="if (this.value !=0){iMUc(this.value);}" class="dropchars">
<option value="0">E</option>
<option value="0">--</option>
<option value="41">&#200;</option>
<option value="73">&#232;</option>
<option value="42">&#201;</option>
<option value="74">&#233;</option>
<option value="43">&#202;</option>
<option value="75">&#234;</option>
<option value="44">&#203;</option>
<option value="76">&#235;</option>
</select><select 
name="tCharsI" ID="tCharsI" title="I" onchange="if (this.value !=0){iMUc(this.value);}" class="dropchars">
<option value="0">I</option>
<option value="0">--</option>
<option value="45">&#204;</option>
<option value="77">&#236;</option>
<option value="46">&#205;</option>
<option value="78">&#237;</option>
<option value="47">&#206;</option>
<option value="79">&#238;</option>
<option value="48">&#207;</option>
<option value="80">&#239;</option>
</select><select 
name="tCharsO" ID="tCharsO" title="O" onchange="if (this.value !=0){iMUc(this.value);}" class="dropchars">
<option value="0">O</option>
<option value="0">-</option>
<option value="51">&#210;</option>
<option value="83">&#242;</option>
<option value="52">&#211;</option>
<option value="84">&#243;</option>
<option value="53">&#212;</option>
<option value="85">&#244;</option>
<option value="54">&#213;</option>
<option value="86">&#245;</option>
<option value="55">&#214;</option>
<option value="87">&#246;</option>
<option value="57">&#216;</option>
<option value="89">&#248;</option>
</select><select 
name="tCharsU" ID="tCharsU" title="U" onchange="if (this.value !=0){iMUc(this.value);}" class="dropchars">
<option value="0">U</option>
<option value="0">--</option>
<option value="58">&#217;</option>
<option value="90">&#249;</option>
<option value="59">&#218;</option>
<option value="91">&#250;</option>
<option value="60">&#219;</option>
<option value="92">&#251;</option>
<option value="61">&#220;</option>
<option value="93">&#252;</option>
<option value="22">&#181;</option>
</select><select 
name="tCharsM" ID="tCharsM" title="More" onchange="if (this.value !=0){iMUc(this.value);}" class="dropchars">
<option value="0">+</option>
<option value="97">&#036;</option>
<option value="3">&#162;</option>
<option value="4">&#163;</option>
<option value="5">&#164;</option>
<option value="6">&#165;</option>

<option value="2">&#161;</option>
<option value="32">&#191;</option>

<option value="10">&#169;</option>
<option value="15">&#174;</option>
<option value="0">--</option>

<option value="0">Y</option>
<option value="0">--</option>
<option value="62">&#221;</option>
<option value="94">&#253;</option>
<option value="96">&#255;</option>
<option value="0">--</option>

<option value="0">C</option>
<option value="0">--</option>
<option value="40">&#199;</option>
<option value="72">&#231;</option>
<option value="0">--</option>

<option value="0">D</option>
<option value="0">--</option>
<option value="49">&#208;</option>
<option value="81">&#240;</option>
<option value="0">--</option>
<option value="0">N</option>
<option value="0">--</option>
<option value="50">&#209;</option>
<option value="82">&#241;</option>
<option value="0">--</option>
<option value="0">B</option>
<option value="0">--</option>
<option value="63">&#222;</option>
<option value="95">&#254;</option>
<option value="64">&#223;</option>
<option value="0">--</option>
<option value="7">&#166;</option>
<option value="8">&#167;</option>
<option value="9">&#168;</option>
<option value="11">&#170;</option>
<option value="12">&#171;</option>
<option value="13">&#172;</option>
<option value="14">&#173;</option>
<option value="16">&#175;</option>
<option value="17">&#176;</option>
<option value="18">&#177;</option>
<option value="19">&#178;</option>
<option value="20">&#179;</option>
<option value="21">&#180;</option>
<option value="23">&#182;</option>
<option value="24">&#183;</option>
<option value="25">&#184;</option>
<option value="26">&#185;</option>
<option value="27">&#186;</option>
<option value="28">&#187;</option>
<option value="29">&#188;</option>
<option value="30">&#189;</option>
<option value="31">&#190;</option>
<option value="56">&#215;</option>
<option value="88">&#247;</option>
</select><INPUT 
TYPE="text" VALUE="" name="markBoxChar" class="dropnormal" size="1" onclick="this.select()"><a 
href="#" onclick="mGR()" title="Greek-to-ASCII Transliteration"><img 
src="gfx/tags/greek.png" width="62" height="22" border="0" align="top"></a></td></form><form 
name="tagform" id="tagform" onsubmit="return(false);"><td valign="top" align="center"><select 
name="ttagsMore" ID="ttagsMore" title="More Tags" onchange="if (this.value !=0){iMU(this.value);}" class="dropchars">
<option value="0">More Markup</option>
<option value="0">--------------------</option>
<option value="">bar</option>
<option value="">break (bar)</option>
<option value="">chapter</option>
<option value="">chapter section</option>
<option value="">end of line break</option>
<option value="">line number</option>
<option value="">page</option>
<option value="">proofing comment</option>
<option value="0">--------------------</option>

<option value="0">Formatted Blocks</option>
<option value="0">--------------------</option>
<option value="">end of line break</option>
<option value="">address</option>
<option value="">journal / diary entry</option>
<option value="">radio broadcast</option>
<option value="">speech</option>
<option value="">text from news</option>
<option value="">text from sign</option>
<option value="0">--------------------</option>

<option value="0">Lists</option>
<option value="0">--------------------</option>
<option value="">item in list</option>
<option value="">list of illustrations</option>
<option value="">list of other works</option>
<option value="">table of contents</option>
<option value="">other type of list....</option>
<option value="0">--------------------</option>

<option value="0">Tables</option>
<option value="0">--------------------</option>
<option value="">cell</option>
<option value="">row</option>
<option value="0">--------------------</option>

<option value="0">Drama &amp; Speech</option>
<option value="0">--------------------</option>
<option value="">speaker</option>
<option value="">stage directions</option>
<option value="0">--------------------</option>

<option value="0">Correspondence</option>
<option value="0">--------------------</option>
<option value="">address</option>
<option value="">date</option>
<option value="">recipient</option>
<option value="">sender</option>
<option value="0">--------------------</option>

<option value="0">Links</option>
<option value="0">--------------------</option>
<option value="">biblio. reference</option>
<option value="">chapter reference</option>
<option value="">glossary reference</option>
<option value="">line reference</option>
<option value="">page reference</option>
<option value="">section reference</option>
<option value="0">--------------------</option>

<option value="0">Front of Book</option>
<option value="0">--------------------</option>
<option value="">acknowledgements</option>
<option value="">author</option>
<option value="">author biography</option>
<option value="">dedication</option>
<option value="">foreword</option>
<option value="">preface</option>
<option value="">prologue</option>
<option value="">publisher</option>
<option value="">publishing info.</option>
<option value="">title</option>
<option value="0">--------------------</option>

<option value="0">Back of Book</option>
<option value="0">--------------------</option>
<option value="">appendix</option>
<option value="">epilogue</option>
<option value="">index</option>
<option value="0">--------------------</option>
</select><INPUT 
TYPE="text" VALUE="" name="markBox" class="dropnormal" size="9" onclick="this.select()"><a 
href="#" onclick="iMU(21)"><img 
src="gfx/tags/italic.png" width="22" height="22" border="0" align="top" title="italics"></a><a 
href="#" onclick="iMU(22)"><img 
src="gfx/tags/bold.png" width="22" height="22" border="0" align="top" title="bold"></a><a 
href="#" onclick="iMU(23)"><img 
src="gfx/tags/underline.png" width="22" height="22" border="0" align="top" title="underline"></a><a 
href="#" onclick="iMU(24)"><img 
src="gfx/tags/caps.png" width="42" height="22" border="0" align="top" title="caps"></a><a 
href="#" onclick="iMU(25)"><img 
src="gfx/tags/sup.png" width="22" height="22" border="0" align="top" title="superscript"></a><a 
href="#" onclick="iMU(26)"><img 
src="gfx/tags/sub.png" width="22" height="22" border="0" align="top" title="subscript"></a><INPUT 
TYPE="text" VALUE="" name="markBoxEnd" class="dropnormal" size="9" onclick="this.select()"></td></form><form 
name="xform" id="xform" method="POST" action="processtext.php" target="_top"><td 
align="right" valign="top"><a 
href="../../faq/prooffacehelp.html" accesskey="1" target="helpNewWin"><img 
src="gfx/tags/help.png" width="18" height="18" border="0" align="top" alt="Help" title="Help"></a><a 
href="<?PHP
  if($userP['i_newwin']==0)
    {echo "proof_per.php";}
  else
    {echo "JavaScript:window.close();";}
?>" target="_top" onclick="return(confirm('Are you sure you want to \r\n\r\nQuit?'));"><img 
src="gfx/tags/exit.png" width="18" height="18" border="0" align="top" alt="Quit" title="Quit"></a></td></form></tr><tr><td 
valign="top" colspan="3" align="center"><img 
src="gfx/tags/bar1.png" width="752" height="22" border="0" usemap="#markbar1"><br><img 
src="gfx/tags/bar2.png" width="752" height="22" border="0" usemap="#markbar2"></td></tr></table><map 
name="markbar1"><area 
shape="rect" coords="0,0,85,21" href="javaScript:iMU(27)"><area 
shape="rect" coords="86,0,159,21" href="javaScript:iMU(28)"><area 
shape="rect" coords="160,0,245,21" href="javaScript:iMU(29)"><area 
shape="rect" coords="246,0,361,21" href="javaScript:iMU(30)"><area 
shape="rect" coords="362,0,430,21" href="javaScript:iMU(31)"><area 
shape="rect" coords="431,0,488,21" href="javaScript:iMU(32)"><area 
shape="rect" coords="489,0,555,21" href="javaScript:iMU(33)"><area 
shape="rect" coords="556,0,623,21" href="javaScript:iMU(34)"><area 
shape="rect" coords="624,0,722,21" href="javaScript:iMU(35)"><area 
shape="rect" coords="723,0,751,21" href="javaScript:iMU(20)"></map><map 
name="markbar2"><area 
shape="rect" coords="0,0,55,21" href="javaScript:iMU(36)"><area 
shape="rect" coords="56,0,148,21" href="javaScript:iMU(37)"><area 
shape="rect" coords="149,0,224,21" href="javaScript:iMU(38)"><area 
shape="rect" coords="225,0,274,21" href="javaScript:iMU(39)"><area 
shape="rect" coords="275,0,359,21" href="javaScript:iMU(40)"><area 
shape="rect" coords="360,0,409,21" href="javaScript:iMU(41)"><area 
shape="rect" coords="410,0,510,21" href="javaScript:iMU(42)"><area 
shape="rect" coords="511,0,626,21" href="javaScript:iMU(43)"><area 
shape="rect" coords="627,0,695,21" href="javaScript:iMU(44)"><area 
shape="rect" coords="696,0,751,21" href="javaScript:iMU(1)"></map></body></html>