<?
$relPath = '../pinc/';
include($relPath.'connect.inc');
new dbConnect();
include($relPath.'project_states.inc');

echo "
<HTML>
<HEAD><TITLE>Copyright Renewals Status</TITLE></HEAD>
<BODY>
<H1>Status of Copyright Renewals Projects</H1>
";

$title = "Copyright Renewals";
$selector = "nameofwork like 'Copyright Renewal%'";

$res =
	mysql_query("
		SELECT projectid, nameofwork, state
		FROM projects
		WHERE $selector
		ORDER BY nameofwork
	") or die(mysql_error());


$projects = array();
while ( $project = mysql_fetch_assoc($res) )
{
	$projects[$project['nameofwork']] = $project;
}

$rounds = array(1,2,'both');
$done_left = array('done','left');

$bgcolor = array(
	1 => array(
		'done' => 'FFE4B5',
		'left' => 'FFF8DC' ),
	2 => array(
		'done' => 'DDA0DD',
		'left' => 'D8BFD8' ),
	'both' => array(
		'done' => '66CC66',
		'left' => 'E0E0E0' ),
);

$n_proofings_total = array( 1 => array(), 2 => array() );

echo "<p>(The horizontal bars may or may not be rendered correctly, depending on your browser.)</p>\n";

echo "<table border=1>\n";
echo "<tr>\n";
echo "<th colspan=3></th>\n";
echo "<th colspan=2>round 1</th>\n";
echo "<th colspan=2>round 2</th>\n";
echo "<th colspan=2>both rounds</th>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<th>Title</th>\n";
echo "<th>State</th>\n";
echo "<th>Pages</th>\n";
foreach ($rounds as $round)
{
	foreach ($done_left as $dl)
	{
		echo "<th bgcolor='#{$bgcolor[$round][$dl]}'>$dl</th>\n";
	}
}

$known_n_pages = array(
	1950 => 363,
	1951 => 225,
	1952 => 294,
	1953 => 318,
	1954 => 318,

	1955 => 354,
	1956 => 318,
	1957 => 399,
	1958 => 384,
	1959 => 387,

	1960 => 399,
	1961 => 393,
	1962 => 435,
	1963 => 507,
	1964 => 504,

	1965 => 468,
	1966 => 513,
	1967 => 537,
	1968 => 621,
	1969 => 564,

	1970 => 552,
	1971 => 531,
	1972 => 492,
	1973 => 513,
	1974 => 573,

	1975 => 576,
	1976 => 582,
	1977 => 628,
);

for ( $year = 1950; $year < 1978; $year ++ )
{
	$n_pages = $known_n_pages[$year];

	$nameofwork = "Copyright Renewals $year";

	$n_proofings = array( 1 => array(), 2 => array(), 'both' => array() );

	$project = $projects[$nameofwork];
	if ($project)
	{
		$state = $project['state'];
		$state_str = project_states_text($state);

		if ($state == PROJ_NEW )
		{
			$n_proofings[1]['done'] = 0;
			$n_proofings[2]['done'] = 0;
		}
		else if (
			$state == PROJ_PROOF_FIRST_UNAVAILABLE or
			$state == PROJ_PROOF_FIRST_WAITING_FOR_RELEASE or
			$state == PROJ_PROOF_FIRST_VERIFY or
			$state == PROJ_PROOF_FIRST_AVAILABLE or
			$state == PROJ_PROOF_FIRST_BAD_PROJECT or
			$state == PROJ_PROOF_FIRST_COMPLETE or

			$state == PROJ_PROOF_SECOND_UNAVAILABLE or
			$state == PROJ_PROOF_SECOND_WAITING_FOR_RELEASE or
			$state == PROJ_PROOF_SECOND_VERIFY or
			$state == PROJ_PROOF_SECOND_AVAILABLE or
			$state == PROJ_PROOF_SECOND_BAD_PROJECT or
			$state == PROJ_PROOF_SECOND_COMPLETE )
		{
			$projectid  = $project['projectid'];
			$res2 = mysql_query("
				SELECT 
					SUM(state='save_first' OR state LIKE '%_second') as n_done1,
					SUM(state='save_second') as n_done2,
					COUNT(*) as n_pages
				FROM $projectid
			") or print(mysql_error());
			list($n_proofings[1]['done'],$n_proofings[2]['done'],$n_pages) = mysql_fetch_row($res2);
			assert( $n_pages == $known_n_pages[$year] );
		}
		else
		{
			$n_proofings[1]['done'] = $n_pages;
			$n_proofings[2]['done'] = $n_pages;
		}
	}
	else
	{
		$state_str = 'Not Yet On-site';
		$n_proofings[1]['done'] = 0;
		$n_proofings[2]['done'] = 0;
	}

	$n_proofings[1]['left'] = $n_pages - $n_proofings[1]['done'];
	$n_proofings[2]['left'] = $n_pages - $n_proofings[2]['done'];

	$n_proofings['both']['done'] = $n_proofings[1]['done'] + $n_proofings[2]['done'];
	$n_proofings['both']['left'] = $n_proofings[1]['left'] + $n_proofings[2]['left'];

	echo '<tr>';
	echo "<td>$nameofwork</td>\n";
	echo "<td>$state_str</td>\n";
	echo "<td align='right'>$n_pages</td>\n";
	foreach ( $rounds as $round )
	{
		foreach ( $done_left as $dl )
		{
			echo "<td align='right' bgcolor='#{$bgcolor[$round][$dl]}'>";
			echo $n_proofings[$round][$dl];
			echo "</td>\n";

			$n_proofings_total[$round][$dl] += $n_proofings[$round][$dl];
		}
	}
	echo "<td>\n";
	foreach ( $done_left as $dl )
	{
		$color = $bgcolor['both'][$dl];
		$width = $n_proofings['both'][$dl] / 4;
		echo "<img src='../graphics/$color.gif' height='10' width='$width' hspace='0'>\n";
	}
	echo "</td>\n";
	echo '</tr>';

	$n_pages_total += $n_pages;
}
echo "<tr>\n";
echo "<th>-</th>\n";
echo "<th>-</th>\n";
echo "<th align='right'>$n_pages_total</th>\n";
foreach ( $rounds as $round )
{
	foreach ( $done_left as $dl )
	{
		echo "<th align='right' bgcolor='#{$bgcolor[$round][$dl]}'>\n";
		echo $n_proofings_total[$round][$dl];
		echo "</td>\n";
	}
}
echo "</tr>\n";

echo "</table>\n";


$num = $n_proofings_total['both']['done'];
$den = 2 * $n_pages_total;
$percentage = sprintf( "%.1f", 100 * $num / $den ); 
echo "<p><b>Proofreading is $percentage% ($num/$den) done.</b>";

echo "</BODY></HTML>\n";

?>
