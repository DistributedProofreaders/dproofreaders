<?php

class DBConnectionError extends Exception
{
}

class DBQueryError extends Exception
{
}

final class DPDatabase
{
    private static $_connection = false;
    private static $_db_name = null;
    private static $_default_db_charset = null;
    private static $_default_db_collation = null;
    public static $skip_encoding_check = false;

    public static function connect()
    {
        include('udb_user.php');
        self::$_db_name = $db_name;

        // Throw exceptions for DB call failures (PHP 8.1 default values)
        // See forum_interface.inc for some special-cases when dealing with
        // phpBB for login / logout.
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        try {
            self::$_connection = mysqli_connect($db_server, $db_user, $db_password);
        } catch (mysqli_sql_exception $e) {
            throw new DBConnectionError("Unable to connect to database");
        }

        try {
            mysqli_select_db(self::$_connection, $db_name);
        } catch (mysqli_sql_exception $e) {
            throw new DBConnectionError("Unable to locate database.");
        }

        // Update the sql_mode to disable ONLY_FULL_GROUP_BY
        // Several places in the code, many in the stats pages, use GROUP BY
        // in a way that breaks if ONLY_FULL_GROUP_BY is enforced by MySQL.
        // We disable it here at the session level rather than in my.cnf
        // to not impact other database users (forum, wiki, etc).
        $sql = "SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";
        try {
            mysqli_query(self::$_connection, $sql);
        } catch (mysqli_sql_exception $e) {
            throw new DBConnectionError("Unable to set sql_mode");
        }

        // To prevent corrupting Latin-1 encoded databases with UTF-8
        // characters, bail if the database is not in the expected UTF-8
        // encoding. We allow an override for upgrade scripts to connect
        // and do the conversion.
        if (!self::$skip_encoding_check and self::get_default_db_charset() != "utf8mb4") {
            throw new DBConnectionError("Database not configured for UTF8.");
        }
        //-------------------------------------------------------------------

        // set character set to UTF8
        mysqli_set_charset(self::$_connection, "utf8mb4");
    }

    public static function close()
    {
        if (!self::$_connection) {
            return;
        }

        mysqli_close(self::$_connection);
    }

    public static function get_connection()
    {
        return self::$_connection;
    }

    public static function escape($value)
    {
        return mysqli_real_escape_string(self::$_connection, $value);
    }

    public static function escape_like_wildcards($value)
    {
        return addcslashes($value, "%_");
    }

    public static function query($sql, $throw_on_failure = true, $log_on_failure = true)
    {
        try {
            $result = mysqli_query(self::$_connection, $sql);
        } catch (mysqli_sql_exception $e) {
            if ($log_on_failure) {
                // include this function's caller in the backtrace
                $error = self::log_error(1);
            } else {
                $error = _("An error occurred during a database query.");
            }
            if ($throw_on_failure) {
                throw new DBQueryError($error);
            }
        }
        return $result;
    }

    public static function affected_rows()
    {
        return mysqli_affected_rows(self::$_connection);
    }

    public static function log_error($backtrace_level = 0)
    {
        // Log the SQL error to the PHP error log and return a generic error
        // for display to the user
        $backtrace = debug_backtrace();
        $caller = $backtrace[$backtrace_level]['file'] . ":" . $backtrace[$backtrace_level]['line'];
        $error = str_replace("\n", "\\n", mysqli_error(DPDatabase::get_connection()));
        error_log("DPDatabase.inc - log_error from $caller: $error");
        return _("An error occurred during a database query and has been logged.");
    }

    private static function get_db_defaults()
    {
        $result = self::query(sprintf("
            SELECT *
            FROM information_schema.schemata
            WHERE schema_name='%s';
        ", self::$_db_name));

        $row = mysqli_fetch_assoc($result);
        self::$_default_db_charset = $row['DEFAULT_CHARACTER_SET_NAME'];
        self::$_default_db_collation = $row['DEFAULT_COLLATION_NAME'];
        mysqli_free_result($result);
    }

    public static function get_default_db_charset()
    {
        if (!self::$_default_db_charset) {
            self::get_db_defaults();
        }
        return self::$_default_db_charset;
    }

    public static function get_default_db_collation()
    {
        if (!self::$_default_db_collation) {
            self::get_db_defaults();
        }
        return self::$_default_db_collation;
    }

    public static function is_table_utf8($table_name)
    {
        $sql = sprintf("
            SELECT TABLE_COLLATION
            FROM information_schema.tables
            WHERE table_schema='%s' AND table_name='%s'",
            self::$_db_name,
            self::escape($table_name));
        $result = self::query($sql);
        $row = mysqli_fetch_assoc($result);
        $table_encoding = $row['TABLE_COLLATION'];
        mysqli_free_result($result);

        return stripos($table_encoding, 'utf8mb4') === 0;
    }

    public static function does_table_exist($table_name)
    {
        $sql = sprintf("
            SELECT TABLE_NAME
            FROM information_schema.tables
            WHERE table_schema='%s' AND table_name='%s'",
            self::$_db_name,
            self::escape($table_name));
        $result = self::query($sql);
        return mysqli_fetch_assoc($result) !== null;
    }

    // Prevent this class from being instantiated
    protected function __construct()
    {
    }
    protected function __clone()
    {
    }
    public function __sleep()
    {
    }
    public function __wakeup()
    {
    }
}
