<?php

class DBConnectionError extends Exception
{
}

class DBQueryError extends Exception
{
}

final class DPDatabase
{
    private static ?mysqli $_connection = null;
    private static ?string $_db_name = null;
    private static ?string $_default_db_charset = null;
    private static ?string $_default_db_collation = null;
    public static bool $skip_encoding_check = false;
    public static ?string $tracing_log = null;

    public static function connect(?string $db_server = null, ?string $db_name = null, ?string $db_user = null, ?string $db_password = null): void
    {
        self::$_db_name = $db_name ?? SiteConfig::get()->db_name;

        // Throw exceptions for DB call failures (PHP 8.1 default values)
        // See forum_interface.inc for some special-cases when dealing with
        // phpBB for login / logout.
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        try {
            self::$_connection = mysqli_connect(
                $db_server ?? SiteConfig::get()->db_server,
                $db_user ?? SiteConfig::get()->db_user,
                $db_password ?? SiteConfig::get()->db_password
            );
        } catch (mysqli_sql_exception $e) {
            throw new DBConnectionError("Unable to connect to database");
        }

        try {
            self::$_connection->select_db(self::$_db_name);
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
            self::$_connection->query($sql);
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

    public static function close(): void
    {
        if (!self::$_connection) {
            return;
        }

        self::$_connection->close();
        self::$_connection = null;
    }

    public static function get_connection(): ?mysqli
    {
        return self::$_connection;
    }

    public static function escape(?string $value): string
    {
        return mysqli_real_escape_string(self::$_connection, $value ?? '');
    }

    public static function escape_like_wildcards(?string $value): string
    {
        return addcslashes($value, "%_");
    }

    /**
     * Execute a query against the MySQL server
     *
     * In the background, this calls mysqli_query and thus
     * will return:
     *  - on success, either a mysqli_result or true depending on
     *    the SQL statement (see the documentation for mysqli_query).
     *  - on failure, it depends on the value of `$throw_on_failure`:
     *      - If it is true, it will throw an exception.
     *      - If it is false, it will return false.
     *
     * @return mysqli_result|bool
     */
    public static function query(string $sql, bool $throw_on_failure = true, bool $log_on_failure = true)
    {
        try {
            $result = self::$_connection->query($sql);
            DPDatabase::log_trace($sql);
        } catch (mysqli_sql_exception $e) {
            if ($log_on_failure) {
                // include this function's caller in the backtrace
                $error = self::log_error(1);
            } else {
                $error = _("An error occurred during a database query.");
            }
            if ($throw_on_failure) {
                throw new DBQueryError($error, 0, $e);
            }
            return false;
        }
        return $result;
    }

    public static function affected_rows(): int
    {
        return self::$_connection->affected_rows;
    }

    public static function log_error(int $backtrace_level = 0): string
    {
        // Log the SQL error to the PHP error log and return a generic error
        // for display to the user
        $backtrace = debug_backtrace();
        $caller = $backtrace[$backtrace_level]['file'] . ":" . $backtrace[$backtrace_level]['line'];
        $error = str_replace("\n", "\\n", mysqli_error(DPDatabase::get_connection()));
        error_log("DPDatabase.inc - log_error from $caller: $error");
        return _("An error occurred during a database query and has been logged.");
    }

    private static function get_db_defaults(): void
    {
        $sql = sprintf(
            "
            SELECT *
            FROM information_schema.schemata
            WHERE schema_name='%s';
            ",
            self::$_db_name
        );
        $result = self::query($sql);

        $row = $result->fetch_assoc();
        self::$_default_db_charset = $row['DEFAULT_CHARACTER_SET_NAME'];
        self::$_default_db_collation = $row['DEFAULT_COLLATION_NAME'];
        $result->free();
    }

    public static function get_default_db_charset(): string
    {
        if (!self::$_default_db_charset) {
            self::get_db_defaults();
        }
        return self::$_default_db_charset;
    }

    public static function get_default_db_collation(): string
    {
        if (!self::$_default_db_collation) {
            self::get_db_defaults();
        }
        return self::$_default_db_collation;
    }

    public static function is_table_utf8(string $table_name): bool
    {
        $sql = sprintf(
            "
            SELECT TABLE_COLLATION
            FROM information_schema.tables
            WHERE table_schema='%s' AND table_name='%s'
            ",
            self::$_db_name,
            self::escape($table_name)
        );
        $result = self::query($sql);
        $row = $result->fetch_assoc();
        $table_encoding = $row['TABLE_COLLATION'] ?? "";
        $result->free();

        return stripos($table_encoding, 'utf8mb4') === 0;
    }

    public static function does_table_exist(string $table_name): bool
    {
        $sql = sprintf(
            "
            SELECT TABLE_NAME
            FROM information_schema.tables
            WHERE table_schema='%s' AND table_name='%s'
            ",
            self::$_db_name,
            self::escape($table_name)
        );
        $result = self::query($sql);
        return $result->fetch_assoc() !== null;
    }

    private static function log_trace(string $sql): void
    {
        if (!DPDatabase::$tracing_log) {
            return;
        }

        try {
            $backtrace = debug_backtrace();
            $caller = $backtrace[1]['file'] . ":" . $backtrace[1]['line'];

            $message = sprintf(
                "%s %s %s\n",
                date("c"),
                $caller,
                preg_replace("/\s+/", " ", $sql)
            );
            $fp = fopen(DPDatabase::$tracing_log, 'a');
            fwrite($fp, $message);
            fclose($fp);
        } catch (Exception $e) {
            // never fail on tracing
        }
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
        return [];
    }
    public function __wakeup()
    {
    }
}
