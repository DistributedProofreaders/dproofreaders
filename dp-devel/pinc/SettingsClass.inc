<?php

// $Id$

/**
 * Settings Class.

  Note: Everything marked "Implementation:" is information
        that should remain encapsulated inside the class.

 This class rovides access to user settings.
     ******
     Implementation: Initially limited to those stored in the usersettings table.
                     Subsequently expanded to cover "manager", "postprocessor",
                     and "sitemanager" from the "users" table. 
     ******

 There are three setting types:

1. Boolean settings. Return value is True or False.
 Example setting: post_proofer
     ******
    Implementation:
        Setting to True.
            Will ensure that a record exists with "yes" in the value column.
        Setting to anything other than True
            Will ensure that the record is deleted.
        Getting.
            Will return True if there is a record and the value column holds "yes".
            Will return False otherwise.
     ******

2. Value settings. Non-empty string settings.
 Example: R1order (sort order of project listing) Example value: "GenreA" (Genre Ascending)
     ******
    Implementation:
        Setting:
            Will ensure that record is deleted if the provided value is anything
                resolving to Null in PHP (including zero-valued numbers).
            Will ensure that a record exists with the value column set to the string
                value provided (or its PHP auto-conversion-string if other data type.)
        Getting:
            Will provide exactly what's in the value column. (Note this may result
            in something-in/something-else-out if PHP auto-conversion took place.)
     ******

3. Boolean-string-settings (for want of a better name.) These are cases where a record
is inserted to indicate that a boolean value is True for a user for (typically) a project.
Example setting: posted_notice, value "projectID3dfe7f4b3c2ca"
     ******
    Implementation:
        Setting and Getting: Set and get exactly what's provided.

12/30/04 (DAK) Removed last vestige of a preference for/default of the logged on user.
               The constructor now requires a username argument.
*/
        

function ExecSQL($sql)
{
    $result = mysql_query($sql);
    if(mysql_errno())
        echo mysql_error();
}

class Settings
{
    // assoc. array containing all user setting name/value pairs.
    var $settings_array = array();
    // username of subject user.
    var $username;

    function Settings($name)
    {
        if($name == '')
        {
            // Return a Settings object with an empty $settings_array.
            return;
        }

        $this->username = $name;

        // Query the "usersettings" table and get all the rows for our user.
        // build an array with the settings, to use when somebody asks.
        $sql = "SELECT  u.sitemanager,
                        u.manager,
                        u.postprocessor,
                        us.*
            FROM users AS u
            LEFT OUTER JOIN usersettings AS us ON u.username = us.username
            WHERE u.username = '$this->username'";

        $result = mysql_query($sql);
        // To know whether we've populated fields from 'users' table yet:
        $isUserVals = false;
        while ($row = mysql_fetch_assoc($result))
        {
            // get the onesies from the first row
            // yes, this is more efficient than two separate queries.
            if(!$isUserVals)
            {
                $isUserVals = true;
                if ($row['sitemanager'] == 'yes')
                    $this->settings_array['sitemanager'] = 'yes';
                if ($row['manager'] == 'yes')
                    $this->settings_array['manager'] = 'yes';
                if ($row['postprocessor'] == 'yes')
                    $this->settings_array['postprocessor'] = 'yes';
            }
            // now continue to add values from usersettings table.
            // This could be null values if the outer join matched nothing
            // so check there is something in the "setting" column.
            if($row['setting'])
                $this->settings_array[$row['setting']] = $row['value'];
        }
        mysql_free_result($result);
    }

    // check if this is an updateable setting in this context.
    function _isSettable($settingCode)
    {
        switch($settingCode)
        {
            default:
                return true;
            case 'sitemanager':
            case 'manager':
            case 'postprocessor':
                return false;
        }
    }

    // whom do I describe?
    function UserName()
    {
        return $this->username ? $this->username : "[none]";
    }

    // -------------------------------------------------------------------------

    // Setting to True
    function set_true($settingCode)
    {
        $this->set_boolean($settingCode,TRUE);
    }

    // Setting to False
    function set_false($settingCode)
    {
        $this->set_boolean($settingCode,FALSE);
    }

    function set_boolean($settingCode, $boolval)
    // A wrapper around set_value() for boolean values.
    {
        $this->set_value($settingCode, ($boolval ? 'yes' : NULL) );
    }

    // Return True iff the setting exists and its value is 'yes'.
    // Otherwise, return False.
    function get_boolean($settingCode)
    {
        return ( $this->get_value($settingCode) === 'yes' );
    }

    // -------------------------------------------------------------------------

    // If $value is NULL, remove the setting.
    // Otherwise, set Value to $value.
    function set_value($settingCode, $value)
    {
        if(!$this->_isSettable($settingCode))
        {
            die("Error: cannot set '$settingCode'");
        }

        if(is_null($value))
        {
            $sql = "DELETE FROM usersettings 
                    WHERE username = '$this->username'
                    AND setting = '$settingCode'" ;
            ExecSQL($sql);
            unset($this->settings_array[$settingCode]);
        }
        else
        {
            if (array_key_exists($settingCode, $this->settings_array)) {
                $sql = "UPDATE usersettings
                        SET value = '$value'
                        WHERE username = '$this->username'
                        AND setting = '$settingCode'" ;
            }
            else {
                $sql = "INSERT INTO usersettings
                        (username, setting, value)
                        VALUES ('$this->username', '$settingCode', '$value')" ;
            }
            ExecSQL($sql);
            $this->settings_array[$settingCode] = $value;
        }
    }

    // If no record exists, return $default.
    // Otherwise return what's in the Value column.
    // Note: if setting is really boolean, this will NOT return True, but 'yes' (a string).
    function get_value($settingCode, $default = Null)
    {
        if (!array_key_exists($settingCode, $this->settings_array)) return $default;
        return $this->settings_array[$settingCode];
    }

    // -------------------------------------------------------------------------

    function settings_count()
    {
        return count($this->settings_array);
    }

    // Get an object for this $username. If such an object has
    // already been created, return it. By using this function only
    // and not the constructor, there will only be one object
    // around for each user, and no problems will arise with
    // setting/getting the same settings at various places.
    // If the name is not set, null is returned.
    //
    // We use assignment-by-reference and return-by-reference (note the ampersands)
    // to ensure that multiple returns for the same $username are references to
    // the originally created object, rather than copies of it.
    // (See the PHP manual under "Assignment Operators" and "References Explained".)
    // Callers should also use assignment-by-reference, e.g.
    //     $settings =& Settings::get_settings($username);
    // to ensure that changes in settings are visible everywhere they should be.
    //
    function & get_Settings($username)
    {
        static $Settings_for_ = array();
        if (array_key_exists($username, $Settings_for_))
        {
            return $Settings_for_[$username];
        }
        else
        {
            $settings =& new Settings($username);
            $Settings_for_[$username] =& $settings;
            return $settings;
        }
    }
}

// vim: sw=4 ts=4 expandtab
?>
