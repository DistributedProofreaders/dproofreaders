<?php

function ExecSQL($sql)
{
    $result = mysql_query($sql);
    if(mysql_errno())
        echo mysql_error();
}

class Settings
{
    var $setting_array;

    function Settings()
    {
        global $pguser;

        if(!isset($pguser))
            return;
        // Query the "usersettings" table and get all the rows for our user.
        // build an array with the settings, to use when somebody asks.
        $sql = "SELECT *
            FROM usersettings
            WHERE username = '$username'";

        $result = mysql_query($sql);

        while ($row = mysql_fetch_assoc($result))
        {
            $this->settings[$row['setting']] = $row['value'];
        }
        mysql_free_result($result);
    }


    function set_true($settingCode)
    {
        global $pguser;
        ExecSQL("REPLACE INTO usersettings
                SET value = '1',
                    username = '$pguser',
                    setting = '$settingcode'");
        $this->settings_array[$settingCode] = '1';
    }

    function set_false($settingCode)
    {
        global $pguser;
        ExecSQL("DELETE FROM usersettings 
                WHERE username = '$pguser' AND setting = '$settingCode'");
        unset($this->settings_array[$settingCode]);
    }

    function set_boolean($settingCode, $boolval)
    {
        if($boolval)
            set_true($settingCode);
        else
            set_false($settingCode);
    }

    function get_boolean($settingCode)
    {
        return isset($this->settings_array[$settingCode]);
    }

    function set_value($settingCode, $value, $default = Null)
    {
        global $pguser;
        if($value == Null)
            $value = $default;

        if($value == Null)
        {
            $this->set_false($settingCode);
        }
        else
        {
            ExecSQL("REPLACE usersettings
                    SET value = '$value',
                    username = '$pguser', 
                    setting = '$settingCode'");
            $this->settings_array[$settingCode] = $value;
        }
    }

    function get_value($settingCode, $default = Null)
    {
        return ($val = $this->settings_array[$settingCode]) ? $val : $default ;
    }
}
?>
