<?php
include_once($relPath.'site_vars.php');

ini_set( 'session.name',            "{$site_abbreviation}_Session" );
ini_set( 'session.use_cookies',     1 );
ini_set( 'session.cookie_domain',   '' ); // A sensible value will be used.
ini_set( 'session.cookie_lifetime', 86400 * 31); // 31 days ~= 1 month

// -----------------------------------------------------------------------------

function dpsession_begin_( $userID )
{
    global $pguser;

    session_set_handlers_and_start();

    $pguser = $userID;
    $_SESSION['pguser'] = $pguser;
    dpsession_set_preferences_from_db();
}

function dpsession_resume_()
{
    global $pguser, $userP, $use_secure_cookies;

    session_set_handlers_and_start();

    if ( isset($_SESSION['userP']) && !empty($_SESSION['userP']) )
    {
        // Refresh the cookie
        // (session_start() used to do this for us,
        // but they changed that in PHP 4.3.9)
        setcookie(
            ini_get('session.name'),
             session_id(),
            time() + ini_get('session.cookie_lifetime'),
            '/',
            '',
            $use_secure_cookies
        );

        // set global variables $pguser and $userP.
        if (isset($_SESSION['pguser'])) 
            $pguser = $_SESSION['pguser'];
        $userP = $_SESSION['userP'];
        return TRUE;
    }
    else
    {
        session_unset();
        session_destroy();
        return FALSE;
    }
}

function dpsession_set_preferences_from_db()
{
    global $pguser;

    $users_res = mysqli_query(DPDatabase::get_connection(), "
        SELECT
            u_id, u_profile, email_updates,
            u_neigh, u_align,
            i_prefs, i_theme, i_pmdefault, id,
            team_1, team_2, team_3,
            u_intlang, u_privacy, last_login
        FROM users
        WHERE username='$pguser'
        ");
    $users_data = mysqli_fetch_assoc($users_res);

    $user_profiles_res = mysqli_query(DPDatabase::get_connection(), "
        SELECT
            profilename,
            i_res, i_type, i_layout, i_toolbar, i_statusbar, i_newwin,
            v_fnts, v_fntf, v_fntf_other, v_zoom, v_tframe, v_tlines, v_tchars, v_tscroll, v_twrap,
            h_fnts, h_fntf, h_fntf_other, h_zoom, h_tframe, h_tlines, h_tchars, h_tscroll, h_twrap
        FROM user_profiles
        WHERE u_ref='{$users_data['u_id']}' AND id='{$users_data['u_profile']}'
        ");
    $user_profiles_data = mysqli_fetch_assoc($user_profiles_res);

    $prefsChanged_data = array("prefschanged" => 0);

    $_SESSION['userP'] = array_merge($users_data, $user_profiles_data, $prefsChanged_data);
}

function dpsession_set_preferences_temp( $user_prefs )
{
    $_SESSION['userP'] = $user_prefs;
}

function dpsession_end()
{
    session_unset();
    session_destroy();
}

// -----------------------------------------------------------------------------

// The 'debouncer' variable

function dpsession_page_set($info)
{
    $_SESSION['debouncer'] = $info;
}

function dpsession_page_end()
{
    $_SESSION['debouncer'] = NULL;
}

function dpsession_page_is_set()
{
    return isset($_SESSION['debouncer']);
}

function dpsession_page_get()
{
    return $_SESSION['debouncer'];
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// Helper functions that aren't part of the dpsession API.

function session_set_handlers_and_start()
{
    if ( session_id() )
    {
        // session_start() has already been called
    }
    else
    {
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
    $result = do_session_query("SELECT value FROM sessions WHERE sid = '$sid' AND expiration > ".time()."");
    $row = mysqli_fetch_assoc($result);
    if($row)
    {
        return $row["value"];
    }
    else
    {
        return "";
    }
}

function mysql_session_write($sid, $value)
{
    $expiration = time() + ini_get("session.cookie_lifetime");
    $result = do_session_query("SELECT COUNT(*) FROM sessions WHERE sid = '$sid'");
    $row = mysqli_fetch_row($result);
    $SessionExists = $row[0];
    // The table has a uniqueness constraint on 'sid',
    // so $SessionExists must be 0 or 1.

    // $value is a string (constructed by PHP's built-in session
    // code) that might contain apostrophes (or backslashes?),
    // which will screw up the SQL queries unless we escape them.
    $value = mysqli_real_escape_string(DPDatabase::get_connection(), $value);

    if ( $SessionExists == 0 )
    {
        $query = "
            INSERT INTO sessions
                (sid, expiration, value)
            VALUES
                ('$sid', '$expiration', '$value')
            ";
    }
    else
    {
        $query = "
            UPDATE sessions
            SET
                expiration = '$expiration',
                value = '$value'
            WHERE sid = '$sid' AND expiration > ".time()
            ;
    }
    $result = do_session_query($query);
    return $result;
}

function mysql_session_destroy($sid)
{
    $result = do_session_query("DELETE FROM sessions WHERE sid = '$sid'");
    return $result;
}

function mysql_session_garbage_collect($lifetime)
{
    $result = do_session_query("DELETE FROM sessions WHERE expiration < ". (time()-$lifetime) ."");
    return $result;
}

function do_session_query( $query )
{
    // write_to_session_log( $query );
    $result = mysqli_query(DPDatabase::get_connection(), $query) or die(mysqli_error(DPDatabase::get_connection()));
    return $result;
}

function write_to_session_log( $msg )
{
    $time = strftime('%Y-%m-%d %H:%M:%S');
    $f = fopen('/tmp/session_query_log','a');
    fwrite( $f, "------\n$time\n$msg\n" );
    fclose($f);
}
