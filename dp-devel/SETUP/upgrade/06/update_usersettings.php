<?PHP
$relPath = './pinc/';
include_once($relPath.'connect.inc');
new dbConnect();

// Change R1+R2 to P1+P2

echo "<pre>\n";

for ( $rn = 1; $rn <= 2; $rn++ )
{
	$old = "R{$rn}order";
	$new = "P{$rn}order";

	echo "In 'setting' column, changing $old to $new ...\n";

	mysql_query("
		UPDATE usersettings
		SET setting='$new'
		WHERE setting='$old'
	") or die(mysql_error());
}

echo "done.\n";
echo "</pre>\n";
?>
