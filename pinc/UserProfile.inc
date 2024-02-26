<?php

class NonexistentUserProfileException extends Exception
{
}

/**
 * @property int $id;
 * @property int $u_ref;
 * @property string $profilename;
 * @property bool $i_res;
 * @property bool $i_type;
 * @property bool $i_layout;
 * @property bool $i_toolbar;
 * @property bool $i_statusbar;
 * @property bool $i_newwin;
 * @property int $v_fnts;
 * @property bool $v_fntf;
 * @property string $v_fntf_other;
 * @property int $v_tframe;
 * @property int $v_tlines;
 * @property int $v_tchars;
 * @property int $v_tscroll;
 * @property int $v_twrap;
 * @property int $h_fnts;
 * @property bool $h_fntf;
 * @property string $h_fntf_other;
 * @property int $h_tframe;
 * @property int $h_tlines;
 * @property int $h_tchars;
 * @property int $h_tscroll;
 * @property int $h_twrap;
 */
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

    public function __construct($id = null)
    {
        if ($id !== null) {
            $this->load($id);
        }
    }

    // The __set() and __get() methods allow access to user fields without
    // creating accessors for them all individually.
    // See the PHP docs for "magic methods".
    public function __set($name, $value)
    {
        if (isset($this->$name) && in_array($name, $this->immutable_fields)) {
            throw new DomainException(
                sprintf(
                    _("%s is an immutable field"),
                    $name
                )
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
        $sql = sprintf(
            "
            SELECT *
            FROM user_profiles
            WHERE id = %d
            ",
            $id
        );

        $result = DPDatabase::query($sql);
        if (mysqli_num_rows($result) == 0) {
            throw new NonexistentUserProfileException(
                sprintf(
                    _('No user profile found with id = %s'),
                    $id
                )
            );
        }
        $this->table_row = mysqli_fetch_assoc($result);

        mysqli_free_result($result);
    }

    public function save()
    {
        if (!isset($this->id)) {
            // validate that we have a u_ref
            if (!isset($this->u_ref)) {
                throw new UnexpectedValueException(
                    _('Unable to save new profile without a user reference (u_ref)')
                );
            }

            $set_string = create_mysql_update_string($this->table_row, $this->string_fields);
            $sql = "
                INSERT INTO user_profiles
                SET $set_string
            ";
        } else {
            // remove immutable rows
            $update_data = $this->table_row;
            foreach ($this->immutable_fields as $field) {
                unset($update_data[$field]);
            }

            $set_string = create_mysql_update_string($update_data, $this->string_fields);
            $sql = sprintf(
                "
                UPDATE user_profiles
                SET $set_string
                WHERE id = %d
                ",
                $this->id
            );
        }
        DPDatabase::query($sql);

        if (!isset($this->id)) {
            $this->id = mysqli_insert_id(DPDatabase::get_connection());
        }
    }

    public function delete()
    {
        if (!isset($this->id)) {
            throw new NonexistentUserProfileException(
                _("Cannot delete nonexistent profile")
            );
        }

        $sql = sprintf(
            "
            DELETE FROM user_profiles
            WHERE id = %d
            ",
            $this->id
        );
        DPDatabase::query($sql);
    }

    // static functions
    public static function load_user_profiles($u_ref)
    {
        $sql = sprintf(
            "
            SELECT id
            FROM user_profiles
            WHERE u_ref = %d
            ORDER BY id
            ",
            $u_ref
        );

        $profiles = [];
        $result = DPDatabase::query($sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $profiles[] = new UserProfile($row["id"]);
        }
        mysqli_free_result($result);
        return $profiles;
    }
}
