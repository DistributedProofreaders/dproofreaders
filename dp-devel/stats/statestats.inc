<?

// returns the change in number of projects in $state_selector states
// between $startdate and $enddate (format YYYY-MM-DD)
// $startdate must be >= Oct 3 2003
// $enddate must be yesterday or earlier

function state_change_between_dates( $state_selector, $startdate, $enddate)
{

$state_change = 0;

$qry = "
	SELECT SUM(num_projects) AS Num
	FROM project_state_stats
	WHERE 1 AND $state_selector
	AND date = $startdate
";

$res = mysql_query($qry) or die(mysql_error());
$row1 = mysql_fetch_row($res);
$states_at_start = $row1[0];


$qry = "
	SELECT SUM(num_projects) AS Num
	FROM project_state_stats
	WHERE 1 AND $state_selector
	AND date = $enddate
";

$res = mysql_query($qry) or die(mysql_error());
$row1 = mysql_fetch_row($res);
$states_at_end = $row1[0];

$state_change = $states_at_end - $states_at_start;

return $state_change;

}


// returns the change in number of projects in $state_selector states
// on $date_of_interest (format YYYY-MM-DD)
// $date_of_interest must be between 4 Oct 2003 and yesterday

function state_change_on_given_date( $state_selector, $date_of_interest)
{

list($p_year,$p_month,$p_day) = explode("-",$date_of_interest);

$day_before = date('Y-m-d',mktime(0,0,1,$p_day - 1, $p_month, $p_year));

return state_change_between_dates( $state_selector, $date_of_interest, $day_before);

}

// returns the change in number of projects in $state_selector states
// between $startdate (format YYYY-MM-DD) and right now
// $startdate must be between Oct 3 2003 and yesterday

function state_change_since ( $state_selector, $startdate)
{

$state_change = 0;

$qry = "
	SELECT SUM(num_projects) AS Num
	FROM project_state_stats
	WHERE 1 AND $state_selector
	AND date = '$startdate'
";

$res = mysql_query($qry) or die(mysql_error());
$row1 = mysql_fetch_row($res);
$states_at_start = $row1[0];


$qry = "
	SELECT count(projectid) AS Num
	FROM projects
	WHERE 1 AND $state_selector
";

$res = mysql_query($qry) or die(mysql_error());
$row1 = mysql_fetch_row($res);
$states_at_end = $row1[0];

$state_change = $states_at_end - $states_at_start;

return $state_change;

}



?>
