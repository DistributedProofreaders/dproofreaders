<?
include_once($relPath.'v_site.inc');

function move_forum_thread( $topic_id, $from_forum_id, $to_forum_id)
{

	$i = 0;

	$update_post = mysql_query("
		UPDATE phpbb_posts SET forum_id = $to_forum_id WHERE topic_id=$topic_id
	");

	$i = mysql_affected_rows();

	$update_topic = mysql_query("
		UPDATE phpbb_topics SET forum_id = $to_forum_id WHERE topic_id=$topic_id
	");

	$get_count = mysql_query("
		SELECT forum_posts, forum_topics FROM phpbb_forums
		WHERE forum_id=$from_forum_id
	");

	$forum_posts = mysql_result($get_count, 0, "forum_posts");
	$forum_topics = mysql_result($get_count, 0, "forum_topics");
	$forum_topics = $forum_topics-1;
	$forum_posts = $forum_posts-$i;
	$update_count = mysql_query("
		UPDATE phpbb_forums
		SET forum_posts=$forum_posts, forum_topics=$forum_topics
		WHERE forum_id=$from_forum_id
	");


	$get_count = mysql_query("
		SELECT forum_posts, forum_topics FROM phpbb_forums
		WHERE forum_id=$to_forum_id
	");
	$forum_posts = mysql_result($get_count, 0, "forum_posts");
	$forum_topics = mysql_result($get_count, 0, "forum_topics");
	$forum_topics = $forum_topics+1;
	$forum_posts = $forum_posts+$i;
	$update_count = mysql_query("
		UPDATE phpbb_forums
		SET forum_posts=$forum_posts, forum_topics=$forum_topics
		WHERE forum_id=$to_forum_id
	");

        // TODO: ? add the id of the most recent post in the topic
        // to the forum_last_post_id field of the forums table  record for forum 15?

}

?>
