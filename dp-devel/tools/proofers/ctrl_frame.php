<?php
$relPath="./../../pinc/";
include_once($relPath.'http_headers.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'v_site.inc');

include_once($relPath.'v_resolution.inc');
$i_r= $i_resolutions;
$wSize=explode("x",$i_r[$userP['i_res']*1]);
$menuWidth=$wSize[0]<=800?'99%':'820';

$utf8_site=!strcasecmp($charset,"UTF-8");

include($relPath.'slim_header.inc');
slim_header("Control Frame",TRUE,FALSE);
?>
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
</style>
</head>
<body>
<script language="JavaScript" src="dp_proof.js?1.33.1" type="text/javascript"></script>
<script language="JavaScript" src="dp_scroll.js" type="text/javascript"></script>
<a
	href="#"
	accesskey="="
	onfocus="focusText()"
></a><form
	name="markform"
	id="markform"
	onsubmit="return(false);"
	action="ctrl_frame.php"
><table
	cellpadding="0"
	cellspacing="0"
	align="center"
	width="<?PHP echo $menuWidth; ?>"
	border="0"
><tr><td
	valign="top"
	rowspan="2"
><table
	border="0"
	cellpadding="0"
	cellspacing="0"
><tr><td align="right"><select
	name="tCharsA"
	ID="tCharsA"
	title="A"
	onchange="if (this.options[selectedIndex].value !=0){new_iMUc(this.options[selectedIndex].value);}"
	class="dropchars"
>
<option value="0">A</option>
<option value="0">--</option>
<option value="192">&#192;</option>
<option value="224">&#224;</option>
<option value="193">&#193;</option>
<option value="225">&#225;</option>
<option value="194">&#194;</option>
<option value="226">&#226;</option>
<option value="195">&#195;</option>
<option value="227">&#227;</option>
<option value="196">&#196;</option>
<option value="228">&#228;</option>
<option value="197">&#197;</option>
<option value="229">&#229;</option>
<option value="198">&#198;</option>
<option value="230">&#230;</option>
<? if($utf8_site) { ?>
<option value="256">&#256;</option>
<option value="257">&#257;</option>
<option value="258">&#258;</option>
<option value="259">&#259;</option>
<option value="260">&#260;</option>
<option value="261">&#261;</option>
<? } ?>
</select></td><td align="right"><select
	name="tCharsE"
	ID="tCharsE"
	title="E"
	onchange="if (this.options[selectedIndex].value !=0){new_iMUc(this.options[selectedIndex].value);}"
	class="dropchars"
>
<option value="0">E</option>
<option value="0">--</option>
<option value="200">&#200;</option>
<option value="232">&#232;</option>
<option value="201">&#201;</option>
<option value="233">&#233;</option>
<option value="202">&#202;</option>
<option value="234">&#234;</option>
<option value="203">&#203;</option>
<option value="235">&#235;</option>
<? if($utf8_site) { ?>
<option value="274">&#274;</option>
<option value="275">&#275;</option>
<option value="276">&#276;</option>
<option value="277">&#277;</option>
<option value="278">&#278;</option>
<option value="279">&#279;</option>
<option value="280">&#280;</option>
<option value="281">&#281;</option>
<option value="282">&#282;</option>
<option value="283">&#283;</option>
<? } ?>
</select></td><td align="right"><select
	name="tCharsI"
	ID="tCharsI"
	title="I"
	onchange="if (this.options[selectedIndex].value !=0){new_iMUc(this.options[selectedIndex].value);}"
	class="dropchars"
>
<option value="0">I</option>
<option value="0">--</option>
<option value="204">&#204;</option>
<option value="236">&#236;</option>
<option value="205">&#205;</option>
<option value="237">&#237;</option>
<option value="206">&#206;</option>
<option value="238">&#238;</option>
<option value="207">&#207;</option>
<option value="239">&#239;</option>
<? if($utf8_site) { ?>
<option value="296">&#296;</option>
<option value="297">&#297;</option>
<option value="298">&#298;</option>
<option value="299">&#299;</option>
<option value="300">&#300;</option>
<option value="301">&#301;</option>
<option value="302">&#302;</option>
<option value="303">&#303;</option>
<option value="304">&#304;</option>
<option value="305">&#305;</option>
<? } ?>
</select></td><td align="right"><select
	name="tCharsO"
	ID="tCharsO"
	title="O"
	onchange="if (this.options[selectedIndex].value !=0){new_iMUc(this.options[selectedIndex].value);}"
	class="dropchars"
>
<option value="0">O</option>
<option value="0">--</option>
<option value="210">&#210;</option>
<option value="242">&#242;</option>
<option value="211">&#211;</option>
<option value="243">&#243;</option>
<option value="212">&#212;</option>
<option value="244">&#244;</option>
<option value="213">&#213;</option>
<option value="245">&#245;</option>
<option value="214">&#214;</option>
<option value="246">&#246;</option>
<option value="216">&#216;</option>
<option value="248">&#248;</option>
<? if($utf8_site) { ?>
<option value="332">&#332;</option>
<option value="333">&#333;</option>
<option value="334">&#334;</option>
<option value="335">&#335;</option>
<option value="336">&#336;</option>
<option value="337">&#337;</option>
<option value="338">&#338;</option>
<option value="339">&#339;</option>
<? } ?>
</select></td><td align="right"><select
	name="tCharsU"
	ID="tCharsU"
	title="U"
	onchange="if (this.options[selectedIndex].value !=0){new_iMUc(this.options[selectedIndex].value);}"
class="dropchars"
>
<option value="0">U</option>
<option value="0">--</option>
<option value="217">&#217;</option>
<option value="249">&#249;</option>
<option value="218">&#218;</option>
<option value="250">&#250;</option>
<option value="219">&#219;</option>
<option value="251">&#251;</option>
<option value="220">&#220;</option>
<option value="252">&#252;</option>
<option value="181">&#181;</option>
<? if($utf8_site) { ?>
<option value="362">&#362;</option>
<option value="363">&#363;</option>
<option value="368">&#368;</option>
<option value="369">&#369;</option>
<? } ?>
</select></td><td align="right"><select
	name="tCharsM"
	ID="tCharsM"
	title="More"
	onchange="if (this.options[selectedIndex].value !=0){new_iMUc(this.options[selectedIndex].value);}"
	class="dropchars"
>
<option value="0">+</option>
<option value="0">--</option>
<option value="036">&#036;</option>
<option value="162">&#162;</option>
<option value="163">&#163;</option>
<option value="164">&#164;</option>
<option value="165">&#165;</option>

<option value="161">&#161;</option>
<option value="191">&#191;</option>

<option value="169">&#169;</option>
<option value="174">&#174;</option>
<option value="0">--</option>

<option value="171">&#171;</option>
<option value="187">&#187;</option>
<? if($utf8_site) { ?>
<option value="8222">&#8222;</option>
<option value="8220">&#8220;</option>
<? } ?>
<option value="0">--</option>

<option value="222">&#222;</option>
<option value="254">&#254;</option>
<option value="223">&#223;</option>
<? if($utf8_site) { ?>
<option value="502">&#502;</option>
<option value="405">&#405;</option>
<? } ?>
<option value="0">--</option>

<option value="0">Y</option>
<option value="0">--</option>
<option value="221">&#221;</option>
<option value="253">&#253;</option>
<option value="255">&#255;</option>
<option value="0">--</option>

<option value="0">N</option>
<option value="0">--</option>
<option value="209">&#209;</option>
<option value="241">&#241;</option>
<option value="0">--</option>

<option value="166">&#166;</option>
<option value="167">&#167;</option>
<option value="168">&#168;</option>
<option value="170">&#170;</option>
<option value="172">&#172;</option>
<option value="173">&#173;</option>
<option value="175">&#175;</option>
<option value="176">&#176;</option>
<option value="177">&#177;</option>
<option value="178">&#178;</option>
<option value="179">&#179;</option>
<option value="180">&#180;</option>
<option value="182">&#182;</option>
<option value="183">&#183;</option>
<option value="184">&#184;</option>
<option value="185">&#185;</option>
<option value="186">&#186;</option>
<option value="188">&#188;</option>
<option value="189">&#189;</option>
<option value="190">&#190;</option>
<option value="215">&#215;</option>
<option value="247">&#247;</option>
</select></td><td rowspan="2"><input
	TYPE="text"
	VALUE=""
	accesskey="\"
	name="markBoxChar"
	class="dropnormal"
	size="1"
	onclick="this.select()"
><br><a
	href="#"
	onclick="mGR()"
	title="Greek-to-ASCII Transliteration"
><img
	src="gfx/tags/greek.png"
	width="62"
	height="22"
	border="0"
	align="top"
	alt="Greek Transliteration"
	title="<? echo _("Open Greek Transliteration Window"); ?>"
></a></td></tr><tr><td	
<? if(!$utf8_site) echo "style='display: none;'"; ?>><select
	name="tCharsC"
	ID="tCharsC"
	title="CD"
	onchange="if (this.options[selectedIndex].value !=0){new_iMUc(this.options[selectedIndex].value);}"
	class="dropchars"
	<? if(!$utf8_site) echo "disabled"; ?>
>
<option value="0">CD</option>
<option value="0">--</option>
<option value="199">&#199;</option>
<option value="231">&#231;</option>
<option value="262">&#262;</option>
<option value="263">&#263;</option>
<option value="264">&#264;</option>
<option value="265">&#265;</option>
<option value="266">&#266;</option>
<option value="267">&#267;</option>
<option value="268">&#268;</option>
<option value="269">&#269;</option>
<option value="390">&#390;</option>
<option value="391">&#391;</option>
<option value="208">&#208;</option>
<option value="240">&#240;</option>
<option value="270">&#270;</option>
<option value="271">&#271;</option>
<option value="272">&#272;</option>
<option value="273">&#273;</option>
<option value="393">&#393;</option>
<option value="394">&#394;</option>
</select></td><td	
<? if(!$utf8_site) echo "style='display: none;'"; ?>><select
	name="tCharsD"
	ID="tCharsD"
	title="LN"
	onchange="if (this.options[selectedIndex].value !=0){new_iMUc(this.options[selectedIndex].value);}"
	class="dropchars"
	<? if(!$utf8_site) echo "disabled"; ?>
>
<option value="0">LN</option>
<option value="0">--</option>
<option value="313">&#313;</option>
<option value="314">&#314;</option>
<option value="315">&#315;</option>
<option value="316">&#316;</option>
<option value="317">&#317;</option>
<option value="318">&#318;</option>
<option value="319">&#319;</option>
<option value="320">&#320;</option>
<option value="321">&#321;</option>
<option value="322">&#322;</option>
<option value="209">&#209;</option>
<option value="241">&#241;</option>
<option value="323">&#323;</option>
<option value="324">&#324;</option>
<option value="325">&#325;</option>
<option value="326">&#326;</option>
<option value="327">&#327;</option>
<option value="328">&#328;</option>
<option value="329">&#329;</option>
<option value="330">&#330;</option>
<option value="331">&#331;</option>
</select></td><td	
<? if(!$utf8_site) echo "style='display: none;'"; ?>><select
	name="tCharsS"
	ID="tCharsS"
	title="RS"
	onchange="if (this.options[selectedIndex].value !=0){new_iMUc(this.options[selectedIndex].value);}"
	class="dropchars"
	<? if(!$utf8_site) echo "disabled"; ?>
>
<option value="0">RS</option>
<option value="0">--</option>
<option value="340">&#340;</option>
<option value="341">&#341;</option>
<option value="342">&#342;</option>
<option value="343">&#343;</option>
<option value="344">&#344;</option>
<option value="345">&#345;</option>
<option value="223">&#223;</option>
<option value="346">&#346;</option>
<option value="347">&#347;</option>
<option value="348">&#348;</option>
<option value="349">&#349;</option>
<option value="350">&#350;</option>
<option value="351">&#351;</option>
<option value="352">&#352;</option>
<option value="353">&#353;</option>
</select></td><td	
<? if(!$utf8_site) echo "style='display: none;'"; ?>><select
	name="tCharsZ"
	ID="tCharsZ"
	title="TZ"
	onchange="if (this.options[selectedIndex].value !=0){new_iMUc(this.options[selectedIndex].value);}"
	class="dropchars"
	<? if(!$utf8_site) echo "disabled"; ?>
>
<option value="0">TZ</option>
<option value="0">--</option>
<option value="354">&#354;</option>
<option value="355">&#355;</option>
<option value="356">&#356;</option>
<option value="357">&#357;</option>
<option value="358">&#358;</option>
<option value="359">&#359;</option>
<option value="377">&#377;</option>
<option value="378">&#378;</option>
<option value="379">&#379;</option>
<option value="380">&#380;</option>
<option value="381">&#381;</option>
<option value="382">&#382;</option>
</select></td><td
<? if(!$utf8_site) echo "style='display: none;'"; ?>><select
	name="tCharsCyr"
	ID="tCharsCyr"
	title="Cyrillic"
	onchange="if (this.options[selectedIndex].value !=0){new_iMUc(this.options[selectedIndex].value);}"
	class="dropchars"
	<? if(!$utf8_site) echo "disabled"; ?>
>
<option value="0">&#1035;</option>
<option value="0">--</option>
<option value="1026">&#1026;</option>
<option value="1106">&#1106;</option>
<option value="1027">&#1027;</option>
<option value="1107">&#1107;</option>
<option value="1024">&#1024;</option>
<option value="1104">&#1104;</option>
<option value="1025">&#1025;</option>
<option value="1105">&#1105;</option>
<option value="1028">&#1028;</option>
<option value="1108">&#1108;</option>
<option value="1029">&#1029;</option>
<option value="1109">&#1109;</option>
<option value="1037">&#1037;</option>
<option value="1117">&#1117;</option>
<option value="1030">&#1030;</option>
<option value="1110">&#1110;</option>
<option value="1031">&#1031;</option>
<option value="1111">&#1111;</option>
<option value="1049">&#1049;</option>
<option value="1081">&#1081;</option>
<option value="1032">&#1032;</option>
<option value="1112">&#1112;</option>
<option value="1033">&#1033;</option>
<option value="1113">&#1113;</option>
<option value="1034">&#1034;</option>
<option value="1114">&#1114;</option>
<option value="1035">&#1035;</option>
<option value="1115">&#1115;</option>
<option value="1036">&#1036;</option>
<option value="1116">&#1116;</option>
<option value="1038">&#1038;</option>
<option value="1118">&#1118;</option>
<option value="1039">&#1039;</option>
<option value="1119">&#1119;</option>
<option value="1065">&#1065;</option>
<option value="1097">&#1097;</option>
<option value="1066">&#1066;</option>
<option value="1098">&#1098;</option>
<option value="1067">&#1067;</option>
<option value="1099">&#1099;</option>
<option value="1068">&#1068;</option>
<option value="1100">&#1100;</option>
<option value="1069">&#1069;</option>
<option value="1101">&#1101;</option>
<option value="1070">&#1070;</option>
<option value="1102">&#1102;</option>
<option value="1071">&#1071;</option>
<option value="1103">&#1103;</option>
</select></td><td	
<? if(!$utf8_site) echo "style='display: none;'"; ?>><select
	name="tCharsOCyr"
	ID="tCharsOCyr"
	title="OldCyrillic"
	onchange="if (this.options[selectedIndex].value !=0){new_iMUc(this.options[selectedIndex].value);}"
	class="dropchars"
	<? if(!$utf8_site) echo "disabled"; ?>
>
<option value="0">&#1122;</option>
<option value="0">--</option>
<option value="1120">&#1120;</option>
<option value="1121">&#1121;</option>
<option value="1122">&#1122;</option>
<option value="1123">&#1123;</option>
<option value="1124">&#1124;</option>
<option value="1125">&#1125;</option>
<option value="1126">&#1126;</option>
<option value="1127">&#1127;</option>
<option value="1128">&#1128;</option>
<option value="1129">&#1129;</option>
<option value="1130">&#1130;</option>
<option value="1131">&#1131;</option>
<option value="1132">&#1132;</option>
<option value="1133">&#1133;</option>
<option value="1134">&#1134;</option>
<option value="1135">&#1135;</option>
<option value="1136">&#1136;</option>
<option value="1137">&#1137;</option>
<option value="1138">&#1138;</option>
<option value="1139">&#1139;</option>
<option value="1140">&#1140;</option>
<option value="1141">&#1141;</option>
<option value="1142">&#1142;</option>
<option value="1143">&#1143;</option>
<option value="1144">&#1144;</option>
<option value="1145">&#1145;</option>
<option value="1146">&#1146;</option>
<option value="1147">&#1147;</option>
<option value="1148">&#1148;</option>
<option value="1149">&#1149;</option>
<option value="1150">&#1150;</option>
<option value="1151">&#1151;</option>
<option value="1152">&#1152;</option>
<option value="1153">&#1153;</option>
<option value="1154">&#1154;</option>
</select></td></tr></table></td><td
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
><a
	href="#"
	onclick="new_iMU('<i>','</i>')"
	accesskey="i"
><img
	src="gfx/tags/italic.png"
	width="22"
	height="22"
	border="0"
	align="top"
	title="<? echo _("Italics"); ?>"
	alt="<? echo _("Italics"); ?>"
></a><a
	href="#"
	onclick="new_iMU('<b>','</b>')"
	accesskey="b"
><img
	src="gfx/tags/bold.png"
	width="22"
	height="22"
	border="0"
	align="top"
	title="<? echo _("Bold"); ?>"
	alt="<? echo _("Bold"); ?>"
></a><?PHP


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
></a><a
	href="<?PHP
  if($userP['i_newwin']==0)
    {echo "$code_url/activity_hub.php";}
  else
    {echo "JavaScript:window.parent.close();";}
?>"
	target="_top"
	onclick="return(confirm('Are you sure you want to \r\n\r\nExit the Interface?'));"
	><img
	src="gfx/tags/exit.png"
	width="18"
	height="18"
	border="0"
	align="top"
	alt="<? echo _("Exit"); ?>"
	title="<? echo _("Exit"); ?>"
></a></td></tr><tr><td
	valign="top"
	colspan="2"
	align="center">
<?PHP 
echo "<font size=-1><i>"._("Markup shortcuts").":</i> </font>";
include('ptags.inc'); 
?>
<br>
<font size="-1">
<? 
echo "<i>"._("Reference Information").":</i> ";
echo "[<a style=\"color:#0000FF; text-decoration: underline;\" href='$code_url/faq/".lang_dir()."document.php' target='_blank'>". _('Proofreading Guidelines')."</a>] ";
echo "<i>"._("Proofreading Diagrams:")."</i>"; ?> [<a style="color:#0000FF; text-decoration: underline;" href='<?php echo $code_url; ?>/faq/ProofingDiagram_HighRes.gif' target='_blank'><? echo _("High Res"); ?></a>] 
[<a style="color:#0000FF; text-decoration: underline;" href='<? echo $code_url; ?>/faq/ProofingDiagram_MedRes.gif' target='_blank'><? echo _("Medium Res"); ?></a>] 
[<a style="color:#0000FF; text-decoration: underline;" href='<? echo $code_url; ?>/faq/ProofingDiagram_LowRes.gif' target='_blank'><? echo _("Low Res"); ?></a>]</font>
</td>
</tr>
</table>
</form>

</body>
</html>
