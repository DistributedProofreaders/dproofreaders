<?
$relPath="./../../pinc/";
$linkPath="./../tools/proofers/";
include($relPath.'dp_main.inc');

//Declare variables
$timeposted = time();
$project_id = $_GET['project'];
$post_ip = $_SERVER['REMOTE_ADDR'];
$owner = 527;

//Get info about project
$result = mysql_query("SELECT nameofwork, authorsname, topic_id FROM projects WHERE projectid='$project_id'");
while($row = mysql_fetch_array($result)) {
$title = "Discussion: ".$row['nameofwork']."";
$message =  "Discussion of ".$row['nameofwork']." by ".$row['authorsname']."<br><br>Please <a href=".$linkPath."projects.php?project=$project_id&proofing=1>review</a> the project comments before posting.";
$topic_id = $row['topic_id'];
}

//Determine if there is an existing topic or not
if(($topic_id == "") || ($topic_id == 0)) {
//Add Topic into phpbb_topics
$insert_topic = mysql_query("INSERT INTO phpbb_topics (topic_id, forum_id, topic_title, topic_poster, topic_time, topic_views, topic_replies, topic_status, topic_vote, topic_type, topic_first_post_id, topic_last_post_id, topic_moved_id) VALUES (NULL, 2, '$title', $owner, $timeposted, 0, 0, 0, 0, 0, 1, 1, 0)");
$topic_id = mysql_insert_id();

//Add Post into phpbb_posts
$insert_post = mysql_query("INSERT INTO phpbb_posts (post_id, topic_id, forum_id, poster_id, post_time, poster_ip, post_username, enable_bbcode, enable_html, enable_smilies, enable_sig, post_edit_time, post_edit_count) VALUES (NULL,$topic_id, 2, $owner, $timeposted, '$post_ip', NULL, 1, 0, 1, 1, NULL, 0)");
$post_id = mysql_insert_id();

//Add Post Text into phpbb_posts_text
$insert_post_text = mysql_query("INSERT INTO phpbb_posts_text (post_id, bbcode_uid, post_subject, post_text) VALUES ($post_id, '', '$title', '$message')");

//Update phpbb_topics with post_id
$update_topic = mysql_query("UPDATE phpbb_topics SET topic_first_post_id=$post_id, topic_last_post_id=$post_id WHERE topic_id=$topic_id");

//Update forum post count
$get_count = mysql_query("SELECT forum_posts, forum_topics FROM phpbb_forums WHERE forum_id=2");
while($row = mysql_fetch_array($get_count)) {
$forum_posts = $row['forum_posts'];
$forum_topics = $row['forum_topics'];
$forum_posts++;
$forum_topics++;
$update_count = mysql_query("UPDATE phpbb_forums SET forum_posts=$forum_posts, forum_topics=$forum_topics, forum_last_post_id=$post_id WHERE forum_id=2");
}

//Update project_db with topic_id so it can be deleted
$update_project = mysql_query("UPDATE projects SET topic_id=$topic_id WHERE projectid='$project_id'");

//Redirect to the topic
$redirect_url = "../../phpBB2/viewtopic.php?t=$topic_id";
header("Location: $redirect_url"); 
} else {
$redirect_url = "../../phpBB2/viewtopic.php?t=$topic_id";
header("Location: $redirect_url"); 
}
