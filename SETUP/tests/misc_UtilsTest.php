<?php

// Generic utilities in misc.inc

class MiscUtils extends PHPUnit\Framework\TestCase
{
    public function test_array_get_exists()
    {
        $arr = ["param" => "value"];
        $value = array_get($arr, "param", "default");
        $this->assertEquals($arr["param"], $value);
    }

    public function test_array_get_doesnt_exists()
    {
        $arr = [];
        $value = array_get($arr, "param", "default");
        $this->assertEquals("default", $value);
    }

    public function test_get_changed_fields_for_objects()
    {
        $obj1 = new stdClass();
        $obj1->param1 = "a";
        $obj1->param2 = "b";

        $obj2 = new stdClass();
        $obj2->param1 = "a";
        $obj2->param2 = "d";

        $changed = get_changed_fields_for_objects($obj1, $obj2);
        $this->assertEquals(["param2"], $changed);
    }
}
