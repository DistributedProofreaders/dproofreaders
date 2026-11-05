<?php

class NonactivatedUserTest extends PHPUnit\Framework\TestCase
{
    private string $TEST_USERNAME = "NonactivatedUserTest_php";
    /** @var string[] */
    private $createdRecords = [];

    private function createNonactivatedUser(string $username): NonactivatedUser
    {
        $register = new NonactivatedUser();
        $register->username = $username;
        $register->real_name = "Joe Shmoe";
        $register->email = 'joe@localhost';
        $register->email_updates = true;
        $register->u_intlang = 'en_US';
        $register->user_password = 'password';
        $register->referrer = 'friend';
        $register->http_referrer = '';
        $register->save();
        $this->createdRecords[] = $username;
        return $register;
    }

    protected function tearDown(): void
    {
        foreach ($this->createdRecords as $username) {
            $sql = "
                DELETE FROM non_activated_users
                WHERE username = '$username'
            ";
            DPDatabase::query($sql);
        }
    }

    public function testEmptyConstructor(): void
    {
        $user = new NonactivatedUser();
        $this->assertFalse(isset($user->id));
    }

    public function testCreateRegistration(): void
    {
        $user = $this->createNonactivatedUser($this->TEST_USERNAME);
        $this->assertEquals($user->username, $this->TEST_USERNAME);
    }

    public function testLoadRegistration(): void
    {
        $this->createNonactivatedUser($this->TEST_USERNAME);
        $user = new NonactivatedUser($this->TEST_USERNAME);
        $this->assertEquals("Joe Shmoe", $user->real_name);
    }

    public function testLoadRegistrationById(): void
    {
        $existing_user = $this->createNonactivatedUser($this->TEST_USERNAME);
        $user = NonactivatedUser::load_from_id($existing_user->id);
        $this->assertEquals($this->TEST_USERNAME, $user->username);
    }

    public function testUpdateRegistration(): void
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

    public function testLoadInvalidUser(): void
    {
        $this->expectException(NonexistentNonactivatedUserException::class);
        new NonactivatedUser("blahblah");
    }

    public function testSetUnsettable(): void
    {
        $this->expectException(DomainException::class);
        $user = new NonactivatedUser();
        // @phpstan-ignore-next-line assign.propertyReadOnly (testing setter)
        $user->id = "";
    }

    public function testSetImmutable(): void
    {
        $this->expectException(DomainException::class);
        $user = $this->createNonactivatedUser($this->TEST_USERNAME);
        $user->username = "";
    }

    public function testDeleteNonactivatedUser(): void
    {
        $this->expectException(NonexistentNonactivatedUserException::class);
        // Creating and deleting a user show leave us with no user...
        $user = $this->createNonactivatedUser($this->TEST_USERNAME);
        $user->delete();
        new NonactivatedUser($this->TEST_USERNAME);
    }
}
