<?php

class ParamValidatorTest extends PHPUnit\Framework\TestCase
{
    private $GET = [
        "enum" => "a",
        "i10" => "10",
        "f10" => "10.0",
        "s10" => "ten",
        "date" => "2024-02-10",
        "one" => 1,
    ];
    private $ENUM_CHOICES = ["a", "b"];
    private $ENUM_INT_CHOICES = [1, 3, 5, 9];

    //------------------------------------------------------------------------
    // get_enumerated_param() tests

    public function testEnumInvalidArray()
    {
        // sanity check PHP's built-in type checking, we're not going
        // to do this for every param and every function
        $this->expectException(TypeError::class);
        $this->expectExceptionMessage("must be of the type array, null given");
        get_enumerated_param(null, 'c', null, $this->ENUM_CHOICES);
    }

    public function testEnum()
    {
        $default = null;
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
        $this->expectExceptionMessage("is required");
        $default = null;
        get_enumerated_param($this->GET, 'none', $default, $this->ENUM_CHOICES);
    }

    public function testEnumNull()
    {
        $default = null;
        $result = get_enumerated_param($this->GET, 'none', $default, $this->ENUM_CHOICES, true);
        $this->assertEquals(null, $result);
    }

    public function testEnumInt()
    {
        $default = 9;
        $result = get_enumerated_param($this->GET, 'one', 9, $this->ENUM_INT_CHOICES);
        $this->assertEquals($result, $this->GET['one']);
        $this->assertIsInt($result);
    }

    public function testEnumIntDefault()
    {
        $default = 9;
        $result = get_enumerated_param($this->GET, 'none', $default, $this->ENUM_INT_CHOICES);
        $this->assertEquals($result, $default);
        $this->assertIsInt($result);
    }

    public function testEnumInvalidOption()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("is not one of");
        get_enumerated_param($this->GET, 'i10', null, $this->ENUM_CHOICES);
    }

    public function testEnumInvalidDefault()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("\$default not in \$choices");
        get_enumerated_param($this->GET, 'enum', 'c', $this->ENUM_CHOICES);
    }

    public function testEnumNoChoices()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("is an empty array");
        get_enumerated_param($this->GET, 'enum', 'a', []);
    }

    public function testEnumDefaultAndNull()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("\$allownull = true but \$default is specified");
        get_enumerated_param($this->GET, 'enum', 'a', $this->ENUM_CHOICES, true);
    }

    //------------------------------------------------------------------------
    // get_numeric_param() tests

    // The testInteger* and testFloat* functions test most of this already,
    // so just handle the interesting edgecases

    public function testNumericType()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("must be 'integer' or 'float'");
        get_numeric_param("boolean", $this->GET, 'enum', 1, 0, 1, false);
    }

    public function testNumericMinType()
    {
        $this->expectException(TypeError::class);
        $this->expectExceptionMessage("must be of the type numeric or null");
        get_numeric_param("integer", $this->GET, 'enum', 1, "min", 1, false);
    }

    public function testNumericMaxType()
    {
        $this->expectException(TypeError::class);
        $this->expectExceptionMessage("must be of the type numeric or null");
        get_numeric_param("integer", $this->GET, 'enum', 1, 0, "max", false);
    }

    public function testNumericMinLtMax()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("\$min must be <= \$max");
        get_numeric_param("integer", $this->GET, 'enum', null, 1, 0, true);
    }

    public function testNumericDefaultAndNull()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("\$allownull = true but \$default is specified");
        get_numeric_param("integer", $this->GET, 'enum', 1, 0, 1, true);
    }

    //------------------------------------------------------------------------
    // get_integer_param() tests

    public function testInteger()
    {
        $default = null;
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

    public function testIntegerDefaultNotInt()
    {
        $this->expectException(TypeError::class);
        $this->expectExceptionMessage("must be of the type int or null");
        $default = "string";
        $min = 0;
        $max = 100;
        get_integer_param($this->GET, 'none', $default, $min, $max);
    }

    public function testIntegerNull()
    {
        $default = null;
        $min = 0;
        $max = 100;
        $result = get_integer_param($this->GET, 'none', $default, $min, $max, true);
        $this->assertEquals(null, $result);
    }

    public function testIntegerNoDefault()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("is required");
        $default = null;
        $min = 0;
        $max = 100;
        get_integer_param($this->GET, 'none', $default, $min, $max);
    }

    public function testIntegerNotAnInt()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("is not an integer");
        $default = null;
        $min = 0;
        $max = 100;
        get_integer_param($this->GET, 'f10', $default, $min, $max);
    }


    public function testIntegerLessThanMin()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("is less than the minimum");
        $default = null;
        $min = 90;
        $max = 100;
        get_integer_param($this->GET, 'i10', $default, $min, $max);
    }

    public function testIntegerMoreThanMax()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("is greater than the maximum");
        $default = null;
        $min = 0;
        $max = 9;
        get_integer_param($this->GET, 'i10', $default, $min, $max);
    }

    //------------------------------------------------------------------------
    // get_float_param() tests

    public function testFloat()
    {
        $default = null;
        $min = 0;
        $max = 100;
        $result = get_float_param($this->GET, 'f10', $default, $min, $max);
        $this->assertEquals($this->GET['f10'], $result);
    }

    public function testFloatInteger()
    {
        $default = null;
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

    public function testFloatDefaultNotFloat()
    {
        $this->expectException(TypeError::class);
        $this->expectExceptionMessage("must be of the type float or null");
        $default = "string";
        $min = 0;
        $max = 100;
        get_float_param($this->GET, 'none', $default, $min, $max);
    }

    public function testFloatNull()
    {
        $default = null;
        $min = 0;
        $max = 100;
        $result = get_float_param($this->GET, 'none', $default, $min, $max, true);
        $this->assertEquals(null, $result);
    }

    public function testFloatNoDefault()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("is required");
        $default = null;
        $min = 0;
        $max = 100;
        get_float_param($this->GET, 'none', $default, $min, $max);
    }

    public function testFloatNotAFloat()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("is not a float");
        $default = null;
        $min = 0;
        $max = 100;
        get_float_param($this->GET, 's10', $default, $min, $max);
    }

    public function testFloatLessThanMin()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("is less than the minimum");
        $default = null;
        $min = 90;
        $max = 100;
        get_float_param($this->GET, 'f10', $default, $min, $max);
    }

    public function testFloatMoreThanMax()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("is greater than the maximum");
        $default = null;
        $min = 0;
        $max = 9;
        get_float_param($this->GET, 'f10', $default, $min, $max);
    }

    //------------------------------------------------------------------------
    // get_param_matching_regex() tests

    public function testRegex()
    {
        $regex = '/^\d{4}-\d{2}-\d{2}$/';
        $result = get_param_matching_regex($this->GET, "date", "1999-01-01", $regex);
        $this->assertEquals($this->GET["date"], $result);
    }

    public function testRegexDefault()
    {
        $default = "1999-01-01";
        $regex = '/^\d{4}-\d{2}-\d{2}$/';
        $result = get_param_matching_regex($this->GET, "invalidkey", $default, $regex);
        $this->assertEquals($default, $result);
    }

    public function testRegexNoMatch()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("does not match the regex");
        $default = null;
        $regex = '/^\d{4}-\d{2}-\d{2}$/';
        get_param_matching_regex($this->GET, "enum", null, $regex);
    }

    public function testRegexNoDefault()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("is required");
        $default = null;
        $regex = '/^\d{4}-\d{2}-\d{2}$/';
        get_param_matching_regex($this->GET, "invalidkey", null, $regex);
    }

    public function testRegexDefaultAndNull()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("\$allownull = true but \$default is specified");
        $regex = '/^\d{4}-\d{2}-\d{2}$/';
        get_param_matching_regex($this->GET, "date", "1999-01-01", $regex, true);
    }

}
