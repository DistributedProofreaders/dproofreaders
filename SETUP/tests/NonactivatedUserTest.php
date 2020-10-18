<?php

class NonactivatedUserTest extends PHPUnit\Framework\TestCase
{
    private $TEST_USERNAME = "NonactivatedUserTest_php";
    private $createdRecords = array();

    private function createNonactivatedUser($username)
    {
        $register = new NonactivatedUser();
        $register->username = $username;
        $register->real_name = "Joe Shmoe";
        $register->email = 'joe@localhost';
        $register->email_updates = 'yes';
        $register->u_intlang = 'en_US';
        $register->user_password = 'password';
        $register->referrer = 'friend';
        $register->http_referrer = '';
        $register->save();
        $this->createdRecords[] = $username;
        return $register;
    }

    protected function tearDown()
    {
        foreach($this->createdRecords as $username)
        {
            $sql = "
                DELETE FROM non_activated_users
                WHERE username = '$username'
            ";
            $result = mysqli_query(DPDatabase::get_connection(), $sql);
        }
    }

    public function testEmptyConstructor()
    {
        $user = new NonactivatedUser();
        $this->assertFalse(isset($user->id));
    }

    public function testCreateRegistration()
    {
        $user = $this->createNonactivatedUser($this->TEST_USERNAME);
        $this->assertEquals($user->username, $this->TEST_USERNAME);
    }

    public function testLoadRegistration()
    {
        $this->createNonactivatedUser($this->TEST_USERNAME);
        $user = new NonactivatedUser($this->TEST_USERNAME);
        $this->assertEquals("Joe Shmoe", $user->real_name);
    }

    public function testLoadRegistrationById()
    {
        $existing_user = $this->createNonactivatedUser($this->TEST_USERNAME);
        $user = NonactivatedUser::load_from_id($existing_user->id);
        $this->assertEquals($this->TEST_USERNAME, $user->username);
    }

    public function testUpdateRegistration()
    {
        $this->createNonactivatedUser($this->TEST_USERNAME);

        $user = new NonactivatedUser($this->TEST_USERNAME);
        $user->real_name = "Jane Shmane";
        $user->save();
        $id = $user->id;

        $user = new NonactivatedUser($this->TEST_USERNAME);
        $this->assertEquals("Jane Shmane", $user->real_name);
        $this->assertEquals($id, $user->id);
    }

    /**
     * @expectedException NonexistentNonactivatedUserException
     */
    public function testLoadInvalidUser()
    {
        new NonactivatedUser("blahblah");
    }

    /**
     * @expectedException DomainException
     */
    public function testSetUnsettable()
    {
        $user = new NonactivatedUser();
        $user->id = "";
    }

    /**
     * @expectedException DomainException
     */
    public function testSetImmutable()
    {
        $user = $this->createNonactivatedUser($this->TEST_USERNAME);
        $user->username = "";
    }

    /**
     * @expectedException NonexistentNonactivatedUserException
     */
    public function testDeleteNonactivatedUser()
    {
        # Creating and deleting a user show leave us with no user...
        $user = $this->createNonactivatedUser($this->TEST_USERNAME);
        $user->delete();
        new NonactivatedUser($this->TEST_USERNAME);
    }
}
