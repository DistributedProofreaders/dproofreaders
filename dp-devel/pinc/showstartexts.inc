<?PHP
include_once($relPath.'bookpages.inc');
function showstartexts($etext_limit,$type)
{
if ($type=='bronze')
  {$state='=2 OR state=12 OR state=8 OR state=18';
  $text=" Now Proofing.</font>  The book is currently being processed through this site, sign in and start helping!!!! <br>";}
else if ($type=="silver")
  {$state='>=19 AND state<=29';
  $text=" In Progress.</font>  The book has been processed through this site but not yet posted to the Project Gutenberg archive (going through final proofing/assembly).<br>";}
else {$state='=30';
  $text=" Complete.</font>  The book has been processed through this site and posted to the Project Gutenberg archive.<br>";}
$starresult=mysql_query("SELECT * FROM projects WHERE state$state ORDER BY modifieddate DESC");
$rows=mysql_num_rows($starresult);
echo "<img src=\"web_graphics/{$type}_star.jpg\" border=\"0\" height=\"38\" width=\"40\" alt=\"$type star\"> ".
"= <font face=\"Verdana\" size=\"4\">$rows";
echo $text;
$maxrows=$etext_limit<=$rows?$etext_limit:$rows;
  for ($i=1;$i<=$maxrows;$i++)
    {
    $book=mysql_fetch_assoc($starresult);
    $moddate = date("l, F jS, Y",$book['modifieddate']);
    echo "<font face=\"Verdana\" size=\"1\" color=\"#444444\"><b>$i) \"{$book['nameofwork']}\"</b></font>".
    "<font face=\"Verdana\" size=\"1\">, {$book['authorsname']}<br>";
    if ($type != 'gold') {
      $pages=bookpages($book['projectid'],'>=0');
      echo "{$pages['total']} pages; ";
    }
    echo "$moddate</font><br><br>";
    }
  if ($rows > $etext_limit)
  {echo "<font face=\"Verdana\" size=\"1\">--<a href=\"list_etexts.php?x=".substr($type,0,1)."\">See more $type e-texts</a></font><br><br>";}
}
?>