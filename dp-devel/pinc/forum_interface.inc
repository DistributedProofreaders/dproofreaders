<?
include_once($relPath.'v_site.inc');


// $topic_id can be a comma separated string of topic_ids, or a single topic number
// $from_forum_id and $to_forum_id are the forum ids keying into phpbb_forums table

function move_forum_thread( $topic_id, $from_forum_id, $to_forum_id)
{

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


                // resynch old forum
                // *****************

                // read numbers of posts and topics from old forum

                $get_count = mysql_query("
                        SELECT forum_posts, forum_topics FROM phpbb_forums
                        WHERE forum_id = $from_forum_id
                ");

                $forum_posts = mysql_result($get_count, 0, "forum_posts");
                $forum_topics = mysql_result($get_count, 0, "forum_topics");


                // we've removed $num_topics topics and $num_posts posts

                $forum_topics = $forum_topics - $num_topics;
                $forum_posts = $forum_posts - $num_posts;


                // get id of most recent post in old forum
                // (just in case this changed when we moved the topic(s) out)

                $get_last_post = mysql_query("
                        SELECT MAX(post_id) AS last_post FROM phpbb_posts
                        WHERE forum_id = $from_forum_id
                ");

                $last_post = mysql_result($get_last_post, 0, "last_post");


                $last_post = ($last_post == '') ? 0 : $last_post;

                // adjust total posts and topics and last post for old forum

                $update_count = mysql_query("
                        UPDATE phpbb_forums
                        SET forum_posts = $forum_posts, forum_topics = $forum_topics, forum_last_post_id = $last_post
                        WHERE forum_id = $from_forum_id
                ");


                // resynch new forum
                // *****************

                // read numbers of posts and topics from new forum

                $get_count = mysql_query("
                        SELECT forum_posts, forum_topics FROM phpbb_forums
                        WHERE forum_id=$to_forum_id
                ");
                $forum_posts = mysql_result($get_count, 0, "forum_posts");
                $forum_topics = mysql_result($get_count, 0, "forum_topics");

                // we've added $num_topics topics and $num_posts posts

                $forum_topics = $forum_topics + $num_topics;
                $forum_posts = $forum_posts + $num_posts;

                // get id of most recent post in new forum
                // (just in case this changed when we moved the topic(s) in)

                $get_last_post = mysql_query("
                        SELECT MAX(post_id) AS last_post FROM phpbb_posts
                        WHERE forum_id = $to_forum_id
                ");

                $last_post = mysql_result($get_last_post, 0, "last_post");

                // adjust total posts and topics and last post for new forum

                $update_count = mysql_query("
                        UPDATE phpbb_forums
                        SET forum_posts=$forum_posts, forum_topics=$forum_topics,  forum_last_post_id = $last_post
                        WHERE forum_id=$to_forum_id
                ");

        }
}

?>