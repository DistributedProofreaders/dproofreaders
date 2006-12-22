<?PHP
include_once($relPath.'pg.inc'); // get_pg_catalog_url_for_etext
include_once($relPath.'maybe_mail.inc');

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

function notify_project_event_subscribers( $project, $event )
{
    global $site_url, $auto_email_addr, $site_signoff;

    $projectid = $project->projectid;

    $url = get_pg_catalog_url_for_etext( $project->postednum );

    $res1 = mysql_query("
        SELECT username
        FROM usersettings
        WHERE setting = 'posted_notice' AND value = '$projectid'
    ") or die(mysql_error());
    $numrows = mysql_num_rows($res1);
    while ( list($username) = mysql_fetch_row($res1) )
    {
        $res2 = mysql_query("
            SELECT user_email
            FROM phpbb_users
            WHERE username = '$username'
        ") or die(mysql_error());
        $email = mysql_result($res2, 0, "user_email");
        maybe_mail(
            $email,
            "{$project->nameofwork} Posted to Project Gutenberg",
            "You had requested to be let known once {$project->nameofwork} was ready to be available for reading."
            ." It has been sent to Project Gutenberg and will soon be available for reading."
            ." Most files will be ready by the time you receive this mail;"
            ." sometimes there may be a delay of a day or so."
            ." You can download the files via PG's online catalog at <$url>."
            ."\n"
            ."$site_signoff",
            "From: $auto_email_addr\r\nReply-To: $auto_email_addr\r\n"
        );
    }

    $res3 = mysql_query("
        DELETE FROM usersettings
        WHERE setting = 'posted_notice' AND value = '$projectid'
    ") or die(mysql_error());

    $res4 = mysql_query("
        UPDATE projects
        SET int_level = '$numrows'
        WHERE projectid = '$projectid'
    ") or die(mysql_error());
}

function create_temporary_project_event_subscription_summary_table()
// Create a temp table named 'project_event_subscriptions_grouped_by_project'
// with two columns: 'projectid' and 'nouste_posted', where the latter gives the
// current number of users that are subscribed to the project's 'posted' event.
// ("nouste" = "number of users subscribed to event")
{
    $res = mysql_query("
        CREATE TEMPORARY TABLE project_event_subscriptions_grouped_by_project
        (
            PRIMARY KEY (projectid)
        )
        SELECT
            value    AS projectid,
            COUNT(*) AS nouste_posted
        FROM usersettings
        WHERE setting='posted_notice'
        GROUP BY value
    ") or die(mysql_error());
}

// vim: sw=4 ts=4 expandtab
?>
