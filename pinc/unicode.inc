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
function utf8_combined_chr($character)
{
    $codepoints = explode('>', $character);
    return implode('', array_map('utf8_chr', $codepoints));
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

# Given a string, return a string of codepoints in U+ format
function string_to_codepoints_string($string, $imploder = " ")
{
    return implode($imploder, utf8_codepoints($string, true));
}

# Filter to only characters in $string that are in $valid_characters.
function utf8_filter_to_codepoints($string, $valid_characters, $replacement="")
{
    $pattern_string = build_codepoint_regex_filter($valid_characters);
    $pattern_string = "/$pattern_string/u";
    return preg_replace_callback(
        "/\X/u",
        function ($matches) use($pattern_string, $replacement)
        {
            $match = $matches[0];
            if(1 === preg_match($pattern_string, $match))
            {
                return $match;
            }
            return $replacement;
        },
        $string);
}

# Filter out any characters in $string that are in $remove_codepoints
function utf8_filter_out_codepoints($string, $remove_codepoints, $replacement="")
{
    $char_class = "";
    foreach($remove_codepoints as $codepoint)
    {
        if(strpos($codepoint, '-') === False)
        {
            $char_class .= utf8_chr($codepoint);
        }
        else
        {
            list($start, $end) = explode('-', $codepoint);
            $char_class .= utf8_chr($start) . '-' . utf8_chr($end);
        }
    }
    if(!$char_class)
        return $string;

    $pattern_string = "/[$char_class]/u";
    return preg_replace($pattern_string, $replacement, $string);
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
function get_invalid_characters($string, $valid_characters)
{
    $char_class = build_codepoint_regex_filter($valid_characters);
    $pattern_string = "/$char_class/u";
    $char_count = [];
    if($string)
    {
        preg_replace_callback(
            "/\X/u",
            function ($matches) use($pattern_string, &$char_count)
            {
                $char = $matches[0];
                if(0 === preg_match($pattern_string, $char))
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
            },
            $string);
    }
    return $char_count;
}

# Take an array of Unicode codepoints (U+####), codepoint ranges
# (U+####-U+####), or combined characters (U+####>U+####) and
# return a regular expression character class to match them.
function build_codepoint_regex_filter($characters)
{
    $char_class = "";
    $alternatives = [];
    foreach($characters as $character)
    {
        if(strpos($character, '-') !== False)
        {
            # we have a character range
            list($start, $end) = explode('-', $character);
            $char_class .= utf8_chr($start) . '-' . utf8_chr($end);
        }
        elseif(strpos($character, '>') !== False)
        {
            # we have a combined character
            $alternatives[] = utf8_combined_chr($character);
        }
        else
        {
            # just a regular unicode character
            $char_class .= utf8_chr($character);
        }
    }
    $alternatives[] = "[$char_class]";
    // add begin and end anchors
    // don't add /.../u yet so we can use it also in javascript
    return "^(?:" . implode("|", $alternatives) . ")$";
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

    $text = mb_convert_encoding($text, "UTF-8", $encoding);
    file_put_contents($filename, $text);

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
