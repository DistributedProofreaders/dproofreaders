<?php
include_once($relPath.'site_vars.php');

ini_set('session.name', "{$site_abbreviation}_Session");
ini_set('session.use_cookies', 1);
ini_set('session.cookie_domain', ''); // A sensible value will be used.
ini_set('session.cookie_lifetime', 86400 * 31); // 31 days ~= 1 month
ini_set('session.cookie_secure', $use_secure_cookies);
ini_set('session.cookie_samesite', 'Lax');

// -----------------------------------------------------------------------------

function dpsession_begin_($userID)
{
    global $pguser;

    session_set_handlers_and_start();

    $pguser = $userID;
    $_SESSION['pguser'] = $pguser;
}

function dpsession_resume_()
{
    global $pguser;

    session_set_handlers_and_start();

    if (isset($_SESSION['pguser']) && !empty($_SESSION['pguser'])) {
        // Refresh the cookie
        // (session_start() used to do this for us,
        // but they changed that in PHP 4.3.9)
        dp_setcookie(
            ini_get('session.name'),
            session_id(),
            time() + ini_get('session.cookie_lifetime')
        );

        // set global variable $pguser
        if (isset($_SESSION['pguser'])) {
            $pguser = $_SESSION['pguser'];
        }
        return true;
    } else {
        session_unset();
        session_destroy();
        return false;
    }
}

function dpsession_end_()
{
    session_unset();
    session_destroy();
}

// -----------------------------------------------------------------------------

// The 'debouncer' variable

function dpsession_page_set_($info)
{
    $_SESSION['debouncer'] = $info;
}

function dpsession_page_end_()
{
    $_SESSION['debouncer'] = null;
}

function dpsession_page_is_set_()
{
    return isset($_SESSION['debouncer']);
}

function dpsession_page_get_()
{
    return $_SESSION['debouncer'];
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// Helper functions that aren't part of the dpsession API.

function session_set_handlers_and_start()
{
    if (session_id()) {
        // session_start() has already been called
    } else {
        session_set_save_handler(
            "mysql_session_open",
            "mysql_session_close",
            "mysql_session_select",
            "mysql_session_write",
            "mysql_session_destroy",
            "mysql_session_garbage_collect");

        session_start();
    }
}

function mysql_session_open($session_path, $session_name)
{
    // We've already opened the database so let's not open it again
    return true;
}

function mysql_session_close()
{
    // We use a persistent connection so we don't need to worry about closing it
    return true;
}

function mysql_session_select($sid)
{
    $sql = sprintf(
        "SELECT value FROM sessions WHERE sid = '%s' AND expiration > %d",
        DPDatabase::escape($sid),
        time()
    );
    $result = do_session_query($sql);
    $row = mysqli_fetch_assoc($result);
    if ($row) {
        return $row["value"];
    } else {
        return "";
    }
}

function mysql_session_write($sid, $value)
{
    $expiration = time() + ini_get("session.cookie_lifetime");

    $query = sprintf("
        INSERT INTO sessions
            (sid, expiration, value)
        VALUES
            ('%s', %d, '%s')
        ON DUPLICATE KEY UPDATE
            expiration = %d,
            value = '%s'",
        substr(DPDatabase::escape($sid), 0, 32), // truncate to column size
        $expiration,
        DPDatabase::escape($value),
        $expiration,
        DPDatabase::escape($value));
    $result = do_session_query($query);
    return $result;
}

function mysql_session_destroy($sid)
{
    $result = do_session_query(sprintf(
        "DELETE FROM sessions WHERE sid = '%s'",
        DPDatabase::escape($sid)
    ));
    return $result;
}

function mysql_session_garbage_collect($lifetime)
{
    $result = do_session_query(sprintf(
        "DELETE FROM sessions WHERE expiration < %d",
        time() - $lifetime)
    );
    return $result;
}

function do_session_query($query)
{
    // write_to_session_log( $query );
    $result = DPDatabase::query($query);
    return $result;
}

function write_to_session_log($msg)
{
    $time = date('Y-m-d H:i:s');
    $f = fopen('/tmp/session_query_log', 'a');
    fwrite($f, "------\n$time\n$msg\n");
    fclose($f);
}
