<?php

class DBConnectionError extends Exception { }

final class DPDatabase
{
    static private $_connection = FALSE;

    static public function connect()
    {
        include('udb_user.php');

        // Ignore warnings from mysqli_connect() so we can handle them
        // gracefully elsewhere.
        self::$_connection = @mysqli_connect($db_server, $db_user, $db_password);

        if(!self::$_connection)
            throw new DBConnectionError("Unable to connect to database");

        if(!mysqli_select_db(self::$_connection, $db_name))
            throw new DBConnectionError("Unable to locate database.");
    }

    static public function close()
    {
        if(!self::$_connection)
            return;

        mysqli_close(self::$_connection);
    }

    static public function get_connection()
    {
        return self::$_connection;
    }

    // Prevent this class from being instantiated
    protected function __construct() {}
    protected function __clone() {}
    protected function __sleep() {}
    protected function __wakeup() {}
}

// vim: sw=4 ts=4 expandtab
