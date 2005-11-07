<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'slim_header.inc');

$wh_path="/home/nikola/public_html/wikihiero";
$wh_img="$wh_path/img";
$wh_img_url="http://127.0.0.1/~nikola/wikihiero/img/";

include_once("$wh_path/wh_language.php");
include_once("$wh_path/wikihiero.php");

slim_header();
?>
<style type="text/css">
.table {visibility: hidden; height: 0px; }
td {font-size: small;}
</style>
<?
echo "<table border=1><tr valign=\"top\"><td>\n";
echo wh_text("Tables");

$tables=array(
//	"Phoneme",
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
//	"All"
);

echo "<ul>\n";
foreach($tables as $v) {
	echo "<li><a href=\"#\" onClick=\"showtable('$v'); return false;\">".(strlen($v)==1?"$v &mdash; ":"").wh_text($v)."</a></li>\n";
}
echo "</ul>\n";

echo "</td><td>\n";

foreach($tables as $v) {
	echo "<div id=\"$v\" class=\"table\">\n";
	wh_table($v);
	echo "</div>\n";
}

echo "</td></tr></table>\n";

?>
<script type="text/javascript">
function showtable(name)
{
<?
	foreach($tables as $v)
		echo "document.getElementById('$v').style.visibility='hidden';\n"
?>
	document.getElementById(name).style.visibility='visible';
}
</script>
<?

// Based on Wikihiero's WH_Text in its index.php
function wh_table($table)
{
    global $wh_img,$wh_img_url,$wh_phonemes;

    if($dh = opendir($wh_img)) 
    {
      while(($file = readdir($dh)) !== false) 
      {
        if($table == "All")
        {
          $code = WH_GetCode($file);
					if(in_array($code, $wh_phonemes))
            echo "<img src=\"".$wh_img_url."$file\" title=\"$code [".array_search($code, $wh_phonemes)."]\">\n";
					else
            echo "<img src=\"".$wh_img_url."$file\" title=\"$code\">\n";
        }
        else if($table == "Phoneme")
        {
          $code = WH_GetCode($file);
          if(in_array($code, $wh_phonemes))
            echo "<img src=\"".$wh_img_url."$file\" title=\"$code [".array_search($code, $wh_phonemes)."]\">\n";
        }
        else if($table == "Aa")
        {
          $code = WH_GetCode($file);
					if((substr($code, 0, 2) == $table) && ctype_digit($code[2]))
					{
						if(in_array($code, $wh_phonemes))
							echo "<img src=\"".$wh_img_url."$file\" title=\"$code [".array_search($code, $wh_phonemes)."]\">\n";
						else
							echo "<img src=\"".$wh_img_url."$file\" title=\"$code\">\n";
					}
        }
        else
        {
          $code = WH_GetCode($file);
					if(($code[0] == $table) && ctype_digit($code[1]))
					{
						if(in_array($code, $wh_phonemes))
							echo "<img src=\"".$wh_img_url."$file\" title=\"$code [".array_search($code, $wh_phonemes)."]\">\n";
						else
							echo "<img src=\"".$wh_img_url."$file\" title=\"$code\">\n";
					}
        }
      }
      closedir($dh);
    }
}

// Based on Wikihiero's WH_Text in its index.php
function wh_text( $index )
{
  global $wh_language;
  $lang=short_lang_code();

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
