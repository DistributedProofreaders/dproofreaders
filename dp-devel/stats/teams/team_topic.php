<?
// DP includes
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'project_states.inc');

// PHPBB includes (from the standard installation)
define('IN_PHPBB', true);
$phpbb_root_path = $forums_dir.'/';
include_once($phpbb_root_path . 'extension.inc');
include_once($phpbb_root_path . 'common.'.$phpEx);
include_once($phpbb_root_path . 'includes/bbcode.'.$phpEx);
include_once($phpbb_root_path . 'includes/functions_post.'.$phpEx);

// include the custom PHPBB file
include_once($relPath . 'functions_insert_post.'.$phpEx);

// Which team?
$team_id = $_GET['team'];

// Get info about team

$team_result = mysql_query("SELECT teamname,team_info, webpage, createdby, owner, topic_id FROM user_teams WHERE id=$team_id");

$row = mysql_fetch_array($team_result);

$topic_id = $row['topic_id'];

//Determine if there is an existing topic or not; if not, create one
if(($topic_id == "") || ($topic_id == 0))
{

        $tname = $row['teamname'];
        $towner_name = $row['createdby'];
        $towner_id = $row['owner'];
        $tinfo = $row['team_info'];

        $message = "
Team Name: $tname
Created By: $towner_name
Info: $tinfo
Team Page: [url]".$code_url."/stats/teams/tdetail.php?tid=".$team_id."[/url]
Use this area to have a discussion with your fellow teammates! :-D

";


	// appropriate forum to create thread in
	$forum_id = $teams_forum_idx;

        $post_subject = $tname;

        // determine signature preference of owner

        $id_result = mysql_query("SELECT user_id, user_attachsig FROM phpbb_users WHERE username = '".$towner_name."'");
        $id_row = mysql_fetch_array($id_result);

        $owner = $id_row['user_id'];
        $sig = $id_row['user_attachsig'];
        if ($sig == '') {$sig = 1;}

        // create the post
        $post_result =  insert_post(
                $message,
                $post_subject,
                $forum_id,  
                $owner,
                $towner_name,
                $sig);

        $topic_id = $post_result['topic_id'];

        //Update user_teams with topic_id so it won't be created again
        $update_team = mysql_query("UPDATE user_teams SET topic_id=$topic_id WHERE id=$team_id");

}

// By here, either we had a topic or we've just created one, so redirect to it

$redirect_url = "$forums_url/viewtopic.php?t=$topic_id";
header("Location: $redirect_url");
?>
