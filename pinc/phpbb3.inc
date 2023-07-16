<?php

// Wrappers around some phpBB3 functionality.

// Generally, for functions defined here,
// parameters are in the order Where, What, Who:
//     Where: $forum_id and/or $topic_id
//     What:  $post_subject, $post_text
//     Who:   $poster_name, $poster_is_real

// WARNING:
// This file includes phpBB3 files, which set and use global variables,
// some of which have the same names as global variables that the DP code
// sets and uses. (e.g., $theme, $lang, $starttime).  Because of the
// potential for collisions, you should avoid mixing this file with DP code.

$this_is_top_level_cli_invocation = (
    php_sapi_name() == 'cli' &&
    count(get_included_files()) == 1);
// i.e., this file is being executed because it was the
// script argument to a command-line invocation of php,
// probably invoked by DP code via exec() or system().

if ($this_is_top_level_cli_invocation) {
    // We assume it was invoked from the pinc/ directory.
    $relPath = './';
    include_once($relPath.'site_vars.php'); // $forums_phpbb_dir, $charset
}

// PHPBB includes (from the standard installation)
define('IN_PHPBB', true);
$phpbb_root_path = $forums_phpbb_dir.'/';
$phpEx = 'php';
include_once($phpbb_root_path . 'common.'.$phpEx);
include_once($phpbb_root_path . 'includes/functions_posting.'.$phpEx);
include_once($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include_once($phpbb_root_path . 'includes/functions_admin.' . $phpEx);
// phpBB 3.1 and 3.2 include compatibility functions
if (is_file($phpbb_root_path . 'includes/functions_compatibility.' . $phpEx)) {
    include_once($phpbb_root_path . 'includes/functions_compatibility.' . $phpEx);
}


// -----------------------------------------------------------------------------

function phpbb3_encode_and_hash_password(
    $password
) {
    // phpBB3 code does pre-processing on the password as it comes in from
    // the web browser. We need to do the same pre-processing before
    // hashing the password if we want ours to match theirs.
    set_var($password, $password, 'string', true);

    return phpbb_hash($password);
}

function phpbb3_create_user(
    $username,
    $password,
    $email,
    $lang
) {
    global $forums_phpbb_table_prefix;

    // get the group_id for the REGISTERED user group
    global $db;
    $sql = "SELECT group_id
            FROM {$forums_phpbb_table_prefix}_groups
            WHERE group_name = 'REGISTERED'
    ";
    $result = $db->sql_query($sql);
    $group_id = (int) $db->sql_fetchfield('group_id');
    $db->sql_freeresult($result);

    $user_row = [
        "username" => $username,
        "user_password" => $password,
        "user_email" => $email,
        "group_id" => $group_id,
        "user_type" => USER_NORMAL,
        "user_timezone" => -8.00,
        "user_lang" => $lang,
        "user_regdate" => time(),
        "user_allow_viewemail" => 0,
    ];

    $user_id = user_add($user_row);
    return $user_id;
}

function phpbb3_create_topic(
    $forum_id,
    $post_subject,
    $post_text,
    $poster_name,
    $poster_is_real,
    $make_poster_watch_topic
) {
    $post_result = _insert_post(
        $forum_id,
        0, // causes a new topic to be created
        $post_subject,
        $post_text,
        $poster_name,
        $poster_is_real,
        $make_poster_watch_topic
    );

    $topic_id = $post_result['topic_id'];

    return $topic_id;
}

// -----------------------------------------------------------------------------

function phpbb3_move_topic($topic_id, $forum_id)
{
    move_topics($topic_id, $forum_id);
}

function phpbb3_sync_forum($forum_id)
{
    $forum_ids = [$forum_id];
    sync('forum', 'forum_id', $forum_ids, true, true);
}

// -----------------------------------------------------------------------------

function phpbb3_add_post(
    $topic_id,
    $post_subject,
    $post_text,
    $poster_name,
    $poster_is_real
) {
    global $forums_phpbb_table_prefix;

    // Which forum is the topic in?
    global $db;
    $sql = "
        SELECT forum_id
        FROM {$forums_phpbb_table_prefix}_topics
        WHERE topic_id = $topic_id
    ";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    if (!$row or !$row["forum_id"]) {
        die_nz("no such topic: '$topic_id'\n");
    }

    _insert_post(
        $row["forum_id"],
        $topic_id,
        $post_subject,
        $post_text,
        $poster_name,
        $poster_is_real
    );
}

// -----------------------------------------------------------------------------

function _insert_post(
    $forum_id,
    $topic_id,
    $post_subject,
    $post_text,
    $poster_name,
    $poster_is_real,
    $watch_topic = null
)
// Use submit_post() to post to a topic.
// -- if $poster_is_real, deduce $poster_id from $poster_name;
//    otherwise use Anonymous (ie: guest)
// -- attachsig is deduced from poster's preferences;
// -- we override $user_ip;
// -- we disable smilies
{
    global $charset, $forums_phpbb_table_prefix;

    // see: https://phpbbmodders.net/articles/3.0/create_post/
    global $db, $user, $auth;


    if ($poster_is_real) {
        $username = $poster_name;
        $poster_name = '';
    } else {
        $username = "Anonymous";
    }
    $sql = sprintf("
        SELECT *
        FROM {$forums_phpbb_table_prefix}_users
        WHERE username='%s'
    ", $db->sql_escape($username));
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    if (!$row) {
        die_nz("no user named '$username'");
    }
    $db->sql_freeresult($result);
    $user->data = array_merge($user->data, $row);
    // Don't attach the current user's IP address (if any) to this post.
    $user->ip = '127.0.0.1';
    $auth->acl($user->data);

    if ($poster_is_real) {
        $poster_id = $user->data["user_id"];
        $attach_sig = $user->optionget('attachsig');
    } else {
        // poster is fake
        // (i.e., $poster_name is not the username of an actual user)
        $poster_id = -1;
        $attach_sig = false;
    }

    $post_subject = utf8_recode($post_subject, $charset);
    $post_text = utf8_recode($post_text, $charset);

    // see: https://wiki.phpbb.com/Function.submit_post
    $subject = utf8_normalize_nfc($post_subject);
    $text = utf8_normalize_nfc($post_text);

    // phpBB encodes the subject (but not the body) using ENT_COMPAT, the chain is:
    // phpBB3/posting.php
    //   $request->variable() in phpBB3/phpbb/request/request.php
    //     $type_cast_helper->recursive_set_var() in phpBB3/phpbb/request/type_cast_helper.php
    //       $type_cast_helper->set_var() in phpBB3/phpbb/request/type_cast_helper.php
    $subject = htmlspecialchars($subject, ENT_COMPAT, 'UTF-8', false);

    // phpBB 3.3.x does some munging for Astral characters in phpBB3/posting.php
    if (function_exists('utf8_encode_ucr')) {
        $subject = utf8_encode_ucr($subject);
    }

    $poll = $uid = $bitfield = $options = '';
    generate_text_for_storage($text, $uid, $bitfield, $options, true, true, true);

    $data = [
        // General Posting Settings
        'forum_id' => $forum_id, // The forum ID in which the post will be placed. (int)
        'topic_id' => $topic_id, // Post a new topic or in an existing one? Set to 0 to create a new one, if not, specify your topic ID here instead.
        'icon_id' => false,    // The Icon ID in which the post will be displayed with on the viewforum, set to false for icon_id. (int)

        // Defining Post Options
        'enable_bbcode' => true,    // Enable BBcode in this post. (bool)
        'enable_smilies' => false,   // Enabe smilies in this post. (bool)
        'enable_urls' => true,    // Enable self-parsing URL links in this post. (bool)
        'enable_sig' => $attach_sig, // Enable the signature of the poster to be displayed in the post. (bool)

        // Message Body
        'message' => $text,      // Your text you wish to have submitted. It should pass through generate_text_for_storage() before this. (string)
        'message_md5' => md5($text), // The md5 hash of your message

        // Values from generate_text_for_storage()
        'bbcode_bitfield' => $bitfield, // Value created from the generate_text_for_storage() function.
        'bbcode_uid' => $uid,      // Value created from the generate_text_for_storage() function.

        // Other Options
        'post_edit_locked' => 0,        // Disallow post editing? 1 = Yes, 0 = No
        'topic_title' => $subject, // Subject/Title of the topic. (string)

        // Email Notification Settings
        'post_time' => 0,        // Set a specific time, use 0 to let submit_post() take care of getting the proper time (int)
        'forum_name' => '',       // For identifying the name of the forum in a notification email. (string)

        // Indexing
        'enable_indexing' => true,    // Allow indexing the post? (bool)

        // 3.0.6
        'force_approved_state' => true, // Allow the post to be submitted without going into unapproved queue

        // 3.1-dev, overwrites force_approve_state
        'force_visibility' => true, // Allow the post to be submitted without going into unapproved queue, or make it be deleted
    ];

    // notify_set and notify control if the topic will be watched or not.
    // How notify_set is used seems unintuitive, but this is the logic
    // based on the submit_post() code.
    if ($watch_topic === null) {
        // don't change the watch status
        $data['notify_set'] = false;
        $data['notify'] = false;
    } elseif ($watch_topic) {
        // watch the topic
        $data['notify_set'] = false;
        $data['notify'] = true;
    } else {
        // stop watching the topic
        $data['notify_set'] = true;
        $data['notify'] = false;
    }

    if ($topic_id) {
        $post_method = "reply";
    } else {
        $post_method = "post";
    }

    submit_post($post_method, $subject, $poster_name, POST_NORMAL, $poll, $data);

    $data['poster_id'] = $poster_id;

    return $data;
}

// -----------------------------------------------------------------------------

function die_nz($string)
// Like die($string), but with a non-zero exit status.
{
    echo $string, "\n";
    exit(1);
}

// -----------------------------------------------------------------------------

if ($this_is_top_level_cli_invocation) {
    // $argv[0] is the location of this file.
    // $argv[1] indicates the function to call.
    // $argv[2+] are the arguments to pass to it.

    $func_name = 'phpbb3_' . $argv[1];
    $args = array_slice($argv, 2);

    // echo "calling $func_name with "; var_dump($args);
    $result = call_user_func_array($func_name, $args);
    echo $result;
    // So that $result will show up as the last line of input,
    // which is captured & returned by exec() and system(),
    // assuming that's how this is being invoked.
}
