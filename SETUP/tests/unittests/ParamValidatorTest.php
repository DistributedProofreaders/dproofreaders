<?php

class ParamValidatorTest extends PHPUnit\Framework\TestCase
{
    private $GET = [
        "enum" => "a",
        "i10" => "10",
        "f10" => "10.0",
        "s10" => "ten",
        "date" => "2024-02-10",
        "zero" => 0,
        "one" => 1,
        "zero_str" => "0",
        "one_str" => "1",
        "true" => true,
        "false" => false,
        "true_str" => "true",
        "TRUE_str" => "TRUE",
        "True_str" => "True",
        "false_str" => "false",
        "FALSE_str" => "FALSE",
        "False_str" => "False",
    ];
    private $ENUM_CHOICES = ["a", "b"];
    private $ENUM_INT_CHOICES = [1, 3, 5, 9];

    //------------------------------------------------------------------------
    // get_enumerated_param() tests

    public function testEnumInvalidArray(): void
    {
        // sanity check PHP's built-in type checking, we're not going
        // to do this for every param and every function
        $this->expectException(TypeError::class);
        $this->expectExceptionMessage("type array, null given");
        get_enumerated_param(null, 'c', null, $this->ENUM_CHOICES);
    }

    public function testEnum(): void
    {
        $default = null;
        $result = get_enumerated_param($this->GET, 'enum', $default, $this->ENUM_CHOICES);
        $this->assertEquals($this->GET['enum'], $result);
    }

    public function testEnumDefault(): void
    {
        $default = "a";
        $result = get_enumerated_param($this->GET, 'none', $default, $this->ENUM_CHOICES);
        $this->assertEquals($default, $result);
    }

    public function testEnumNoDefault(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("is required");
        $default = null;
        get_enumerated_param($this->GET, 'none', $default, $this->ENUM_CHOICES);
    }

    public function testEnumNull(): void
    {
        $default = null;
        $result = get_enumerated_param($this->GET, 'none', $default, $this->ENUM_CHOICES, true);
        $this->assertEquals(null, $result);
    }

    public function testEnumInt(): void
    {
        $default = 9;
        $result = get_enumerated_param($this->GET, 'one', 9, $this->ENUM_INT_CHOICES);
        $this->assertEquals($result, $this->GET['one']);
        $this->assertIsInt($result);
    }

    public function testEnumIntDefault(): void
    {
        $default = 9;
        $result = get_enumerated_param($this->GET, 'none', $default, $this->ENUM_INT_CHOICES);
        $this->assertEquals($result, $default);
        $this->assertIsInt($result);
    }

    public function testEnumInvalidOption(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("is not one of");
        get_enumerated_param($this->GET, 'i10', null, $this->ENUM_CHOICES);
    }

    public function testEnumInvalidDefault(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("\$default not in \$choices");
        get_enumerated_param($this->GET, 'enum', 'c', $this->ENUM_CHOICES);
    }

    public function testEnumNoChoices(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("is an empty array");
        get_enumerated_param($this->GET, 'enum', 'a', []);
    }

    public function testEnumDefaultAndNull(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("\$allownull = true but \$default is specified");
        get_enumerated_param($this->GET, 'enum', 'a', $this->ENUM_CHOICES, true);
    }

    //------------------------------------------------------------------------
    // get_numeric_param() tests

    // The testInteger* and testFloat* functions test most of this already,
    // so just handle the interesting edgecases

    public function testNumericType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("must be 'integer' or 'float'");
        get_numeric_param("boolean", $this->GET, 'enum', 1, 0, 1, false);
    }

    public function testNumericMinType(): void
    {
        $this->expectException(TypeError::class);
        $this->expectExceptionMessage("type numeric or null");
        get_numeric_param("integer", $this->GET, 'enum', 1, "min", 1, false);
    }

    public function testNumericMaxType(): void
    {
        $this->expectException(TypeError::class);
        $this->expectExceptionMessage("type numeric or null");
        get_numeric_param("integer", $this->GET, 'enum', 1, 0, "max", false);
    }

    public function testNumericMinLtMax(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("\$min must be <= \$max");
        get_numeric_param("integer", $this->GET, 'enum', null, 1, 0, true);
    }

    public function testNumericDefaultAndNull(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("\$allownull = true but \$default is specified");
        get_numeric_param("integer", $this->GET, 'enum', 1, 0, 1, true);
    }

    //------------------------------------------------------------------------
    // get_integer_param() tests

    public function testInteger(): void
    {
        $default = null;
        $min = 0;
        $max = 100;
        $result = get_integer_param($this->GET, 'i10', $default, $min, $max);
        $this->assertEquals($this->GET['i10'], $result);
    }

    public function testIntegerDefault(): void
    {
        $default = 50;
        $min = 0;
        $max = 100;
        $result = get_integer_param($this->GET, 'none', $default, $min, $max);
        $this->assertEquals($default, $result);
    }

    public function testIntegerDefaultNotInt(): void
    {
        $this->expectException(TypeError::class);
        $this->expectExceptionMessage("must be of");
        $default = "string";
        $min = 0;
        $max = 100;
        get_integer_param($this->GET, 'none', $default, $min, $max);
    }

    public function testIntegerNull(): void
    {
        $default = null;
        $min = 0;
        $max = 100;
        $result = get_integer_param($this->GET, 'none', $default, $min, $max, true);
        $this->assertEquals(null, $result);
    }

    public function testIntegerNoDefault(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("is required");
        $default = null;
        $min = 0;
        $max = 100;
        get_integer_param($this->GET, 'none', $default, $min, $max);
    }

    public function testIntegerNotAnInt(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("is not an integer");
        $default = null;
        $min = 0;
        $max = 100;
        get_integer_param($this->GET, 'f10', $default, $min, $max);
    }


    public function testIntegerLessThanMin(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("is less than the minimum");
        $default = null;
        $min = 90;
        $max = 100;
        get_integer_param($this->GET, 'i10', $default, $min, $max);
    }

    public function testIntegerMoreThanMax(): void
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

    public function testFloat(): void
    {
        $default = null;
        $min = 0;
        $max = 100;
        $result = get_float_param($this->GET, 'f10', $default, $min, $max);
        $this->assertEquals($this->GET['f10'], $result);
    }

    public function testFloatInteger(): void
    {
        $default = null;
        $min = 0;
        $max = 100;
        $result = get_float_param($this->GET, 'i10', $default, $min, $max);
        $this->assertEquals($this->GET['i10'], $result);
    }

    public function testFloatDefault(): void
    {
        $default = 50.0;
        $min = 0;
        $max = 100;
        $result = get_float_param($this->GET, 'none', $default, $min, $max);
        $this->assertEquals($default, $result);
    }

    public function testFloatDefaultNotFloat(): void
    {
        $this->expectException(TypeError::class);
        $this->expectExceptionMessage("must be of");
        $default = "string";
        $min = 0;
        $max = 100;
        get_float_param($this->GET, 'none', $default, $min, $max);
    }

    public function testFloatNull(): void
    {
        $default = null;
        $min = 0;
        $max = 100;
        $result = get_float_param($this->GET, 'none', $default, $min, $max, true);
        $this->assertEquals(null, $result);
    }

    public function testFloatNoDefault(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("is required");
        $default = null;
        $min = 0;
        $max = 100;
        get_float_param($this->GET, 'none', $default, $min, $max);
    }

    public function testFloatNotAFloat(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("is not a float");
        $default = null;
        $min = 0;
        $max = 100;
        get_float_param($this->GET, 's10', $default, $min, $max);
    }

    public function testFloatLessThanMin(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("is less than the minimum");
        $default = null;
        $min = 90;
        $max = 100;
        get_float_param($this->GET, 'f10', $default, $min, $max);
    }

    public function testFloatMoreThanMax(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("is greater than the maximum");
        $default = null;
        $min = 0;
        $max = 9;
        get_float_param($this->GET, 'f10', $default, $min, $max);
    }

    //------------------------------------------------------------------------
    // get_bool_param() tests

    public function testBoolDefault(): void
    {
        $default = false;
        $result = get_bool_param($this->GET, 'none', $default);
        $this->assertEquals(false, $result);
    }

    public function testBoolDefaultToNull(): void
    {
        $default = null;
        $allow_null = true;
        $result = get_bool_param($this->GET, 'none', $default, $allow_null);
        $this->assertEquals(null, $result);
    }

    public function testBoolTrueVariants(): void
    {
        $default = null;
        $result = get_bool_param($this->GET, 'true', $default);
        $this->assertEquals(true, $result);
        $result = get_bool_param($this->GET, 'true_str', $default);
        $this->assertEquals(true, $result);
        $result = get_bool_param($this->GET, 'TRUE_str', $default);
        $this->assertEquals(true, $result);
        $result = get_bool_param($this->GET, 'True_str', $default);
        $this->assertEquals(true, $result);
        $result = get_bool_param($this->GET, 'one', $default);
        $this->assertEquals(true, $result);
        $result = get_bool_param($this->GET, 'one_str', $default);
        $this->assertEquals(true, $result);
    }

    public function testBoolFalseVariants(): void
    {
        $default = null;
        $result = get_bool_param($this->GET, 'false', $default);
        $this->assertEquals(false, $result);
        $result = get_bool_param($this->GET, 'false_str', $default);
        $this->assertEquals(false, $result);
        $result = get_bool_param($this->GET, 'FALSE_str', $default);
        $this->assertEquals(false, $result);
        $result = get_bool_param($this->GET, 'False_str', $default);
        $this->assertEquals(false, $result);
        $result = get_bool_param($this->GET, 'zero', $default);
        $this->assertEquals(false, $result);
        $result = get_bool_param($this->GET, 'zero_str', $default);
        $this->assertEquals(false, $result);
    }

    public function testBoolDefaultNotBool(): void
    {
        // Shockingly, PHP 7.4 (at least) will not throw a TypeError if a
        // non-boolean is passed into a function with a bool type. It instead
        // coerces it into a bool. Maybe later versions will?
        $this->markTestSkipped('PHP will not enforce a bool type');

        $this->expectException(TypeError::class);
        $this->expectExceptionMessage("must be of");
        $default = "string";
        get_bool_param($this->GET, 'none', $default);
    }

    public function testBoolNotABool(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("is not a");
        $default = true;
        get_bool_param($this->GET, 'i10', $default);
    }

    //------------------------------------------------------------------------
    // get_param_matching_regex() tests

    public function testRegex(): void
    {
        $regex = '/^\d{4}-\d{2}-\d{2}$/';
        $result = get_param_matching_regex($this->GET, "date", "1999-01-01", $regex);
        $this->assertEquals($this->GET["date"], $result);
    }

    public function testRegexDefault(): void
    {
        $default = "1999-01-01";
        $regex = '/^\d{4}-\d{2}-\d{2}$/';
        $result = get_param_matching_regex($this->GET, "invalidkey", $default, $regex);
        $this->assertEquals($default, $result);
    }

    public function testRegexNoMatch(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("does not match the regex");
        $default = null;
        $regex = '/^\d{4}-\d{2}-\d{2}$/';
        get_param_matching_regex($this->GET, "enum", null, $regex);
    }

    public function testRegexNoDefault(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("is required");
        $default = null;
        $regex = '/^\d{4}-\d{2}-\d{2}$/';
        get_param_matching_regex($this->GET, "invalidkey", null, $regex);
    }

    public function testRegexDefaultAndNull(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("\$allownull = true but \$default is specified");
        $regex = '/^\d{4}-\d{2}-\d{2}$/';
        get_param_matching_regex($this->GET, "date", "1999-01-01", $regex, true);
    }

}
