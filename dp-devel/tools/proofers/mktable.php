<html>
<head>
<?
$charset="UTF-8";

define("ARRAY_PAD_FRONT", -1);
define("ARRAY_PAD_BACK",   1);
define("ARRAY_PAD_BOTH",   0);

mb_internal_encoding($charset);

$row=intval($_POST['row']); $row=($row>1)?$row:1;
$col=intval($_POST['col']); $col=($col>1)?$col:1;
$bord=($_POST['border']=="n")?FALSE:TRUE;
$trim=($_POST['trim']=="on")?TRUE:FALSE;
?>
<meta http-equiv="Content-Type" content="text/html; charset=<? echo $charset; ?>">
<title>Table Maker</title>
</head>
<body>
<form method="POST">
<input type="submit" value="Create">
<input type="text" name="row" value="<? echo $row; ?>" size="2"> rows and
<input type="text" name="col" value="<? echo $col; ?>" size="2"> columns table
<select name="border">
<option value="y"<? echo $bord?" selected":""; ?>>with</option>
<option value="n"<? echo $bord?"":" selected"; ?>>without</option>
</select> border;
<input type="checkbox" name="trim"<? echo $trim?" checked":""; ?>> trim spaces.<br/>
<table>
<tr>
<td>&nbsp;</td>
<?

if(isset($_POST['clear'])) {
	for($i=0;$i<$row;$i++) {
		$_POST["val$i"]="t";
		for($j=0;$j<$col;$j++) {
			$_POST["al$j"]="l";
			$_POST["a{$i}_{$j}"]="";
		}
	}
}

$al=array();
for($j=0;$j<$col;$j++) {
	switch($t=$_POST["al$j"]) {
		case "r":
			$al[$j]=STR_PAD_LEFT;
			break;
		case "c":
			$al[$j]=STR_PAD_BOTH;
			break;
		default:
			$al[$j]=STR_PAD_RIGHT;
			break;
	}
	echo "<td align=\"center\">\n".
		"<input type=\"radio\" name=\"al$j\" value=\"l\"".
			(($al[$j]==STR_PAD_RIGHT)?" checked":"").
		"><img src=\"./../../graphics/left.gif\" alt=\"left\">\n".
		"<input type=\"radio\" name=\"al$j\" value=\"c\"".
			(($al[$j]==STR_PAD_BOTH)?" checked":"").
		"><img src=\"./../../graphics/center.gif\" alt=\"center\">\n".
		"<input type=\"radio\" name=\"al$j\" value=\"r\"".
			(($al[$j]==STR_PAD_LEFT)?" checked":"").
		"><img src=\"./../../graphics/right.gif\" alt=\"right\">\n".
	"</td>\n";
}

echo "</tr>";

$a=array(); $lng=array(); $tll=array(); $val=array();
//$row=5; $col=5;
for($i=0;$i<$row;$i++) {
	switch($t=$_POST["val$i"]) {
		case "b":
			$val[$i]=ARRAY_PAD_FRONT;
			break;
		case "m":
			$val[$i]=ARRAY_PAD_BOTH;
			break;
		default:
			$val[$i]=ARRAY_PAD_BACK;
			break;
	}
	echo "<tr>\n<td valign=\"middle\">\n".
		"<input type=\"radio\" name=\"val$i\" value=\"t\"".
			(($val[$i]==ARRAY_PAD_BACK)?" checked":"").
		"><img src=\"./../../graphics/top.gif\" alt=\"top\"><br />\n".
		"<input type=\"radio\" name=\"val$i\" value=\"m\"".
			(($val[$i]==ARRAY_PAD_MIDDLE)?" checked":"").
		"><img src=\"./../../graphics/middle.gif\" alt=\"middle\"><br />\n".
		"<input type=\"radio\" name=\"val$i\" value=\"b\"".
			(($val[$i]==ARRAY_PAD_FRONT)?" checked":"").
		"><img src=\"./../../graphics/bottom.gif\" alt=\"bottom\"><br />\n".
		"</td>\n";

	$a[$i]=array();
	for($j=0;$j<$col;$j++) {
		$name="a{$i}_{$j}";
		$a[$i][$j]=explode("\n",str_replace("\r\n","\n",$_POST[$name]=stripslashes($_POST[$name])));
		foreach($a[$i][$j] as $k=>$v) {
			if($trim)
				$a[$i][$j][$k]=$v=trim($v);
			if(($t=mb_strlen($v))>$lng[$j])
				$lng[$j]=$t;
		}
		if($trim)
			$a[$i][$j]=array_filter($a[$i][$j],"str_not_empty");
		if(($t=count($a[$i][$j]))>$tll[$i])
			$tll[$i]=$t;
?>
<td><textarea name="<? echo $name; ?>" wrap="off">
<? echo htmlspecialchars($_POST[$name]); ?>
</textarea></td>
<?
	}
	echo "</tr>";
}
?>
</table>
<input type="submit" value="Draw">
<input type="submit" name="clear" value="Clear">
</form>
<form>
<textarea rows="20" cols="80" wrap="off">
<?
if($bord) hline();

for($i=0;$i<$row;$i++) {
	for($j=0;$j<$col;$j++) {
		$a[$i][$j]=array_pad_internal($a[$i][$j],$tll[$i],$val[$i]);
	}
	for($k=0;$k<$tll[$i];$k++) {
		if($bord) echo "|";
		for($j=0;$j<$col-1;$j++) {
			echo htmlspecialchars(mb_str_pad($a[$i][$j][$k],$lng[$j]," ",$al[$j]))."|";
		}
		echo htmlspecialchars(mb_str_pad($a[$i][$col-1][$k],$lng[$col-1]," ",$al[$col-1]));
		if($bord) echo "|";
		echo "\n";
	}

	if($bord||$i<($row-1))
		hline();
}
?>
</textarea>
</form>
<?
function hline()
{
global $col,$lng,$bord;

if($bord) echo "+";
for($j=0;$j<$col-1;$j++)
	echo mb_str_pad("",$lng[$j],"-")."+";
echo mb_str_pad("",$lng[$col-1],"-");
if($bord) echo "+";
echo "\n";
}

function str_not_empty($str)
{
	return $str!=="";
}

function mb_str_pad($input,$pad_length,$pad_string=" ",$pad_type=STR_PAD_RIGHT,$encoding=NULL)
{
	if($encoding==NULL)
		$encoding=mb_internal_encoding();
	$l=mb_strlen($input,$encoding);
	if($pad_length<=$l)
		return $input;

	switch($pad_type) {
		case STR_PAD_LEFT:
			for($i=0;$i<$pad_length-$l;$i++)
				$input=$pad_string.$input;
			break;
		case STR_PAD_RIGHT:
			for($i=0;$i<$pad_length-$l;$i++)
				$input.=$pad_string;
			break;
		case STR_PAD_BOTH:
			for($i=0;$i<floor(($pad_length-$l)/2);$i++)
				$input=$pad_string.$input;
			for($i=0;$i<ceil(($pad_length-$l)/2);$i++)
				$input.=$pad_string;
			break;
		default:
			break;
	}
	return $input;
}

function array_pad_internal($input,$pad_size,$pad_type)
{
	if($pad_type==ARRAY_PAD_BOTH)
		return array_pad(
			array_pad(
				$input,
				($pad_size-count($input))/2-$pad_size,
				""
			),
			$pad_size,
			""
		);
	else 
		return array_pad($input,$pad_type*$pad_size,"");
}
?>
</body>
</html>
