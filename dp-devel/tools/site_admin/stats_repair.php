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
		SELECT date_updated, total_pagescompleted
		FROM member_stats
		WHERE
			date_updated >= 1080979200
			AND u_id = '$user'
		ORDER by date_updated
	");

	list($dummy, $yester_total) = mysql_fetch_row($userdates);

	while (list($currdate, $new_total) = mysql_fetch_row($userdates)) {

		$diff = $new_total - $yester_total;

		$updq = mysql_query("
			UPDATE member_stats
			SET daily_pagescompleted = $diff
			WHERE
				u_id = '$user'
				AND date_updated = $currdate
		");

		$yester_total = $new_total;

	}

	echo "finished repair attempt of stats for $username<br>\n";


}

echo "All done";

?>
