<?

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
