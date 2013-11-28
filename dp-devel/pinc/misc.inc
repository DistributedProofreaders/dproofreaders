<?php
// Miscellaneous little (non-DP-specific) functions

function define_if_necc( $name, $value )
{
    if ( !defined($name) ) define($name, $value);
}

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

function get_enumerated_param( $arr, $key, $default, $choices, $allownull = false)
// If $arr[$key] is defined and is one of the strings in $choices,
// return that string.
// If it's not defined, and $default is non-null, return $default.
// If $default is null and $allownull is true, return null (useful for optional params).
// Otherwise die with an error.
//
// Example usages:
//     $dir = get_enumerated_param( $_GET, 'direction', 'asc', array('asc', 'desc') );
// The direction must be asc/desc, and defaults to asc if not provided.
//
//     $round = get_enumerated_param( $_GET, 'tally_name', null, $rounds, true);
// The tally_name must be one of the rounds, but show a round menu if not specified.
{
    {
        // sanity checks on the args
        assert( is_array($arr) );
        assert(!is_array($key) );

        assert( is_null($default) || is_string($default) );
        assert( is_array($choices) );
        assert( count($choices) > 0 );

        if ( !is_null($default) )
        {
            assert( in_array($default, $choices) );
            assert( !$allownull);
        }
    }

    if ( isset($arr[$key]) )
    {
        $s = $arr[$key];
        assert(is_string($s));

        // Trim whitespace from both ends of the string.
        $s = trim($s);

        // Although this function was written assuming that $choices is an array
        // of strings, tasks.php started calling it with an array of integers.
        // Now, $s is definitely a string, so you might expect that in_array()
        // would say that it doesn't match any value in an array of integers, and
        // return false, whereupon this function would die saying $s is invalid.
        // And in fact, that's exactly what would happen if we passed TRUE to
        // in_array's $strict parameter. But instead, we let $strict take its
        // default value of FALSE. What happens then is that in_array()
        // converts $s to a numeric value and compares *that* to the integer
        // values in $choices.
        // 
        // Now, that would be fine and exactly what we want to have happen,
        // except that PHP's string-to-number conversion is rather loose: it
        // only looks for valid numeric data at the start of the string, and
        // doesn't care if that data is followed by non-numeric characters.
        // (See "String conversion to numbers" in the PHP online manual.)
        // For example,
        //     in_array("2y", array(0,1,2,3))
        // returns true. Thus, in such a case, the in_array() test is not catching
        // invalid input.
        //
        // To handle this, we check if the first value in $choices is an
        // integer, and if so, require that $s contain only an integer numeral.
        // (Note that this wouldn't work if $choices contained both integers and
        // non-numeric strings.)
        //
        if (is_int(current($choices)))
        {
            if (!is_numeric($s))
            {
                die(htmlspecialchars("parameter '$key' ('$s') is not numeric"));
            }
            $s = 0 + $s;
            if (!is_int($s))
            {
                die(htmlspecialchars("parameter '$key' ('$s') is not an integer"));
            }
        }

        if ( in_array($s, $choices) )
        {
            return $s;
        }
        else
        {
            die(htmlspecialchars("parameter '$key' ('$s') is not valid"));
        }
    }
    else
    {
        // parameter not set, use default
        if ( is_null($default) && !$allownull )
        {
            // There is no default. The parameter is required.
            die(htmlspecialchars("parameter '$key' is required"));
        }
        else
        {
            return $default;
        }
    }
}

function get_integer_param( $arr, $key, $default, $min, $max, $allownull = false )
// If $arr[$key] is defined and is the string-rep of an integer
// satisfying certain simple constraints, return that integer.
// If it's not defined, and $default is non-null, return $default.
// If it's not defined, and $default is null and $allownull is true, return null.
// Otherwise, die with an error.
//
// Example usages:
//     $per_page = get_integer_param($_GET, 'per_page', 20, 1, NULL );
// If the script is not given a 'per_page' parameter via the $_GET array,
// use a default value of 20. If it is supplied, ensure it's an integer
// and that it's at least 1, with no upper limit.
//
//     $per_page = get_integer_param($_GET, 'per_page', null, 1, null, true);
// As before, except if no limit is provided, return null, so the caller can
// know to display all pages rather than some arbitrary maximum.
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
            assert(!$allownull);
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
                die(htmlspecialchars("parameter '$key' ($s) is less than the minimum $min"));
            }

            if ( !is_null($max) && $i > $max )
            {
                die(htmlspecialchars("parameter '$key' ($s) is greater than the maximum $max"));
            }

            return $i;
        }
        else
        {
            die(htmlspecialchars("parameter '$key' ('$s') is not an integer"));
        }
    }
    else
    {
        // not set, use default
        if ( is_null($default) && !$allownull )
        {
            // There is no default. The parameter is required.
            die(htmlspecialchars("parameter '$key' is required"));
        }
        else
        {
            return $default;
        }
    }
}

// -----------------------------------------------------------------------------

function attr_safe($string)
//  Return a copy of $str that has been rendered safe to send as
//  (part of) the value of an attribute in an HTML or XML document.
//
//  Specifically, this function replaces any double-quote or
//  single-quote/apostrophe with an equivalent character reference.
//  The result is then safe for inclusion in attribute values,
//  whether they are delimited by single- or double-quotes. E.g.:
//      $result = attr_safe("Don't!");
//      echo "<e attr='$result'/>";
//      echo "<e attr=\"$result\"/>";
//      echo '<e attr="' . $result . '"/>';
//
//  (Note that in HTML, the delimiting quotes around an attribute value
//  can sometimes be omitted. This function is *not* guaranteed to make
//  $str safe for use in/as such an undelimited attribute value.)
//
//  This function does not replace angle brackets (< >) with character
//  references, because angle brackets are not treated specially within
//  attribute values.
//
//  This function also does not replace ampersands (&), even though they
//  *are* treated specially within attribute values (as the start of
//  character and entity references). The reason for this omission is
//  that one of the intended usage scenarios for this function is to
//  handle the results of gettext-translation, and we want to allow
//  translators to use character references in their translations.
//  For example, a French translator might translate
//      Name of work
//  as
//      Nom de l'&oelig;uvre
//  If we have the code:
//      echo "<e attr='" . attr_safe(_('Name of Work')) . "'/>";
//  then
//      _('Name of work')
//  would, in a French locale, evaluate to the string value:
//      Nom de l'&oelig;uvre
//  and we want attr_safe(...) to return:
//      Nom de l&#39;&oelig;uvre
//  not:
//      Nom de l&#39;&amp;oelig;uvre
{
    return str_replace( 
        array("'", "\""), array("&#39;", "&quot;"), $string);
}

// -----------------------------------------------------------------------------

function javascript_safe($str, $encoding)
// Return a string that is safe for inclusion in a javascript string
// (surrounded by either single or double quotes). 
// Does an implied attr_safe(), so the output can be enclosed directly
// in an html attribute. 
//
// Example usage: 
// echo '<script>alert("' . javascript_safe($string, $charset) . '"); </script>';
// echo "<script>alert('" . javascript_safe($string, $charset) . "'); </script>";
// echo '<a ... onClick=\'confirm("' . javascript_safe($string, $charset) . '")\'>';
// echo "<a ... onClick=\"confirm('" . javascript_safe($string, $charset) . '")\">";
//
// Here are the detailed conversions applied:
// - single quotes and double quotes are converted to \ooo octal notation
//   (this makes the result safe for inclusion inside an HTML attribute)
// - '<' is converted to \ooo octal: this guarantees that "</script>" is not
//   present in the returned string
// - backslash will be converted to \ooo octal too. It needs to be encoded
//   anyway, and \ooo allows to reuse the same regexp.
// - also convert anything that may be control characters to \ooo octal or
//   \uHHHH hexadecimal. That will notably ensure that line ending 
//   characters are not present in the returned string.
//   (according to the ECMA-262 specification, line ending characters can
//   include weird characters such as \u2028 (Line separator) and \u2029 
//   (Paragraph separator) in addition to the plain \r and \n.)
//   Specifically: 
//   - If the encoding is latin-1, \ooo octal encoded will be used for
//     all characters in the range [\000-\017\177-\237]
//   - If the encoding is utf-8, \uHHHH encoding is used for all non-ASCII 
//     characters, and \ooo for control ASCII chars [\000-\017\177]
{
    
    if (!strcasecmp($encoding, 'UTF-8')) 
    {
        // /e modifier causes preg_replace() to evaluate the replacement
        // string as php code and uses the evaluation's return value.
        // When using the /e modifier single quotes, double quotes,
        // backslashes (\) and NULL chars will be escaped by backslashes.
        // So, to avoid computing ord("\'") we handle the single quote
        // separately 
        $str = preg_replace('/([\000-\037\"\\\\<\177])/e', 
            'sprintf("\\%03o", ord("$1"))', $str);
        $str = str_replace("'", '\047', $str);
    
        // convert non-7bit ascii characters to \uHHHH notation
        $str = preg_replace('/([^\000-\177])/eu', 
            'sprintf("\\u%04x", utf8_ord("$1") & 0xffff)', $str);
    }
    else
    {
        // assume Latin-1 encoding
        // See comment above about the /e modifier in preg_replace(). 
        $str = preg_replace('/([\000-\017\\\\"<\177-\237])/e', 
            'sprintf("\\%03o", ord("$1"))', $str);
        $str = str_replace("'", '\047', $str);
    }
    return $str;
}


if (version_compare(PHP_VERSION, '6.0.0') >= 0)
{
    // PHP v6 is said to have native utf-8 support (untested)
    
    function utf8_ord($str)
    {
        return ord($str);
    }
} 
else
{
    function utf8_ord($str)
    // Implementation of UTF-8 compliant ord() function.
    // Characters higher than U+ffff are not supported.
    {
        if ($str == '') 
        {
            return 0;
        }
        $u = unpack('C*', $str);
        $count = count($u);
        if ($count >= 1 && ($u[1] & 0x80) == 0)
        {
            // 0xxxxxxx    
            return $u[1];
        }
        else if ($count >= 2 && ($u[1] & 0xE0) == 0xC0)  
        {
            // 110xxxxx   
            return (($u[1] & 0x1F) << 6) | ($u[2] & 0x3F);  
        }
        else if ($count >= 3 && ($u[1] & 0xF0) == 0xE0)
        {
            // 1110xxxx 
            return (($u[1] & 0x0F) << 12) 
               | (($u[2] & 0x3F) << 6) | ($u[3] & 0x3F);
        }
        else
        {
            // higher characters, or malformed string, return '?'
            return ord('?');
        }
    }
}

// -----------------------------------------------------------------------------

function echo_html_comment($comment_body)
{
    // We need to check/tweak $comment_body to ensure that we generate a valid
    // HTML comment. The actual syntax of HTML comments is rather interesting --
    // see http://htmlhelp.com/reference/wilbur/misc/comment.html for some
    // discussion and surprising examples.
    // Of course, what browsers accept is a different matter.
    // Here, we adopt the rule that a comment's body cannot contain two adjacent
    // hyphens, and so replace any double-hyphen in $comment_body with the
    // similar-looking double-tilde.
    $n_occurrences = substr_count($comment_body, '--');
    $safe_comment_body = str_replace('--', '~~', $comment_body);

    echo "<!--\n";
    if ( $n_occurrences > 0 )
    {
        echo "(NOTE: In this comment, $n_occurrences occurrence(s) of double-hyphen have been changed to double-tilde.)\n";
    }
    echo $safe_comment_body;
    echo "\n-->";
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

// Return a list of only the files directly inside in the specified directory.
// On error, returns False.
//
// $dirpath: the directory to search in. May be relative to the cwd.
// $validext: an array of extensions to check filenames against, may inclue the '.'
// $with_path: If true, the list of returned names has the directory prepended.
function get_filelist($dirpath, $validext = array(), $with_path = False)
{
    $filelist = array();
    if (false === ($d = opendir($dirpath))) return False;
    while (false !== ($f = readdir($d)))
    {
        // Ignore non-files
        if (!is_file("$dirpath/$f")) continue;

        // If we're not filering on validexts, all filenames are OK
        $filename_ok = count($validext) == 0;

        // Otherwise iterate over all the extensions,
        // stopping if we've matched on any so far.
        foreach($validext as $ext)
        {
            if ($filename_ok) break;
            $filename_ok = endswith($f, $ext);
        }
        if ($filename_ok)
        {
            $filelist[] = $with_path ? "$dirpath/$f" : $f;
        }
    }
    closedir($d);
    return $filelist;
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

// stripos() is predefined in PHP 5 and on.
if ( !function_exists('stripos') )
{
    function stripos($haystack, $needle, $offset = 0)
    {
        return strpos(strtolower($haystack), strtolower($needle), $offset);
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

// openssl_random_pseudo_bytes() is defined in PHP 5.2 and later
if ( !function_exists('openssl_random_pseudo_bytes') )
{
    // based on:
    // http://us.php.net/manual/en/function.openssl-random-pseudo-bytes.php#93078
    function openssl_random_pseudo_bytes($length)
    {
        $length_n = (int) $length; // shell injection is no fun
        $handle = popen("RANDFILE=/tmp/php-openssl.rnd /usr/bin/openssl rand $length_n", "r");
        $data = stream_get_contents($handle);
        pclose($handle);

        // if no data, the call to openssl failed, fall back to /dev/urandom
        if(!$data) {
            $handle = fopen("/dev/urandom","r");
            $data = fread($handle, $length);
            fclose($handle);
        }
        return $data;
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

// The following became PHP constants in PHP 4.3.0. 
define_if_necc( 'UPLOAD_ERR_OK',         0 );
define_if_necc( 'UPLOAD_ERR_INI_SIZE',   1 );
define_if_necc( 'UPLOAD_ERR_FORM_SIZE',  2 );
define_if_necc( 'UPLOAD_ERR_PARTIAL',    3 );
define_if_necc( 'UPLOAD_ERR_NO_FILE',    4 );
define_if_necc( 'UPLOAD_ERR_NO_TMP_DIR', 6 );
define_if_necc( 'UPLOAD_ERR_CANT_WRITE', 7 );
define_if_necc( 'UPLOAD_ERR_EXTENSION',  8 );

function get_upload_err_msg($upload_error_code)
// Return an error message appropriate to $upload_error_code.
// (The wording is taken from "Handling File Uploads: Error Messages Explained"
// in the PHP online documentation.)
{
    switch ($upload_error_code)
    {
        case UPLOAD_ERR_OK:
            return _('There is no error, the file uploaded with success.');
        case UPLOAD_ERR_INI_SIZE:
            return _('The uploaded file exceeds the upload_max_filesize directive in php.ini.');
        case UPLOAD_ERR_FORM_SIZE:
            return _('The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.');
        case UPLOAD_ERR_PARTIAL:
            return _('The uploaded file was only partially uploaded.');
        case UPLOAD_ERR_NO_FILE:
            return _('No file was uploaded.');
        case UPLOAD_ERR_NO_TMP_DIR:
            return _('Missing a temporary folder.');
        case UPLOAD_ERR_CANT_WRITE:
            return _('Failed to write file to disk.');
        case UPLOAD_ERR_EXTENSION:
            return _('File upload stopped by extension.');
        default:
            return _('Unknown upload error code');
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

// -----------------------------------------------------------------------------

function requester_is_localhost()
// function to determine if page requester is from localhost
{
    if($_SERVER['REMOTE_ADDR'] == '127.0.0.1' ||
        $_SERVER['REMOTE_ADDR'] == $_SERVER['SERVER_ADDR'])
    {
        return TRUE;
    }
    else
    {
        return FALSE;
    }
}

// -----------------------------------------------------------------------------

function is_url_for_current_page($url)
// Determine if the specified URL is for the current page by comparing the
// paths and the query parameters, but not any anchors.
// This function, by design, will return TRUE if the URL query parameters
// are a subset of the current page. For example, if the current page is:
//    http://www.pgdp.net/c/tools/proofers/round.php?round_id=P1&numofpages=12
// and the URL to check is:
//    http://www.pgdp.net/c/tools/proofers/round.php?round_id=P1
// the function will return TRUE despite the provided URL not having a
// numofpages parameter. The converse, however, is not true.
{
    $url_components = parse_url($url);
    
    // first check the path
    if(@$url_components['path'] != $_SERVER['SCRIPT_NAME'])
    {
        return FALSE;
    }
    
    // now check the queries
    if(isset($url_components['query']))
    {
        $parameters = explode('&', $url_components['query']);
        foreach($parameters as $parameter)
        {
            list($key, $value) = explode('=', $parameter);
            if(@$_GET[$key] != $value)
            {
                return FALSE;
            }
        }
    }
    
    return TRUE;
}

// -----------------------------------------------------------------------------

function undo_all_magic_quotes()
// Detect if magic_quotes_gpc is enabled, and if so strip the slashes from
// all magic-quoted variables to return them to their pre-escaped form.
// Initially we'll use this as a transition function to get us off
// magic_quotes_gpc. To do so we'll change each file one at a time to use
// this function. After all files are using it, we'll move it to a global
// include file and remove it from all individual files and recommend that
// users disable magic_quotes_gpc from PHP altogether.
//
// NOTE: Using this function requires you to do appropriate escaping when
// outputting variables in SQL and HTML. ie:
// Code like the following relies on magic_quotes_gpc being on:
//    $x = $_GET['x'];
//    $res = mysql_query("... WHERE x = '$x'");
// Whereas the following works the same regardless of how magic_quotes_gpc
// is set...
//    undo_all_magic_quotes();
//    $x = $_GET['x'];
//    $res = mysql_query(
//               sprintf("... WHERE x = '%s'", mysql_real_escape_string($x))
//    );
{
    if (get_magic_quotes_gpc())
    {
        stripslashes_arr_inplace($_GET);
        stripslashes_arr_inplace($_POST);
        stripslashes_arr_inplace($_COOKIE);
        stripslashes_arr_inplace($_REQUEST);
        // Also the deprecated $HTTP_* vars, but we don't use them.
        // In PHP 4, $_ENV too, but we don't use it.
    } 
}

function stripslashes_arr_inplace(&$value, $key = NULL)
// Run stripslashes on an array that may contain other arrays.
// For space efficiency it changes the values in-place by taking the
// first param as a reference.
{
    if(is_array($value))
        array_walk($value, 'stripslashes_arr_inplace');
    else
        $value = stripslashes($value);

    // no need to return $value as it was passed by reference
}

// vim: sw=4 ts=4 expandtab
