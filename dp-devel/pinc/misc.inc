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

// vim: sw=4 ts=4 expandtab
?>
