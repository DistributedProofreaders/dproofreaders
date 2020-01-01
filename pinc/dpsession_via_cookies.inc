<?php
include_once($relPath.'site_vars.php');

$server_name = ( isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : "" );
define( 'COOKIE_DOMAIN', substr_count($server_name, ".") > 0 ?  $server_name : "" );

// -----------------------------------------------------------------------------

// 'user' cookie and 'prefs' cookie...

function dpsession_begin_( $userID )
{
    global $pguser;

    $pguser = $userID;

    user_cookie_send();

    dpsession_set_preferences_from_db();
}

function dpsession_resume_()
{
    global $pguser, $userP;

    if ( !user_cookie_was_received_from_client() )
    {
        return FALSE;
    }

    $parts = explode("|",user_cookie_getvalue());

    $pguser = $parts[0];

    // ---------------

    // Refresh the user's cookies
    user_cookie_send();

    $user_prefs_string = prefs_cookie_getvalue();
    prefs_cookie_send( $user_prefs_string );

    // ---------------

    // (I don't understand why we use the error-suppressing '@' here.)

    @$user_pref_values = explode("|", $user_prefs_string);

    $userP = array();
    $i = 0;
    // from users
    @$userP['u_id'         ] = $user_pref_values[$i++];
    @$userP['u_profile'    ] = $user_pref_values[$i++];
    @$userP['email_updates'] = $user_pref_values[$i++];
    @$userP['u_neigh'      ] = $user_pref_values[$i++];
    @$userP['u_align'      ] = $user_pref_values[$i++];
    @$userP['i_prefs'      ] = $user_pref_values[$i++];
    @$userP['i_theme'      ] = $user_pref_values[$i++];
    @$userP['i_pmdefault'  ] = $user_pref_values[$i++];
    @$userP['user_id'      ] = $user_pref_values[$i++];
    @$userP['team_1'       ] = $user_pref_values[$i++];
    @$userP['team_2'       ] = $user_pref_values[$i++];
    @$userP['team_3'       ] = $user_pref_values[$i++];
    @$userP['u_intlang'    ] = $user_pref_values[$i++];
    @$userP['u_privacy'    ] = $user_pref_values[$i++];
    @$userP['last_login'   ] = $user_pref_values[$i++];

    // from user_profiles
    @$userP['profilename'  ] = $user_pref_values[$i++];
    @$userP['i_res'        ] = $user_pref_values[$i++];
    @$userP['i_type'       ] = $user_pref_values[$i++];
    @$userP['i_layout'     ] = $user_pref_values[$i++];
    @$userP['i_toolbar'    ] = $user_pref_values[$i++];
    @$userP['i_statusbar'  ] = $user_pref_values[$i++];
    @$userP['i_newwin'     ] = $user_pref_values[$i++];
    @$userP['v_fnts'       ] = $user_pref_values[$i++];
    @$userP['v_fntf'       ] = $user_pref_values[$i++];
    @$userP['v_fntf_other' ] = $user_pref_values[$i++];
    @$userP['v_zoom'       ] = $user_pref_values[$i++];
    @$userP['v_tframe'     ] = $user_pref_values[$i++];
    @$userP['v_tlines'     ] = $user_pref_values[$i++];
    @$userP['v_tchars'     ] = $user_pref_values[$i++];
    @$userP['v_tscroll'    ] = $user_pref_values[$i++];
    @$userP['v_twrap'      ] = $user_pref_values[$i++];
    @$userP['h_fnts'       ] = $user_pref_values[$i++];
    @$userP['h_fntf'       ] = $user_pref_values[$i++];
    @$userP['h_fntf_other' ] = $user_pref_values[$i++];
    @$userP['h_zoom'       ] = $user_pref_values[$i++];
    @$userP['h_tframe'     ] = $user_pref_values[$i++];
    @$userP['h_tlines'     ] = $user_pref_values[$i++];
    @$userP['h_tchars'     ] = $user_pref_values[$i++];
    @$userP['h_tscroll'    ] = $user_pref_values[$i++];
    @$userP['h_twrap'      ] = $user_pref_values[$i++];

    @$userP['prefschanged' ] = $user_pref_values[$i++];

    return TRUE;
}

function dpsession_set_preferences_from_db()
{
    global $pguser;

    $users_q = "
        SELECT
            u_id, u_profile, email_updates,
            u_neigh, u_align,
            i_prefs, i_theme, i_pmdefault, id,
            team_1, team_2, team_3,
            u_intlang, u_privacy, last_login
        FROM users
        WHERE username='$pguser'
        ";
    $users_res = mysqli_query(DPDatabase::get_connection(), $users_q);
    $users_data = mysqli_fetch_assoc($users_res);

    $user_profiles_q = "
        SELECT
            profilename,
            i_res, i_type, i_layout, i_toolbar, i_statusbar, i_newwin,
            v_fnts, v_fntf, v_zoom, v_tframe, v_tlines, v_tchars, v_tscroll, v_twrap,
            h_fnts, h_fntf, h_zoom, h_tframe, h_tlines, h_tchars, h_tscroll, h_twrap
        FROM user_profiles
        WHERE u_ref='{$users_data['u_id']}' AND id='{$users_data['u_profile']}'
        ";
    $user_profiles_res = mysqli_query(DPDatabase::get_connection(), $user_profiles_q);
    $user_profiles_data = mysqli_fetch_assoc($user_profiles_res);

    $user_prefs_string = implode("|",$users_data)."|".implode("|",$user_profiles_data)."|0";
    prefs_cookie_send( $user_prefs_string );
}

function dpsession_set_preferences_temp( $user_prefs )
{
    $user_prefs_string = implode( "|", $user_prefs );
    prefs_cookie_send( $user_prefs_string );
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

function prefs_cookie_send( $cookie_value )
{
    global $pguser;

    $cookieName = md5($pguser."_prefs");
    local_send_cookie( $cookieName, $cookie_value );
}

function prefs_cookie_getvalue()
{
    global $pguser;

    $cookieName = md5($pguser."_prefs");
    return $_COOKIE[$cookieName];
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
    setcookie( $cookie_name, $cookie_value, time() + $t_offset, "/", COOKIE_DOMAIN, $use_secure_cookies );
}

function local_encrypt( $str )
{
    global $cookie_encryption_key;

    return md5( $str . $cookie_encryption_key );
}
