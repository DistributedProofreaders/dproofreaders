<?PHP
$relPath = './pinc/';
include_once($relPath.'connect.inc');
new dbConnect();

// Convert expressions in queue_defns.release_criterion column:
// get rid of 'projects2' and 'pages2',
// replace 'projects1' and 'pages1' with just 'projects' and 'pages'.

echo "<pre>\n";

$res = mysql_query("
	SELECT name, release_criterion
	FROM queue_defns
") or die(mysql_error());

$n_rc_updated = 0;
while( list($name,$release_criterion) = mysql_fetch_row($res) )
{
	if ( !preg_match( '/(pages|projects)[12]/', $release_criterion ) )
	{
		continue;
	}

	// echo "$name\n";
	$rc = $release_criterion;
	$rc = preg_replace( '/\s+/', ' ', $rc );
	$rc = preg_replace( '/(pages|projects)2/', 'ZERO', $rc );
	$rc = preg_replace( '/\s*\+\s*ZERO/', '', $rc );
	$rc = preg_replace( '/ZERO\s*\+\s*/', '', $rc );
	$rc = preg_replace( '/ZERO\s*<\s*\d+/', 'TRUE', $rc );
	$rc = preg_replace( '/\(TRUE or TRUE\)/', 'TRUE', $rc );
	$rc = preg_replace( '/\s*(and|&&)\s*TRUE/', '', $rc );
	$rc = preg_replace( '/(pages|projects)1/', '\1', $rc );

	$name = addslashes($name);
	// echo "    $rc               $release_criterion\n";
	mysql_query("
		UPDATE queue_defns
		SET release_criterion='$rc'
		WHERE name='$name'
	") or die(mysql_error());
	$n_rc_updated++;
}
echo "$n_rc_updated release_criterion values updated\n";

// -------------------------------------------------------------------

// Add queue_defns.round_number column

mysql_query("
	ALTER TABLE queue_defns
	ADD COLUMN round_number TINYINT(1) UNSIGNED NOT NULL DEFAULT 1 FIRST,
	DROP INDEX ordering,
	DROP INDEX name,
	ADD UNIQUE ordering (round_number,ordering),
       	ADD UNIQUE name     (round_number,name)
") or die(mysql_error());

// -------------------------------------------------------------------

echo "done.\n";
echo "</pre>\n";
?>
