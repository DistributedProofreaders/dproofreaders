<?php

class UnicodeTests extends PHPUnit\Framework\TestCase
{
    private $a_to_z_codepoints = [
        'U+0061-U+007a',  # a-z
    ];

    public function testSubstrReplace()
    {
        $string = "ЖЖaac";
        $new_string = utf8_substr_replace($string, "bb", 2, 2);
        $this->assertEquals("ЖЖbbc", $new_string);
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

    public function testFilterOutodepoints()
    {
        $string = "abc1Ж3xyz";
        $new_string = utf8_filter_out_codepoints($string, $this->a_to_z_codepoints);
        $this->assertEquals("1Ж3", $new_string);
    }

    public function testFilterOutodepointsReplace()
    {
        $string = "abc1Ж3xyz";
        $new_string = utf8_filter_out_codepoints($string, $this->a_to_z_codepoints, "_");
        $this->assertEquals("___1Ж3___", $new_string);
    }

    public function testConvertCodepointsToCharacters()
    {
        $chars = str_split("abcdefghijklmnopqrstuvwxyz");
        $string = convert_codepoint_ranges_to_characters($this->a_to_z_codepoints);
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
}
