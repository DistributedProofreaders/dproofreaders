<?php

// $Id$

/**
 * Settings Class.

  Note: Everything marked "Implementation:" is information
        that should remain encapsulated inside the class.

 This class rovides access to user settings.
     ******
     Implementation: Initially limited to those stored in the usersettings table.
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
 Example: R1order (sort order of project listing) Example value: "GenreA"
     ******
    Implementation:
        Setting:
            Will ensure that record is deleted if the provided value is anything
                resolving to Null in PHP (including zero-valued numbers).
            Will ensure that a record exists with the value column set to the string
                value provided (or its PHP auto-conversion-string if other data type.)
        Getting:
            Will provide exactly what's in the value column. (Note this may result
            in something-in-something-else-out if PHP auto-conversion took place.)
     ******

3. Boolean-string-settings (for want of a better name.) These are cases where a record
is inserted to indicate that a boolean value is True for a user for (typically) a project.
Example setting: posted_notice, value "projectID3dfe7f4b3c2ca"
     ******
    Implementation:
        Setting and Getting: Set and get exactly what's provided.

Later....
Added read-only access to the three booleans from the "user" table (sitemanager, manager,
and postprocessor), since I could initialize them from the same query. Can add update
capability if needed.
*/
        

function ExecSQL($sql)
{
    $result = mysql_query($sql);
    if(mysql_errno())
        echo mysql_error();
}

class Settings
{
    var $setting_array;
    var $username;

    function Settings($name = Null)
    {
        global $pguser;

        // don't let them do anything if not logged in.
        if(!isset($pguser)) return;

        // if an override username provided, use it.
        if($name != Null)
            $this->username = $name;
        else
            $this->username = $pguser;

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
        while ($row = mysql_fetch_assoc($result))
        {
            // to know whether we've populated user table fields yet.
            static $isUserVals = false;
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
            $this->settings_array[$row['setting']] = $row['value'];
        }
        mysql_free_result($result);
    }

    // if $settingCode from "users" table, can't set it here. 
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

    // create/update a row with Value set to 'yes'
    function set_true($settingCode)
    {
        if(!$this->_isSettable($settingCode)) return;
        
        ExecSQL("REPLACE INTO usersettings
                SET value = 'yes',
                    username = '$this->username',
                    setting = '$settingCode'");
        $this->settings_array[$settingCode] = 'yes';
    }

    // delete any existing row.
    function set_false($settingCode)
    {
        if(!$this->_isSettable($settingCode)) return;
        ExecSQL("DELETE FROM usersettings 
                WHERE username = '$this->username' AND setting = '$settingCode'");
        unset($this->settings_array[$settingCode]);
    }

    // Either set_true (if $boolval == True, by any php interpretation,
    // otherwise set_false (i.e. delete).
    function set_boolean($settingCode, $boolval)
    {
        if(!$this->_isSettable($settingCode)) return;
        if($boolval == 'yes')
            $this->set_true($settingCode);
        else
            $this->set_false($settingCode);
    }

    // Return True iff a row exists and its value is 'yes'. Otherwise, False.
    function get_boolean($settingCode)
    {
        if (!array_key_exists($settingCode, $this->settings_array)) return false;
        $val = $this->settings_array[$settingCode] ;
        return ($this->settings_array[$settingCode] == 'yes') ? True : False;
    }

    // If $value and $default are both equivalent to PHP Null, set_false (i.e. delete.)
    // If $value equivalent to PHP Null and $default is not, set Value to $default.
    // If $value is not equivalent to PHP Null, set Value to $value.
    function set_value($settingCode, $value, $default = Null)
    {
        if(!$this->_isSettable($settingCode)) return;

        if(!$value)
            $value = $default;

        if(!$value && ($default != Null))
        {
            $this->set_false($settingCode);
        }
        else
        {
            $sql = "REPLACE usersettings
                    SET value = '$value',
                    username = '$this->username', 
                    setting = '$settingCode'" ;
            ExecSQL($sql);
            $this->settings_array[$settingCode] = $value;
        }
    }

    // If no record exists, return Null.
    // If Value column maps to PHP Null equivalent, return $default (even if Null.)
    // Otherwise return what's in the Value column.
    // Note: if setting is really boolean, this will NOT return True, but 'yes'.
    function get_value($settingCode, $default = Null)
    {
        if (!array_key_exists($settingCode, $this->settings_array)) return null;
        $val = $this->settings_array[$settingCode];
        return $val ? $val : $default;
    }

    function settings_count()
    {
        return count($this->settings_array);
    }
}
?>
