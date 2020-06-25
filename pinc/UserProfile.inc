<?php
include_once($relPath."misc.inc"); // create_mysql_update_string()

class NonexistentUserProfileException extends Exception { }

class UserProfile
{
    private $table_row;

    // List of fields that when set for a profile should never change
    private $immutable_fields = [
        "id",
        "u_ref",
    ];

    // Fields are assumed to be integers unless included here
    private $string_fields = [
        "profilename",
        "v_fntf_other",
        "h_fntf_other",
    ];

    public function __construct($id=NULL)
    {
        if($id !== NULL)
        {
            $this->load($id);
        }
    }

    // The __set() and __get() methods allow access to user fields without
    // creating accessors for them all individually.
    // See the PHP docs for "magic methods".
    public function __set($name, $value)
    {
        if(isset($this->$name) && in_array($name, $this->immutable_fields))
        {
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

    private function load($id)
    {
        $sql = sprintf("
            SELECT *
            FROM user_profiles
            WHERE id = %d
        ", $id);

        $result = mysqli_query(DPDatabase::get_connection(), $sql);
        if(!$result)
        {
            throw new UnexpectedValueException(DPDatabase::log_error());
        }
        elseif(mysqli_num_rows($result) == 0)
        {
            throw new NonexistentUserProfileException(sprintf(
                _('No user profile found with id = %s'),
                    $id)
            );
        }
        $this->table_row = mysqli_fetch_assoc($result);

        mysqli_free_result($result);
    }

    public function save()
    {
        if(!isset($this->id))
        {
            // validate that we have a u_ref
            if(!isset($this->u_ref))
            {
                throw new UnexpectedValueException(
                    _('Unable to save new profile without a user reference (u_ref)')
                );
            }

            $set_string = create_mysql_update_string($this->table_row, $this->string_fields);
            $sql = "
                INSERT INTO user_profiles
                SET $set_string
            ";
        }
        else
        {
            // remove immutable rows
            $update_data = $this->table_row;
            foreach($this->immutable_fields as $field)
                unset($update_data[$field]);

            $set_string = create_mysql_update_string($update_data, $this->string_fields);
            $sql = sprintf("
                UPDATE user_profiles
                SET $set_string
                WHERE id = %d
            ", $this->id);
        }
        mysqli_query(DPDatabase::get_connection(), $sql);

        if(!isset($this->id))
        {
            $this->id = mysqli_insert_id(DPDatabase::get_connection());
        }
    }

    public function delete()
    {
        if(!isset($this->id))
        {
            throw NonexistentUserProfileException(
                _("Cannot delete nonexistent profile")
            );
        }

        $sql = sprintf("
            DELETE FROM user_profiles
            WHERE id = %d
        ", $this->id);
        mysqli_query(DPDatabase::get_connection(), $sql);
    }

    // static functions
    static public function load_user_profiles($u_ref)
    {
        $sql = sprintf("
            SELECT id
            FROM user_profiles
            WHERE u_ref = %d
            ORDER BY id
        ", $u_ref);

        $profiles = [];
        $result = mysqli_query(DPDatabase::get_connection(), $sql);
        while($row = mysqli_fetch_assoc($result))
        {
            $profiles[] = new UserProfile($row["id"]);
        }
        mysqli_free_result($result);
        return $profiles;
    }
}
