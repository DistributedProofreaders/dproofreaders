<?php

class UserTest extends PHPUnit_Framework_TestCase
{
    private $EXISTENT_USERNAME;
    private $NONEXISTENT_USERNAME = 'blahblahblah';

    protected function setUp()
    {
        // Load a valid username from the database for use during the tests
        $sql = "SELECT username FROM users LIMIT 1";
        $result = mysql_query($sql);
        $row = mysql_fetch_assoc($result);
        $this->EXISTENT_USERNAME = $row['username'];
        mysql_free_result($result);
    }

    public function testEmptyConstructor()
    {
        $user = new User();
    }

    public function testNonemptyConstructor()
    {
        $user = new User($this->EXISTENT_USERNAME);
    }

    public function testValidateValidUser()
    {
        $is_valid = User::is_valid_user($this->EXISTENT_USERNAME);
        $this->assertTrue($is_valid);
    }

    public function testValidateInvalidUser()
    {
        $is_valid = User::is_valid_user($this->NONEXISTENT_USERNAME);
        $this->assertFalse($is_valid);
    }

    public function testLoadExisting()
    {
        $user = new User();
        $user->load("username", $this->EXISTENT_USERNAME);
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
