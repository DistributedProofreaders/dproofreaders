<?
$relPath='./../pinc/';
include($relPath.'connect.inc');
$db_Connection=new dbConnect();

$today=getdate();
$fin=strtotime("+1 month",mktime(12,00,00,$today['mon'],$today['mday'],$today['year']));

$res=mysql_query("SELECT MAX(date) FROM pagestats") or die(mysql_error());
$r=mysql_fetch_row($res);

$res=mysql_query("SELECT year,month,day,dailygoal FROM pagestats WHERE date='{$r[0]}'") or die(mysql_error());
$r=mysql_fetch_assoc($res);

for($i=mktime(12,00,00,$r['month'],$r['day'],$r['year'])+86400;$i<=$fin;$i+=86400) {
	// 86400=60*60*24
	$d=getdate($i);
	mysql_query("INSERT INTO pagestats VALUES({$d['year']},{$d['mon']},{$d['mday']},'{$d['year']}-{$d['mon']}-{$d['mday']}',0,{$r[dailygoal]})");
}
?>
