<?
$relPath = '../../pinc/';
include_once( $relPath.'site_vars.php' );
include_once( $relPath.'dpsql.inc' );
include_once( $relPath.'connect.inc' );
new dbConnect;

function shift_range( $projectid, $n_digits, $lo, $hi, $offset, $dryrun )
// Shift a range of pages by some fixed offset.
// For each affected page number:
//    -- renames the .png file;
//    -- renames the .txt file;
//    -- updates the corresponding row of the page table,
//       modifying the 'image' and 'fileid' values.
{
	echo "<pre>\n";

	$assertions = array(
		'is_int($n_digits)',
		'is_int($lo)',
		'is_int($hi)',
		'is_int($offset)',

		'$n_digits > 0',
		'$lo <= $hi',
		'$offset != 0',
		'$lo + $offset >= 0'
	);

	$n_assertions_that_failed = 0;

	foreach ( $assertions as $assertion )
	{
		if ( eval( "return $assertion;" ) )
		{
			// good
		}
		else
		{
			echo "ERROR: shift_range: assertion failed: $assertion\n";
			$n_assertions_that_failed ++;
		}
	}

	if ( $n_assertions_that_failed > 0 )
	{
		echo "Aborting due to assertion failures.\n";
		echo "</pre>";
		return;
	}

	// --------------------------------------------------------------------

	// Move range $lo -> $hi to range $lo+$offset -> $hi+$offset.

	if ( $offset < 0 )
	{
		$start_i = $lo;
		$end_i   = $hi + 1;
		$incr_i  = +1;
	}
	else
	{
		$start_i = $hi;
		$end_i   = $lo - 1;
		$incr_i  = -1;
	}

	$padded_fmt = "%0{$n_digits}d";

	global $projects_dir;
	echo "cd $projects_dir/$projectid\n";
	chdir( "$projects_dir/$projectid" ) or die("aborting");

	// Pass 1: Make sure that we're not shifting into occupied territory.
	// Pass 2: Do the shift.
	for ( $pass = 1; $pass <= 2; $pass++ )
	{
		echo "\n";
		echo "PASS $pass starts\n";

		if ( $pass == 2 )
		{
			if ( $dryrun )
			{
				echo "(dry run)\n";
			}
			else
			{
				echo "(the real thing!)\n";
			}
		}

		for ( $i = $start_i; $i != $end_i; $i += $incr_i )
		{
			$j = $i + $offset;

			if ( $pass == 1 and $j == $start_i )
			{
				// From here on, we *expect* $png_filename_j [etc] to exist,
				// but they won't exist once the shift is happening.
				break;
			}

			$padded_i = sprintf( $padded_fmt, $i );
			$padded_j = sprintf( $padded_fmt, $j );

			echo "\n";
			echo "  $padded_i -> $padded_j\n";

			// -------------------------------------------
			// Rename .png file

			$png_filename_i = $padded_i . '.png';
			$png_filename_j = $padded_j . '.png';

			if ( $pass == 1 )
			{
				if ( is_file( $png_filename_j ) )
				{
					echo "ERROR: shift_range: $png_filename_i would become $png_filename_j, but that already exists.\n";
					echo "</pre>";
					return;
				}
			}
			else
			{
				echo "    renaming $png_filename_i as $png_filename_j\n";
				if ( !$dryrun ) rename( $png_filename_i, $png_filename_j );
			}


			// -------------------------------------------
			// Rename .txt file

			$txt_filename_i = $padded_i . '.txt';
			$txt_filename_j = $padded_j . '.txt';

			if ( $pass == 1 )
			{
				if ( is_file( $txt_filename_j ) )
				{
					echo "ERROR: shift_range: $txt_filename_i would become $txt_filename_j, but that already exists.\n";
					echo "</pre>";
					return;
				}
			}
			else
			{
				echo "    renaming $txt_filename_i as $txt_filename_j\n";
				if ( !$dryrun ) rename( $txt_filename_i, $txt_filename_j );
			}

			// -------------------------------------------
			// Check/tweak the DB.

			if ( $pass == 1 )
			{
				// Check whether a row for $png_filename_j already exists.
				$q = "SELECT image FROM $projectid WHERE image='$png_filename_j'";
				// echo "    $q\n";
				if ( mysql_num_rows( dpsql_query($q) ) > 0 )
				{
					echo "ERROR: shift_range: $png_filename_i would become $png_filename_j, but there is already a row in the page table for the latter.\n";
					echo "</pre>";
					return;
				}

			}
			else
			{
				echo "    updating the page table\n";
				if ( !$dryrun )
				{

					if ($writeBIGtable) {
						$q = "UPDATE project_pages SET fileid='$padded_j', image='$png_filename_j' WHERE projectid = '$projectid' AND  image='$png_filename_i'";
						// echo "    $q\n";
						dpsql_query($q);
					}
					$q = "UPDATE $projectid SET fileid='$padded_j', image='$png_filename_j' WHERE image='$png_filename_i'";
					// echo "    $q\n";
					dpsql_query($q);
				}
			}
		}

		echo "\n";
		echo "PASS $pass done\n";
	}

	echo "</pre>";

}

// shift_range( 'shift_test', 3,   2,   6,   +2, TRUE );
// shift_range( 'shift_test', 3, 901, 903, -900, TRUE );

// Copyright Renewals 1950:
// There were 363 pages, but the first 9 were really bad.
// So Juliet only processed and installed the latter 354 (as 001 to 354).
// Later, I typed in the text for the first 9, and added them to the project (as 901 to 909).
// That's how they went through round 1.
// But I wanted to give pages their rightful numbers,
// so between round 1 and round 2, I stalled the project, and tweaked it with these commands:
//
//     shift_range( 'projectID3f4e051bed5ac', 3,    1,  354,   +9, TRUE );
//     shift_range( 'projectID3f4e051bed5ac', 3,  901,  909, -900, TRUE );
//
// That is, shift 001-354   up 9   to 010-363,
//     then shift 901-909 down 900 to 001-009

?>
