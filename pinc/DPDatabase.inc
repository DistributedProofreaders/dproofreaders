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

        // Update the sql_mode to disable ONLY_FULL_GROUP_BY
        // Several places in the code, many in the stats pages, use GROUP BY
        // in a way that breaks if ONLY_FULL_GROUP_BY is enforced by MySQL.
        // We disable it here at the session level rather than in my.cnf
        // to not impact other database users (forum, wiki, etc).
        $sql = "SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";
        if(!mysqli_query(self::$_connection, $sql))
            throw new DBConnectionError("Unable to set sql_mode");
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

    static public function is_table_utf8($table_name)
    {
        $sql = sprintf("
            SELECT table_collation
            FROM information_schema.tables
            WHERE table_schema='%s' AND table_name='%s'
        ", self::$_db_name, $table_name);

        $result = mysqli_query(DPDatabase::get_connection(), $sql);
        if(!$result)
            throw new DBQueryError(mysqli_error(DPDatabase::get_connection()));

        $row = mysqli_fetch_assoc($result);
        $table_encoding = $row['table_collation'];
        mysqli_free_result($result);

        return $table_encoding == 'utf8mb4_general_ci';
    }

    // Prevent this class from being instantiated
    protected function __construct() {}
    protected function __clone() {}
    protected function __sleep() {}
    protected function __wakeup() {}
}

// vim: sw=4 ts=4 expandtab
