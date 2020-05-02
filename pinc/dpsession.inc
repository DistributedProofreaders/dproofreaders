<?php

// An API of session-handling functions:
//
// dpsession_begin( $userID )
//     Start a session. (The user has logged in.)
//     Record the session variable 'pguser' (from $userID)
//
// dpsession_resume()
//     If the request claimed to belong to a session,
//     and that session is valid/current/non-expired,
//     then refresh the session,
//     reinstate the global variable ($pguser) of that session,
//     and return TRUE.
//     Otherwise, return FALSE.
//
// dpsession_end()
//     End a session. (The user has logged out.)
//
// Page-info...
// dpsession_page_set($info)
// dpsession_page_end()
// dpsession_page_is_set()
// dpsession_page_get()

$pguser = NULL;

include_once($relPath.'site_vars.php');

if ( $use_php_sessions )
{
    include_once( $relPath.'dpsession_via_php_sessions.inc' );
}
else
{
    include_once( $relPath.'dpsession_via_cookies.inc' );
}

// -----------------------------------------------------------------------------

function dpsession_begin( $userID )
{
    dpsession_begin_( $userID );
    _update_user_activity_time( TRUE );
}

function dpsession_resume()
{
    // Since this function is sometimes called several times per page,
    // and since the side-effects or result will not change in one
    // page, we cache the result of this function.
    static $user_is_logged_in = NULL;
    if ( $user_is_logged_in !== NULL )
    {
        return $user_is_logged_in;
    }
    
    $user_is_logged_in = dpsession_resume_();
    if ( $user_is_logged_in )
    {
        _update_user_activity_time( FALSE );
    }

    // If the sender isn't logged in, and this is the first time in this
    // browsing session that they're hitting the site, we grab the request's
    // http_referer (if any), and stash that in a cookie for later use, in
    // case they register in this browsing session.
    if(!$user_is_logged_in && !isset($_COOKIE["http_referer"]))
    {
        if(isset($_SERVER["HTTP_REFERER"]))
            setcookie('http_referer', $_SERVER["HTTP_REFERER"]);
        else
            setcookie('http_referer', '');
    }

    return $user_is_logged_in;
}

function _update_user_activity_time( $update_login_time_too )
{
    global $pguser;

    $settings = "t_last_activity=UNIX_TIMESTAMP()";

    // To avoid thrashing the users table by updating t_last_activity for
    // every single page load, only update t_last_activity if it hasn't
    // been updated in the last 5 minutes. This should result in reduced
    // DB load while still giving the resolution on a user's activity
    // that we want.
    $additional_where = "AND t_last_activity <= UNIX_TIMESTAMP() - 60 * 5";

    if ( $update_login_time_too )
    {
        $settings = "$settings, last_login=UNIX_TIMESTAMP()";

        // If we're updating the login time, always run the query
        $additional_where = "";
    }

    mysqli_query(DPDatabase::get_connection(), "
        UPDATE users
        SET $settings
        WHERE username='$pguser' $additional_where
    ") or die(DPDatabase::log_error());
}

// vim: sw=4 ts=4 expandtab
