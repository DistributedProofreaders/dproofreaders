<?PHP

function bgcol($row,$round)
{
	$theme = $GLOBALS['theme'];
	$bgcolor_odd = $theme['color_mainbody_bg'];
	$bgcolor_even = $theme['color_navbar_bg'];
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
	if ($round == 'post_2')
	{
		$bgcolor_odd = '#EAF7F7'; // "paledarkskyblue"
		$bgcolor_even = '#99FFFF'; // "harshflourolightblue"
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