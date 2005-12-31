<?
$relPath="./../../../pinc/";
include_once($relPath.'slim_header.inc');

include_once("$wikihiero_dir/wh_language.php");
include_once("$wikihiero_dir/wikihiero.php");

slim_header();

$tables=array(
	"Phoneme",
	"A",
	"B",
	"C",
	"D",
	"E",
	"F",
	"G",
	"H",
	"I",
	"K",
	"L",
	"M",
	"N",
	"O",
	"P",
	"Q",
	"R",
	"S",
	"T",
	"U",
	"V",
	"W",
	"X",
	"Y",
	"Z",
	"Aa",
	"All"
);

$syntax=array("-",":","*","!");
?>
<form name="hieroform" method="POST">
<table>
<tr><td rowspan="2">
<textarea name="hierobox" rows="4" cols="30">
<? echo $hierobox=stripslashes(@$_POST['hierobox']); ?>
</textarea>
</td><td colspan="2">
<select onChange="window.parent.hierotable.location='table.php?table='+this.value;">
<?
echo "<option>".WH_Text("Tables")."</option>\n";
echo "<option>----</option>\n";
foreach($tables as $v)
	echo "<option value=\"$v\">".(strlen($v)<=2?"$v &mdash; ":"").WH_Text($v)."</option>\n";
?>
</select>
</td></tr>
<tr><td>
<select onChange="hierobox.value+=this.value; this.value=0;">
<?
echo "<option value=\"0\">".WH_Text("Syntax")."</option>\n";
echo "<option>----</option>\n";
foreach($syntax as $v)
	echo "<option value=\"$v\"> $v ".WH_Text($v)."</option>";
?>
</select>
</td><td align="right">
<input type="submit" value="Submit">
<input type="button" value="Reset" onClick="hierobox.value='';">
</td></tr>
<tr><td colspan="3">
<?
// Stupid, but it works:
echo preg_replace(
	"|".WH_IMG_DIR.WH_IMG_PRE."|",
	"$wikihiero_url/".WH_IMG_DIR.WH_IMG_PRE,
	WikiHiero($hierobox,WH_MODE_HTML)
);
?>
</td></tr>
</table>
</form>
<?
// Based on Wikihiero's WH_Text in its index.php

  function WH_Text( $index )
  {
    global $wh_language;
    global $lang;

    if(isset($wh_language[$index]))
    {
      if(isset($wh_language[$index][$lang]))
        return $wh_language[$index][$lang];
      else
        return $wh_language[$index]["en"];
    }
    return "";
  }
?>
</body>
</html>
