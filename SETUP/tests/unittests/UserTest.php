<?php

class UserTest extends PHPUnit\Framework\TestCase
{
    private string $TEST_USERNAME = "UserTest_php";
    private string $NONTEST_USERNAME = 'blahblahblah';

    protected function setUp(): void
    {
        create_test_user($this->TEST_USERNAME);
        create_test_user("$this->TEST_USERNAME-2");
    }

    protected function tearDown(): void
    {
        delete_test_user($this->TEST_USERNAME);
        delete_test_user("$this->TEST_USERNAME-2");
    }

    public function testEmptyConstructor(): void
    {
        $user = new User();
        $this->assertTrue(!isset($user->username));
    }

    public function testNonemptyConstructor(): void
    {
        $user = new User($this->TEST_USERNAME);
        $this->assertTrue(isset($user->username));
    }

    public function testValidateValidUserStrict(): void
    {
        $is_valid = User::is_valid_user($this->TEST_USERNAME);
        $this->assertTrue($is_valid);
    }

    public function testValidateValidUserStrictDifferCase(): void
    {
        $username = strtoupper($this->TEST_USERNAME);
        $is_valid = User::is_valid_user($username);
        $this->assertFalse($is_valid);
    }

    public function testValidateValidUserStrictDifferWhitespace(): void
    {
        $username = $this->TEST_USERNAME . "  ";
        $is_valid = User::is_valid_user($username);
        $this->assertFalse($is_valid);
    }

    public function testValidateValidUserNotStrict(): void
    {
        $is_valid = User::is_valid_user($this->TEST_USERNAME, false);
        $this->assertTrue($is_valid);
    }

    public function testValidateValidUserNotStrictDifferCase(): void
    {
        $username = strtoupper($this->TEST_USERNAME);
        $is_valid = User::is_valid_user($username, false);
        $this->assertTrue($is_valid);
    }

    public function testValidateInvalidUser(): void
    {
        $is_valid = User::is_valid_user($this->NONTEST_USERNAME);
        $this->assertFalse($is_valid);
    }

    public function testLoadExistingDifferCase(): void
    {
        $this->expectException(NonexistentUserException::class);
        $username = strtoupper($this->TEST_USERNAME);
        new User($username);
    }

    public function testLoadExistingDifferWhitespace(): void
    {
        $this->expectException(NonexistentUserException::class);
        $username = $this->TEST_USERNAME . '   ';
        new User($username);
    }

    public function testLoadNonexisting(): void
    {
        $this->expectException(NonexistentUserException::class);
        new User($this->NONTEST_USERNAME);
    }

    public function testGetters(): void
    {
        $user = new User($this->TEST_USERNAME);
        $this->assertEquals(
            $this->TEST_USERNAME,
            $user->username
        );
    }

    public function testSetters(): void
    {
        $user = new User();
        $user->i_theme = "1";
        $this->assertEquals($user->i_theme, "1");
    }

    public function testSetNewImmutable(): void
    {
        $user = new User();
        $user->username = "blah"; // @phpstan-ignore assign.propertyReadOnly (testing __set works for uninitialized User)
        $this->assertEquals($user->username, "blah");
    }

    public function testSetImmutable(): void
    {
        $this->expectException(DomainException::class);
        $user = new User($this->TEST_USERNAME);
        $user->username = "blah"; // @phpstan-ignore assign.propertyReadOnly (testing __set works for uninitialized User)
    }

    public function testSave(): void
    {
        $this->expectException(NotImplementedException::class);
        $user = new User();
        $user->save();
    }


    // Username validation tests

    public function testValidRegUsername(): void
    {
        $error = check_username("oneONE1- .", true);
        $this->assertEquals("", $error);
    }

    public function testValidNonRegUsername(): void
    {
        $error = check_username("oneONE1- .@_");
        $this->assertEquals("", $error);
    }

    public function testInvalidUsernameTrailingSpace(): void
    {
        $error = check_username("one two ");
        $this->assertStringContainsString("leading or trailing whitespace", $error);
    }

    public function testInvalidUsernameAdjacentSpace(): void
    {
        $error = check_username("one  two");
        $this->assertStringContainsString("contains adjacent space characters", $error);
    }

    public function testInvalidRegUsernameUnderscore(): void
    {
        $error = check_username("one_two", true);
        $this->assertStringContainsString("contains invalid characters", $error);
    }

    public function testInvalidRegUsernameAt(): void
    {
        $error = check_username("one@two", true);
        $this->assertStringContainsString("contains invalid characters", $error);
    }
}
