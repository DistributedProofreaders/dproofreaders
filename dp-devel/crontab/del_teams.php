<?
$relPath="./../pinc/";
include($relPath.'dp_main.inc');

$old_date = time() - 7776000;
    
$result = mysql_query ("SELECT * FROM user_teams WHERE active_members <= 1");

while ($row = mysql_fetch_assoc($result)) {
        if ($row['created'] <= $old_date && $row['active_members'] == 0) {
		$del = mysql_query("DELETE FROM user_teams WHERE id = ".$row['id']."");
	} elseif ($row['created'] <= $old_date && $row['active_members'] == 1) {
		$mbrCheck = mysql_result("SELECT * FROM users WHERE team_1 = ".$row['id']." || team_2 = ".$row['id']." || team_3 = ".$row['id']."");
		if (mysql_num_rows($mbrCheck) == 0) {
			$del = mysql_query("DELETE FROM user_teams WHERE id = ".$row['id']."");
		}
	}
}

echo "<center>Deletion Finished</center>";
?>
