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

include_once($relPath.'slim_header.inc');
slim_header("Control Frame",TRUE,FALSE);

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
	align="right"
><?

echo_character_selectors_block();

?>
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
echo "<font size='-1'>\n";

echo "<i>"._("Markup shortcuts").":</i>\n";
echo_tool_links( $round->pi_tools['tool_links'] );
echo "\n<br>\n";

echo "<i>", _('Pop-up tools'), ":</i>\n";
echo_popup_links( $round->pi_tools['popup_links'] );
echo "\n<br>\n";

echo "<i>"._("Reference Information").":</i> ";
$url = "$code_url/faq/".lang_dir().$round->document;
echo "<a style=\"color:#0000FF; text-decoration: underline;\" href='$url' target='_blank'>";
echo _('Guidelines');
echo "</a>\n";

echo "<i>"._("Proofreading Diagrams:")."</i>\n"; ?>
[<a style="color:#0000FF; text-decoration: underline;" href='<? echo $code_url; ?>/faq/ProofingDiagram_HighRes.gif' target='_blank'><? echo _("High Res"); ?></a>] 
[<a style="color:#0000FF; text-decoration: underline;" href='<? echo $code_url; ?>/faq/ProofingDiagram_MedRes.gif' target='_blank'><? echo _("Medium Res"); ?></a>] 
[<a style="color:#0000FF; text-decoration: underline;" href='<? echo $code_url; ?>/faq/ProofingDiagram_LowRes.gif' target='_blank'><? echo _("Low Res"); ?></a>]

</font>
</td>
</tr>
</table>
</form>

</body>
</html>
