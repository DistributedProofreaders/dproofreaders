<?
$relPath='../pinc/';
include_once($relPath.'prefs_options.inc'); // PRIVACY_*
include_once($relPath.'dp_main.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'f_dpsql.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'page_tally.php');

$taly_name = @$_GET['tally_name'];
if (empty($tally_name))
{
	die("parameter 'tally_name' is unset/empty");
}


$title = sprintf( _('Top 100 Proofreaders for Round %s'), $tally_name );

theme($title, 'header');

echo "<br><h2>$title</h2>\n";

echo "<br>\n";
echo "<br>\n";

if (isset($GLOBALS['pguser'])) 
// if user logged on
{

	// hide names of users who don't want even logged on people to see their names
	$proofreader_expr = "IF(u_privacy = ".PRIVACY_ANONYMOUS.",'Anonymous', username)";
} 
else
{

	// hide names of users who don't want unlogged on people to see their names
	$proofreader_expr = "IF(u_privacy != ".PRIVACY_PUBLIC.",'Anonymous', username)";
}

{
	$subtitle = sprintf( _('Users with the Highest Number of Pages Saved-as-Done in Round %s'), $tally_name );

	echo "<h3>$subtitle</h3>\n";

	$users_tallyboard = new TallyBoard( $tally_name, 'U' );

	list($joined_with_user_page_tallies,$user_page_tally_column) =
		$users_tallyboard->get_sql_joinery_for_current_tallies('users.u_id');

	dpsql_dump_themed_ranked_query("
		SELECT
			$proofreader_expr AS 'Proofreader',
			$user_page_tally_column AS '$tally_name Pages Completed'
		FROM users $joined_with_user_page_tallies
		WHERE $user_page_tally_column > 0
		ORDER BY 2 DESC, 1 ASC
		LIMIT 100
	");

	echo "<br>\n";
	echo "<br>\n";
}

theme("","footer");
?>
