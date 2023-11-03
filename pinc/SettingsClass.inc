<?php

/**
 * Settings Class
 *
 * This class provides access to user settings stored in the user_settings table.
 */
class Settings
{
    // Multi-dimensional array containing all user settings. Each setting
    // may have one or more value associated with it. Format is:
    //     $settings_array[$setting] = array($value1, ...);
    private $settings_array = [];

    // username of subject user.
    private $username = null;

    public function __construct($name)
    {
        if ($name == '') {
            // Note that that's a "loose equals", so $name could be:
            // the empty string, the integer zero, the boolean false, or null.
            // Of those, the likeliest is null, indicating that the
            // current http-requestor is a "guest", not a logged-in user.

            // Return a Settings object with an empty $settings_array.
            return;
        }

        $this->username = $name;

        // Query the "usersettings" table and get all the rows for our user.
        // build an array with the settings, to use when somebody asks.
        $sql = sprintf(
            "
            SELECT *
            FROM usersettings
            WHERE username = '%s'
            ",
            DPDatabase::escape($this->username)
        );

        $result = DPDatabase::query($sql);
        // To know whether we've populated fields from 'users' table yet:
        while ($row = mysqli_fetch_assoc($result)) {
            $this->_load_value($row['setting'], $row['value']);
        }
        mysqli_free_result($result);
    }

    public function UserName()
    {
        return $this->username ? $this->username : "[none]";
    }

    // -------------------------------------------------------------------------

    /**
     * Set a setting to true
     *
     * @param string $settingCode
     *
     * @return void
     */
    public function set_true($settingCode)
    {
        $this->set_boolean($settingCode, true);
    }

    /**
     * Set a setting to false
     *
     * @param string $settingCode
     *
     * @return void
     */
    public function set_false($settingCode)
    {
        $this->set_boolean($settingCode, false);
    }

    /**
     * A wrapper around set_value() for boolean values.
     *
     * @param string $settingCode
     * @param bool $boolval
     *
     * @return void
     */
    public function set_boolean($settingCode, $boolval)
    {
        $this->set_value($settingCode, ($boolval ? 'yes' : null));
    }

    /**
     * Return True iff the setting exists and its value is 'yes',
     * otherwise, return False.
     *
     * @param string $settingCode
     *
     * @return bool
     */
    public function get_boolean($settingCode)
    {
        return ($this->get_value($settingCode) === 'yes');
    }

    // -------------------------------------------------------------------------

    private function _clear_setting($settingCode)
    {
        $sql = "
            DELETE FROM usersettings 
            WHERE username = '$this->username'
                AND setting = '$settingCode'
        " ;
        DPDatabase::query($sql);
    }

    private function _insert_setting_value($settingCode, $value)
    {
        $sql = sprintf(
            "
            INSERT INTO usersettings
                (username, setting, value)
            VALUES ('%s', '%s', '%s')
            ",
            DPDatabase::escape($this->username),
            DPDatabase::escape($settingCode),
            DPDatabase::escape($value)
        );
        DPDatabase::query($sql);
    }

    private function _delete_setting_value($settingCode, $value)
    {
        $sql = sprintf(
            "
            DELETE FROM usersettings
            WHERE username = '%s'
                AND setting = '%s'
                AND value = '%s'
            ",
            DPDatabase::escape($this->username),
            DPDatabase::escape($settingCode),
            DPDatabase::escape($value)
        );
        DPDatabase::query($sql);
    }

    /**
     * Set a setting to a specific value.
     *
     * If this is a multi-valued setting this will become a single-valued setting.
     * If $value is NULL, remove the setting.
     *
     * @param string $settingCode
     * @param mixed $value
     *
     * @return void
     */
    public function set_value($settingCode, $value)
    {
        if ($this->username == '') {
            // If we don't have a username set, don't try to write anything
            // to the database. A likely cause of this would be that the
            // current http-requestor is a "guest", not a logged-in user.
            return;
        }

        $this->_clear_setting($settingCode);
        unset($this->settings_array[$settingCode]);

        if (!is_null($value)) {
            $this->add_value($settingCode, $value);
        }
    }

    /**
     * Get the value for a setting
     *
     * If no record exists, return `$default`. Otherwise return what's in the
     * Value column. Note: if setting is really boolean, this will NOT return
     * True, but 'yes' (a string).
     *
     * @param string $settingCode
     * @param mixed $default
     *
     * @return mixed
     */
    public function get_value($settingCode, $default = null)
    {
        $values = $this->get_values($settingCode);

        if (count($values) == 0) {
            return $default;
        }

        // Ideally we'd throw an exception if we detect that the number of
        // values is more than 1 -- indeed we did this for a while. The
        // problem is that in rare cases two processes can race clearing and
        // setting values. Instead, we resort back to what we did prior to
        // supporting multiple values, which is to just return one of them.
        // The next time this setting is set with set_value(), it should be
        // reduced back down to 1.

        return $values[0];
    }

    /**
     * Load a setting value into the $settings_array data structure
     *
     * @param string $setting
     * @param mixed $value
     *
     * @return void
     */
    private function _load_value($setting, $value)
    {
        if (isset($this->settings_array[$setting])) {
            $this->settings_array[$setting][] = $value;
        } else {
            $this->settings_array[$setting] = [$value];
        }
    }

    /**
     * Add a value to the setting without changing it, even if it already
     * exists.
     *
     * This allows a setting to have multiple values.
     *
     * @param string $settingCode
     * @param mixed $value
     *
     * @return void
     */
    public function add_value($settingCode, $value)
    {
        $this->_load_value($settingCode, $value);
        $this->_insert_setting_value($settingCode, $value);
    }

    /**
     * Remove a setting:value pair
     *
     * @param string $settingCode
     * @param mixed $value
     *
     * @return void
     */
    public function remove_value($settingCode, $value)
    {
        if (array_key_exists($settingCode, $this->settings_array) && in_array($value, $this->settings_array[$settingCode])) {
            $this->settings_array[$settingCode] = array_diff(
                $this->settings_array[$settingCode],
                [$value]
            );

            $this->_delete_setting_value($settingCode, $value);
        }
    }

    /**
     * Returns an array of values for the setting
     *
     * @param string $settingCode
     *
     * @return mixed
     */
    public function get_values($settingCode)
    {
        if (!array_key_exists($settingCode, $this->settings_array)) {
            return [];
        }

        return $this->settings_array[$settingCode];
    }

    // -------------------------------------------------------------------------

    /**
     * Count the number of settings for this user
     *
     * @return int
     */
    public function settings_count()
    {
        return count($this->settings_array);
    }

    /**
     * Get a Settings object for a user.
     *
     * If such an object has already been created, return it. By using this
     * function only and not the constructor, there will only be one object
     * around for each user, and no problems will arise with
     * setting/getting the same settings at various places.
     * If the name is not set, null is returned.
     *
     * We use assignment-by-reference and return-by-reference (note the ampersands)
     * to ensure that multiple returns for the same $username are references to
     * the originally created object, rather than copies of it.
     * (See the PHP manual under "Assignment Operators" and "References Explained".)
     * Callers should also use assignment-by-reference, e.g.
     * ```
     * $settings =& Settings::get_settings($username);
     * ```
     * to ensure that changes in settings are visible everywhere they should be.
     *
     * @param string $username
     *
     * @return object Settings
     */
    public static function & get_Settings($username)
    {
        static $Settings_for_ = [];
        if (array_key_exists($username, $Settings_for_)) {
            return $Settings_for_[$username];
        } else {
            $settings = new Settings($username);
            $Settings_for_[$username] = & $settings;
            return $settings;
        }
    }

    /**
     * Return a list of unique usernames that have a specific setting
     * specified (and optionally with a specific value).
     *
     * @param string $setting
     * @param mixed $value
     *
     * @return array
     */
    public static function get_users_with_setting($setting, $value = null)
    {
        $usernames = [];
        $sql = sprintf(
            "
            SELECT DISTINCT username
            FROM usersettings
            WHERE setting = '%s'
            ",
            DPDatabase::escape($setting)
        );

        if ($value) {
            $sql .= sprintf(
                " AND value='%s'",
                DPDatabase::escape($value)
            );
        }

        $result = DPDatabase::query($sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $usernames[] = $row['username'];
        }
        mysqli_free_result($result);

        return $usernames;
    }
}
