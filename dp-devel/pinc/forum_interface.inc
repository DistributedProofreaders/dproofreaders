<?
// forum_interface.inc

include_once($relPath."languages.inc"); // phpbb_lang()

// This file provides functions to interface with the forum software (phpBB
// in this case). Ideally if the forum software changes due to version
// upgrades or using an entirely different forum software, this is one of the
// few files that would require changes.

function is_username_password_valid($username, $password)
// Check to see if the given username/password pair is valid.
// Returns:
//     TRUE  - is valid (user exists with specified password)
//     FALSE - invalid (username/password pair were not found)
{
    $digested_password = MD5($password);

    $query = sprintf("
        SELECT *
        FROM phpbb_users
        WHERE username='%s' AND user_password='%s'
        ", mysql_real_escape_string($username), mysql_real_escape_string($digested_password));
    $res = mysql_query($query) or die(mysql_error());

    $valid = (mysql_num_rows($res) == 1);

    mysql_free_result($res);

    return $valid;
}

function create_forum_user($username, $password, $email, $password_is_digested=FALSE)
// Creates a forum user with the specified username, password, and email.
// The DP registration process already digests the password, hence the
// need for $password_is_digested.
{
    $digested_password = ($password_is_digested ? $password : md5($password) );

    // Code primarily lifted from bb_register.php
    $sql = "SELECT max(user_id) FROM phpbb_users";
    $res = mysql_query($sql) or die(mysql_error());
    list($max_user_id) = mysql_fetch_array($res);
    mysql_free_result($res);

    $max_user_id += 1; 
    $currtime = time();
    $sql = sprintf("INSERT INTO phpbb_users SET user_id=$max_user_id, username='%s', user_regdate=$currtime, user_timezone=-8.00, user_lang='%s', user_email='%s', user_password='%s', user_viewemail=0", mysql_real_escape_string($username), (phpbb_lang()?phpbb_lang():"english"), mysql_real_escape_string($email), mysql_real_escape_string($digested_password));
    mysql_query($sql) or die(mysql_error());
}

function get_forum_user_details($username)
// Given a username, return details about the user.
// Returns an associative array with the following keys:
//     id        - forum user id
//     username  - forum username
//     lastvisit - timestamp the user last visisted
//     from      - location
//     occ       - occupation
//     interests - interests
//     viewemail - if their email address is viewable
//     email     - email
//     avatar    - filename of their avatar, if defined
//     icq       - ICQ address
//     website   - website URL
//     aim       - AIM address
//     yim       - Yahoo address
//     msnm      - MS Messenger address
//     jabber    - Jabber address
// If the user isn't found, the function returns NULL.
{
    $interested_columns = array("id","username","lastvisit","from","occ","interests","viewemail","email","avatar","icq","website","aim","yim","msnm","jabber");

    $return_data = array();

    $query = sprintf("
        SELECT *
        FROM phpbb_users
        WHERE username='%s'
        ", mysql_real_escape_string($username));
    $res = mysql_query($query) or die(mysql_error());

    if (mysql_num_rows($res) != 1)
        return NULL;

    $row = mysql_fetch_assoc($res);

    foreach($interested_columns as $column)
    {
        if(isset($row["user_$column"]))
        {
            $return_data[$column]=$row["user_$column"];
        }
        elseif(isset($row[$column]))
        {
            $return_data[$column]=$row[$column];
        }
        else
        {
            $return_data[$column]=NULL;
        }
    }

    return $return_data;
}

function get_forum_user_id($username)
// Given a forum username, return the forum user ID.
{
    // Use a local in-memory cache so we don't pummel the database
    // for pages that end up calling this for the same small set 
    // of users over and over. Cache is local to this function.
    static $uidCache;
    // if it's in the cache, return it
    if(isset($uidCache[$username])) return $uidCache[$username];

    $query = sprintf("
        SELECT user_id
        FROM phpbb_users
        WHERE username = '%s' 
        ", mysql_real_escape_string($username));
    $res = mysql_query($query) or die(mysql_error());

    if (mysql_num_rows($res) == 0)
        return NULL;

    list($id) = mysql_fetch_row($res);
    mysql_free_result($res);

    // update the cache
    $uidCache[$username] = $id;

    return $id;
}

function get_forum_email_address($username)
// Given a forum username, return the email address.
{
    $user_details = get_forum_user_details($username);

    return $user_details["email"];
}

function get_url_to_compose_message_to_user($username)
// Given a forum username, return a URL that can be used to
// access a form to send the user a message.
{
    global $forums_url;

    $userid = get_forum_user_id($username);

    return "$forums_url/privmsg.php?mode=post&u=$userid";
}

function get_url_to_view_forum($forum_id)
// Given a forum id, return a URL that can be used to view the forum.
{
    global $forums_url;

    return "$forums_url/viewforum.php?f=$forum_id";
}

function get_url_to_view_topic($topic_id)
// Given a topic id, return a URL that can be used to view the topic.
{
    global $forums_url;

    return "$forums_url/viewtopic.php?t=$topic_id";
}

function get_url_to_view_post($post_id)
// Given a post id, return a URL that can be used to view the post.
{
    global $forums_url;

    return "$forums_url/viewtopic.php?p=$post_id#$post_id";
}

function get_url_for_user_avatar($username)
// Given a forum username, return a URL that can be used to load the user's
// avatar. If no avatar is defined, this function returns NULL.
{
    global $forums_url;

    $user_details = get_forum_user_details($username);

    if(empty($user_details["avatar"]))
        return NULL;

    return "$forums_url/images/avatars/" . $user_details["avatar"];
}

function get_url_to_edit_profile()
// Return a URL that can be used to edit the current user's profile.
{
    global $forums_url;

    return "$forums_url/profile.php?mode=editprofile";
}

function get_url_for_inbox()
// Return the URL for accessing the current user's inbox.
{
    global $forums_url;
    
    return "$forums_url/privmsg.php?folder=inbox";
}

function get_number_of_unread_messages($username)
// Given a forum username, return the number of unread messages.
{
    $forum_userid = get_forum_user_id($username);

    if($forum_userid == NULL)
        return NULL;

    $query = "
        SELECT COUNT(*)
        FROM phpbb_privmsgs
        WHERE privmsgs_to_userid = $forum_userid and (privmsgs_type = 1 or privmsgs_type = 5)
        ";
    $res = mysql_query($query) or die(mysql_error());

    list($num_messages) = mysql_fetch_row($res);
    mysql_free_result($res);

    return $num_messages;
}

function does_topic_exist($topic_id)
// Confirm a specific topic ID exists.
// Returns:
//     FALSE - doesn't exist
//     TRUE  - does exist
{
    $query = "
        SELECT 1
        FROM phpbb_topics
        WHERE topic_id = $topic_id;
        ";
    $res = mysql_query($query) or die(mysql_error());

    $exists = (mysql_num_rows($res) > 0);

    mysql_free_result($res);

    return $exists;
}

function get_last_post_time_in_topic($topic_id)
// Given a forum topic, return the time of the last post
// in UNIX time format (seconds since UNIX epoch).
// If no topic is found, function returns NULL.
{
    // Validate that $topic_id is an integer and if not, return NULL
    if(!is_numeric($topic_id))
        return NULL;

    $query = "
        SELECT MAX(post_time)
        FROM phpbb_posts
        WHERE topic_id = $topic_id
        ";
    $res = mysql_query($query) or die(mysql_error());

    if (mysql_num_rows($res) == 0)
        return NULL;

    list($time) = mysql_fetch_row($res);
    mysql_free_result($res);

    return $time;
}

function get_topic_details($topic_id)
// Returns the following details about a topic as an associative array.
//     topic_id         - the ID of the topic (for completeness)
//     title            - the title of the topic
//     num_replies      - the number of replies in the topic
//     forum_name       - name of the forum the topic is in
//     forum_id         - the id of the forum the topic is in
//     creator_username - the username of the topic creator
{
    $query = "
        SELECT phpbb_topics.topic_id AS topic_id,
            phpbb_topics.topic_title AS title,
            phpbb_topics.topic_replies AS num_replies,
            phpbb_forums.forum_name AS forum_name,
            phpbb_forums.forum_id AS forum_id,
            phpbb_users.username AS creator_username
        FROM phpbb_topics
        INNER JOIN phpbb_forums ON phpbb_topics.forum_id = phpbb_forums.forum_id
        INNER JOIN phpbb_users ON phpbb_topics.topic_poster = phpbb_users.user_id
        WHERE phpbb_topics.topic_id = $topic_id
        ";
    $res = mysql_query($query) or die(mysql_error());

    $row = mysql_fetch_assoc($res);
    mysql_free_result($res);

    return $row;
}

// ---------------------------------------------------------------------------

// functions from the original pinc/topic.inc

// Parameters for topic_create() and topic_add_post()
// are in the order Where, What, Who:
//     Where: $forum_id or $topic_id
//     What:  $post_subject, $post_text
//     Who:   $poster_name, $poster_is_real

// -----------------------------------------------------------------------------

function topic_create(
    $forum_id,
    $post_subject,
    $post_text,
    $poster_name,
    $poster_is_real,
    $make_poster_watch_topic
)
// A proxy for phpbb2_create_topic() in phpbb2.inc
{
    $args = func_get_args();
    $topic_id = call_phpbb2_function( 'create_topic', $args );

    if ( !preg_match('/^\d+$/', $topic_id) )
    {
        $topic_id = NULL;
    }

    return $topic_id;
}

// -----------------------------------------------------------------------------

function topic_add_post(
    $topic_id, 
    $post_subject,
    $post_text,
    $poster_name,
    $poster_is_real
)
// A proxy for phpbb2_add_post() in phpbb2.inc
{
    $args = func_get_args();
    call_phpbb2_function( 'add_post', $args );
}

// -----------------------------------------------------------------------------

function call_phpbb2_function( $func_name, $args )
// It's risky to combine DP and phpBB code in the same PHP context,
// so we run the phpBB code in a separate process.
{
    // echo "call_phpbb2_function( $func_name, ", print_r($args,TRUE), ")<br>\n";

    global $forums_dir;
    if (!is_dir($forums_dir))
    {
        echo "Warning: unable to call '$func_name' because \$forums_dir ($forums_dir) does not exist.\n";
        return;
    }

    $escaped_args_str = implode(' ',array_map('my_escapeshellarg',$args));

    // (cd into the pinc directory, so the script doesn't
    // have to guess where it's being invoked from.)
    global $code_dir, $php_cli_executable;
    $command = "cd $code_dir/pinc && $php_cli_executable -f phpbb2.inc $func_name $escaped_args_str";
    // echo "command: $command<br>\n";

    $output = array();
    $last_line = exec($command, $output, $exit_status);
    // echo "exec'd command's last line = '$last_line'<br>\n";

    if ( $exit_status != 0 )
    {
        echo "<pre>\n";
        echo "$func_name command exited with status=$exit_status\n";
        if ( count($output) > 0 )
        {
            echo "Here's its output:\n";
            foreach ( $output as $line )
            {
                echo "$line\n";
            }
        }
        exit;
    }

    return $last_line;
}

function my_escapeshellarg( $arg )
// Work-around for escapeshellarg's anomalous treatment of empty args.
{
    $s = escapeshellarg($arg);
    if ( empty($s) ) $s = "''";
    return $s;
}

// -----------------------------------------------------------------------------

function topic_change_forum( $topic_id, $to_forum_id)
{
    assert( $topic_id != '' );

    $result = mysql_query("
        SELECT forum_id
        FROM phpbb_topics
        WHERE topic_id=$topic_id
    ");
    $from_forum_id = mysql_result($result, 0, "forum_id");  

    if ($from_forum_id != $to_forum_id)
    {
        // move posts to new forum
        mysql_query("
            UPDATE phpbb_posts
            SET forum_id = $to_forum_id
            WHERE topic_id = $topic_id
        ");

        // move topic to new forum
        mysql_query("
            UPDATE phpbb_topics
            SET forum_id = $to_forum_id
            WHERE topic_id = $topic_id
        ");

        forum_resynch( $from_forum_id );
        forum_resynch( $to_forum_id );
    }
}

// -----------------------------------------------------------------------------

function forum_resynch( $forum_id )
{
    // Count the number of topics in the forum
    $res1 = mysql_query("
        SELECT COUNT(*)
        FROM phpbb_topics
        WHERE forum_id = $forum_id
    ");
    list($forum_topics) = mysql_fetch_row($res1);

    // Count the number of posts in the forum,
    // and get the id of most recent post in the forum,
    // which might have changed when we moved the topic(s).
    $res2 = mysql_query("
        SELECT COUNT(*), MAX(post_id)
        FROM phpbb_posts
        WHERE forum_id = $forum_id
    ");
    list($forum_posts, $last_post) = mysql_fetch_row($res2);
    if ( $forum_posts == 0 )
    {
        // $last_post is NULL
        $last_post = 0;
    }

    // adjust total posts and topics and last post for forum

    $update_count = mysql_query("
        UPDATE phpbb_forums
        SET
            forum_posts  = $forum_posts,
            forum_topics = $forum_topics,
            forum_last_post_id = $last_post
        WHERE forum_id = $forum_id
    ");
}

// vim: sw=4 ts=4 expandtab
?>
