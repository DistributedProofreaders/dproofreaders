<?PHP
function bgcol($row)
{if ($row % 2) {$bgcolor = "\"#CCCCCC\"";}
else {$bgcolor = "\"#999999\"";}
return $bgcolor;}

function tde($text,$row,$cntr)
{$cntr=$cntr?" align=\"center\"":'';
$bgcolor=bgcol($row);
 $tdt="<td bgcolor=\"$bgcolor\"$cntr>$text</td>";
echo $tdt;}
?>