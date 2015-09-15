<?php
// forum_interface.inc

// This file provides functions to interface with the forum software.
// Ideally if the forum software changes due to version upgrades or
// using an entirely different forum software, this is one of the
// few files that would require changes.
//
// Currently only phpBB versions 2 and 3 are supported.

assert("PHPBB_VERSION == 2 || PHPBB_VERSION == 3 /* Only phpBB versions 2 and 3 are supported */");

function phpbb_lang($langcode=FALSE)
// Return the language string phpbb uses for $langcode for languages
// installed in the forum interface. Fall back to English if $langcode
// is not installed on the system.
{
    if($langcode===FALSE)
        $langcode=@$GLOBALS['locale'];

    if(PHPBB_VERSION == 2) {
        global $relPath;
        include($relPath."lang_data.inc"); // $phpbb2langs

        if(@$phpbb2langs[$langcode])
            return $phpbb2langs[$langcode];
        else
            return "english";
    }

    if(PHPBB_VERSION == 3) {
        $phpbb_table_prefix = PHPBB_TABLE_PREFIX;
        $query = sprintf("
            SELECT *
            FROM ${phpbb_table_prefix}_lang
            WHERE lang_iso='%s'
            ", mysql_real_escape_string($langcode));
        $res = mysql_query($query) or die(mysql_error());

        if (mysql_num_rows($res) == 1)
            return $langcode;
        else
            return "en";
    }
}

function is_username_password_valid($username, $password)
// NOTE: This function is only used for phpBB2 and can be removed
//       when support for that version is removed.
// Check to see if the given username/password pair is valid.
// Returns:
//     TRUE  - is valid (user exists with specified password)
//     FALSE - invalid (username/password pair were not found)
{
    assert(PHPBB_VERSION == 2);

    $phpbb_table_prefix = PHPBB_TABLE_PREFIX;

    $digested_password = forum_password_hash($password);

    $query = sprintf("
        SELECT *
        FROM {$phpbb_table_prefix}_users
        WHERE username='%s' AND user_password='%s'
        ", mysql_real_escape_string($username), mysql_real_escape_string($digested_password));
    $res = mysql_query($query) or die(mysql_error());

    $valid = (mysql_num_rows($res) == 1);

    mysql_free_result($res);

    return $valid;
}

function forum_password_hash($password)
// Implement or call the hashing function used by the forum to hash
// the password stored in the database. This is required because we
// store the hashed password in the non_activated_users table and use
// it directly when creating the account post-activation.
{
    if(PHPBB_VERSION == 2) {
        return md5($password);
    }

    if(PHPBB_VERSION == 3) {
        // phpBB3 hashing function is involved, so we'll call it directly
        // instead of duplicating it here, but we don't want to pollute
        // the DP codespace so we'll do it via phpbb3.inc.
        $args = func_get_args();
        return call_phpbb_function( 'hash_password', $args );
    }
}

function create_forum_user($username, $password, $email, $password_is_digested=FALSE)
// Creates a forum user with the specified username, password, and email.
// The DP registration process already digests the password, hence the
// need for $password_is_digested.
// Returns:
//     TRUE  - user successfully created
//     !TRUE (error string) - user creation failed
// Note: Use a strict comparison operator (=== or !==) against TRUE
// to evaluate the pass/fail status of this function.
{
    global $forums_dir;
    $phpbb_table_prefix = PHPBB_TABLE_PREFIX;

    if(PHPBB_VERSION == 2) {
        $digested_password = ($password_is_digested ? $password : forum_password_hash($password) );

        // Code primarily lifted from bb_register.php
        $sql = "SELECT max(user_id) FROM {$phpbb_table_prefix}_users";
        $res = mysql_query($sql) or die(mysql_error());
        list($max_user_id) = mysql_fetch_array($res);
        mysql_free_result($res);

        $max_user_id += 1; 
        $currtime = time();
        $sql = sprintf("INSERT INTO {$phpbb_table_prefix}_users SET user_id=$max_user_id, username='%s', user_regdate=$currtime, user_timezone=-8.00, user_lang='%s', user_email='%s', user_password='%s', user_viewemail=0", mysql_real_escape_string($username), phpbb_lang(), mysql_real_escape_string($email), mysql_real_escape_string($digested_password));
        mysql_query($sql) or die(mysql_error());

        return TRUE;
    }

    if(PHPBB_VERSION == 3) {
        // Use the phpBB3 API to create the user, but do so via phpbb3.inc
        // as to not mix the DP and phpBB3 codespace.
        $args = array(
            0 => $username,
            1 => $password,
            2 => $email,
            3 => phpbb_lang()
        );
        $user_id = call_phpbb_function( 'create_user', $args );

        // create_user() should return a numeric $user_id, if it doesn't
        // it likely returned a phpBB3 error string. Lets return that instead.
        if(!is_numeric($user_id))
            return $user_id;

        # if $password_is_digested we have to update the password in the DB directly
        if($password_is_digested) {
            $sql = sprintf("
                UPDATE {$phpbb_table_prefix}_users
                SET user_password='%s'
                WHERE user_id=%d",
                mysql_real_escape_string($password), $user_id);
            mysql_query($sql) or die(mysql_error());
        }

        return TRUE;
    }
}

function login_forum_user($username, $password)
// Log in the user to the forums.
// Returns:
//     TRUE  - user sucessfully logged in
//     FALSE - login failed
{
    global $forums_dir;

    if(!is_dir($forums_dir))
        return FALSE;

    // phpbb code sets variables (e.g. $db) upon include assuming they are
    // global and accesses them in functions via e.g. global $db. However,
    // when we include phpbb files within this dp function, those variables
    // will now be set at function-scope, and so global $db will no longer
    // access the intended variable. So we have to insert global
    // declarations for the phpbb3 variables, so that when the
    // variable-assignment occurs at function-scope, it affects the global
    // variable.

    if(PHPBB_VERSION == 2) {
        if(!is_username_password_valid($username, $password))
            return FALSE;

        $user_id = get_forum_user_id($username);

        // these globals are pulled from:
        // phpBB2/includes/sessions.php:session_end()
        global $db, $board_config;
        global $HTTP_COOKIE_VARS, $HTTP_GET_VARS, $SID;

        define('IN_PHPBB', true);

        $phpbb_root_path = $forums_dir."/";
        include($phpbb_root_path.'extension.inc');
        include($phpbb_root_path.'common.php');
        include($phpbb_root_path.'config.php');

        session_begin($user_id, $user_ip, PAGE_INDEX, false, 1);

        define('IN_PHPBB', false);

        return TRUE;
    }

    if(PHPBB_VERSION == 3) {
        global $phpbb_root_path, $phpEx;
        $phpbb_root_path = $forums_dir."/";
        $phpEx = "php";

        // these globals are pulled from
        // phpBB2/includes/sessions.php:setup()
        global $db, $template, $config, $auth, $phpEx, $phpbb_root_path, $cache;
        global $SID, $_SID, $user;

        define('IN_PHPBB', true);
        define('ROOT_PATH', $forums_dir);

        include($phpbb_root_path . 'common.' . $phpEx);

        $user->session_begin();
        $auth->acl($user->data);

        // Upon initial login, if the password is in the phpBB2 format
        // it will be converted to the new format. It does this by using
        // $_REQUEST['password'], so we need to populate that here.
        $_REQUEST['password'] = $password;

        $results = $auth->login($username, $password, $remember, 1, 0);

        define('IN_PHPBB', false);

        return ($results["status"] == LOGIN_SUCCESS);
    }
}

function logout_forum_user()
// Log out the currently-authenticated user from the forum.
{
    global $forums_dir;
    global $pguser;

    if(!is_dir($forums_dir))
        return;

    // See login_forum_user() for about the need for the global
    // declarations below.

    if(PHPBB_VERSION == 2) {
        $user_id = get_forum_user_id($pguser);

        // these globals are pulled from
        // phpBB2/includes/sessions.php:session_end()
        global $db, $lang, $board_config, $userdata;
        global $HTTP_COOKIE_VARS, $HTTP_GET_VARS, $SID;

        define('IN_PHPBB', true);

        $phpbb_root_path = $forums_dir."/";
        include($phpbb_root_path.'extension.inc');
        include($phpbb_root_path.'common.php');
        include($phpbb_root_path.'config.php');

        $session_id = $_COOKIE['phpbb2mysql_sid'];
        session_end($session_id, $user_id);

        define('IN_PHPBB', false);
    }

    if(PHPBB_VERSION == 3) {
        global $phpbb_root_path, $phpEx;
        $phpbb_root_path = $forums_dir."/";
        $phpEx = "php";

        // these globals are pulled from
        // phpBB2/includes/sessions.php:session_create()
        global $SID, $_SID, $db, $config, $cache, $auth;
        global $user;

        define('IN_PHPBB', true);
        define('ROOT_PATH', $forums_dir);

        include($phpbb_root_path . 'common.' . $phpEx);

        $user->session_kill();
        $user->session_begin();

        define('IN_PHPBB', false);
    }
}

function get_reset_password_url()
{
    global $forums_url;

    if(PHPBB_VERSION == 2) {
        return "$forums_url/profile.php?mode=sendpassword";
    }

    if(PHPBB_VERSION == 3) {
        return "$forums_url/ucp.php?mode=sendpassword";
    }
}

function get_forum_user_details($username)
// Given a username, return details about the user.
// Returns an associative array with the following keys:
//     id        - forum user id
//     username  - forum username
//     lastvisit - timestamp the user last visited
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
//     rank      - forum rank (index)
//     posts     - number of forum posts
// If the user isn't found, the function returns NULL.
{
    $phpbb_table_prefix = PHPBB_TABLE_PREFIX;

    $interested_columns = array("id","username","lastvisit","from","occ","interests","viewemail","email","avatar","icq","website","aim","yim","msnm","jabber","rank","posts");

    $return_data = array();

    $query = sprintf("
        SELECT *
        FROM {$phpbb_table_prefix}_users
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

function set_forum_user_details($username,$details)
// Updates a users's details
// $details is an associative array with one or more of the following keys:
//     from      - location
//     occ       - occupation
//     interests - interests
//     email     - email
//     icq       - ICQ address
//     website   - website URL
//     aim       - AIM address
//     yim       - Yahoo address
//     msnm      - MS Messenger address
//     jabber    - Jabber address
// Returns TRUE if the update succeeded or FALSE otherwise.
{
    $phpbb_table_prefix = PHPBB_TABLE_PREFIX;

    $interested_columns = array("from","occ","interests","email","icq","website","aim","yim","msnm","jabber");

    $set_data = array();

    // all the interested columns are strings, so escape them as we
    // pull them into $set_data
    foreach($interested_columns as $column)
    {
        if(isset($details[$column]))
        {
            $set_data[] = sprintf("user_%s = '%s'",
                $column, mysql_real_escape_string($details[$column]));
        }
    }

    // don't do an update if no valid columns were specified
    if(!count($set_data))
    {
       return TRUE;
    }

    $query = sprintf("
        UPDATE {$phpbb_table_prefix}_users
        SET %s
        WHERE username='%s'
        ", 
        implode($set_data, ", "),
        mysql_real_escape_string($username));
    $res = mysql_query($query);

    return $res;
}

function get_forum_user_id($username)
// Given a forum username, return the forum user ID.
{
    $phpbb_table_prefix = PHPBB_TABLE_PREFIX;

    // Use a local in-memory cache so we don't pummel the database
    // for pages that end up calling this for the same small set 
    // of users over and over. Cache is local to this function.
    static $uidCache = array();
    // if it's in the cache, return it
    if(isset($uidCache[$username])) return $uidCache[$username];

    $query = sprintf("
        SELECT user_id
        FROM {$phpbb_table_prefix}_users
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

function get_forum_rank_title($rank)
// Given a forum rank number, return the text title of that rank.
{
    $phpbb_table_prefix = PHPBB_TABLE_PREFIX;

    $query = sprintf("
        SELECT rank_title
        FROM {$phpbb_table_prefix}_ranks
        WHERE rank_id = %d
        ",$rank);
    $res = mysql_query($query) or die(mysql_error());

    if (mysql_num_rows($res) == 0)
        return NULL;

    list($rank_title) = mysql_fetch_row($res);
    mysql_free_result($res);

    return $rank_title;
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

    if(PHPBB_VERSION == 2) {
        return "$forums_url/privmsg.php?mode=post&u=$userid";
    }

    if(PHPBB_VERSION == 3) {
        return "$forums_url/ucp.php?i=pm&mode=compose&u=$userid";
    }
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

    if(PHPBB_VERSION == 2) {
        return "$forums_url/images/avatars/" . $user_details["avatar"];
    }

    if(PHPBB_VERSION == 3) {
        return "$forums_url/download/file.php?avatar=" . $user_details["avatar"];
    }
}

function get_url_to_edit_profile()
// Return a URL that can be used to edit the current user's profile.
{
    global $forums_url;

    if(PHPBB_VERSION == 2) {
        return "$forums_url/profile.php?mode=editprofile";
    }

    if(PHPBB_VERSION == 3) {
        return "$forums_url/ucp.php";
    }
}

function get_url_to_view_profile($user_id)
// Return a URL that can be used to view a given user's profile.
{
    global $forums_url;

    if(PHPBB_VERSION == 2) {
        return "$forums_url/profile.php?mode=viewprofile&u=$user_id";
    }

    if(PHPBB_VERSION == 3) {
        return "$forums_url/memberlist.php?mode=viewprofile&u=$user_id";
    }
}

function get_url_for_inbox()
// Return the URL for accessing the current user's inbox.
{
    global $forums_url;
    
    if(PHPBB_VERSION == 2) {
        return "$forums_url/privmsg.php?folder=inbox";
    }

    if(PHPBB_VERSION == 3) {
        return "$forums_url/ucp.php?i=pm&folder=inbox";
    }
}

function get_number_of_unread_messages($username)
// Given a forum username, return the number of unread messages.
{
    $phpbb_table_prefix = PHPBB_TABLE_PREFIX;

    $forum_userid = get_forum_user_id($username);

    if($forum_userid == NULL)
        return NULL;

    if(PHPBB_VERSION == 2) {
        $query = "
            SELECT COUNT(*)
            FROM {$phpbb_table_prefix}_privmsgs
            WHERE privmsgs_to_userid = $forum_userid and (privmsgs_type = 1 or privmsgs_type = 5)
            ";
    }

    if(PHPBB_VERSION == 3) {
        # from: includes/functions_privmsgs.php:get_folder($user_id)
        $query = "
            SELECT SUM(pm_unread)
            FROM ${phpbb_table_prefix}_privmsgs_to
            WHERE user_id = $forum_userid
            ";
    }

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
    $phpbb_table_prefix = PHPBB_TABLE_PREFIX;

    $query = "
        SELECT 1
        FROM {$phpbb_table_prefix}_topics
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
    $phpbb_table_prefix = PHPBB_TABLE_PREFIX;

    // Validate that $topic_id is an integer and if not, return NULL
    if(!is_numeric($topic_id))
        return NULL;

    $query = "
        SELECT MAX(post_time)
        FROM {$phpbb_table_prefix}_posts
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
    $phpbb_table_prefix = PHPBB_TABLE_PREFIX;

    $phpbb_topics = "${phpbb_table_prefix}_topics";
    $phpbb_forums = "${phpbb_table_prefix}_forums";
    $phpbb_users = "${phpbb_table_prefix}_users";

    $query = "
        SELECT $phpbb_topics.topic_id AS topic_id,
            $phpbb_topics.topic_title AS title,
            $phpbb_topics.topic_replies AS num_replies,
            $phpbb_forums.forum_name AS forum_name,
            $phpbb_forums.forum_id AS forum_id,
            $phpbb_users.username AS creator_username
        FROM $phpbb_topics
        INNER JOIN $phpbb_forums ON $phpbb_topics.forum_id = $phpbb_forums.forum_id
        INNER JOIN $phpbb_users ON $phpbb_topics.topic_poster = $phpbb_users.user_id
        WHERE $phpbb_topics.topic_id = $topic_id
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
// A proxy for phpbb[2|3]_create_topic() in external phpbb[2|3].inc
{
    $args = func_get_args();
    $topic_id = call_phpbb_function( 'create_topic', $args );

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
// A proxy for phpbb[2|3]_add_post() in external phpbb[2|3].inc
{
    $args = func_get_args();
    call_phpbb_function( 'add_post', $args );
}

// -----------------------------------------------------------------------------

function call_phpbb_function( $func_name, $args )
// It's risky to combine DP and phpBB code in the same PHP context,
// so we run the phpBB code in a separate process.
{
    // echo "call_phpbb_function( $func_name, ", print_r($args,TRUE), ")<br>\n";

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
    if(PHPBB_VERSION == 2) {
        $phpbb_include_file = "phpbb2.inc";
    }
    if(PHPBB_VERSION == 3) {
        $phpbb_include_file = "phpbb3.inc";
    }
    $command = "cd $code_dir/pinc && $php_cli_executable -f $phpbb_include_file $func_name $escaped_args_str";
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

function topic_change_forum( $topic_id, $to_forum_id )
{
    $phpbb_table_prefix = PHPBB_TABLE_PREFIX;

    assert( $topic_id != '' );

    $result = mysql_query("
        SELECT forum_id
        FROM {$phpbb_table_prefix}_topics
        WHERE topic_id=$topic_id
    ");

    // If no rows were returned (because the topic_id doesn't exist), stop.
    if(mysql_num_rows($result)==0)
        return;

    $from_forum_id = mysql_result($result, 0, "forum_id");  

    if ($from_forum_id != $to_forum_id)
    {
        // move posts to new forum
        mysql_query("
            UPDATE {$phpbb_table_prefix}_posts
            SET forum_id = $to_forum_id
            WHERE topic_id = $topic_id
        ");

        // move topic to new forum
        mysql_query("
            UPDATE {$phpbb_table_prefix}_topics
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
    $phpbb_table_prefix = PHPBB_TABLE_PREFIX;

    // Count the number of topics in the forum
    $res1 = mysql_query("
        SELECT COUNT(*)
        FROM {$phpbb_table_prefix}_topics
        WHERE forum_id = $forum_id
    ");
    list($forum_topics) = mysql_fetch_row($res1);

    // Count the number of posts in the forum,
    // and get the id of most recent post in the forum,
    // which might have changed when we moved the topic(s).
    $res2 = mysql_query("
        SELECT COUNT(*), MAX(post_id)
        FROM {$phpbb_table_prefix}_posts
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
        UPDATE {$phpbb_table_prefix}_forums
        SET
            forum_posts  = $forum_posts,
            forum_topics = $forum_topics,
            forum_last_post_id = $last_post
        WHERE forum_id = $forum_id
    ");
}

// vim: sw=4 ts=4 expandtab
?>
