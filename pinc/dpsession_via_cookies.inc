<?php
include_once($relPath.'site_vars.php');

$server_name = ( isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : "" );
define( 'COOKIE_DOMAIN', substr_count($server_name, ".") > 0 ?  $server_name : "" );

// -----------------------------------------------------------------------------

// 'user' cookie

function dpsession_begin_( $userID )
{
    global $pguser;

    $pguser = $userID;

    user_cookie_send();
}

function dpsession_resume_()
{
    global $pguser;

    if ( !user_cookie_was_received_from_client() )
    {
        return FALSE;
    }

    $parts = explode("|",user_cookie_getvalue());

    $pguser = $parts[0];

    // ---------------

    // Refresh the user's cookies
    user_cookie_send();

    return TRUE;
}

function dpsession_end()
{
    user_cookie_disable();
}

// -----------------------------------------------------------------------------

// The 'debouncer' cookie...

function dpsession_page_set($info)
{
    local_send_cookie("debouncer", $info);
}

function dpsession_page_end()
{
    local_send_cookie("debouncer", "");
}

function dpsession_page_is_set()
{
    return isset($_COOKIE['debouncer']);
}

function dpsession_page_get()
{
    return $_COOKIE['debouncer'];
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// Helper functions that aren't part of the dpsession API.

function user_cookie_send()
{
    global $pguser;

    $cookie_value = $pguser . '|' . local_encrypt($pguser);
    local_send_cookie( 'pguser', $cookie_value );
}


function user_cookie_was_received_from_client()
{
    if ( ! isset($_COOKIE['pguser']) )
    {
        return FALSE;
    }
    $parts = explode( "|", user_cookie_getvalue() );
    return ( local_encrypt($parts[0]) == $parts[1] );
}

function user_cookie_getvalue()
{
    return $_COOKIE['pguser'];
}

function user_cookie_disable()
{
    local_send_cookie('pguser', "");
}

// -------------

function local_send_cookie( $cookie_name, $cookie_value )
{
    global $use_secure_cookies;
    if ( $cookie_value == "" )
    {
        // Tell the receiver to expire the cookie.
        $t_offset = -86400;
    }
    else
    {
        $t_offset = +86400;
    }
    setcookie( $cookie_name, $cookie_value, [
      "expires" => time() + $t_offset,
      "path" => "/",
      "domain" => COOKIE_DOMAIN,
      "secure" => $use_secure_cookies,
      "samesite" => "Lax"]);
}

function local_encrypt( $str )
{
    global $cookie_encryption_key;

    return md5( $str . $cookie_encryption_key );
}
