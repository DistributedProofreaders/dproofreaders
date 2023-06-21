<?php
// DP includes
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'forum_interface.inc'); // topic_create & get_url_to_view_topic
include_once($relPath.'misc.inc'); // get_integer_param()

require_login();

// Which team?
$team_id = get_integer_param($_GET, 'team', null, 0, null);

// Get info about team

$sql = sprintf("
    SELECT teamname, team_info, webpage, createdby, owner, topic_id
    FROM user_teams
    WHERE id=%d", $team_id);
$team_result = DPDatabase::query($sql);

$row = mysqli_fetch_array($team_result);

// If no row was returned, there is no team matching that ID,
// this can only happen due to URL hacking so just throw an error.
if (!$row) {
    throw new ValueError("No team with ID $team_id");
}

$topic_id = $row['topic_id'];

//Determine if there is an existing topic or not; if not, create one
if (($topic_id == "") || ($topic_id == 0)) {
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

    $topic_id = topic_create(
                $forum_id,
                $post_subject,
                $message,
                $towner_name,
                true,
                false);

    //Update user_teams with topic_id so it won't be created again
    $update_team = DPDatabase::query("UPDATE user_teams SET topic_id=$topic_id WHERE id=$team_id");
}

// By here, either we had a topic or we've just created one, so redirect to it

$redirect_url = get_url_to_view_topic($topic_id);
header("Location: $redirect_url");
