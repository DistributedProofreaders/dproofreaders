<?PHP
$relPath = './pinc/';
include_once($relPath.'connect.inc');
new dbConnect();

// Handles transition from "one filter for all rounds" to "one filter per round".

echo "<pre>\n";

echo "Copying 'proof_' rows to 'P1_' rows.\n";
mysql_query("
	INSERT IGNORE INTO user_filters
	SELECT username, REPLACE(filtertype, 'proof_', 'P1_'), value
	FROM user_filters
	WHERE filtertype LIKE 'proof_%'
") or die(mysql_error());
echo mysql_affected_rows(), " rows affected\n";

echo "Copying 'proof_' rows to 'P2_' rows.\n";
mysql_query("
	INSERT IGNORE INTO user_filters
	SELECT username, REPLACE(filtertype, 'proof_', 'P2_'), value
	FROM user_filters
	WHERE filtertype LIKE 'proof_%'
") or die(mysql_error());
echo mysql_affected_rows(), " rows affected\n";

echo "Delete the 'proof_' rows.\n";
mysql_query("
	DELETE FROM user_filters
	WHERE filtertype LIKE 'proof_%'
") or die(mysql_error());
echo mysql_affected_rows(), " rows affected\n";

echo "done.\n";
echo "</pre>\n";
?>
