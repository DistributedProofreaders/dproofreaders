<?php

class UserTest extends PHPUnit_Framework_TestCase
{
    private $TEST_USERNAME = "UserTest_php";
    private $NONTEST_USERNAME = 'blahblahblah';

    protected function setUp()
    {
        // Attempt to load our test user, if it exists don't create it
        $sql = "SELECT username FROM users WHERE username = '$this->TEST_USERNAME'";
        $result = mysqli_query(DPDatabase::get_connection(), $sql);
        $row = mysqli_fetch_assoc($result);
        if(!$row)
        {
            $sql = "
                INSERT INTO users
                SET id = '$this->TEST_USERNAME',
                    real_name = '$this->TEST_USERNAME',
                    username = '$this->TEST_USERNAME',
                    email = '$this->TEST_USERNAME@localhost'
            ";
            $result = mysqli_query(DPDatabase::get_connection(), $sql);
            if(!$result)
                throw new Exception("Unable to create test user 1");

            $sql = "
                INSERT INTO users
                SET id = '$this->TEST_USERNAME-2',
                    real_name = '$this->TEST_USERNAME-2',
                    username = '$this->TEST_USERNAME-2',
                    email = '$this->TEST_USERNAME@localhost'
            ";
            $result = mysqli_query(DPDatabase::get_connection(), $sql);
            if(!$result)
                throw new Exception("Unable to create test user 2");
        }
        else
        {
            mysqli_free_result($result);
        }
    }

    protected function tearDown()
    {
        $sql = "
            DELETE FROM users
            WHERE id = '$this->TEST_USERNAME' or id = '$this->TEST_USERNAME-2'
        ";
        $result = mysqli_query(DPDatabase::get_connection(), $sql);
    }

    public function testEmptyConstructor()
    {
        $user = new User();
    }

    public function testNonemptyConstructor()
    {
        $user = new User($this->TEST_USERNAME);
    }

    public function testValidateValidUserStrict()
    {
        $is_valid = User::is_valid_user($this->TEST_USERNAME);
        $this->assertTrue($is_valid);
    }

    public function testValidateValidUserStrictDifferCase()
    {
        $username=strtoupper($this->TEST_USERNAME);
        $is_valid = User::is_valid_user($username);
        $this->assertFalse($is_valid);
    }

    public function testValidateValidUserStrictDifferWhitespace()
    {
        $username=$this->TEST_USERNAME . "  ";
        $is_valid = User::is_valid_user($username);
        $this->assertFalse($is_valid);
    }

    public function testValidateValidUserNotStrict()
    {
        $is_valid = User::is_valid_user($this->TEST_USERNAME, FALSE);
        $this->assertTrue($is_valid);
    }

    public function testValidateValidUserNotStrictDifferCase()
    {
        $username=strtoupper($this->TEST_USERNAME);
        $is_valid = User::is_valid_user($username, FALSE);
        $this->assertTrue($is_valid);
    }

    public function testValidateValidUserNotStrictDifferWhitespace()
    {
        $username=$this->TEST_USERNAME . "  ";
        $is_valid = User::is_valid_user($username, FALSE);
        $this->assertTrue($is_valid);
    }

    public function testValidateInvalidUser()
    {
        $is_valid = User::is_valid_user($this->NONTEST_USERNAME);
        $this->assertFalse($is_valid);
    }

    /**
     * @expectedException NonexistentUserException
     */
    public function testLoadExistingDifferCase()
    {
        $username = strtoupper($this->TEST_USERNAME);
        $user = new User($username);
    }

    /**
     * @expectedException NonexistentUserException
     */
    public function testLoadExistingDifferWhitespace()
    {
        $username = $this->TEST_USERNAME . '   ';
        $user = new User($username);
    }

    /**
     * @expectedException NonexistentUserException
     */
    public function testLoadNonexisting()
    {
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
        $user = new User($this->TEST_USERNAME);
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
