<?
function showstartexts($etext_limit,$type) {

$i = 1;

if ($type == "bronze") {
$state = "state=2 OR state=12 OR state=8 OR state=18";
$text = " Now Proofing.</font>  The book is currently being processed through this site, sign in and start helping!!!! <br>";
} elseif ($type == "silver") {
$state = "state>=19 AND state<=29";
$text = " In Progress.</font>  The book has been processed through this site but not yet posted to the Project Gutenberg archive (going through final proofing/assembly).<br>";
} elseif ($type == "gold") {
$state = "state=30";
$text = " Complete.</font>  The book has been processed through this site and posted to the Project Gutenberg archive.<br>";
}

$total = mysql_num_rows(mysql_query("SELECT projectid FROM projects WHERE $state"));
$result = mysql_query("SELECT * FROM projects WHERE $state ORDER BY modifieddate DESC LIMIT $etext_limit");

echo "<img src='web_graphics/".$type."_star.jpg' border='0' height='38' width='40' alt='$type star'> = <font face='Verdana' size='4'>$total $text";

while ($row = mysql_fetch_array($result)) {
$moddate = date("l, F jS, Y",$row['modifieddate']);
echo "<font face='Verdana' size='1' color='#444444'><b>$i) \"".$row['nameofwork']."\"</b></font>";
echo "<font face='Verdana' size='1'>, ".$row['authorsname']."<br>";

if ($type == "gold") {
$links="";
if (trim($row['ziplink']) <> "") $links=$links."<a href='".$row['ziplink']."'>zip version</a>, ";
if (trim($row['txtlink']) <> "") $links=$links."<a href='".$row['txtlink']."'>text version</a>, ";
if (trim($row['htmllink']) <> "") $links=$links."<a href='".$row['htmllink']."'>html version</a>";
if (substr($links,-2) == ", ") { $links = substr($links,0,-2); }
echo "$moddate<br>";
echo "$links<br><br>";
} else {
$totalpages = mysql_num_rows(mysql_query("SELECT fileid FROM ".$row['projectid'].""));
echo "$totalpages pages; $moddate</font><br><br>";
}
$i++;
}

if ($total > $etext_limit) {
echo "<font face='Verdana' size='1'>--<a href='list_etexts.php?x=".substr($type,0,1)."'>See more $type e-texts</a></font><br><br>";
}

}
?>
