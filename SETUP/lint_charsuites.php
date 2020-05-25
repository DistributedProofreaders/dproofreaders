#!/usr/bin/env php
<?php
$relPath="../pinc/";
include_once($relPath."CharSuites.inc");
include_once($relPath."unicode.inc");

foreach(CharSuites::get_all() as $charsuite)
{
    echo "Validating charsuite $charsuite->name...\n";

    // Validate that the character suite only contains normalized codepoints
    $nonnormalized_codepoints = $charsuite->get_nonnormalized_codepoints();
    if($nonnormalized_codepoints)
    {
        echo "ERROR: codepoints are not normalized:\n";
        foreach($nonnormalized_codepoints as $orig => $norm)
        {
            echo sprintf("    %s normalized is %s\n", $orig, $norm);
        }
        exit(1);
    }

    // Validate that the character suite does not contain codepoints we convert
    // to ASCII
    $disallowed_characters = convert_codepoint_ranges_to_characters(get_disallowed_codepoints());
    $charsuite_characters = convert_codepoint_ranges_to_characters($charsuite->codepoints);
    $characters = array_intersect($disallowed_characters, $charsuite_characters);
    if($characters != [])
    {
        echo "ERROR: disallowed characters found in suite\n";
        foreach($characters as $char)
        {
            echo sprintf("    %s: %s\n", $char, utf8_chr_to_hex($char));
        }
        exit(1);
    }

    // Confirm that all hex codes are in lowercase
    validate_hex_codes_lowercase($charsuite->codepoints, "uppercase hex values found in charsuite codepoints");

    // Validate pickersets only contain characters within the suite
    foreach($charsuite->pickerset->get_subsets() as $title => $picker_subset)
    {
        foreach($picker_subset as $codepoints)
        {
            $string = implode("", convert_codepoint_ranges_to_characters($codepoints));
            $invalid_chars = get_invalid_characters($string, $charsuite->codepoints);
            if($invalid_chars)
            {
                echo "ERROR: pickerset $title has codepoints not in charsuite:\n";
                foreach($invalid_chars as $char => $count)
                {
                    echo sprintf("    %s: %s\n", $char, utf8_chr_to_hex($char));
                }
                exit(1);
            }

            // Confirm hex codes are in lowercase
            validate_hex_codes_lowercase($codepoints, "uppercase hex values found in picker set");
        }
    }
}

function validate_hex_codes_lowercase($codepoints, $error_message)
{
    // Confirm that all hex codes are in lowercase
    $uppercase_codepoints = [];
    foreach($codepoints as $codepoint)
    {
        if(preg_match('/[ABCDEF]/', $codepoint))
        {
            $uppercase_codepoints[] = $codepoint;
        }
    }
    if($uppercase_codepoints)
    {
        echo "ERROR: $error_message\n";
        foreach($uppercase_codepoints as $codepoint)
        {
            echo sprintf("    %s\n", $codepoint);
        }
        exit(1);
    }
}
