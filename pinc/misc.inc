<?php
// Miscellaneous little (non-DP-specific) functions

use voku\helper\UTF8;

function array_get($arr, $key, $default)
// Return $arr[$key], or if it's not defined, $default.
{
    if (isset($arr[$key])) {
        return $arr[$key];
    } else {
        return $default;
    }
}

function get_enumerated_param($arr, $key, $default, $choices, $allownull = false)
// If $arr[$key] is defined and is one of the strings in $choices,
// return that string.
// If it's not defined, and $default is non-null, return $default.
// If $default is null and $allownull is true, return null (useful for optional params).
// Otherwise throw an InvalidArgumentException with an error.
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
        assert(is_array($arr));
        assert(!is_array($key));

        assert(is_null($default) || is_string($default));
        assert(is_array($choices));
        assert(count($choices) > 0);

        if (!is_null($default)) {
            assert(in_array($default, $choices));
            assert(!$allownull);
        }
    }

    if (isset($arr[$key])) {
        $s = $arr[$key];

        if (!is_string($s)) {
            throw new InvalidArgumentException(sprintf(
                _("Parameter '%1\$s' is not a valid type"),
                $key));
        }

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
        if (is_int(current($choices))) {
            if (!is_numeric($s)) {
                throw new InvalidArgumentException(sprintf(
                    _("Parameter '%1\$s' ('%2\$s') is not numeric"),
                    $key, $s));
            }
            $s = 0 + $s;
            if (!is_int($s)) {
                throw new InvalidArgumentException(sprintf(
                    _("Parameter '%1\$s' ('%2\$s') is not an integer"),
                    $key, $s));
            }
        }

        if (in_array($s, $choices)) {
            return $s;
        } else {
            throw new InvalidArgumentException(sprintf(
                _("Parameter '%1\$s' ('%2\$s') is not one of %3\$s"),
                $key, $s, implode(", ", $choices)));
        }
    } else {
        // parameter not set, use default
        if (is_null($default) && !$allownull) {
            // There is no default. The parameter is required.
            throw new InvalidArgumentException(sprintf(
                _("Parameter '%1\$s' is required"),
                $key));
        } else {
            return $default;
        }
    }
}

function get_integer_param($arr, $key, $default, $min, $max, $allownull = false)
// If $arr[$key] is defined and is the string-rep of an integer
// satisfying certain simple constraints, return that integer.
// If it's not defined, and $default is non-null, return $default.
// If it's not defined, and $default is null and $allownull is true, return null.
// Otherwise, throw an InvalidArgumentException with an error.
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
    return get_numeric_param("integer", $arr, $key, $default, $min, $max, $allownull);
}

function get_float_param($arr, $key, $default, $min, $max, $allownull = false)
// This function operates identically to get_integer_param() except for floats
{
    return get_numeric_param("float", $arr, $key, $default, $min, $max, $allownull);
}

function get_numeric_param($type, $arr, $key, $default, $min, $max, $allownull)
// Parent function of get_integer_param() and get_float_param(), use those
// versions instead.
//
// $type should be one of "integer" or "float".
{
    {
        // sanity checks on the args
        assert(is_array($arr));
        assert(!is_array($key));

        if ($type == 'integer') {
            assert(is_null($default) || is_int($default));
        } else {
            assert(is_null($default) || is_float($default));
        }
        assert(is_null($min) || is_numeric($min));
        assert(is_null($max) || is_numeric($max));

        if (!is_null($default)) {
            if (!is_null($min)) {
                assert($default >= $min);
            }
            if (!is_null($max)) {
                assert($default <= $max);
            }
            assert(!$allownull);
        }

        if (!is_null($min) && !is_null($max)) {
            assert($min <= $max);
        }
    }

    if (isset($arr[$key])) {
        $s = $arr[$key];

        if (!is_string($s)) {
            throw new InvalidArgumentException(sprintf(
                _("Parameter '%1\$s' is not a valid type"),
                $key));
        }

        // Trim whitespace from both ends of the string.
        $s = trim($s);

        if ($type == 'integer') {
            if (preg_match('/^[-+]?\d+$/', $s)) {
                // It's a well-formed (decimal) rep of an integer
                $i = intval($s);
            } else {
                throw new InvalidArgumentException(sprintf(
                    _("Parameter '%1\$s' ('%2\$s') is not an integer"),
                    $key, $s));
            }
        } elseif ($type == 'float') {
            // We'll accept an integer or a floating point number
            if (preg_match('/^[-+]?\d+$/', $s) ||
                 preg_match('/^[-+]?\d*\.\d+$/', $s)) {
                // It's a well-formed (decimal) rep of a float
                $i = floatval($s);
            } else {
                throw new InvalidArgumentException(sprintf(
                    _("Parameter '%1\$s' ('%2\$s') is not a float"),
                    $key, $s));
            }
        }

        if (!is_null($min) && $i < $min) {
            throw new InvalidArgumentException(sprintf(
                _("Parameter '%1\$s' ('%2\$s') is less than the minimum %3\$d"),
                $key, $s, $min));
        }

        if (!is_null($max) && $i > $max) {
            throw new InvalidArgumentException(sprintf(
                _("Parameter '%1\$s' ('%2\$s') is greater than the maximum %3\$d"),
                $key, $s, $max));
        }

        return $i;
    } else {
        // not set, use default
        if (is_null($default) && !$allownull) {
            // There is no default. The parameter is required.
            throw new InvalidArgumentException(sprintf(
                _("Parameter '%1\$s' is required"),
                $key));
        } else {
            return $default;
        }
    }
}

function get_param_matching_regex($arr, $key, $default, $regex, $allownull = false)
// If $arr[$key] is defined and matches $regex, return it.
// If it's not defined, and $default is non-null, return $default.
// If it's not defined, and $default is null and $allownull is true, return null.
// Otherwise, throw an InvalidArgumentException with an error.
//
// NOTE:
// For security, $regex should almost certainly be anchored at both ends,
// and should not contain:
//  -- any complemented character classes (e.g. "[^A-Z]"), or
//  -- (unescaped) '.' (e.g., in "/foo.+bar/").
//
// Example usage:
//     $word = get_param_matching_regex($_GET, 'word', NULL, '/^\w+$/')
// Parameter 'word' is required, and its supplied value must consist entirely
// of alphanumeric characters (including underscore).
{
    // sanity checks on args
    {
        assert(is_array($arr));
        assert(!is_array($key));

        assert(is_null($default) || is_string($default));
        assert(is_string($regex));

        if (!is_null($default)) {
            assert(!$allownull);
        }
    }

    if (isset($arr[$key])) {
        $s = $arr[$key];

        if (!is_string($s)) {
            throw new InvalidArgumentException(sprintf(
                _("Parameter '%1\$s' is not a valid type"),
                $key));
        }

        // Trim whitespace from both ends of the string.
        $s = trim($s);

        if (preg_match($regex, $s)) {
            return $s;
        } else {
            throw new InvalidArgumentException(sprintf(
                _("Parameter '%1\$s' ('%2\$s') does not match the regex %3\$s"),
                $key, $s, $regex));
        }
    } else {
        // not set, use default
        if (is_null($default) && !$allownull) {
            // There is no default. The parameter is required.
            throw new InvalidArgumentException(sprintf(
                _("Parameter '%1\$s' is required"),
                $key));
        } else {
            return $default;
        }
    }
}

// -----------------------------------------------------------------------------

// Return a copy of a string correctly encoded for inclusion in an HTML
// document. This is essentially a wrapper around htmlspecialchars() but it
// is aware of the global character set. Using this wrapper means we don't
// have to rely on the global $charset variable being accessible everywhere
// we need to call htmlspecialchars().
//
// By default this escapes all quotes with ENT_QUOTES. This can be changed
// with the $flags argument which is passed directly into htmlspecailchars().
//
// If the $charset global isn't defined, use whatever the PHP default_charset
// is. Note that using default_charset is the default behavior for
// htmlspecialchars in PHP >= 5.6, whereas 5.4 and 5.5 use UTF-8.
function html_safe($string, $flags = ENT_QUOTES)
{
    $charset = @$GLOBALS['charset'];
    if (!@$charset) {
        $charset = ini_get("default_charset");
    }
    return htmlspecialchars($string, $flags, $charset);
}

// -----------------------------------------------------------------------------

function attr_safe($string)
//  Return a copy of $string that has been rendered safe to send as
//  (part of) the value of an attribute in an HTML or XML document.
//
//  The result is then safe for inclusion in attribute values,
//  whether they are delimited by single- or double-quotes. E.g.:
//      $result = attr_safe("Don't!");
//      echo "<e attr='$result'>";
//      echo "<e attr=\"$result\">";
//      echo '<e attr="' . $result . '">';
//
//  It does not encode valid HTML entities. The reason for this is
//  that one of the intended usage scenarios for this function is to
//  handle the results of gettext-translation, and we want to allow
//  translators to use character references in their translations.
//  For example, a French translator might translate
//      Name of work
//  as
//      Nom de l'&oelig;uvre
//  If we have the code:
//      echo "<e attr='" . attr_safe(_('Name of Work')) . "'>";
//  then
//      _('Name of work')
//  would, in a French locale, evaluate to the string value:
//      Nom de l'&oelig;uvre
//  and we want attr_safe(...) to return:
//      Nom de l&#39;&oelig;uvre
//  not:
//      Nom de l&#39;&amp;oelig;uvre
{
    global $charset;
    return htmlspecialchars($string, ENT_QUOTES, $charset,
                            false /* $double_encode */);
}

// -----------------------------------------------------------------------------

function javascript_safe($str, $encoding = null)
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
    global $charset;
    if (!$encoding) {
        $encoding = $charset;
    }

    if (!strcasecmp($encoding, 'UTF-8')) {
        $str = preg_replace_callback('/([\000-\037\"\'\\\\<\177])/',
            function ($matches) {
                return sprintf("\\%03o", ord($matches[0]));
            }, $str);

        // convert non-7bit ascii characters to \uHHHH notation
        $str = preg_replace_callback('/([^\000-\177])/u',
            function ($matches) {
                return sprintf("\\u%04x", UTF8::ord($matches[0]) & 0xffffff);
            }, $str);
    } else {
        // assume Latin-1 encoding
        $str = preg_replace_callback('/([\000-\017\\\\"\'<\177-\237])/',
            function ($matches) {
                return sprintf("\\%03o", ord($matches[0]));
            }, $str);
    }
    return $str;
}

// -----------------------------------------------------------------------------

// Use HTMLPurifier to sanitize HTML
function sanitize_html($html, $parent_tag = 'div')
{
    $config = HTMLPurifier_Config::createDefault();
    $config->set('HTML.Parent', $parent_tag);
    $config->set('Attr.EnableID', true);
    $config->set('CSS.AllowTricky', true);
    $config->set('CSS.MaxImgLength', null);
    $config->set('Cache.SerializerPath', sys_get_temp_dir());

    $purifier = new HTMLPurifier($config);
    return $purifier->purify($html);
}

// -----------------------------------------------------------------------------

function normalize_whitespace($str)
// Return a string with normalized whitespace characters.
//
// Example usage:
//   echo normalize_whitespace("William  Shakespeare");
//   echo normalize_whitespace("William Shakespeare ");
//   echo normalize_whitespace(" William Shakespeare");
//   echo normalize_whitespace("\tWilliam\tShakespeare\t");
//
//   They all return "William Shakespeare".
//
//
// Replaces each run of whitespace characters with a single space and
//   strips the resulting string.
{
    return trim(preg_replace('/\s+/u', ' ', $str));
}


// -----------------------------------------------------------------------------

// Safely encode a string for use in an XML document.
function xmlencode($string)
{
    global $charset;
    return htmlspecialchars($string, ENT_COMPAT, $charset);
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
    if ($n_occurrences > 0) {
        echo "(NOTE: In this comment, $n_occurrences occurrence(s) of double-hyphen have been changed to double-tilde.)\n";
    }
    echo $safe_comment_body;
    echo "\n-->";
}

// -----------------------------------------------------------------------------

function startswith($subject, $prefix)
// Return TRUE iff $subject starts with $prefix.
{
    return (strncmp($subject, $prefix, strlen($prefix)) == 0);
}

function endswith($subject, $suffix)
// Return TRUE iff $subject ends with $suffix.
{
    return (substr($subject, -strlen($suffix)) == $suffix);
}

if (!function_exists('str_contains')) {
    // str_contains() now exists in PHP8
    function str_contains($haystack, $needle)
    {
        return (strpos($haystack, $needle) !== false);
    }
}

function surround_and_join($strings, $L, $R, $joiner)
{
    $parts = [];
    foreach ($strings as $string) {
        $parts[] = $L . $string . $R;
    }
    return implode($joiner, $parts);
}

/**
 * Return a SQL "colname='value'" clause, where `$colname` is
 * not escaped, and `$s` is an escaped and quoted string.
 */
function set_col_str($colname, $s)
{
    return sprintf("%s='%s'", $colname, DPDatabase::escape($s));
}

/**
 * Return a SQL "colname=value" clause, where `$colname` is
 * not escaped, and `$i` is treated as an integer.
 *
 * If `$i` is non-numeric, an `InvalidArgumentException` is thrown.
 */
function set_col_num($colname, $i)
{
    if (!is_numeric($i)) {
        throw new InvalidArgumentException("$i is not a number");
    }
    return sprintf("%s=%d", $colname, $i);
}

// Given an associative array, return a MySQL string that can be use in an
// INSERT or UPDATE string.
// $source_array is an associative array where the keys are MySQL column
//    names and the values are the desired values to set in the database
// $string_fields
//    is a list of keys such that $source_array[$key] should be a string,
//    otherwise the value is assumed to be an integer
// This function checks that those expectations are satisfied, and constructs a
// string of column=value 'assignments' that can be used in an SQL UPDATE
// command (where each $key is assumed to be a column name). (String values
// will be properly escaped in this string.)
//
// Currently this function will set default values ("" for strings, 0 for
// numeric values) for all fields that are not within $source_array.
function create_mysql_update_string($source_array, $string_fields = [])
{
    $update_fields = [];
    foreach ($source_array as $field => $value) {
        if (in_array($field, $string_fields)) {
            $update_fields[] = set_col_str($field, trim($value));
        } else {
            $update_fields[] = set_col_num($field, $value ? intval($value) : 0);
        }
    }

    return join(", ", $update_fields);
}

// Return a list of only the files directly inside in the specified directory.
// On error, returns False.
//
// $dirpath: the directory to search in. May be relative to the cwd.
// $validext: an array of extensions to check filenames against, may inclue the '.'
// $with_path: If true, the list of returned names has the directory prepended.
function get_filelist($dirpath, $validext = [], $with_path = false)
{
    $filelist = [];
    if (false === ($d = opendir($dirpath))) {
        return false;
    }
    while (false !== ($f = readdir($d))) {
        // Ignore non-files
        if (!is_file("$dirpath/$f")) {
            continue;
        }

        // If we're not filering on validexts, all filenames are OK
        $filename_ok = count($validext) == 0;

        // Otherwise iterate over all the extensions,
        // stopping if we've matched on any so far.
        foreach ($validext as $ext) {
            if ($filename_ok) {
                break;
            }
            $filename_ok = endswith($f, $ext);
        }
        if ($filename_ok) {
            $filelist[] = $with_path ? "$dirpath/$f" : $f;
        }
    }
    closedir($d);
    return $filelist;
}


// Flattens the contents of a directory recursively.
//
// For example, the following directory structure:
//      dir1/
//          a.txt
//          dir2/
//              b.txt
//          dir3/
//              dir4/
//                  c.txt
//
// would be flattened like so:
//      dir1/
//          a.txt
//          b.txt
//          c.txt
//
// If the first argument is not an existing directory, an InvalidArgumentException will be thrown.
// If it is unable to flatten the directory, it will throw a RuntimeException.
function flatten_directory(string $directory_to_flatten)
{
    if (!file_exists($directory_to_flatten) || !is_dir($directory_to_flatten)) {
        throw new InvalidArgumentException(sprintf(
            _("Argument '%1\$s' is not a valid output directory (it does not exist)."),
            $directory_to_flatten));
    }

    $iterator = new RecursiveDirectoryIterator($directory_to_flatten, RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::CHILD_FIRST);

    foreach ($files as $file) {
        if ($file->isDir()) {
            $result = rmdir($file->getRealPath());

            if (!$result) {
                throw new RuntimeException(sprintf(_("Could not remove directory '%1\$s' while flattening '%2\$s'."),
                    $file->getRealPath(), $directory_to_flatten));
            }
        } else {
            $new_name = $directory_to_flatten . '/' . $file->getFilename();
            $result = rename($file->getRealPath(), $new_name);

            if (!$result) {
                throw new RuntimeException(sprintf(_("Could not move file '%1\$s' to '%2\$s' while flattening '%3\$s'."),
                    $file->getRealPath(), $new_name, $directory_to_flatten));
            }
        }
    }
}

// -----------------------------------------------------------------------------
// Return whether the file is a valid zip file.
//
// The following conditions are used to check whether the file is considered a valid zip file:
//   * It must exist
//   * It must have the zip file extension
//   * It must be openable by ZipArchive
//
// If any of the conditions are not satisfied, it will return false, otherwise it will return true.
// The extension check can be disabled by passing true as the second argument.
function is_valid_zip_file(string $file_path, bool $ignore_extension_check = false): bool
{
    if (!file_exists($file_path)) {
        return false;
    }

    if (!$ignore_extension_check && !endswith($file_path, '.zip')) {
        return false;
    }

    $zip = new ZipArchive();

    $result = $zip->open($file_path);

    if ($result === true) {
        $zip->close();
    }

    return $result === true;
}

// Return an array of files contained within the zip archive.
//
// It uses the is_valid_zip_file function to check whether the argument is a valid zip file, if it
// is not, an InvalidArgumentException is thrown.
function list_files_in_zip(string $file_path): array
{
    if (!is_valid_zip_file($file_path)) {
        throw new InvalidArgumentException(sprintf(
            _("Argument '%1\$s' is not a valid zip file."),
            $file_path));
    }

    $zip = new ZipArchive();

    // No need to check the result of the function since it is checked in is_valid_zip_file
    $zip->open($file_path);

    $files = [];

    for ($i = 0; $i < $zip->numFiles; $i++) {
        $files[] = $zip->getNameIndex($i);
    }

    return $files;
}

// Extracts the zip directory's contents into the output directory.
//
// It uses the is_valid_zip_file function to check whether the first argument is a valid zip file, if it
// is not, an InvalidArgumentException is thrown.
//
// If the output directory does not exist or is not a valid directory, an InvalidArgumentException is thrown.
//
// Returns whether the extraction was successful.
function extract_zip_to(string $path_to_zip, string $output_directory): bool
{
    if (!is_valid_zip_file($path_to_zip)) {
        throw new InvalidArgumentException(sprintf(
            _("Argument '%1\$s' is not a valid zip file."),
            $path_to_zip));
    }

    if (!file_exists($output_directory) || !is_dir($output_directory)) {
        throw new InvalidArgumentException(sprintf(
            _("Argument '%1\$s' is not a valid output directory (it does not exist)."),
            $output_directory));
    }

    $zip = new ZipArchive();

    // No need to check the result of the function since it is checked in is_valid_zip_file
    $zip->open($path_to_zip);

    return $zip->extractTo($output_directory);
}

// If there is a common base directory remove it.
// Use is_valid_zip_file() to check the zip file,
// if it is not valid, throw an InvalidArgumentException.
function remove_common_basedir_from_zip(string $path_to_zip)
{
    if (!is_valid_zip_file($path_to_zip)) {
        throw new InvalidArgumentException(sprintf(
            _("Argument '%1\$s' is not a valid zip file."),
            $path_to_zip));
    }

    $zip = new ZipArchive();
    $zip->open($path_to_zip);

    $filenames = [];
    $last_index = $zip->numFiles - 1;
    for ($i = 0; $i <= $last_index; $i++) {
        $filenames[] = $zip->getNameIndex($i);
    }

    sort($filenames);

    // If there is a common base directory there will be a common prefix
    // ending in /. If the zip was made in Linux or macos the directory itself
    // will be listed but not for windows.
    // If the files are sorted, then if the first contains a / there could be
    // a common directory prefix. Check if the last has this prefix. Then all
    // files in the archives are within the directory and we remove it from all
    // of them. If the directory itself is listed (must be first) delete it.
    $first_file = $filenames[0];
    $first_solidus = strpos($first_file, "/");
    if ($first_solidus !== false) {
        $basedir_len = $first_solidus + 1;
        $basedir = substr($first_file, 0, $basedir_len);
        if (startswith($filenames[$last_index], $basedir)) {
            // we have a common subdirectory
            if (strlen($first_file) === $basedir_len) {
                // it is the dir name itself - delete it and remove basedir from others
                $zip->deleteName($first_file);
                $i = 1;
            } else {
                // remove basedir from all
                $i = 0;
            }
            while ($i <= $last_index) {
                $zip->renameName($filenames[$i], substr($filenames[$i], $basedir_len));
                $i++;
            }
        }
    }
    $zip->close();
}

// Creates a zip archive containing the specified files.
//
// The first argument represents the files to add to the zip archive and globing is allowed.
// If there is a directory the files from it are added with its name prefixed (only one level)
// The second argument represents the path to the zip archive which should be created.
//
// Returns whether the zip file was created successfully.
//
// Throws a InvalidArgumentException if:
//   1) It cannot open a ZipArchive using the second argument as the path
//   2) It cannot add one of the files specified by the first argument to the ZipArchive
function create_zip_from(array $files_to_zip, string $path_to_zip): bool
{
    $zip = new ZipArchive();

    if ($zip->open($path_to_zip, ZipArchive::CREATE) !== true) {
        throw new InvalidArgumentException(sprintf(
            _("Could not open '%1\$s' as a ZipArchive."),
            $path_to_zip));
    }

    foreach ($files_to_zip as $file) {
        if (is_dir($file)) {
            $status = $zip->addGlob("$file/*", 0, ['remove_all_path' => true, 'add_path' => basename($file) . "/"]);
        } else {
            $status = $zip->addGlob($file, 0, ['remove_all_path' => true]);
        }
        if (!$status) {
            throw new InvalidArgumentException(sprintf(_("Could not add '%1\$s' to '%2\$s' ZipArchive."), $file, $path_to_zip));
        }
    }

    return $zip->close();
}

// -----------------------------------------------------------------------------

function get_upload_err_msg($upload_error_code)
// Return an error message appropriate to $upload_error_code.
// (The wording is taken from "Handling File Uploads: Error Messages Explained"
// in the PHP online documentation.)
{
    switch ($upload_error_code) {
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

// Represent $n bytes as a  $m KB-- $m PB string
function humanize_bytes($n)
{
    $i = 0;
    $units = ["B", "KB", "MB", "GB", "TB", "PB"];
    while ($n >= 1024) {
        $i += 1;
        $n /= 1024.0;
    }
    $format = (($i == 0) ? "%d " : "%.2f ") . $units[$i];
    return sprintf($format, $n);
}

// -----------------------------------------------------------------------------

function factor_strings($strings)
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
    assert(count($strings) > 0);

    // Find the shortest string
    $str_with_minlen = null;
    $minlen = null;
    foreach ($strings as $string) {
        $len = strlen($string);
        if (is_null($minlen) || $len < $minlen) {
            $minlen = $len;
            $str_with_minlen = $string;
        }
    }

    $base = $str_with_minlen;

    // --------------------------------------------------------------

    for ($L = 0; ; $L++) {
        // Invariant: all strings match in their first $L characters.

        if ($L == $minlen) {
            break;
        }

        // Do they match in their first $L+1 characters?
        // Examine the ($L+1)th character, i.e. the one at index $L.

        $c = substr($base, $L, 1);
        foreach ($strings as $string) {
            if (substr($string, $L, 1) == $c) {
                // good so far
            } else {
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

    for ($R = 0; ; $R++) {
        // Invariant: all strings match in their last $R characters.

        if ($left_match_length + $R == $minlen) {
            break;
        }

        // Do they match in their last $R+1 characters?
        // Examine the ($R+1)th-last character, i.e., the one at index -($R+1).
        // e.g. when $R == 0, examine the last character, at index -1
        //      when $R == 1, examine the 2nd-last character, at index -2

        $c = substr($base, -($R + 1), 1);

        foreach ($strings as $string) {
            if (substr($string, -($R + 1), 1) == $c) {
                // good so far
            } else {
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

    $left_common = null;
    $right_common = null;
    $middles = [];

    foreach ($strings as $string) {
        assert($left_match_length >= 0);
        assert($right_match_length >= 0);
        assert($left_match_length + $right_match_length <= strlen($string));

        if ($left_match_length == strlen($string)) {
            // substr() misbehaves
            $left = $string;
            $middle = '';
            $right = '';
        } else {
            $left = substr($string, 0, $left_match_length);

            if ($right_match_length == 0) {
                $middle = substr($string, $left_match_length);
                $right = '';
            } else {
                $middle = substr($string, $left_match_length, -$right_match_length);
                $right = substr($string, -$right_match_length);
            }
        }

        if (is_null($left_common)) {
            $left_common = $left;
            $right_common = $right;
        } else {
            assert($left == $left_common);
            assert($right == $right_common);
        }

        $middles[] = $middle;
    }

    return [$left_common, $middles, $right_common];
}

// -----------------------------------------------------------------------------

function requester_is_localhost()
// function to determine if page requester is from localhost
{
    if (php_sapi_name() == "cli") {
        return true;
    }

    if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1' ||
        $_SERVER['REMOTE_ADDR'] == $_SERVER['SERVER_ADDR']) {
        return true;
    } else {
        return false;
    }
}

function require_localhost_request($deny_cli = false)
// If the requester is not localhost, bail
{
    if (!requester_is_localhost() ||
        ($deny_cli && php_sapi_name() == 'cli')) {
        die("You are not authorized to perform this request.\n");
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
    if (@$url_components['path'] != $_SERVER['SCRIPT_NAME']) {
        return false;
    }

    // now check the queries
    if (isset($url_components['query'])) {
        $parameters = explode('&', $url_components['query']);
        foreach ($parameters as $parameter) {
            [$key, $value] = explode('=', $parameter);
            if (@$_GET[$key] != $value) {
                return false;
            }
        }
    }

    return true;
}

function return_bytes($size_str)
// from http://php.net/manual/en/function.ini-get.php
{
    switch (substr($size_str, -1)) {
        case 'M': case 'm': return (int)$size_str * 1048576;
        case 'K': case 'k': return (int)$size_str * 1024;
        case 'G': case 'g': return (int)$size_str * 1073741824;
        default: return $size_str;
    }
}

// -----------------------------------------------------------------------------

class InvalidCSRFTokenException extends UnexpectedValueException
{
}

function set_csrf_token($force_set = true)
{
    if (!$force_set && @$_COOKIE["CSRFtoken"]) {
        return;
    }

    $token = bin2hex(random_bytes(32));
    dp_setcookie("CSRFtoken", $token, time() + 60 * 60 * 24, [
        "httponly" => true,
        "samesite" => "Strict",
    ]);
    $_COOKIE["CSRFtoken"] = $token;
}

function destroy_csrf_token()
{
    dp_setcookie("CSRFtoken", "", time() - 60);
}

function get_csrf_token_form_input()
{
    return "<input type='hidden' name='CSRFtoken' value='" . attr_safe($_COOKIE["CSRFtoken"]) . "'>";
}

function echo_csrf_token_form_input()
{
    echo get_csrf_token_form_input();
}

function validate_csrf_token()
{
    if (@$_POST["CSRFtoken"] != @$_COOKIE["CSRFtoken"]) {
        throw new InvalidCSRFTokenException(
            _("Form submission timeout, go back and try again.")
        );
    }
}


// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// Useful exceptions

class NotImplementedException extends BadMethodCallException
{
}
