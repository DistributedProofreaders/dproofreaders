<?
include_once($relPath.'v_site.inc');
include_once($relPath.'project_states.inc');
function showstartexts($etext_limit,$type) {
global $code_url;

$i = 1;

if ($type == "bronze") {
$state = SQL_CONDITION_BRONZE;
$text = " Now Proofing.</font>  These books are currently being processed through our site; sign in and start helping!!!! <br>";
} elseif ($type == "silver") {
$state = SQL_CONDITION_SILVER;
$text = " In Progress.</font>  These books have been processed through our site but have not yet posted to the Project Gutenberg archive (currently going through their final proofing/assembly).<br>";
} elseif ($type == "gold") {
$state = SQL_CONDITION_GOLD;
$text = " Complete.</font>  These books have been processed through our site and posted to the Project Gutenberg archive. ";
$text .= "<a href='".$GLOBALS['xmlfeeds_url']."/backend.php?content=posted'><img src='$code_url/graphics/xml.gif' border='0' width='36' height='14' style='vertical-align:middle'></a>&nbsp;<a href='".$GLOBALS['xmlfeeds_url']."/backend.php?content=posted&type=rss'><img src='$code_url/graphics/rss.gif' border='0' width='36' height='14' style='vertical-align:middle'></a><br>";
}

$total = mysql_num_rows(mysql_query("SELECT projectid FROM projects WHERE $state"));
$result = mysql_query("SELECT * FROM projects WHERE $state ORDER BY modifieddate DESC LIMIT $etext_limit");

echo "<img src='$code_url/graphics/{$type}_star.jpg' border='0' height='38' width='40' alt='$type star'> = <font face='Verdana' size='4'>".number_format($total)." $text";

while ($row = mysql_fetch_array($result)) {
$moddate = date("l, F jS, Y",$row['modifieddate']);
echo "<font face='Verdana' size='1' color='#444444'><b>$i) \"".$row['nameofwork']."\"</b></font>";
echo "<font face='Verdana' size='1'>, ".$row['authorsname']."<br>";

if ($type == "gold") {
$links="";

if (trim($row['ziplink']) <> "") $links=$links."<a href='".$row['ziplink']."'>zip version</a>, ";

if (trim($row['txtlink']) <> "") $links=$links."<a href='".$row['txtlink']."'>text version</a>, ";

if (trim($row['htmllink']) <> "") $links=$links."<a href='".$row['htmllink']."'>html version</a>, ";

if ($row['state'] == PROJ_SUBMIT_PG_POSTED) {
	$links=$links."<a href='".$code_url."/tools/correct/uploadcorr.php?project=".$row['projectid']."'>submit corrections</a>";
} else {
	$links=$links."under review";
}

if (substr($links,-2) == ", ") { $links = substr($links,0,-2); }
echo "$moddate<br>";
echo "$links</font><br><br>";
} else {
$totalpages = mysql_query("SELECT COUNT(*) FROM ".$row['projectid']."");
echo mysql_result($totalpages,0)." pages; $moddate</font><br><br>";
}
$i++;
}

if ($total > $etext_limit) {
echo "<font face='Verdana' size='1'>--<a href='list_etexts.php?x=".substr($type,0,1)."'>See more $type e-texts</a></font><br><br>";
}

}
?>
