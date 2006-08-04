<?PHP

// Wrappers around some phpBB2 functionality.

// Generally, for functions defined here,
// parameters are in the order Where, What, Who:
//     Where: $forum_id and/or $topic_id
//     What:  $post_subject, $post_text
//     Who:   $poster_name

// It should probably stay fairly independent of DP.
// (So it's code that could conceivably have been provided by phpBB itself.)

// PHPBB includes (from the standard installation)
define('IN_PHPBB', true);
$phpbb_root_path = $forums_dir.'/';
include_once($phpbb_root_path . 'extension.inc');
include_once($phpbb_root_path . 'common.'.$phpEx);
include_once($phpbb_root_path . 'includes/bbcode.'.$phpEx);
include_once($phpbb_root_path . 'includes/functions_post.'.$phpEx);

// include the custom PHPBB file
include_once($relPath . 'functions_insert_post.'.$phpEx);

// -----------------------------------------------------------------------------

function phpbb2_create_topic(
    $forum_id,
    $post_subject,
    $post_text,
    $poster_name,
    $make_poster_watch_topic
)
{
    // determine forum id and signature preference of poster
    $id_result = mysql_query("
        SELECT user_id, user_attachsig
        FROM phpbb_users
        WHERE username = '$poster_name'
    ");
    list($poster_id,$poster_attachsig) = mysql_fetch_row($id_result);

    // I'm not sure why we'd need this bit, but it was in the old code.
    if ($poster_attachsig == '') {$poster_attachsig = 1;}

    // Don't attach the current user's IP address (if any) to this post.
    // functions_insert_post.php gets the IP address from global $user_ip
    global $user_ip;
    $user_ip = '7f000001'; //127.0.0.1

    // Create the topic (by calling insert_post() without a topic_id)
    $post_result = insert_post(
        $post_text,
        $post_subject,
        $forum_id,  
        $poster_id,
        $poster_name,
        $poster_attachsig);

    $topic_id = $post_result['topic_id'];

    // if the PM wanted to be signed up for notifications, do so

    if ($make_poster_watch_topic)
    {
         mysql_query("
             INSERT INTO phpbb_topics_watch
             SET
                 user_id = $poster_id,
                 topic_id = $topic_id,
                 notify_status = 0
         ") or die(mysql_error());
    }

    return $topic_id;
}

// vim: sw=4 ts=4 expandtab
?>
