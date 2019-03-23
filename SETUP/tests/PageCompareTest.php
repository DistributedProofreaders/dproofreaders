<?php
$relPath='../../pinc/';
include_once($relPath."PageUnformatter.inc"); // PageUnformatter()

class PageCompareTest extends PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider textProvider
     */

    public function testRemoveFormatting($formatted_file, $expected_result_file)
    {
        $un_formatter = new PageUnformatter();
        $text = $un_formatter->remove_formatting(file_get_contents($formatted_file), false);
        $this->assertEquals($text, file_get_contents($expected_result_file));
    }

    public function textProvider()
    {
        $file_array = [];
        $data_dir_name = "page_compare_data";
        $data_dir_handle = opendir($data_dir_name);
        if($data_dir_handle)
        {
            while (($file = readdir($data_dir_handle)) !== false)
            {
                if ($file == '.' || $file == '..')
                {
                    continue;
                }
                $file_path = $data_dir_name . "/" . $file;
                if(is_dir($file_path))
                {
                    $file_path .= "/";
                    $file_array[] = [$file_path . "formatted.txt", $file_path . "unformatted.txt"];
                }
            }
        }
        return $file_array;
    }

    public function testUnwrap()
    {
        $un_formatter = new PageUnformatter();
        $text = $un_formatter->remove_formatting("\n line1 \n\n line2 \n", true);
        $this->assertEquals($text, "line1 line2");
    }
}
