<?
include_once($relPath.'v_site.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'page_states.inc');
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
	if (mysql_num_rows($res) == 0)
	{
	    return "$projectid: "._("no such project");
	}

	$project = mysql_fetch_array($res);
	$current_state = $project['state'];

	$transition_str = "$projectid: "._("transition from")." $current_state "._("to")." $new_state";
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
			echo "{$indent}$transition_str: "._("succeeds trivially")."{$eol}";
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
			echo "{$indent}$transition_str: "._("success")."{$eol}";
		}

		return '';
	}

	// -------------------------------------------------------------------------

	// Re transitions marked "misleading":
	// The pop-up menus on the PM project listings page imply that these
	// transitions are possible, but project_manager/changestate.php
	// refers these cases (among others) to automodify.php, which may
	// cause a transition, but (in these cases) not the requested one.

	$transition_graph = array(
		PROJ_NEW => array(
			PROJ_PROOF_FIRST_UNAVAILABLE
			),

		PROJ_NEW_FILE_UPLOADED => array(
			PROJ_NEW_METADATA_FIRST
			),

		PROJ_PROOF_FIRST_BAD_PROJECT => array(
			PROJ_PROOF_FIRST_UNAVAILABLE,
			// PROJ_PROOF_FIRST_VERIFY // misleading
			PROJ_PROOF_FIRST_AVAILABLE,
			PROJ_PROOF_FIRST_COMPLETE,
			),
		PROJ_PROOF_FIRST_UNAVAILABLE => array(
			PROJ_PROOF_FIRST_WAITING_FOR_RELEASE
			),
		PROJ_PROOF_FIRST_WAITING_FOR_RELEASE => array(
			PROJ_PROOF_FIRST_BAD_PROJECT,
			PROJ_PROOF_FIRST_UNAVAILABLE,
			PROJ_PROOF_FIRST_AVAILABLE, // queue-jumping trick (if invoked from PM page)
			),
		PROJ_PROOF_FIRST_AVAILABLE => array(
			PROJ_PROOF_FIRST_BAD_PROJECT,
			PROJ_PROOF_FIRST_UNAVAILABLE,
			PROJ_PROOF_FIRST_VERIFY, // misleading (but valid via c_pages)
			PROJ_PROOF_FIRST_COMPLETE,
			),
		PROJ_PROOF_FIRST_VERIFY => array(
			PROJ_PROOF_FIRST_UNAVAILABLE,
			PROJ_PROOF_FIRST_AVAILABLE,
			PROJ_PROOF_FIRST_COMPLETE,
			),
		PROJ_PROOF_FIRST_COMPLETE => array(
			PROJ_PROOF_SECOND_UNAVAILABLE,
			PROJ_PROOF_SECOND_AVAILABLE,
			),

		PROJ_PROOF_SECOND_BAD_PROJECT => array(
			PROJ_PROOF_SECOND_UNAVAILABLE,
			// PROJ_PROOF_SECOND_VERIFY // misleading
			PROJ_PROOF_SECOND_AVAILABLE,
			PROJ_PROOF_SECOND_COMPLETE,
			),
		PROJ_PROOF_SECOND_UNAVAILABLE => array(
			PROJ_PROOF_SECOND_VERIFY,
			),
		PROJ_PROOF_SECOND_WAITING_FOR_RELEASE => array(
			PROJ_PROOF_SECOND_UNAVAILABLE,
			PROJ_PROOF_SECOND_AVAILABLE,
			// PROJ_PROOF_SECOND_VERIFY // misleading
			),
		PROJ_PROOF_SECOND_AVAILABLE => array(
			PROJ_PROOF_SECOND_BAD_PROJECT,
			PROJ_PROOF_SECOND_UNAVAILABLE,
			PROJ_PROOF_SECOND_VERIFY, // misleading (but valid via c_pages)
			PROJ_PROOF_SECOND_COMPLETE,
			),
		PROJ_PROOF_SECOND_VERIFY => array(
			PROJ_PROOF_SECOND_AVAILABLE,
			PROJ_PROOF_SECOND_COMPLETE,
			),
		PROJ_PROOF_SECOND_COMPLETE => array(
			PROJ_POST_FIRST_CHECKED_OUT,
			PROJ_POST_FIRST_AVAILABLE,
			),

		PROJ_POST_FIRST_UNAVAILABLE => array(
			PROJ_POST_FIRST_AVAILABLE,
			PROJ_POST_FIRST_CHECKED_OUT,
			),
		PROJ_POST_FIRST_AVAILABLE => array(
			PROJ_POST_FIRST_UNAVAILABLE,
			PROJ_POST_FIRST_CHECKED_OUT,
			),
		PROJ_POST_FIRST_CHECKED_OUT => array(
			PROJ_POST_FIRST_UNAVAILABLE,
			PROJ_POST_FIRST_AVAILABLE,
			PROJ_POST_SECOND_AVAILABLE,
			PROJ_POST_SECOND_CHECKED_OUT,
			PROJ_POST_COMPLETE,
			PROJ_SUBMIT_PG_POSTED,
			),
		PROJ_POST_SECOND_AVAILABLE => array(
			PROJ_POST_SECOND_CHECKED_OUT,
			PROJ_POST_COMPLETE,
			PROJ_SUBMIT_PG_POSTED,
			),
		PROJ_POST_SECOND_CHECKED_OUT => array(
			PROJ_POST_SECOND_AVAILABLE,
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
		PROJ_SUBMIT_PG_POSTED => array(
			PROJ_CORRECT_AVAILABLE,
			),

		PROJ_CORRECT_AVAILABLE => array(
			PROJ_CORRECT_CHECKED_OUT,
			),
		PROJ_CORRECT_CHECKED_OUT => array(
			PROJ_CORRECT_AVAILABLE,
			PROJ_SUBMIT_PG_POSTED,
			),

	);

	if ( ! in_array( $new_state, $transition_graph[$current_state] ) )
	{
		// Requested transition is illegal.
		return "$transition_str "._("is illegal");
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
			return "$transition_str: "._("disallowed because of project problems").":\n<pre>\n$errors</pre>";
		}
		$settings .= ", modifieddate='$now'";
	}
	else if ( $current_state == PROJ_PROOF_FIRST_WAITING_FOR_RELEASE &&
		in_array( $new_state, array(PROJ_PROOF_FIRST_BAD_PROJECT,PROJ_PROOF_FIRST_AVAILABLE) ) )
	{
		$settings .= ", modifieddate='$now'";
	}
	else if ( $current_state == PROJ_POST_FIRST_CHECKED_OUT &&
		in_array( $new_state, array(PROJ_POST_FIRST_UNAVAILABLE,PROJ_POST_FIRST_AVAILABLE) ) )
	{
		$settings .= ", modifieddate='$now'";
		$settings .= ", checkedoutby=''";
	}
	else if ( $new_state == PROJ_POST_FIRST_CHECKED_OUT )
	{
		if ( $extras['checkedoutby'] == '' )
		{
			return "$transition_str: \$extras['checkedoutby'] is unset";
		}
		$settings .= ", modifieddate='$now'";
		$settings .= ", checkedoutby='{$extras['checkedoutby']}'";
	}
	else if ( $current_state == PROJ_POST_FIRST_CHECKED_OUT &&
		$new_state == PROJ_POST_SECOND_AVAILABLE )
	{
		global $postcomments;
		$settings .= ", modifieddate='$now'";
		$settings .= ", postproofer='{$project['checkedoutby']}'";
		$settings .= ", postcomments='{$postcomments}'";
	}
	else if ( ( $current_state == PROJ_POST_FIRST_CHECKED_OUT ||
		    $current_state == PROJ_POST_SECOND_AVAILABLE )
		&&
		$new_state == PROJ_POST_SECOND_CHECKED_OUT )
	{
		if ( $extras['checkedoutby'] == '' )
		{
			return "$transition_str: \$extras['checkedoutby'] is unset";
		}
		$settings .= ", modifieddate='$now'";
		$settings .= ", postproofer='{$project['checkedoutby']}'";
		$settings .= ", checkedoutby='{$extras['checkedoutby']}'";
	}
	else if ( $current_state == PROJ_POST_SECOND_CHECKED_OUT &&
		$new_state == PROJ_POST_SECOND_AVAILABLE )
	{
		$settings .= ", modifieddate='$now'";
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

		//Move the forum topic (if there is one) to the projects archive forum 
		$result = mysql_query("
			SELECT topic_id FROM projects WHERE projectid='$projectid'
		");
		$topic_id = mysql_result($result, 0, "topic_id");
		if ($topic_id != "")
		{

			$i = 0;

			$update_post = mysql_query("
				UPDATE phpbb_posts SET forum_id = 15 WHERE topic_id=$topic_id
			");

			$i = mysql_affected_rows();

			$update_topic = mysql_query("
				UPDATE phpbb_topics SET forum_id = 15 WHERE topic_id=$topic_id
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


			$get_count = mysql_query("
				SELECT forum_posts, forum_topics FROM phpbb_forums
				WHERE forum_id=15
			");
			$forum_posts = mysql_result($get_count, 0, "forum_posts");
			$forum_topics = mysql_result($get_count, 0, "forum_topics");
			$forum_topics = $forum_topics+1;
			$forum_posts = $forum_posts+$i;
			$update_count = mysql_query("
				UPDATE phpbb_forums
				SET forum_posts=$forum_posts, forum_topics=$forum_topics
				WHERE forum_id=15
			");

                        // TODO: ? add the id of the most recent post in the topic
                        // to the forum_last_post_id field of the forums table  record for forum 15?


		}

		// TODO: Archive the project

	}
      else if ( $current_state == PROJ_SUBMIT_PG_POSTED &&
		$new_state == PROJ_CORRECT_AVAILABLE)
	{
		if ( $extras['correctedby'] == '' )
		{
			return "$transition_str: \$extras['correctedby'] is unset";
		}
		$settings .= ", modifieddate='$now'";
		$settings .= ", correctedby='{$extras['correctedby']}'";
	}
	else if ( $current_state == PROJ_CORRECT_AVAILABLE &&
		$new_state == PROJ_CORRECT_CHECKED_OUT )
	{
		if ( $extras['checkedoutby'] == '' )
		{
			return "$transition_str: \$extras['checkedoutby'] is unset";
		}
		$settings .= ", modifieddate='$now'";
		$settings .= ", checkedoutby='{$extras['checkedoutby']}'";
	}
	else if ( $current_state == PROJ_CORRECT_CHECKED_OUT &&
		$new_state == PROJ_CORRECT_AVAILABLE )
	{
		$settings .= ", modifieddate='$now'";
		$settings .= ", checkedoutby=''";
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

	// ---------------------------------------------------------------------
	// Okay, so the update to the 'projects' table succeeded.
	// Now make any collateral changes to other tables.
	// (This needs to be expanded.)

	if ( $current_state == PROJ_PROOF_FIRST_WAITING_FOR_RELEASE &&
		$new_state == PROJ_PROOF_FIRST_AVAILABLE )
	{
		// Releasing the project.
		// Update the corresponding projectID* table.
		$result = mysql_query("
			UPDATE $projectid
			SET state = '".AVAIL_FIRST."'
			WHERE state = '".UNAVAIL_FIRST."'
		");
		// Update the page_counts table.
		update_total_pages($projectid, 1);
	}

	// (All the stuff under PROJ_SUBMIT_PG_POSTED, except for the assignment
	// to $settings, should probably be moved here.)

	// ---------------------------------------------------------------------

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

	if ($nameofwork == '') $errors .= "  "._("The 'Title' field is blank.")."\n";
	if ($author == '')     $errors .= "  "._("The 'Author' field is blank.")."\n";
	if ($language == '')   $errors .= "  "._("The 'Language' field is blank.")."\n";
	if ($clearance == '')  $errors .= "  "._("The 'Clearance' field is blank.")."\n";

	if ($errors) $errors .= "\n";

	$res = mysql_query("SELECT image,LENGTH(master_text) as master_text_length FROM $projectid ORDER BY image ASC");

	if (!$res)
	{
		$errors .= "  "._("There is no page-table.")."\n";
		return $errors;
	}
	

	if (mysql_num_rows($res) == 0) $errors .= "  "._("There are no pages.")."\n";

	while ( $page = mysql_fetch_assoc($res) )
	{
		$filename = $page['image'];
		$filepath = "$projects_dir/$projectid/$filename";

		if (!file_exists($filepath)) {
			$errors .= "  $filename: "._("image file is missing")."\n";
		} else if (filesize($filepath) < 100) {
			$errors .= "  $filename: "._("image file is small and probably bad")."\n";
		}

		if ($page['master_text_length'] == 0) {
			$errors .= "  $filename: "._("text is empty")."\n";
		}
	}

	return $errors;
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function maybe_mail_project_manager( $project, $info )
// $project is either a string containing a projectid,
// or an associative array containing various attributes of a project.
{
	global $code_url, $auto_email_addr;

	if (is_string($project))
	{
		$project = mysql_fetch_assoc(mysql_query("
			SELECT * FROM projects WHERE projectid='$project'
		"));
	}

	$projectid  = $project['projectid'];
	$nameofwork = $project['nameofwork'];
	$username   = $project['username'];

	$body =
_("This is an automated message from the Distributed Proofreaders site.")."

"._("Regarding").":
\"$nameofwork\"
  ($projectid)
  $code_url/tools/project_manager/project_detail.php?project=$projectid

$info

"._("Thank You!")."
"._("Distributed Proofreaders")."
";

	$email_addr = mysql_result(mysql_query("
		SELECT email FROM users WHERE username = '$username'
	"),0);

	maybe_mail($email_addr, "DP: \"$nameofwork\"", $body,
		"From: $auto_email_addr\r\nReply-To: $auto_email_addr\r\n");
}


?>
