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

// vim: sw=4 ts=4 expandtab
?>
