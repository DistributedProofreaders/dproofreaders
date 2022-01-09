<?php

class UserTest extends PHPUnit\Framework\TestCase
{
    private $TEST_USERNAME = "UserTest_php";
    private $NONTEST_USERNAME = 'blahblahblah';

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

    public function testEmptyConstructor()
    {
        $user = new User();
        $this->assertTrue(!isset($user->username));
    }

    public function testNonemptyConstructor()
    {
        $user = new User($this->TEST_USERNAME);
        $this->assertTrue(isset($user->username));
    }

    public function testValidateValidUserStrict()
    {
        $is_valid = User::is_valid_user($this->TEST_USERNAME);
        $this->assertTrue($is_valid);
    }

    public function testValidateValidUserStrictDifferCase()
    {
        $username = strtoupper($this->TEST_USERNAME);
        $is_valid = User::is_valid_user($username);
        $this->assertFalse($is_valid);
    }

    public function testValidateValidUserStrictDifferWhitespace()
    {
        $username = $this->TEST_USERNAME . "  ";
        $is_valid = User::is_valid_user($username);
        $this->assertFalse($is_valid);
    }

    public function testValidateValidUserNotStrict()
    {
        $is_valid = User::is_valid_user($this->TEST_USERNAME, false);
        $this->assertTrue($is_valid);
    }

    public function testValidateValidUserNotStrictDifferCase()
    {
        $username = strtoupper($this->TEST_USERNAME);
        $is_valid = User::is_valid_user($username, false);
        $this->assertTrue($is_valid);
    }

    public function testValidateInvalidUser()
    {
        $is_valid = User::is_valid_user($this->NONTEST_USERNAME);
        $this->assertFalse($is_valid);
    }

    public function testLoadExistingDifferCase()
    {
        $this->expectException(NonexistentUserException::class);
        $username = strtoupper($this->TEST_USERNAME);
        $user = new User($username);
    }

    public function testLoadExistingDifferWhitespace()
    {
        $this->expectException(NonexistentUserException::class);
        $username = $this->TEST_USERNAME . '   ';
        $user = new User($username);
    }

    public function testLoadNonexisting()
    {
        $this->expectException(NonexistentUserException::class);
        $user = new User($this->NONTEST_USERNAME);
    }

    public function testGetters()
    {
        $user = new User($this->TEST_USERNAME);
        $this->assertEquals(
            $this->TEST_USERNAME,
            $user->username
        );
    }

    public function testSetters()
    {
        $user = new User();
        $user->i_theme = 1;
        $this->assertEquals($user->i_theme, 1);
    }

    public function testSetNewImmutable()
    {
        $user = new User();
        $user->username = "blah";
        $this->assertEquals($user->username, "blah");
    }

    public function testSetImmutable()
    {
        $this->expectException(DomainException::class);
        $user = new User($this->TEST_USERNAME);
        $user->username = "blah";
    }

    public function testSave()
    {
        $this->expectException(NotImplementedException::class);
        $user = new User();
        $user->save();
    }
}
