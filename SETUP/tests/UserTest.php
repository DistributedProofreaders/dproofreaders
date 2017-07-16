<?php

class UserTest extends PHPUnit_Framework_TestCase
{
    private $EXISTENT_USERNAME;
    private $NONEXISTENT_USERNAME = 'blahblahblah';

    protected function setUp()
    {
        // Load a valid username from the database for use during the tests
        // We need one with an alphabetic character in it for the user
        // validation tests.
        $sql = "SELECT username FROM users WHERE username like '%a%' LIMIT 1";
        $result = mysqli_query(DPDatabase::get_connection(), $sql);
        $row = mysqli_fetch_assoc($result);
        $this->EXISTENT_USERNAME = $row['username'];
        mysqli_free_result($result);
    }

    public function testEmptyConstructor()
    {
        $user = new User();
    }

    public function testNonemptyConstructor()
    {
        $user = new User($this->EXISTENT_USERNAME);
    }

    public function testValidateValidUserStrict()
    {
        $is_valid = User::is_valid_user($this->EXISTENT_USERNAME);
        $this->assertTrue($is_valid);
    }

    public function testValidateValidUserStrictDifferCase()
    {
        $username=strtoupper($this->EXISTENT_USERNAME);
        $is_valid = User::is_valid_user($username);
        $this->assertFalse($is_valid);
    }

    public function testValidateValidUserStrictDifferWhitespace()
    {
        $username=$this->EXISTENT_USERNAME . "  ";
        $is_valid = User::is_valid_user($username);
        $this->assertFalse($is_valid);
    }

    public function testValidateValidUserNotStrict()
    {
        $is_valid = User::is_valid_user($this->EXISTENT_USERNAME, FALSE);
        $this->assertTrue($is_valid);
    }

    public function testValidateValidUserNotStrictDifferCase()
    {
        $username=strtoupper($this->EXISTENT_USERNAME);
        $is_valid = User::is_valid_user($username, FALSE);
        $this->assertTrue($is_valid);
    }

    public function testValidateValidUserNotStrictDifferWhitespace()
    {
        $username=$this->EXISTENT_USERNAME . "  ";
        $is_valid = User::is_valid_user($username, FALSE);
        $this->assertTrue($is_valid);
    }

    public function testValidateInvalidUser()
    {
        $is_valid = User::is_valid_user($this->NONEXISTENT_USERNAME);
        $this->assertFalse($is_valid);
    }

    public function testLoadExistingStrict()
    {
        $user = new User();
        $user->load("username", $this->EXISTENT_USERNAME);
    }

    /**
     * @expectedException NonexistentUserException
     */
    public function testLoadExistingStrictDifferCase()
    {
        $username = strtoupper($this->EXISTENT_USERNAME);
        $user = new User();
        $user->load("username", $username);
    }

    /**
     * @expectedException NonexistentUserException
     */
    public function testLoadExistingStrictDifferWhitespace()
    {
        $username = $this->EXISTENT_USERNAME . '   ';
        $user = new User();
        $user->load("username", $username);
    }

    public function testLoadExistingNotStrictDifferCase()
    {
        $username = strtoupper($this->EXISTENT_USERNAME);
        $user = new User();
        $user->load("username", $username, FALSE);
    }

    public function testLoadExistingNotStrictDifferWhitespace()
    {
        $username = $this->EXISTENT_USERNAME . '   ';
        $user = new User();
        $user->load("username", $username, FALSE);
    }

    /**
     * @expectedException NonexistentUserException
     */
    public function testLoadNonexisting()
    {
        $user = new User();
        $user->load("username", $this->NONEXISTENT_USERNAME);
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testLoadInvalidField()
    {
        $user = new User();
        $user->load("not_a_field", "blah");
    }

    /**
     * @expectedException NonuniqueUserException
     */
    public function testLoadMultipleUsers()
    {
        $user = new User();
        $user->load("team_1", 0);
    }

    public function testGetters()
    {
        $user = new User($this->EXISTENT_USERNAME);
        $this->assertEquals(
            $this->EXISTENT_USERNAME,
            $user->username
        );
    }

    public function testSetters()
    {
        $user = new User();
        $user->team_1 = 1;
    }

    public function testSetNewImmutable()
    {
        $user = new User();
        $user->username = "blah";
    }

    /**
     * @expectedException DomainException
     */
    public function testSetImmutable()
    {
        $user = new User($this->EXISTENT_USERNAME);
        $user->username = "blah";
    }

    /**
     * @expectedException NotImplementedException
     */
    public function testSave()
    {
        $user = new User();
        $user->save();
    }
}
