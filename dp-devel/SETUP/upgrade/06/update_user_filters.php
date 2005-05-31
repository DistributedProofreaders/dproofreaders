<?PHP
$relPath = '../../../pinc/';
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

// Handle rationalization of filtertypes for pool-related filters.

echo "Pool-related filters...\n";
$old_to_new = array(
	'avail_PP_'   => 'PP_av_',
	'avail_PPV_'  => 'PPV_av_',
);
foreach( $old_to_new as $old_stem => $new_stem )
{
	echo "Changing '$old_stem' to '$new_stem'...\n";
	mysql_query("
		UPDATE user_filters
		SET filtertype = REPLACE( filtertype, '$old_stem', '$new_stem' )
		WHERE filtertype LIKE '$old_stem%'
	") or die(mysql_error());
	echo mysql_affected_rows(), " rows affected\n";
}

echo "done.\n";
echo "</pre>\n";
?>
