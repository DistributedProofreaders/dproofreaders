<?PHP
$relPath = './pinc/';
include_once($relPath.'connect.inc');
new dbConnect();

// Change R1+R2 to P1+P2

echo "<pre>\n";

$old_to_new = array(
	'R1order'    => 'P1order',
	'R2order'    => 'P2order',
);

foreach ( $old_to_new as $old => $new )
{
	echo "In 'setting' column, changing $old to $new ...\n";

	mysql_query("
		UPDATE usersettings
		SET setting='$new'
		WHERE setting='$old'
	") or die(mysql_error());
	echo "    ", mysql_affected_rows(), " rows affected.\n";
}

echo "done.\n";
echo "</pre>\n";
?>
