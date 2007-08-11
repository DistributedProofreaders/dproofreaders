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

function get_integer_param( $arr, $key, $default, $min, $max )
// If $arr[$key] is defined and is the string-rep of an integer
// satisfying certain simple constraints, return that integer.
// If it's not defined, and $default is non-null, return $default.
// Otherwise, die with an error.
//
// Example usage:
//     $per_page = get_integer_param($_GET, 'per_page', 20, 1, NULL );
// If the script is not given a 'per_page' parameter via the $_GET array,
// use a default value of 20. If it is supplied, ensure it's an integer
// and that it's at least 1, with no upper limit.
{
    {
        // sanity checks on the args
        assert( is_array($arr) );
        assert(!is_array($key) );

        assert( is_null($default) || is_int($default) );
        assert( is_null($min)     || is_int($min) );
        assert( is_null($max)     || is_int($max) );

        if ( !is_null($default) )
        {
            if (!is_null($min)) assert( $default >= $min );
            if (!is_null($max)) assert( $default <= $max );
        }

        if ( !is_null($min) && !is_null($max) )
        {
            assert( $min <= $max );
        }
    }

    if ( isset($arr[$key]) )
    {
        $s = $arr[$key];
        assert(is_string($s));

        // Trim whitespace from both ends of the string.
        $s = trim($s);
        
        if ( preg_match('/^[-+]?\d+$/', $s ) )
        {
            // It's a well-formed (decimal) rep of an integer.
            $i = intval($s);
            if ( !is_null($min) && $i < $min )
            {
                die("parameter '$key' ($s) is less than the minimum $min");
            }

            if ( !is_null($max) && $i > $max )
            {
                die("parameter '$key' ($s) is greater than the maximum $max");
            }

            return $i;
        }
        else
        {
            die("parameter '$key' ('$s') is not an integer");
        }
    }
    else
    {
        // not set, use default
        if ( is_null($default) )
        {
            // There is no default. The parameter is required.
            die("parameter '$key' is required");
        }
        else
        {
            return $default;
        }
    }
}

// -----------------------------------------------------------------------------

function startswith( $subject, $prefix )
// Return TRUE iff $subject starts with $prefix.
{
	return ( strncmp( $subject, $prefix, strlen($prefix) ) == 0 );
}

function endswith( $subject, $suffix )
// Return TRUE iff $subject ends with $suffix.
{
    return ( substr( $subject, -strlen($suffix) ) == $suffix );
}

function str_contains( $haystack, $needle )
{
    return ( strpos( $haystack, $needle ) !== FALSE );
}

function surround_and_join( $strings, $L, $R, $joiner )
{
    $parts = array();
    foreach ( $strings as $string )
    {
        $parts[] = $L . $string . $R;
    }
    return implode($joiner,$parts);
}

function urlencode_assoc($name_value_mapping)
// Encode $name_value_mapping according to the content type
// "application/x-www-form-urlencoded".
// See http://www.w3.org/TR/html401/interact/forms.html#h-17.13.4.1
{
    $p = array();
    foreach ( $name_value_mapping as $name => $value )
    {
        $p[] = urlencode($name) . '=' . urlencode($value);
    }
    return implode('&', $p);
}

// -----------------------------------------------------------------------------

// file_get_contents() is predefined in PHP 4.3.0 and on.
if ( !function_exists('file_get_contents') )
{
    function file_get_contents( $filepath )
    {
        $array = file($filepath);
        return ( $array === FALSE ? FALSE : implode('', $array) );
    }
} 

// file_put_contents() is predefined in PHP 5 and on.
if ( !function_exists('file_put_contents') )
{
    function file_put_contents( $filepath, $contents )
    {
        if(is_array($contents))
            $contents = implode('',$contents);

        $wasWritten = FALSE;
        $fh = fopen($filepath,"w");
        if($fh) {
           $wasWritten = fwrite($fh,$contents);
           fclose($fh);
        }
        return $wasWritten;
    }
}

// array_intersect_key() is predefined in PHP 5.1.0RC1
if ( !function_exists('array_intersect_key') )
{
    // This is only the two-arg version.
    function array_intersect_key( $array1, $array2 )
    {
        $result = array();
        foreach ( $array1 as $k => $v )
        {
            if ( array_key_exists($k,$array2) )
            {
                $result[$k] = $v;
            }
        }
        return $result;
    }
}

// str_split() is predefined in PHP 5 and on.
if ( !function_exists('str_split') )
{
    function str_split( $string )
    {
        return preg_split("//",$string, -1, PREG_SPLIT_NO_EMPTY);
    }
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

function all_possible_concatenations()
// Each arg is an array of strings (or else is a string,
// which is treated as if it were an array of length 1).
// Return an array containing the "concatenation cross product" of the args:
// the set of all strings obtained by picking one string from each arg and
// concatenating them in the given (args) order.
{
	$args = func_get_args();
	return all_possible_concats_r( $args );
}

function all_possible_concats_r( $args )
{
	if ( count($args) == 0 ) return array('');

	$arg0 = array_shift( $args );
	$rest_concats = all_possible_concats_r( $args );

	if ( is_string($arg0) )
	{
		$arg0 = array( $arg0 );
	}

	$result = array();
	foreach ( $arg0 as $str0 )
	{
		foreach ( $rest_concats as $rest )
		{
			$result[] = $str0 . $rest;
		}
	}
	return $result;
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
