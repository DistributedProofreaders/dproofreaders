<?php

class ParamValidatorTest extends PHPUnit\Framework\TestCase
{
    private $GET = array(
        "enum" => "a",
        "i10" => "10",
        "f10" => "10.0",
        "s10" => "ten",
    );
    private $ENUM_CHOICES = array("a", "b");

    #------------------------------------------------------------------------
    # get_enumerated_param() tests

    public function testEnum()
    {
        $default = NULL;
        $result = get_enumerated_param($this->GET, 'enum', $default, $this->ENUM_CHOICES);
        $this->assertEquals($this->GET['enum'], $result);
    }

    public function testEnumDefault()
    {
        $default = "a";
        $result = get_enumerated_param($this->GET, 'none', $default, $this->ENUM_CHOICES);
        $this->assertEquals($default, $result);
    }

    public function testEnumNoDefault()
    {
        $this->expectException(InvalidArgumentException::class);
        $default = NULL;
        $result = get_enumerated_param($this->GET, 'none', $default, $this->ENUM_CHOICES);
    }

    public function testEnumNull()
    {
        $default = NULL;
        $result = get_enumerated_param($this->GET, 'none', $default, $this->ENUM_CHOICES, TRUE);
        $this->assertEquals(NULL, $result);
    }

    public function testEnumInvalidOption()
    {
        $this->expectException(InvalidArgumentException::class);
        $result = get_enumerated_param($this->GET, 'i10', NULL, $this->ENUM_CHOICES);
    }


    #------------------------------------------------------------------------
    # get_integer_param() tests

    public function testInteger()
    {
        $default = NULL;
        $min = 0;
        $max = 100;
        $result = get_integer_param($this->GET, 'i10', $default, $min, $max);
        $this->assertEquals($this->GET['i10'], $result);
    }

    public function testIntegerDefault()
    {
        $default = 50;
        $min = 0;
        $max = 100;
        $result = get_integer_param($this->GET, 'none', $default, $min, $max);
        $this->assertEquals($default, $result);
    }

    public function testIntegerNull()
    {
        $default = NULL;
        $min = 0;
        $max = 100;
        $result = get_integer_param($this->GET, 'none', $default, $min, $max, TRUE);
        $this->assertEquals(NULL, $result);
    }

    public function testIntegerNoDefault()
    {
        $this->expectException(InvalidArgumentException::class);
        $default = NULL;
        $min = 0;
        $max = 100;
        $result = get_integer_param($this->GET, 'none', $default, $min, $max);
    }

    public function testIntegerNotAnInt()
    {
        $this->expectException(InvalidArgumentException::class);
        $default = NULL;
        $min = 0;
        $max = 100;
        $result = get_integer_param($this->GET, 'f10', $default, $min, $max);
    }


    public function testIntegerLessThanMin()
    {
        $this->expectException(InvalidArgumentException::class);
        $default = NULL;
        $min = 90;
        $max = 100;
        $result = get_integer_param($this->GET, 'i10', $default, $min, $max);
    }

    public function testIntegerMoreThanMax()
    {
        $this->expectException(InvalidArgumentException::class);
        $default = NULL;
        $min = 0;
        $max = 9;
        $result = get_integer_param($this->GET, 'i10', $default, $min, $max);
    }

    #------------------------------------------------------------------------
    # get_float_param() tests

    public function testFloat()
    {
        $default = NULL;
        $min = 0;
        $max = 100;
        $result = get_float_param($this->GET, 'f10', $default, $min, $max);
        $this->assertEquals($this->GET['f10'], $result);
    }

    public function testFloatInteger()
    {
        $default = NULL;
        $min = 0;
        $max = 100;
        $result = get_float_param($this->GET, 'i10', $default, $min, $max);
        $this->assertEquals($this->GET['i10'], $result);
    }

    public function testFloatDefault()
    {
        $default = 50.0;
        $min = 0;
        $max = 100;
        $result = get_float_param($this->GET, 'none', $default, $min, $max);
        $this->assertEquals($default, $result);
    }

    public function testFloatNull()
    {
        $default = NULL;
        $min = 0;
        $max = 100;
        $result = get_float_param($this->GET, 'none', $default, $min, $max, TRUE);
        $this->assertEquals(NULL, $result);
    }

    public function testFloatNoDefault()
    {
        $this->expectException(InvalidArgumentException::class);
        $default = NULL;
        $min = 0;
        $max = 100;
        $result = get_float_param($this->GET, 'none', $default, $min, $max);
    }

    public function testFloatNotAFloat()
    {
        $this->expectException(InvalidArgumentException::class);
        $default = NULL;
        $min = 0;
        $max = 100;
        $result = get_float_param($this->GET, 's10', $default, $min, $max);
    }

    public function testFloatLessThanMin()
    {
        $this->expectException(InvalidArgumentException::class);
        $default = NULL;
        $min = 90;
        $max = 100;
        $result = get_float_param($this->GET, 'f10', $default, $min, $max);
    }

    public function testFloatMoreThanMax()
    {
        $this->expectException(InvalidArgumentException::class);
        $default = NULL;
        $min = 0;
        $max = 9;
        $result = get_float_param($this->GET, 'f10', $default, $min, $max);
    }
}
