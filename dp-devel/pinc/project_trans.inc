<?
include_once($relPath.'v_site.inc');
include_once($relPath.'project_states.inc');

function project_transition( $projectid, $new_state, $extras = array() )
// Move the given project to the given state,
// and perform any attendant processing.
// If there are any problems, return a string containing an error message.
// Otherwise, return an empty string.
//
// This function produces no output except for debugging messages.
{
	global $testing;
	// error_reporting(E_ALL);

	$res = mysql_query("
		SELECT state, checkedoutby, postproofer
		FROM projects
		WHERE projectid = '$projectid'
	");
	$project = mysql_fetch_array($res);
    $current_state = $project['state'];

	$transition_str = "$projectid: transition from $current_state to $new_state";
	// echo "$transition_str<br>";

	// -------------------------------------------------------------------------

	if ( $new_state == $current_state )
	{
		// The transition succeeds trivially.
		// No attendant processing.
		if ($testing)
		{
			echo "$transition_str: succeeds trivially<br>\n";
		}
		return '';
	}

	// -------------------------------------------------------------------------

	// A project can be deleted when it's in any state.
	if ( $new_state == PROJ_DELETE )
	{
		$result = mysql_query("DROP TABLE $projectid");

		$result = mysql_query("
			DELETE FROM projects WHERE projectid = '$projectid'
		");

		global $projects_dir;
		exec("rm -rf $projects_dir/$projectid");

		if ($testing)
		{
			echo "$transition_str: success<br>\n";
		}

		return '';
	}

	// -------------------------------------------------------------------------

	// Re transitions marked "misleading":
	// The pop-up menus on the PM project listings page imply that these
	// transitions are possible, but project_manager/chanagestate.php
	// refers these cases (among others) to automodify.php, which may
	// cause a transition, but (in these cases) not the requested one.

	$transition_graph = array(
		PROJ_NEW => array(
			PROJ_PROOF_FIRST_UNAVAILABLE
			),

		PROJ_PROOF_FIRST_UNAVAILABLE => array(
			PROJ_PROOF_FIRST_WAITING_FOR_RELEASE
			),
		PROJ_PROOF_FIRST_BAD_PROJECT => array(
			PROJ_PROOF_FIRST_UNAVAILABLE,
			// PROJ_PROOF_FIRST_VERIFY // misleading
			PROJ_PROOF_FIRST_AVAILABLE,
			PROJ_PROOF_FIRST_COMPLETE,
			),
		PROJ_PROOF_FIRST_WAITING_FOR_RELEASE => array(
			PROJ_PROOF_FIRST_UNAVAILABLE,
			PROJ_PROOF_FIRST_VERIFY, // queue-jumping trick
			),
		PROJ_PROOF_FIRST_VERIFY => array(
			PROJ_PROOF_FIRST_UNAVAILABLE,
			PROJ_PROOF_FIRST_AVAILABLE,
			PROJ_PROOF_FIRST_COMPLETE,
			),
		PROJ_PROOF_FIRST_AVAILABLE => array(
			PROJ_PROOF_FIRST_BAD_PROJECT,
			PROJ_PROOF_FIRST_UNAVAILABLE,
			// PROJ_PROOF_FIRST_VERIFY // misleading
			PROJ_PROOF_FIRST_COMPLETE,
			),
		PROJ_PROOF_FIRST_COMPLETE => array(
			PROJ_PROOF_SECOND_AVAILABLE,
			),

		PROJ_PROOF_SECOND_UNAVAILABLE => array(
			PROJ_PROOF_SECOND_VERIFY,
			),
		PROJ_PROOF_SECOND_BAD_PROJECT => array(
			PROJ_PROOF_SECOND_UNAVAILABLE,
			// PROJ_PROOF_SECOND_VERIFY // misleading
			PROJ_PROOF_SECOND_AVAILABLE,
			PROJ_PROOF_SECOND_COMPLETE,
			),
		PROJ_PROOF_SECOND_WAITING_FOR_RELEASE => array(
			PROJ_PROOF_SECOND_UNAVAILABLE,
			// PROJ_PROOF_SECOND_VERIFY // misleading
			),
		PROJ_PROOF_SECOND_VERIFY => array(
			PROJ_PROOF_SECOND_AVAILABLE,
			PROJ_PROOF_SECOND_COMPLETE,
			),
		PROJ_PROOF_SECOND_AVAILABLE => array(
			PROJ_PROOF_SECOND_BAD_PROJECT,
			PROJ_PROOF_SECOND_UNAVAILABLE,
			// PROJ_PROOF_SECOND_VERIFY // misleading
			PROJ_PROOF_SECOND_COMPLETE,
			),

		PROJ_POST_UNAVAILABLE => array(
			PROJ_POST_AVAILABLE,
			PROJ_POST_CHECKED_OUT,
			),
		PROJ_POST_AVAILABLE => array(
			PROJ_POST_UNAVAILABLE,
			PROJ_POST_CHECKED_OUT,
			),
		PROJ_POST_CHECKED_OUT => array(
			PROJ_POST_UNAVAILABLE,
			PROJ_POST_AVAILABLE,
			PROJ_POST_VERIFY,
			PROJ_POST_VERIFYING,
			PROJ_POST_COMPLETE,
			PROJ_SUBMIT_PG_POSTED,
			),
		PROJ_POST_VERIFY => array(
			PROJ_POST_VERIFYING,
			PROJ_POST_COMPLETE,
			PROJ_SUBMIT_PG_POSTED,
			),
		PROJ_POST_VERIFYING => array(
			PROJ_POST_VERIFY,
			PROJ_POST_COMPLETE,
			PROJ_SUBMIT_PG_POSTED,
			),
		PROJ_POST_COMPLETE => array(
			PROJ_SUBMIT_PG_UNAVAILABLE,
			PROJ_SUBMIT_PG_AVAILABLE,
			PROJ_SUBMIT_PG_POSTING,
			PROJ_SUBMIT_PG_POSTED,
			),

		PROJ_SUBMIT_PG_UNAVAILABLE => array(
			PROJ_SUBMIT_PG_AVAILABLE,
			PROJ_SUBMIT_PG_POSTING,
			),
		PROJ_SUBMIT_PG_AVAILABLE => array(
			PROJ_SUBMIT_PG_UNAVAILABLE,
			PROJ_SUBMIT_PG_POSTING,
			PROJ_SUBMIT_PG_POSTED,
			),
		PROJ_SUBMIT_PG_POSTING => array(
			PROJ_SUBMIT_PG_UNAVAILABLE,
			PROJ_SUBMIT_PG_AVAILABLE,
			PROJ_SUBMIT_PG_POSTED,
			),
	);

	if ( ! in_array( $new_state, $transition_graph[$current_state] ) )
	{
		// Requested transition is illegal.
		return "$transition_str is illegal";
	}

	// -------------------------------------------------------------------------

	$now = time();

	$settings = "state='$new_state'";

	if ( $current_state == PROJ_PROOF_FIRST_UNAVAILABLE &&
	     $new_state == PROJ_PROOF_FIRST_WAITING_FOR_RELEASE )
	{
        // A project cannot be released if it has no pages.
        $result = mysql_query("SELECT fileid FROM $projectid");
        if (mysql_num_rows($result) == 0)
		{
            return "$transition_str is disallowed because project has no pages";
        }
		$settings .= ", modifieddate='$now'";
	}
	else if ( $current_state == PROJ_POST_CHECKED_OUT &&
		in_array( $new_state, array(PROJ_POST_UNAVAILABLE,PROJ_POST_AVAILABLE) ) )
	{
		$settings .= ", modifieddate=''";
		$settings .= ", checkedoutby=''";
	}
	else if ( $new_state == PROJ_POST_CHECKED_OUT )
	{
		if ( $extras['checkedoutby'] == '' )
		{
			return "$transition_str: \$extras['checkedoutby'] is unset";
		}
		$settings .= ", modifieddate='$now'";
		$settings .= ", checkedoutby='{$extras['checkedoutby']}'";
	}
	else if ( $current_state == PROJ_POST_CHECKED_OUT &&
		$new_state == PROJ_POST_VERIFY )
	{
		global $postcomments;
		$settings .= ", modifieddate='$now'";
		$settings .= ", postproofer='{$project['checkedoutby']}'";
		$settings .= ", postcomments='{$postcomments}'";
	}
	else if ( $current_state == PROJ_POST_VERIFY &&
		$new_state == PROJ_POST_VERIFYING )
	{
		if ( $extras['checkedoutby'] == '' )
		{
			return "$transition_str: \$extras['checkedoutby'] is unset";
		}
		$settings .= ", modifieddate='$now'";
		$settings .= ", postproofer='{$project['checkedoutby']}'";
		$settings .= ", checkedoutby='{$extras['checkedoutby']}'";
	}
	else if ( $current_state == PROJ_POST_VERIFYING &&
		$new_state == PROJ_POST_VERIFY )
	{
		$settings .= ", modifieddate=''";
		$settings .= ", checkedoutby='{$project['postproofer']}'";
	}
	else if ( $new_state == PROJ_SUBMIT_PG_POSTED )
	{
		$settings .= ", modifieddate='$now'";

		//Delete the topic from forum if there is one
		$result = mysql_query("
			SELECT topic_id FROM projects WHERE projectid='$projectid'
		");
		$topic_id = mysql_result($result, 0, "topic_id");
		if ($topic_id != "")
		{
			$i = 0;
			$post_list = mysql_query("
				SELECT * FROM phpbb_posts WHERE topic_id=$topic_id
			");
			while($row = mysql_fetch_array($post_list) )
			{
				$postid = $row['post_id'];
				$i++;
				$delete_post_text = mysql_query("
					DELETE FROM phpbb_posts_text WHERE post_id=$postid
				");
			}
			$delete_post = mysql_query("
				DELETE FROM phpbb_posts WHERE topic_id=$topic_id
			");
			$delete_topic = mysql_query("
				DELETE FROM phpbb_topics WHERE topic_id=$topic_id
			");
			$get_count = mysql_query("
				SELECT forum_posts, forum_topics FROM phpbb_forums
				WHERE forum_id=2
			");
			$forum_posts = mysql_result($get_count, 0, "forum_posts");
			$forum_topics = mysql_result($get_count, 0, "forum_topics");
			$forum_topics = $forum_topics-1;
			$forum_posts = $forum_posts-$i;
			$update_count = mysql_query("
				UPDATE phpbb_forums
				SET forum_posts=$forum_posts, forum_topics=$forum_topics
				WHERE forum_id=2
			");
		}

		// TODO: Archive the project
	}


	if ($testing)
	{
		echo "settings = $settings<br>\n";
	}

	$res = mysql_query("
		UPDATE projects SET $settings WHERE projectid = '$projectid'
	");
	if (!$res)
	{
		return "$transition_str: mysql error: " . mysql_error();
	}

	if ($testing)
	{
		echo "$transition_str: success<br>\n";
	}
	return '';
}

// vim: ts=4 sw=4
?>
