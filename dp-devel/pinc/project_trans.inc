<?
include_once($relPath.'v_site.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'bookpages.inc');

function project_transition( $projectid, $new_state, $extras = array() )
// Move the given project to the given state,
// and perform any attendant processing.
// If there are any problems, return a string containing an error message.
// Otherwise, return an empty string.
//
// This function produces no output except for debugging messages.
//
// This function should encapsulate the entire transition graph for project
// states. That is, it should be the only code that modifies the 'state'
// column of the 'projects' table.
// It should perhaps also be the only place that modifies these columns:
//     modifieddate checkedoutby postproofer postcomments
{
	global $testing;
	// error_reporting(E_ALL);

	$res = mysql_query("
		SELECT *
		FROM projects
		WHERE projectid = '$projectid'
	");
	$project = mysql_fetch_array($res);
    $current_state = $project['state'];

	$transition_str = "$projectid: transition from $current_state to $new_state";
	if ($testing)
	{
		$indent = '    ';
		if (FALSE)
		{
			// The trace output will be going into a <pre> element,
			// or to a plain-text document.
			$eol = "\n";
		}
		else
		{
			// The trace output will be going to an HTML doc,
			// but not into a <pre> element.
			$eol = "<br>\n";
		}

		// echo "{$indent}$transition_str{$eol}";
	}

	// -------------------------------------------------------------------------

	if ( $new_state == $current_state )
	{
		// The transition succeeds trivially.
		// No attendant processing.
		if ($testing)
		{
			echo "{$indent}$transition_str: succeeds trivially{$eol}";
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
			echo "{$indent}$transition_str: success{$eol}";
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
			PROJ_PROOF_FIRST_BAD_PROJECT,
			PROJ_PROOF_FIRST_UNAVAILABLE,
			PROJ_PROOF_FIRST_VERIFY, // queue-jumping trick (if invoked from PM page)
			),
		PROJ_PROOF_FIRST_VERIFY => array(
			PROJ_PROOF_FIRST_UNAVAILABLE,
			PROJ_PROOF_FIRST_AVAILABLE,
			PROJ_PROOF_FIRST_COMPLETE,
			),
		PROJ_PROOF_FIRST_AVAILABLE => array(
			PROJ_PROOF_FIRST_BAD_PROJECT,
			PROJ_PROOF_FIRST_UNAVAILABLE,
			PROJ_PROOF_FIRST_VERIFY, // misleading (but valid via c_pages)
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
			PROJ_PROOF_SECOND_VERIFY, // misleading (but valid via c_pages)
			PROJ_PROOF_SECOND_COMPLETE,
			),
		PROJ_PROOF_SECOND_COMPLETE => array(
			PROJ_POST_CHECKED_OUT,
			PROJ_POST_AVAILABLE,
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
		$errors = project_pre_release_check( $project );
		if ($errors)
		{
			return "$transition_str: disallowed because of project problems:\n<pre>\n$errors</pre>";
		}
		$settings .= ", modifieddate='$now'";
	}
	else if ( $current_state == PROJ_PROOF_FIRST_WAITING_FOR_RELEASE &&
		in_array( $new_state, array(PROJ_PROOF_FIRST_BAD_PROJECT,PROJ_PROOF_FIRST_VERIFY) ) )
	{
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

		//Delete from the HEAP table all page counts
		$result = mysql_query("SELECT COUNT(projectid) AS rowExists FROM page_counts WHERE projectid = '$projectid'");
		$rowExists = mysql_result($result, 0, "rowExists");
		if (!empty($rowExists)) {
			mysql_query ("DELETE FROM page_counts WHERE projectid = '$projectid'");
		}

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
		echo "{$indent}settings: $settings{$eol}";
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
		echo "{$indent}$transition_str: success{$eol}";
	}
	return '';
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function project_pre_release_check( $project )
// Check whether the project seems ready to be released.
// If it is, return an empty string.
// If it has problems, return a detailed error-message.
{
	global $projects_dir;

	$errors = '';

	$projectid  = $project['projectid'];
	$nameofwork = $project['nameofwork'];
	$author     = $project['authorsname'];
	$language   = $project['language'];
	$clearance  = $project['clearance'];

	if ($nameofwork == '') $errors .= "  The 'Title' field is blank.\n";
	if ($author == '')     $errors .= "  The 'Author' field is blank.\n";
	if ($language == '')   $errors .= "  The 'Language' field is blank.\n";
	if ($clearance == '')  $errors .= "  The 'Clearance' field is blank.\n";

	if ($errors) $errors .= "\n";

	$res = mysql_query("SELECT image,LENGTH(master_text) as master_text_length FROM $projectid ORDER BY image ASC");

	if (mysql_num_rows($res) == 0) $errors .= "  There are no pages.\n";

	while ( $page = mysql_fetch_assoc($res) )
	{
		$filename = $page['image'];
		$filepath = "$projects_dir/$projectid/$filename";

		if (!file_exists($filepath)) {
			$errors .= "  $filename: image file is missing\n";
		} else if (filesize($filepath) < 100) {
			$errors .= "  $filename: image file is small (probably bad)\n";
		}

		if ($page['master_text_length'] == 0) {
			$errors .= "  $filename: text is empty\n";
		}
	}

	return $errors;
}
// vim: ts=4 sw=4
?>
