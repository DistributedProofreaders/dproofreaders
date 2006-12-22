<?PHP

function subscribe_user_to_project_event( $username, $projectid, $event )
{
    assert( $event == 'posted' );
    $res = mysql_query("
        INSERT INTO usersettings
        SET
            username = '$username',
            setting  = 'posted_notice',
            value    = '$projectid'
    ") or die(mysql_error());
}

function unsubscribe_user_from_project_event( $username, $projectid, $event )
{
    assert( $event == 'posted' );
    $res = mysql_query("
        DELETE FROM usersettings
        WHERE username  = '$username'
            AND setting = 'posted_notice'
            AND value   = '$projectid'
    ") or die(mysql_error());
}

function user_is_subscribed_to_project_event( $username, $projectid, $event )
{
    assert( $event == 'posted' );
    $res = mysql_query("
        SELECT *
        FROM usersettings
        WHERE username = '$username' AND setting = 'posted_notice' AND value = '$projectid'
    ") or die(mysql_error());
    return ( mysql_num_rows($res) > 0 );
}

// vim: sw=4 ts=4 expandtab
?>
