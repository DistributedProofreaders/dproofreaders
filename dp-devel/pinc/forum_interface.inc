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
