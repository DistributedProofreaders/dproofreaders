<?php
$relPath="./../../pinc/";
include_once($relPath.'http_headers.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'v_site.inc');

include_once($relPath.'v_resolution.inc');
$i_r= $i_resolutions;
$wSize=explode("x",$i_r[$userP['i_res']*1]);
$menuWidth=$wSize[0]<=800?'99%':'820';

include($relPath.'slim_header.inc');
slim_header("Control Frame");
?>
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
-->
</style><a
href="#" accesskey="=" onfocus="focusText()"></a><form
name="markform" id="markform" onsubmit="return(false);" action="ctrl_frame.php"><table cellpadding="0" cellspacing="0"
align="center" width="<?PHP echo $menuWidth; ?>" border="0"><tr><td valign="top"><select
name="tCharsA" ID="tCharsA" title="A" onchange="if (this.options[selectedIndex].value !=0){iMUc(this.options[selectedIndex].value);}" class="dropchars">
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
name="tCharsE" ID="tCharsE" title="E" onchange="if (this.options[selectedIndex].value !=0){iMUc(this.options[selectedIndex].value);}" class="dropchars">
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
name="tCharsI" ID="tCharsI" title="I" onchange="if (this.options[selectedIndex].value !=0){iMUc(this.options[selectedIndex].value);}" class="dropchars">
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
name="tCharsO" ID="tCharsO" title="O" onchange="if (this.options[selectedIndex].value !=0){iMUc(this.options[selectedIndex].value);}" class="dropchars">
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
name="tCharsU" ID="tCharsU" title="U" onchange="if (this.options[selectedIndex].value !=0){iMUc(this.options[selectedIndex].value);}" class="dropchars">
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
name="tCharsM" ID="tCharsM" title="More" onchange="if (this.options[selectedIndex].value !=0){iMUc(this.options[selectedIndex].value);}" class="dropchars">
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

<option value="63">&#222;</option>
<option value="95">&#254;</option>
<option value="64">&#223;</option>
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
TYPE="text" VALUE="" accesskey="\" name="markBoxChar" class="dropnormal" size="1" onclick="this.select()"><a
href="#" onclick="mGR()" title="Greek-to-ASCII Transliteration"><img
src="gfx/tags/greek.png" width="62" height="22" border="0" align="top" alt="Greek Transliteration" title="Open Greek Transliteration Window"></a></td><td
valign="top" align="center"><INPUT
accesskey="[" TYPE="text" VALUE="" name="markBox" class="dropnormal" size="9" onclick="this.select();"><INPUT
accesskey="]" TYPE="text" VALUE="" name="markBoxEnd" class="dropnormal" size="9" onclick="this.select()"><a
href="#" onclick="iMU(21)"><img
src="gfx/tags/italic.png" width="22" height="22" border="0" align="top" title="italics" alt="italics"></a><a
href="#" onclick="iMU(22)"><img
src="gfx/tags/bold.png" width="22" height="22" border="0" align="top" title="bold" alt="bold"></a><?PHP


/* temp disabled
<a
href="#" onclick="iMU(23)"><img
src="gfx/tags/underline.png" width="22" height="22" border="0" align="top" title="underline" alt="underline"></a><a
href="#" onclick="iMU(24)"><img
src="gfx/tags/caps.png" width="42" height="22" border="0" align="top" title="caps" alt="caps"></a><a
href="#" onclick="iMU(25)"><img
src="gfx/tags/sup.png" width="22" height="22" border="0" align="top" title="superscript" alt="superscript"></a><a
href="#" onclick="iMU(26)"><img
src="gfx/tags/sub.png" width="22" height="22" border="0" align="top" title="subscript" alt="subscript"></a>
*/

?></td><td
align="right" valign="top"><a
href="../../faq/prooffacehelp.php" accesskey="1" target="helpNewWin"><img
src="gfx/tags/help.png" width="18" height="18" border="0" align="top" alt="Help" title="Help"></a><a
href="<?PHP
  if($userP['i_newwin']==0)
    {echo "proof_per.php";}
  else
    {echo "JavaScript:window.close();";}
?>" target="_top" onclick="return(confirm('Are you sure you want to \r\n\r\nExit the Interface?'));"><img
src="gfx/tags/exit.png" width="18" height="18" border="0" align="top" alt="Exit" title="Exit"></a></td></tr><tr><td
valign="top" colspan="3" align="center"><?PHP
include('ptags.inc');
?>
<br>Proofreading Diagrams: [<a href='<?php echo $code_url; ?>/faq/ProofingDiagram_HighRes.gif' target='_new'>High Res</a>] 
[<a href='<? echo $code_url; ?>/faq/ProofingDiagram_MedRes.gif' target='_new'>Medium Res</a>] 
[<a href='<? echo $code_url; ?>/faq/ProofingDiagram_LowRes.gif' target='_new'>Low Res</a>]</font>
</td>
</tr>
</table>
</form>

</body>
</html>
