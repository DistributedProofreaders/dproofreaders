<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

echo "Simplifying release_criterion values to only deal with one round...\n";

// Convert expressions in queue_defns.release_criterion column:
// get rid of 'projects2' and 'pages2',
// replace 'projects1' and 'pages1' with just 'projects' and 'pages'.

$res = mysqli_query(DPDatabase::get_connection(), "
	SELECT name, release_criterion
	FROM queue_defns
") or die(mysqli_error(DPDatabase::get_connection()));

$n_rc_updated = 0;
while( list($name,$release_criterion) = mysqli_fetch_row($res) )
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

	$name = mysqli_real_escape_string(DPDatabase::get_connection(), $name);
	// echo "    $rc               $release_criterion\n";
	mysqli_query(DPDatabase::get_connection(), "
		UPDATE queue_defns
		SET release_criterion='$rc'
		WHERE name='$name'
	") or die(mysqli_error(DPDatabase::get_connection()));
	$n_rc_updated++;
}
echo "$n_rc_updated release_criterion values updated\n";

// -------------------------------------------------------------------

echo "Adding queue_defns.round_number column...\n";

mysqli_query(DPDatabase::get_connection(), "
	ALTER TABLE queue_defns
	ADD COLUMN round_number TINYINT(1) UNSIGNED NOT NULL DEFAULT 1 FIRST,
	DROP INDEX ordering,
	DROP INDEX name,
	ADD UNIQUE ordering (round_number,ordering),
       	ADD UNIQUE name     (round_number,name)
") or die(mysqli_error(DPDatabase::get_connection()));

// -------------------------------------------------------------------

echo "Converting Birthday/Otherday project_selectors to use an abstract date...\n";

include_once($relPath.'release_queue.inc');

mysqli_query(DPDatabase::get_connection(), "
	UPDATE queue_defns
	SET project_selector=
		REPLACE(
			REPLACE(
				project_selector,
				'$today_MMDD',
				'{today_MMDD}'
			),
			'$tomorrow_MMDD',
			'{tomorrow_MMDD}'
		)
") or die(mysqli_error(DPDatabase::get_connection()));

echo mysqli_affected_rows(DPDatabase::get_connection()), " project_selectors changed.\n";

// -------------------------------------------------------------------

echo "\nDone!\n";
?>
