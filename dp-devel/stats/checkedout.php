<?
$relPath="../pinc/";
include_once($relPath.'dp_main.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'theme.inc');

$state = ( isset($_GET['state']) ? $_GET['state'] : PROJ_POST_FIRST_CHECKED_OUT );
if ( $state == PROJ_POST_FIRST_CHECKED_OUT )
{
	$activity = _('Post Processing');
      $order = (isset($_GET['order']) ? $_GET['order'] : 'checkedoutby' );
}
elseif ( $state == PROJ_POST_SECOND_CHECKED_OUT )
{
	$activity = _('Post Processing Verification');
       $inPPV = 1;
      $order = (isset($_GET['order']) ? $_GET['order'] : 'postproofer' );
}
else
{
	echo "checkedout.php: bad value for state: '$state'";
	exit;
}

$order = (isset($_GET['order']) ? $_GET['order'] : 'checkedoutby' );

if ( $order == 'nameofwork' )
{
	$orderclause = 'nameofwork ASC';
}
elseif ( $order == 'checkedoutby' )
{
	$orderclause = 'checkedoutby ASC, modifieddate ASC';
}
elseif ( $order == 'postproofer' )
{
	$orderclause = 'postproofer ASC, modifieddate ASC';
}
elseif ( $order == 'modifieddate' )
{
	$orderclause = 'modifieddate ASC';
}
elseif ( $order == 'holder_last_login' )
{
	$orderclause = 'holder_last_login ASC';
}
else
{
	echo "checkedout.php: bad order value: '$order'";
	exit;
}

// ------------------

$title = _("Books Checked Out for ") . $activity;
theme($title,'header');

echo "<br><h2>$title</h2>\n";

// ------------------

// Header row

if (isset($inPPV)) {
    $colspecs = array(
	'#'                  => 'bogus',
	'Name of Work'       => 'nameofwork',
       'PPer'              => 'postproofer',
	'Checked Out To'     => 'checkedoutby',
	'Date Last Modified' => 'modifieddate',
	'User Last Login'    => 'holder_last_login'
   );

   $numcols = 6;

} else {

$colspecs = array(
	'#'                  => 'bogus',
	'Name of Work'       => 'nameofwork',
	'Checked Out To'     => 'checkedoutby',
	'Date Last Modified' => 'modifieddate',
	'User Last Login'    => 'holder_last_login'
);

   $numcols = 5;

}



echo "<table border='1' bordercolor='#111111' cellspacing='0' cellpadding='2' style='border-collapse: collapse' width='99%'>\n";
echo "<tr><td colspan='$numcols' bgcolor='".$theme['color_headerbar_bg']."'><center><font color='".$theme['color_headerbar_font']."'><b>$title</b></font></center></td></tr>";

echo "<tr bgcolor='".$theme['color_navbar_bg']."'>";
foreach ( $colspecs as $col_header => $col_order )
{
	$s = $col_header;
	// Make each column-header a link that will sort on that column,
	// except for the header of the column that we're already sorting on.
	if ( $col_order != $order && $col_order != 'bogus' )
	{
		$s = "<a href='checkedout.php?state=$state&order=$col_order'>$s</a>";
	}
	$s = "<th><center>".$s ."</font></th>";
	echo "$s\n";
}
echo "</tr>\n";

// ------------------

// Body

$result = mysql_query("
	SELECT
		nameofwork,
             postproofer,
		checkedoutby,
		modifieddate,
		users.last_login as holder_last_login
	FROM projects
		LEFT OUTER JOIN users
		ON projects.checkedoutby = users.username
	WHERE state = '$state'
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

	//calc last login date for user
	$today = getdate($project->holder_last_login);
	$month = $today['month'];
	$mday = $today['mday'];
	$year = $today['year'];
	$lastlogindate = "$month $mday, $year";

	echo "
		<tr bgcolor='".$theme['color_navbar_bg']."'>
		<td>$rownum</td>
		<td width='200'>$project->nameofwork</td>
      ";
     
      if (isset($inPPV)) { 

            echo "	<td>$project->postproofer</td>";
      }
      echo "       
		<td>$project->checkedoutby</td>
		<td>$datestamp</td>
		<td>$lastlogindate</td>
		</tr>
	";
}

echo "</table>";
theme("","footer");
?>

