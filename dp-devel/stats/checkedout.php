<?
$relPath="../pinc/";
include_once($relPath.'dp_main.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'theme.inc');

$state = ( isset($_GET['state']) ? $_GET['state'] : PROJ_POST_FIRST_CHECKED_OUT );
if ( $state == PROJ_POST_FIRST_CHECKED_OUT )
{
	$activity = 'Post Processing';
}
elseif ( $state == PROJ_POST_SECOND_CHECKED_OUT )
{
	$activity = 'Post Processing Verification';
}
else
{
	echo "checkedout.php: bad value for state: '$state'";
	exit;
}

$url_base = "checkedout.php?state=$state";

theme("Books Checked Out for $activity", "header");

if (isset($_GET['order']))
{
	if ( $_GET['order'] == "default")
	{
		$orderclause = ' ORDER BY checkedoutby, modifieddate ASC';
	}
	else
	{
		$orderclause = ' ORDER BY '.$_GET['order'].' ASC';
	}
}
else
{
	$orderclause = "";
}

echo "<a href ='$url_base'>Default Sort Order </a>is Checked Out To and then Date Last Modified";

//get projects that have been checked out
$result = mysql_query("
	SELECT
		nameofwork,
		checkedoutby,
		modifieddate,
		users.last_login as holder_last_login
	FROM projects
		LEFT OUTER JOIN users
		ON projects.checkedoutby = users.username
	WHERE state = '$state'
	$orderclause
");

echo "<table border='1'>";
echo "
	<tr>
	<td><b>#</b></td>
	<td><b>Name of Work</b></td>
	<td><b><a href='$url_base&order=checkedoutby'>Checked Out To</a></b></td>
	<td><b><a href='$url_base&order=modifieddate'>Date Last Modified</a></b></td>
	<td>User Last Login</td>
	</tr>
";

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
		<tr>
		<td>$rownum</td>
		<td width='200'>$project->nameofwork</td>
		<td>$project->checkedoutby</td>
		<td>$datestamp</td>
		<td>$lastlogindate</td>
		</tr>
	";
}

echo "</table>";
theme("","footer");
?>
