<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'dp_main.inc');


if (! user_is_a_sitemanager())
{
	echo "You are not authorized to invoke this script.";
	exit;
}

//$user_condition = "u_id > 0 AND u_id <= 1000";

$user_condition =  "u_id >= 20000 ";
$user_condition = '1'; // TESTING ONLY

$users = mysql_query("
	SELECT u_id, username
	FROM users
	WHERE $user_condition
	ORDER BY u_id
");

while ($user_row = mysql_fetch_assoc($users)) {

	$user = $user_row['u_id'];
	$username = $user_row['username'];

	echo "attempting to repair stats for $username\n";

	$userdates = mysql_query("
		SELECT timestamp, tally_value
		FROM past_tallies
		WHERE
			timestamp >= 1080979200
			AND holder_type='U'
			AND holder_id = '$user'
			AND tally_name='P'
		ORDER by timestamp
	");

	list($dummy, $yester_total) = mysql_fetch_row($userdates);

	while (list($currdate, $new_total) = mysql_fetch_row($userdates)) {

		$diff = $new_total - $yester_total;

		$updq = mysql_query("
			UPDATE past_tallies
			SET tally_delta = $diff
			WHERE
				timestamp = $currdate
				AND holder_type='U'
				AND holder_id='$user'
				AND tally_name='P'
		");

		$yester_total = $new_total;

	}

	echo "finished repair attempt of stats for $username<br>\n";


}

echo "All done";

?>
