<?PHP

function bgcol($row,$round)
{
	$bgcolor_odd = '#CCCCCC'; // darker grey
	$bgcolor_even = '#999999'; // lighter grey
	if ($round == 'first')
	{
		$bgcolor_odd = '#FFF8DC'; // cornsilk
		$bgcolor_even = '#FFE4B5'; // mocassin
	}
	if ($round == 'second')
	{
		$bgcolor_odd = '#D8BFD8'; // thistle
		$bgcolor_even = '#DDA0DD'; // plum
	}
	if ($row % 2) 
	{
		$bgcolor = " bgcolor='" . $bgcolor_odd . "'";
	}
	else 
	{
		$bgcolor = " bgcolor='" . $bgcolor_even . "'";
	}
	return $bgcolor;
}

function tde($text,$td_controls)
{
	$tdt="\n<td $td_controls>$text</td>";
	echo $tdt;
}
?>