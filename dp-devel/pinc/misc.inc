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

// vim: sw=4 ts=4 expandtab
?>
