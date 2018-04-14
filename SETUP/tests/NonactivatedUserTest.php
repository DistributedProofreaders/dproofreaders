<?php

class NonactivatedUserTest extends PHPUnit_Framework_TestCase
{
    private $TEST_USERNAME = "NonactivatedUserTest_php";

    private function createRecord($username)
    {
        $register = new NonactivatedUser();
        $register->username = $username;
        $register->real_name = "Joe Shmoe";
        $register->email = 'joe@localhost';
        $register->email_updates = 'yes';
        $register->u_intlang = 'en_US';
        $register->user_password = 'password';
        $register->save();
        return $register;
    }

    protected function tearDown()
    {
        $sql = "
            DELETE FROM non_activated_users
            WHERE username = '$this->TEST_USERNAME'
        ";
        $result = mysqli_query(DPDatabase::get_connection(), $sql);
    }

    public function testEmptyConstructor()
    {
        $user = new NonactivatedUser();
    }

    public function testCreateRegistration()
    {
        $register = $this->createRecord($this->TEST_USERNAME);
    }

    public function testLoadRegistration()
    {
        $this->createRecord($this->TEST_USERNAME);
        $register = new NonactivatedUser($this->TEST_USERNAME);
        $this->assertEquals("Joe Shmoe", $register->real_name);
    }

    public function testLoadRegistrationById()
    {
        $register = $this->createRecord($this->TEST_USERNAME);
        $validate = new NonactivatedUser();
        $validate->load("id", $register->id);
        $this->assertEquals($this->TEST_USERNAME, $validate->username);
    }

    public function testUpdateRegistration()
    {
        $this->createRecord($this->TEST_USERNAME);

        $register = new NonactivatedUser($this->TEST_USERNAME);
        $register->real_name = "Jane Shmane";
        $register->save();

        $validate = new NonactivatedUser($this->TEST_USERNAME);
        $this->assertEquals("Jane Shmane", $validate->real_name);
    }

    /**
     * @expectedException NonexistentNonactivatedUserException
     */
    public function testLoadInvalidUser()
    {
        $register = new NonactivatedUser("blahblah");
    }

    /**
     * @expectedException DomainException
     */
    public function testSetUnsettable()
    {
        $register = new NonactivatedUser();
        $register->id = "";
    }

    /**
     * @expectedException DomainException
     */
    public function testSetImmuable()
    {
        $register = $this->createRecord($this->TEST_USERNAME);
        $register->username = "";
    }
}
