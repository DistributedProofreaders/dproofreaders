<?php
$relPath="../pinc/";
include_once($relPath.'dp_main.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'theme.inc');


$clausemap = array(
    'nameofwork'   => 'nameofwork ASC',
    'modifieddate' => 'modifieddate ASC',
    'PPer'         => 'postproofer ASC',
    'PM'           => 'username ASC'
);
$order = get_enumerated_param($_GET, 'order', 'nameofwork', array_keys($clausemap));
$orderclause = $clausemap[$order];

// ------------------

$title = _("Books Available for PPV");
theme($title,'header');

echo "<br><h2>$title</h2>\n";

// ------------------

// Header row

$colspecs = array(
    'bogus'        => _('#'),
    'nameofwork'   => _('Name of Work'),
    'PM'           => _('Project Manager'),
    'PPer'         => _('Post-Processed By'),
    'modifieddate' => _('Date Last Modified'),
);

echo "<table border='1' bordercolor='#111111' cellspacing='0' cellpadding='2' style='border-collapse: collapse' width='99%'>\n";
echo "<tr><td colspan='5' bgcolor='".$theme['color_headerbar_bg']."'><center><font color='".$theme['color_headerbar_font']."'><b>$title</b></font></center></td></tr>";

echo "<tr bgcolor='".$theme['color_navbar_bg']."'>";
foreach ( $colspecs as $col_order => $col_header )
{
	$s = $col_header;
	// Make each column-header a link that will sort on that column,
	// except for the header of the column that we're already sorting on.
	if ( $col_order != $order && $col_order != 'bogus' )
	{
		$s = "<a href='PPV_avail.php?order=$col_order'>$s</a>";
	}
	$s = "<th><center>".$s."</center></th>";
	echo "$s\n";
}
echo "</tr>\n";

// ------------------

// Body

$result = mysql_query("
	SELECT
		nameofwork,
             username,
		postproofer,
		modifieddate
	FROM projects
	WHERE state = '".PROJ_POST_SECOND_AVAILABLE."'
	ORDER BY $orderclause
");

$rownum = 0;
while ( $project = mysql_fetch_object( $result ) )
{
	$rownum++;

	//calc last modified date for project
	$today = getdate($project->modifieddate);
	$month = $today['month'];
	$mday = $today['mday'];
	$year = $today['year'];
	$datestamp = "$month $mday, $year";

	echo "
		<tr bgcolor='".$theme['color_navbar_bg']."'>
		<td>$rownum</td>
		<td width='200'>$project->nameofwork</td>
		<td>$project->username</td>
		<td>$project->postproofer</td>
		<td>$datestamp</td>
		</tr>
	";
}

echo "</table>";
theme("","footer");
?>

