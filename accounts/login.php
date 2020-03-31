<?php
/*
This script is invoked when the user clicks the 'Login' button. It extracts
the username and password from the request, does the authentication to phpBB
(which sets up the phpBB session information) and sets up the correct
session information for DP.

By design, this file does no page output to the user, but instead redirects
them to their final destination upon successful login, or to login_failure.php
upon login failure. login_failure.php handles all the information output to
the user.

The authentication and user-presentation functions were split out to further
minimize possible code collisions between the DP code and the phpBB code.
Accordingly, this file should pull in only the smallest possible set of DP
includes needed to provide authentication.
*/

$relPath="./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'forum_interface.inc');
// We must *not* include User.inc here, as phpBB also defines a User class
// and including pinc/User.inc here would cause a conflict.

$destination = ( isset($_REQUEST['destination']) ? $_REQUEST['destination'] : '' );

$userNM = @$_POST['userNM'];
$userPW = @$_POST['userPW'];

if ($userNM == '')
{
    login_failure('no_username', $destination);
}


if ($userPW == '')
{
    login_failure('no_password', $destination);
}

// Attempt to log into forum
list($success, $reason) = login_forum_user($userNM, $userPW);
if(!$success)
{
    if($reason == 'unknown')
    {
        login_failure('unknown_failure', $destination);
    }
    elseif($reason == 'too_many_attempts')
    {
        login_failure('too_many_attempts', $destination);
    }
    else
    {
        login_failure('auth_failure', $destination);
    }
}

// Look for user in 'users' table.
$q = sprintf("
    SELECT * FROM users WHERE username='%s'
    ", mysqli_real_escape_string(DPDatabase::get_connection(), $userNM)
);
$u_res = mysqli_query(DPDatabase::get_connection(), $q) or die(mysqli_error(DPDatabase::get_connection()));
$u_row = mysqli_fetch_assoc($u_res);
if (!$u_row)
{
    login_failure('reg_mismatch', $destination);
}

// -------------------------------------
// The login is successful!

// Note that phpbb_users.username and users.username are non-BINARY varchar,
// so the SQL comparison "username='$userNM'" is evaluated case-insensitively.
// This means that the user may have just logged in by typing a username
// that's case-different from the username that they registered with.
// That is, $userNM may be case-different from $u_row['username'].
// (E.g., I registered as 'jmdyck', but I can login as 'JMDyck'.)
//
// However, some places in the PHP code do case-sensitive comparisons of
// usernames (e.g., the code that determines whether the user is entitled
// to pull a particular page out of DONE or IN-PROGRESS). For those places,
// it's important that we always use the same form of the username. Following
// the principle of least surprise, we use the form used at registration time,
// i.e. the form stored in the users table.
//
$userNM = $u_row['username'];

// Start the DP session.
dpsession_begin( $userNM );

// It's possible that a user might be redirected back to this page after
// a successful login in this scenario:
//   1. User logs in with an incorrect password and ends up on login_failure.php.
//   2. While on login_failure.php user logs in with a correct password and is
//      redirected back to login_failure.php as that was the page they were on
//      prior to login.
//   3. User is presented with an error message since $_POST has no login
//      information.
// To avoid this case we ignore $destination if it points to login_failure.php.
// send them to the correct page. We also ignore $destination if it is
// default.php and intentionally redirect them to the Activity Hub.
if (!empty($destination) && !endswith($destination, "login_failure.php") &&
    !endswith($destination, "default.php"))
{
    // They were heading to $destination (via a bookmark, say)
    // when we sidetracked them into the login pages.
    // Make sure they get to where they were going.
    $url = $destination;
}
else
{
    $url = "$code_url/activity_hub.php";
}
metarefresh(0, $url, "", "");

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// When a login failure occurs, redirect the user to login_failure.php to see
// the error message and some tips to correct it.
//
// $error_code is a short URL-friendly key into an array defined in
// login_failure.php that contains a translated, user-friendly error message.
function login_failure($error_code, $destination)
{
    global $code_url;

    metarefresh(0, "$code_url/accounts/login_failure.php?error_code=$error_code&destination=" . urlencode($destination), "", "");
}

// vim: sw=4 ts=4 expandtab
