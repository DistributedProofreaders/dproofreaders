<?php

use voku\helper\UTF8;

class UnicodeTest extends PHPUnit\Framework\TestCase
{
    private $a_to_z_codepoints = [
        'U+0061-U+007a',  // a-z
    ];
    private $combined_codepoints = [
        'U+004E',  // N
        'U+004E>U+0305',  // N̅
        'U+004E>U+0306',  // N̆
    ];

    public function testSubstrReplace()
    {
        $string = "ЖЖaac";
        $new_string = UTF8::substr_replace($string, "bb", 2, 2);
        $this->assertEquals("ЖЖbbc", $new_string);
    }

    public function testUtf8CombinedChr()
    {
        $codepoint = 'U+004E';  // N
        $char = utf8_combined_chr($codepoint);
        $this->assertEquals("N", $char);
    }

    public function testAreCodepointsNormalized()
    {
        $codepoints = ['U+004E>U+0305'];  // N̅
        $this->assertEquals(get_nonnormalized_codepoints($codepoints), []);
    }

    public function testAreCodepointsNormalizedFail()
    {
        $codepoints = ['U+004e>U+0307'];  // Ṅ but normalizes to U+1e44
        $this->assertEquals(get_nonnormalized_codepoints($codepoints), ["U+004e>U+0307" => "U+1e44"]);
    }

    public function testUtf8CombinedChrCombined()
    {
        $codepoint = 'U+004E>U+0305';  // N̅
        $char = utf8_combined_chr($codepoint);
        $this->assertEquals("\u{4e}\u{305}", $char);
    }

    public function testUtf8CharacterName()
    {
        $char = 'N';
        $name = utf8_character_name($char);
        $this->assertEquals("LATIN CAPITAL LETTER N", $name);
    }

    public function testUtf8CharacterNameCombined()
    {
        $char = 'N̅';
        $name = utf8_character_name($char);
        $this->assertEquals("LATIN CAPITAL LETTER N + COMBINING OVERLINE", $name);
    }

    public function testStringToCodepopintsString()
    {
        $string = "Name";
        $codepoint_string = string_to_codepoints_string($string);
        $this->assertEquals("U+004e U+0061 U+006d U+0065", $codepoint_string);
    }

    public function testStringToCodepopintsStringCombined()
    {
        $string = "N̅ame";
        $codepoint_string = string_to_codepoints_string($string);
        $this->assertEquals("U+004e U+0305 U+0061 U+006d U+0065", $codepoint_string);
    }

    public function testFilterToCodepoints()
    {
        $string = "abc1Ж3xyz";
        $new_string = utf8_filter_to_codepoints($string, $this->a_to_z_codepoints);
        $this->assertEquals("abcxyz", $new_string);
    }

    public function testFilterToCodepointsReplace()
    {
        $string = "abc1Ж3xyz";
        $new_string = utf8_filter_to_codepoints($string, $this->a_to_z_codepoints, "_");
        $this->assertEquals("abc___xyz", $new_string);
    }

    public function testFilterOutCodepoints()
    {
        $string = "abc1Ж3xyz";
        $new_string = utf8_filter_out_codepoints($string, $this->a_to_z_codepoints);
        $this->assertEquals("1Ж3", $new_string);
    }

    public function testFilterOutCodepointsReplace()
    {
        $string = "abc1Ж3xyz";
        $new_string = utf8_filter_out_codepoints($string, $this->a_to_z_codepoints, "_");
        $this->assertEquals("___1Ж3___", $new_string);
    }

    // Confirm that combined characters are properly filtered, specifically
    // we use a string that has characters that decompose into overlapping
    // codepoints to ensure we handle them all.

    public function testFilterToCodepointsCombined()
    {
        $string = "abcNN̆N̆N͉̅A̅ĂȦxyz";
        $new_string = utf8_filter_to_codepoints($string, $this->combined_codepoints);
        $this->assertEquals("NN̆N̆", $new_string);
    }

    public function testFilterToCodepointsCombinedReplace()
    {
        $string = "abcNN̆N̆N͉̅A̅ĂȦxyz";
        $new_string = utf8_filter_to_codepoints($string, $this->combined_codepoints, "_");
        $this->assertEquals("___NN̆N̆_______", $new_string);
    }

    public function testFilterOutCodepointsCombined()
    {
        $string = "abcNN̆N̆N͉̅A̅ĂȦxyz";
        $new_string = utf8_filter_out_codepoints($string, $this->combined_codepoints);
        $this->assertEquals("abcN͉̅A̅ĂȦxyz", $new_string);
    }

    public function testFilterOutCodepointsCombinedReplace()
    {
        $string = "abcNN̆N̆N͉̅A̅ĂȦxyz";
        $new_string = utf8_filter_out_codepoints($string, $this->combined_codepoints, "_");
        $this->assertEquals("abc___N͉̅A̅ĂȦxyz", $new_string);
    }

    public function testConvertCodepointsToCharacters()
    {
        $chars = str_split("abcdefghijklmnopqrstuvwxyz");
        $string = convert_codepoint_ranges_to_characters($this->a_to_z_codepoints);
        $this->assertEquals($chars, $string);
    }

    public function testConvertCodepointsToCharactersCombined()
    {
        $chars = ["N", "N̅", "N̆"];
        $string = convert_codepoint_ranges_to_characters($this->combined_codepoints);
        $this->assertEquals($chars, $string);
    }

    public function testGetInvalidCharacters()
    {
        $string = "abc@##Жabc";
        $chars = [
            "#" => 2,
            "@" => 1,
            "Ж" => 1,
        ];
        $invalid_chars = get_invalid_characters($string, $this->a_to_z_codepoints);
        $this->assertEquals($chars, $invalid_chars);
    }

    public function testGetInvalidCharactersCombined()
    {
        $string = "abc@##ЖN̆N̆abc";
        $chars = [
            "#" => 2,
            "@" => 1,
            "Ж" => 1,
            "N̆" => 2,
        ];
        $invalid_chars = get_invalid_characters($string, $this->a_to_z_codepoints);
        $this->assertEquals($chars, $invalid_chars);
    }

    public function testUtf8CharScript()
    {
        $char = 'a';
        $script = utf8_char_script($char);
        $this->assertEquals('Latin', $script);
    }

    public function testUtf8StringScripts()
    {
        $string = 'a.';
        $scripts = utf8_string_scripts($string);
        $this->assertEquals(['Latin', 'Common'], $scripts);
    }

    public function testSplitMultiscriptString()
    {
        $string = "abcd";
        $chunks = split_multiscript_string($string);
        $this->assertEquals(["abcd"], $chunks);

        $string = "a   ";
        $chunks = split_multiscript_string($string);
        $this->assertEquals(["a", "   "], $chunks);

        $string = "aaaЖ";
        $chunks = split_multiscript_string($string);
        $this->assertEquals(["aaa", "Ж"], $chunks);

        $string = "aN̅c";
        $chunks = split_multiscript_string($string);
        $this->assertEquals(["aN̅c"], $chunks);
    }
}
