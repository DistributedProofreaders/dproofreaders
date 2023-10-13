<?php

class NonexistentNonactivatedUserException extends Exception
{
}

class NonuniqueNonactivatedUserException extends Exception
{
}

class NonactivatedUser
{
    private $table_row;

    // List of fields that can never be set outside of this class
    private $unsettable_fields = [
        "id",
        "date_created",
    ];

    // List of fields that when set should never change
    private $immutable_fields = [
        "username",
    ];

    // Fields are assumed to be strings unless included here
    private $integer_fields = [
        "email_updates",
    ];

    public function __construct($username = null)
    {
        if ($username !== null) {
            $this->load("username", $username);
        }
    }

    // The __set() and __get() methods allow access to user fields without
    // creating accessors for them all individually.
    // See the PHP docs for "magic methods".
    public function __set($name, $value)
    {
        if (in_array($name, $this->unsettable_fields)) {
            throw new DomainException(sprintf(
                _("%s is an unsettable field"), $name)
            );
        }

        if (isset($this->$name) && in_array($name, $this->immutable_fields)) {
            throw new DomainException(sprintf(
                _("%s is an immutable field"), $name)
            );
        }

        $this->table_row[$name] = $value;
    }

    public function __get($name)
    {
        return $this->table_row[$name];
    }

    public function __isset($name)
    {
        return isset($this->table_row[$name]);
    }

    private function load($field, $value, $strict = true)
    {
        if (!in_array($field, $this->integer_fields)) {
            $escaped_value = sprintf("'%s'", DPDatabase::escape($value));
        } else {
            $escaped_value = $value;
        }

        $sql = "
            SELECT *
            FROM non_activated_users
            WHERE $field=$escaped_value
        ";
        $result = DPDatabase::query($sql);

        if (mysqli_num_rows($result) == 0) {
            throw new NonexistentNonactivatedUserException(sprintf(
                _('No non_activated_user found with %1$s = %2$s'),
                    $field, $value)
            );
        } elseif (mysqli_num_rows($result) > 1) {
            throw new NonuniqueNonactivatedUserException(sprintf(
                _('Multiple non_activated_users found with %1$s = %2$s'),
                    $field, $value)
            );
        }
        $this->table_row = mysqli_fetch_assoc($result);

        // MySQL does case-insensitive matches for strings, as well as
        // ignores trailing whitespace. If $strict is TRUE, and the field
        // is the username, require that the requested $username matches the
        // existing username in case and whitespace by doing a PHP string
        // compare.
        if ($strict and $field == 'username' and $this->username != $value) {
            throw new NonexistentNonactivatedUserException(sprintf(
                _('No non_activated_user found with %1$s = %2$s'),
                    $field, $value)
            );
        }

        mysqli_free_result($result);
    }

    public function save()
    {
        $todays_date = time();

        // If this object doesn't have a unique ID, generate one before saving it to
        // the database.
        if (!isset($this->table_row["id"])) {
            // 16 random bytes turn into a 32-character hex string prefixed with 'userID'
            $this->table_row["id"] = "userID" . bin2hex(openssl_random_pseudo_bytes(16));
        }

        $referrer_details = $this->referrer == 'other' ? $this->referrer_details : '';

        // This function persists the object in the database, either by
        // creating a new row or updating an existing one. It does updates
        // with the use of an INSERT INTO / ON DUPLICATE KEY based on the
        // primary key (username). The use of ON DUPLICATE KEY creates the
        // possibility of an unlikely race condition where two simultaneous
        // registrations with the same ID would "succeed" where the second
        // request just saves over the prior one.
        $query = sprintf("
            INSERT INTO non_activated_users
            SET
                id = '%s',
                date_created = $todays_date,
                username = '%s',
                real_name = LEFT('%s', 100),
                email = LEFT('%s', 100),
                email_updates = %d,
                referrer = '%s',
                referrer_details = LEFT('%s', 256),
                http_referrer = LEFT('%s', 256),
                u_intlang = '%s',
                user_password = '%s'
            ON DUPLICATE KEY UPDATE
                real_name = LEFT('%s', 100),
                email = LEFT('%s', 100),
                email_updates = %d,
                referrer = '%s',
                referrer_details = LEFT('%s', 256),
                http_referrer = LEFT('%s', 256),
                u_intlang = '%s',
                user_password = '%s'
        ", $this->table_row["id"],
            DPDatabase::escape($this->username),
            DPDatabase::escape($this->real_name),
            DPDatabase::escape($this->email),
            (int)$this->email_updates,
            DPDatabase::escape($this->referrer),
            DPDatabase::escape($referrer_details),
            DPDatabase::escape($this->http_referrer),
            DPDatabase::escape($this->u_intlang),
            DPDatabase::escape($this->user_password),
            // and for the updates
            DPDatabase::escape($this->real_name),
            DPDatabase::escape($this->email),
            (int)$this->email_updates,
            DPDatabase::escape($this->referrer),
            DPDatabase::escape($referrer_details),
            DPDatabase::escape($this->http_referrer),
            DPDatabase::escape($this->u_intlang),
            DPDatabase::escape($this->user_password)
        );

        $result = DPDatabase::query($query, false);
        if (!$result) {
            throw new UnexpectedValueException(DPDatabase::log_error());
        }
    }

    public function delete()
    {
        if (!isset($this->id)) {
            return;
        }

        $sql = sprintf("
            DELETE FROM non_activated_users
            WHERE id = '%s'",
            DPDatabase::escape($this->id)
        );
        DPDatabase::query($sql);
    }

    // static functions

    /**
     * Load a NonactivatedUser record by ID
     *
     * Example: `$user = NonactivatedUser::load_from_id($id);`
     *
     * @param string $id
     *
     * @return object NonactivatedUser
     */
    public static function load_from_id($id)
    {
        $user = new NonactivatedUser();
        $user->load('id', $id);
        return $user;
    }

    /**
     * Load a NonactivatedUser record by email address
     *
     * Example: `$user = NonactivatedUser::load_from_email($email);`
     *
     * @param string $email
     *
     * @throws NonuniqueNonactivatedUserException
     *   The email address is not guaranteed to be unique.
     *
     * @return object NonactivatedUser
     */
    public static function load_from_email($email)
    {
        $user = new NonactivatedUser();
        $user->load('email', $email);
        return $user;
    }
}
