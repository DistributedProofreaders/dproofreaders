<?

// $topic_id can be a comma separated string of topic_ids, or a single topic number
// $to_forum_id is the forum id keying into phpbb_forums table

function phpbb2_move_topic( $topic_id, $to_forum_id)
{
    assert( $topic_id != '' );

    $result = mysql_query("
        SELECT forum_id FROM phpbb_posts WHERE topic_id=$topic_id
    ");
    $from_forum_id = mysql_result($result, 0, "forum_id");  

    if ($from_forum_id != $to_forum_id)
    {
        $i = 0;


        // move posts to new forum
        // ***********************

        $update_post = mysql_query("
            UPDATE phpbb_posts SET forum_id = $to_forum_id WHERE topic_id in ($topic_id)
        ");

        $num_posts = mysql_affected_rows();


        // move topics to new forum
        // ***********************

        $update_topic = mysql_query("
            UPDATE phpbb_topics SET forum_id = $to_forum_id WHERE topic_id in ($topic_id)
        ");

        $num_topics = mysql_affected_rows();

        forum_resynch( $from_forum_id, -$num_posts, -$num_topics );
        forum_resynch( $to_forum_id,   +$num_posts, +$num_topics );
    }
}

// -----------------------------------------------------------------------------

function forum_resynch( $forum_id, $change_in_n_posts, $change_in_n_topics )
{
    // read numbers of posts and topics from forum

    $get_count = mysql_query("
        SELECT forum_posts, forum_topics
        FROM phpbb_forums
        WHERE forum_id = $forum_id
    ");
    $forum_posts = mysql_result($get_count, 0, "forum_posts");
    $forum_topics = mysql_result($get_count, 0, "forum_topics");

    $forum_topics = $forum_topics + $change_in_n_topics;
    $forum_posts = $forum_posts + $change_in_n_posts;

    // get id of most recent post in forum
    // (just in case this changed when we moved the topic(s))

    $get_last_post = mysql_query("
        SELECT MAX(post_id) AS last_post
        FROM phpbb_posts
        WHERE forum_id = $forum_id
    ");
    $last_post = mysql_result($get_last_post, 0, "last_post");
    $last_post = ($last_post == '') ? 0 : $last_post;

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
