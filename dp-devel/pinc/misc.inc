<?PHP
// Miscellaneous little (non-DP-specific) functions

function array_get( $arr, $key, $default )
// Return $arr[$key], or if it's not defined, $default.
{
    if ( isset($arr[$key]) )
    {
        return $arr[$key];
    }
    else
    {
        return $default;
    }
}

function startswith( $subject, $prefix )
// Return TRUE iff $subject starts with $prefix.
{
	return ( strncmp( $subject, $prefix, strlen($prefix) ) == 0 );
}

function mkdir_recursive( $dir, $mode )
// Like mkdir, but recursively creates any parent directories too.
// (PHP 5's mkdir has this capability built-in.)
{
    if ( file_exists($dir) )
    {
        if ( is_dir($dir) )
        {
            // Great!
        }
        else
        {
            die( "$dir exists, but isn't a directory." );
        }
    }
    else
    {
        mkdir_recursive( dirname($dir), $mode );
        mkdir( $dir, $mode ) or die( "Unable to create $dir" );
    }
}

// -----------------------------------------------------------------------------

function factor_strings( $strings )
// Given an array of N>0 strings, return an array
//     [$left_common, $middles, $right_common]
// where
// $left_common is the maximal common prefix of the strings;
// $right_common is the maximal common suffix, subject to the constraint that it
//     cannot include characters covered by the common prefix; and
// $middles is an array of N strings, each being that part of the corresponding
//     input string that is not covered by the common prefix and suffix.
//
// That is,
//     $strings[$i] == $left_common . $middles[$i] . $right_common
// for all $i.
{
	assert( count($strings) > 0 );

	// Find the shortest string
    $str_with_minlen = NULL;
	$minlen = NULL;
	foreach ( $strings as $string )
	{
		$len = strlen($string);
		if (is_null($minlen) || $len < $minlen)
		{
			$minlen = $len;
            $str_with_minlen = $string;
		}
	}

    $base = $str_with_minlen;

	// --------------------------------------------------------------

	for ( $L = 0; ; $L++ )
	{
		// Invariant: all strings match in their first $L characters.

        if ($L == $minlen) break;

        // Do they match in their first $L+1 characters?
        // Examine the ($L+1)th character, i.e. the one at index $L.

		$c = substr( $base, $L, 1 );
		foreach ( $strings as $string )
		{
			if ( substr( $string, $L, 1 ) == $c )
			{
				// good so far
			}
			else
			{
				// mismatch.
				// The invariant does not hold for $L+1.
                // So $L is the maximum value that satisfies the invariant.
				break 2;
			}
		}
		// No mismatch found for any string for index $L.
		// So the invariant holds for $L+1.
	}
	$left_match_length = $L;

	// --------------------------------------------------------------

	for ( $R = 0; ; $R++ )
	{
		// Invariant: all strings match in their last $R characters.

        if ( $left_match_length + $R == $minlen ) break;

        // Do they match in their last $R+1 characters?
        // Examine the ($R+1)th-last character, i.e., the one at index -($R+1).
        // e.g. when $R == 0, examine the last character, at index -1
        //      when $R == 1, examine the 2nd-last character, at index -2

		$c = substr($base,-($R+1),1);

		foreach ( $strings as $string )
		{
			if ( substr( $string, -($R+1), 1 ) == $c )
			{
				// good so far
			}
			else
			{
				// mismatch.
				// The invariant does not hold for $R+1.
                // So $R is the maximum value that satisfies the invariant.
				break 2;
			}
		}
		// No mismatch found for any string at that index.
		// So the invariant holds for $R+1.
	}
	$right_match_length = $R;

	// --------------------------------------------------------------

    $left_common = NULL;
    $right_common = NULL;
	$middles = array();

	foreach ( $strings as $string )
	{
        assert( $left_match_length >= 0 );
        assert( $right_match_length >= 0 );
        assert( $left_match_length + $right_match_length <= strlen($string) );

        if ( $left_match_length == strlen($string) )
        {
            // substr() misbehaves
            $left = $string;
            $middle = '';
            $right = '';
        }
        else
        {
            $left = substr( $string, 0, $left_match_length );

            if ( $right_match_length == 0 )
            {
                $middle = substr( $string, $left_match_length );
                $right  = '';
            }
            else
            {
                $middle = substr( $string, $left_match_length, -$right_match_length );
                $right  = substr( $string, -$right_match_length );
            }
        }

        if ( is_null($left_common) )
        {
            $left_common  = $left;
            $right_common = $right;
        }
        else
        {
            assert( $left  == $left_common );
            assert( $right == $right_common );
        }

		$middles[] = $middle;
	}

	return array( $left_common, $middles, $right_common );
}

// vim: sw=4 ts=4 expandtab
?>
