<?php
include_once($relPath."3rdparty/portable-utf8/portable-utf8.php");

function utf8_substr_replace($string, $replacement, $start, $length)
{
    $start_string = utf8_substr($string, 0, $start);
    $end_string = utf8_substr($string, $start + $length, utf8_strlen($string) - 1);
    return $start_string . $replacement . $end_string;
}

# Normalize UTF-8 strings to NFC
function utf8_normalize($string)
{
    $normalizer = new Normalizer();
    return $normalizer->normalize($string);
}

# Given one or more codepoints, return the UTF-8 character that it describes.
# This is very similar to utf8_chr(), except if $codepoint contains a >, it
# is assumed to represent a combined character.
function utf8_combined_chr($codepoint)
{
    $codepoints = explode('>', $codepoint);
    return implode('', array_map('utf8_chr', $codepoints));
}

# Given a character, return the Unicode script it belongs to
function utf8_char_script($char)
{
    $enum = IntlChar::getIntPropertyValue($char, IntlChar::PROPERTY_SCRIPT);
    return IntlChar::getPropertyValueName(IntlChar::PROPERTY_SCRIPT, $enum);
}

# Given a string, return an array of Unicode scripts used within the string.
function utf8_string_scripts($string)
{
    $scripts = [];

    // Reduce the string completely, or after 20 iterations in case PHP's
    // PREG library doesn't have the complete set of Unicode scripts.
    // Most strings will be through here in only as many Unicode scripts it
    // contains, usually just one or two.
    for($i = 0; $i < 20; $i++)
    {
        if(strlen($string) == 0)
            break;

        $char = utf8_substr($string, 0, 1);
        $script_name = utf8_char_script($char);
        $scripts[] = $script_name;
        $string = preg_replace("/\p{".$script_name."}/u", "", $string);
    }

    // Fall back to character by character to make sure we have everything.
    // Most cases will skip this.
    if($string)
    {
        foreach(utf8_split($string) as $char)
        {
            $scripts[] = utf8_char_script($char);
        }
    }

    return array_unique($scripts);
}

# Get codepoints that are not normalized.
# Returns an associative array where the key is the nonnormalized codepoint
# and the value is the normalized version. If all codepoints are normalized
# it returns an empty array.
function get_nonnormalized_codepoints($codepoints)
{
    $characters = convert_codepoint_ranges_to_characters($codepoints);
    $nonnorm_codepoints = [];
    foreach($characters as $character)
    {
        $norm_character = utf8_normalize($character);
        if($character != $norm_character)
        {
            $norm_codepoint = implode('>', array_map('utf8_chr_to_hex', utf8_split($norm_character)));
            $orig_codepoint = implode('>', array_map('utf8_chr_to_hex', utf8_split($character)));
            $nonnorm_codepoints[$orig_codepoint] = $norm_codepoint;
        }
    }
    return $nonnorm_codepoints;
}

# Given a single character, or a combined character, return its name
function utf8_character_name($characters)
{
    $title_pieces = [];
    foreach(utf8_split($characters) as $char)
    {
        $title_pieces[] = IntlChar::charName($char);
    }
    return implode(" + ", $title_pieces);
}

function split_graphemes($string)
{
    $next = 0;
    $maxbytes = strlen($string);

    while ($next < $maxbytes)
    {
        yield grapheme_extract($string, 1, GRAPHEME_EXTR_COUNT, $next, $next);
    }
}

# Split a Unicode string into codepoints keeping combining characters together
function utf8_codepoints_combining($string)
{
    foreach(split_graphemes($string) as $grapheme)
    {
        $codepoints[] = string_to_codepoints_string($grapheme, ">");
    }
    return $codepoints;
}

# Given a string, return a string of codepoints in U+ format
function string_to_codepoints_string($string, $imploder=" ")
{
    return implode($imploder, utf8_codepoints($string, true));
}

# Filter to only characters in $string that are in $valid_codepoints.
function utf8_filter_to_codepoints($string, $valid_codepoints, $replacement="")
{
    $pattern_string = build_character_regex_filter($valid_codepoints);
    $result = "";
    foreach(split_graphemes($string) as $grapheme)
    {
        if(1 === preg_match("/$pattern_string/u", $grapheme))
        {
            $result .= $grapheme;
        }
        else
        {
            $result .= $replacement;
        }
    }

    return $result;
}

# Filter out any characters in $string that are in $remove_codepoints
function utf8_filter_out_codepoints($string, $remove_codepoints, $replacement="")
{
    $pattern_string = build_character_regex_filter($remove_codepoints);
    $result = "";
    foreach (split_graphemes($string) as $grapheme) {
        if(1 === preg_match("/$pattern_string/u", $grapheme))
        {
            $result .= $replacement;
        }
        else
        {
            $result .= $grapheme;
        }
    }
    return $result;
}

# Take an array of Unicode codepoints (U+####), codepoint ranges
# (U+####-U+####), or combined characters (U+####>U+####) and
# return a regular expression character class to match a single
# character.
function build_character_regex_filter($codepoints)
{
    $char_class = "";
    $alternatives = [];
    foreach($codepoints as $codepoint)
    {
        if(strpos($codepoint, '-') !== False)
        {
            # we have a character range
            list($start, $end) = explode('-', $codepoint);
            $char_class .= utf8_chr($start) . '-' . utf8_chr($end);
        }
        elseif(strpos($codepoint, '>') !== False)
        {
            # we have a combined character
            $alternatives[] = utf8_combined_chr($codepoint);
        }
        else
        {
            # just a regular unicode character
            $char_class .= utf8_chr($codepoint);
        }
    }
    $alternatives[] = "[$char_class]";
    return "^(?:" . implode("|", $alternatives) . ")\$";
}

# Take an array of Unicode codepoints (U+####), codepoint ranges
# (U+####-U+####), or combined characters (U+####>U+####) and
# return an array of unicode characters.
function convert_codepoint_ranges_to_characters($codepoints)
{
    $return_array = [];
    if(!is_array($codepoints))
        $codepoints = [$codepoints];

    foreach($codepoints as $codepoint)
    {
        if($codepoint === NULL)
        {
            $return_array[] = NULL;
        }
        elseif(strpos($codepoint, '-') !== False)
        {
            # we have a character range
            list($start, $end) = explode('-', $codepoint);
            $return_array = array_merge($return_array, utf8_range($start, $end));
        }
        else
        {
            # a single character or a combined character
            $return_array[] = utf8_combined_chr($codepoint);
        }
    }
    return $return_array;
}

# Return an array of invalid characters and their count
function get_invalid_characters($string, $codepoints)
{
    $string = utf8_filter_out_codepoints($string, $codepoints);
    $char_count = [];
    if($string)
    {
        foreach (split_graphemes($string) as $char)
        {
            if(!isset($char_count[$char]))
            {
                $char_count[$char] = 1;
            }
            else
            {
                $char_count[$char]++;
            }
        }
    }
    return $char_count;
}

# Convert a text file to UTF-8, guessing its encoding returns:
# [ $success, $message ]
# where $success is TRUE if the file is already UTF-8 or was
# sucessfully converted. $message is a more detailed message.
function convert_text_file_to_utf8($filename)
{
    $text = file_get_contents($filename);
    $encoding = guess_string_encoding($text);

    if($encoding === FALSE)
    {
        return [ FALSE, "Unable to detect coding for $filename." ];
    }

    if($encoding == 'UTF-8')
    {
        return [ TRUE, "$filename already in UTF-8." ];
    }

    if(!is_writeable($filename))
    {
        return [ FALSE, "Unable to write to $filename." ];
    }

    $text = mb_convert_encoding($text, "UTF-8", $encoding);
    $success = file_put_contents($filename, $text);

    if($success === FALSE)
    {
        return [ FALSE, "Failure trying to write to $filename." ];
    }

    return [ TRUE, "$filename was converted from $encoding to UTF-8." ];
}

# Attempt to detect a string's encoding from a subset of expected encodings:
#   * UTF-8 (includes pure-ASCII which is a valid subset)
#   * UTF-16BE, UTF-16LE, UTF-32BE, UTF-32LE based on the BOM
#   * Windows-1252
#   * ISO-8859-1
# These strings match what mb_detect_encoding() would return. The function
# returns False if it's unable to guess, although it will readily return
# ISO-8859-1 in many circumstances.
function guess_string_encoding($text)
{
    if(preg_match('//u', $text))
        return 'UTF-8';

    # evaluate the BOM, if one exists, borrowed from
    # https://stackoverflow.com/questions/49546403/php-checking-if-string-is-utf-8-or-utf-16le
    $first2 = substr($text, 0, 2);
    $first4 = substr($text, 0, 4);
    if ($first4 == "\x00\x00\xFE\xFF") return 'UTF-32BE';
    elseif ($first4 == "\xFF\xFE\x00\x00") return 'UTF-32LE';
    elseif ($first2 == "\xFE\xFF") return 'UTF-16BE';
    elseif ($first2 == "\xFF\xFE") return 'UTF-16LE';

    # if there are any characters in ranges that are either control characters
    # or invalid for ISO-8859-1 or CP-1252, return False
    if(preg_match('/[\x00-\x08\x0E-\x1F\x81\x8D\x8F\x90\x9D]/', $text, $matches))
        return False;

    # if we get here, we're going to assume it's either Windows-1252 or ISO-8859-1.
    # if the string contains characters in the ISO-8859-1 reserved range,
    # that's probably Windows-1252
    if(preg_match('/[\x80-\x9F]/', $text))
        return 'Windows-1252';

    # Give up and return ISO-8859-1
    return 'ISO-8859-1';
}

# Return a list of Unicode codepoints we want to replace with some ASCII
# equivalence. We convert these codepoints to ASCII up on page save in
# DPage.inc.
function get_utf8_to_ascii_codepoints()
{
    return [
        '-' => [
            'U+2011', # non-breaking hyphen
            'U+2013', # en-dash
            'U+2010', # hyphen
            'U+2212', # minus sign
        ],
        '--' => [
            'U+2014', # em-dash
            'U+2012', # figure dash
            'U+2015', # horizontal bar
        ],
        '"' => [
            'U+201c', # open curly double quote
            'U+201d', # closing curly double quote
        ],
        "'" => [
            'U+2018', # open curly single quote
            'U+2019', # closing curly single quote
        ],
        "..." => [
            'U+2026', # ellipsis
        ],
        " " => [
            'U+0009', # tab
            'U+00a0', # no-break space
            'U+1680', # ogham space mark
            'U+2000', # en quad
            'U+2001', # em quad
            'U+2002', # en space
            'U+2003', # em space
            'U+2004', # three-per-em space
            'U+2005', # four-per-em space
            'U+2006', # six-per-em space
            'U+2007', # figure space
            'U+2008', # punctuation space
            'U+2009', # thin space
            'U+20a0', # hair space
            'U+202f', # narrow no-break space
            'U+205f', # medium mathematical space
            'U+3000', # ideographic space
        ],
        "\n" => [
            'U+000b', # vertical tab
            'U+000c', # form feed
            'U+0085', # next line
            'U+2028', # line separator
            'U+2029', # paragraph separator
        ],
    ];
}

# Return a list of disallowed codepoints. These codepoints are converted
# to ASCII upon page save and are not valid in character suites.
function get_disallowed_codepoints()
{
    $invalid_codepoints = [];
    foreach(get_utf8_to_ascii_codepoints() as $ascii => $codepoints)
    {
        $invalid_codepoints = array_merge($invalid_codepoints, $codepoints);
    }
    return $invalid_codepoints;
}
