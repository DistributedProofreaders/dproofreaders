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

# Filter to only characters in $string that are in $valid_codepoints.
function utf8_filter_to_codepoints($string, $valid_codepoints, $replacement="")
{
    $char_class = "";
    foreach($valid_codepoints as $codepoint)
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

    $pattern_string = "/[^$char_class]/u";
    return preg_replace($pattern_string, $replacement, $string);
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

# Take an array of Unicode codepoints (U+####), or codepoint ranges
# (U+####-U+####) and return an array of unicode characters.
function convert_codepoint_ranges_to_characters($codepoints)
{
    $return_array = [];
    if(!is_array($codepoints))
        $codepoints = [$codepoints];
    foreach($codepoints as $codepoint)
    {
        if(strpos($codepoint, '-') === False)
        {
            $return_array[] = utf8_chr($codepoint);
        }
        else
        {
            list($start, $end) = explode('-', $codepoint);
            $return_array = array_merge($return_array, utf8_range($start, $end));
        }
    }
    return $return_array;
}

# Filter text to an array of codepoint / codepoint ranges. The function
# already accommodates new lines and handles whitespaces. $codepoints should
# only include desired characters for the project.
function filter_text_to_codepoints($string, $codepoints)
{
    # normalize string
    $string = utf8_normalize($string);

    # replace most Unicode whitespace with a space
    $string = utf8_filter_out_codepoints($string, get_utf8_whitespace_codepoints(), " ");

    # filter to desired codepoints
    $string = utf8_filter_to_codepoints($string, $codepoints);

    return $string;
}

function get_utf8_whitespace_codepoints()
{
    static $whitespace_codepoints = [];
    if($whitespace_codepoints)
        return $whitespace_codepoints;

    $whitespaces = utf8_ws();
    unset($whitespaces[0]); # NUL
    unset($whitespaces[10]); # New line
    unset($whitespaces[13]); # Carriage return
    unset($whitespaces[32]); # Normal space
    $whitespace_codepoints = [];
    foreach($whitespaces as $numeric => $char)
        $whitespace_codepoints[] = utf8_int_to_hex($numeric);

    return $whitespace_codepoints;
}

