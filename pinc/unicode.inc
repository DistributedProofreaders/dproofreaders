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

# Return an array of invalid characters and their count
function get_invalid_characters($string, $codepoints)
{
    $string = utf8_filter_out_codepoints($string, $codepoints);
    $char_count = [];
    if($string)
    {
        foreach(utf8_split($string) as $char)
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
