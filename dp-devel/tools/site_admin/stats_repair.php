<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'connect.inc');
new dbConnect();


//$user_condition = "u_id > 0 AND u_id <= 1000";

$user_condition =  "u_id >= 20000 ";

$users = mysql_query("
	SELECT u_id, username FROM users WHERE $user_condition ORDER BY u_id
");

while ($user_row = mysql_fetch_assoc($users)) {

	$user = $user_row['u_id'];
	$username = $user_row['username'];

	echo "attempting to repair stats for $username\n";

	$userdates = mysql_query("
		SELECT * FROM member_stats WHERE date_updated >=  1080979200
			AND u_id = '$user' ORDER by date_updated
	");

	$userdate_row = mysql_fetch_assoc($userdates);

	$yester_total = $userdate_row['total_pagescompleted'];

	while ($userdate_row = mysql_fetch_assoc($userdates)) {

		$new_total = $userdate_row['total_pagescompleted'];
		$currdate = $userdate_row['date_updated'];

		$diff = $new_total - $yester_total;

		$updq = mysql_query("
			UPDATE member_stats SET daily_pagescompleted = $diff
			WHERE u_id = '$user' AND date_updated = $currdate
		");

		$yester_total = $new_total;

	}

	echo "finished repair attempt of stats for $username<br>\n";


}

echo "All done";

?>
