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
*/
        

function ExecSQL($sql)
{
    $result = mysql_query($sql);
    if(mysql_errno())
        echo mysql_error();
}

class Settings
{
    var $username;
    var $setting_array;

    function Settings($name = Null)
    {
        global $pguser;

        if(isset($name))
            $this->username = $name;
        else
        {
            if(!isset($pguser))
                return;
            $this->username = $pguser;
        }

        // Query the "usersettings" table and get all the rows for our user.
        // build an array with the settings, to use when somebody asks.
        $sql = "SELECT *
            FROM usersettings
            WHERE username = '$this->username'";

        $result = mysql_query($sql);

        while ($row = mysql_fetch_assoc($result))
        {
            $this->settings[$row['setting']] = $row['value'];
        }
        mysql_free_result($result);
    }


    // create/update a row with Value set to 'yes'
    function set_true($settingCode)
    {
        ExecSQL("REPLACE INTO usersettings
                SET value = 'yes',
                    username = '$this->username',
                    setting = '$settingcode'");
        $this->settings_array[$settingCode] = 'yes';
    }

    // delete any existing row.
    function set_false($settingCode)
    {
        ExecSQL("DELETE FROM usersettings 
                WHERE username = '$this->username' AND setting = '$settingCode'");
        unset($this->settings_array[$settingCode]);
    }

    // Either set_true (if $boolval == True, by any php interpretation,
    // otherwise set_false (i.e. delete).
    function set_boolean($settingCode, $boolval)
    {
        if($boolval == 'yes')
            set_true($settingCode);
        else
            set_false($settingCode);
    }

    // Return True iff a row exists and its value is 'yes'. Otherwise, False.
    function get_boolean($settingCode)
    {
        return ($this->settings_array[$settingCode] == 'yes') ? True : False;
    }

    // If $value and $default are both equivalent to PHP Null, set_false (i.e. delete.)
    // If $value equivalent to PHP Null and $default is not, set Value to $default.
    // If $value is not equivalent to PHP Null, set Value to $value.
    function set_value($settingCode, $value, $default = True)
    {
        if(!$value)
            $value = $default;

        if(!$value) {
            $this->set_false($settingCode);
        }
        else
        {
            ExecSQL("REPLACE usersettings
                    SET value = '$value',
                    username = '$this->username', 
                    setting = '$settingCode'");
            $this->settings_array[$settingCode] = $value;
        }
    }

    // If no record exists, return Null.
    // If Value column maps to PHP Null equivalent, return $default (even if Null.)
    // Otherwise return what's in the Value column.
    // Note: if setting is really boolean, this will NOT return True, but 'yes'.
    function get_value($settingCode, $default = Null)
    {
        return ($val = $this->settings_array[$settingCode]) ? $val : $default ;
    }
}
?>
