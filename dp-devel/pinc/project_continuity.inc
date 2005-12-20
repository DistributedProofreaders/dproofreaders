<?PHP
include_once($relPath.'project_states.inc');
include_once($relPath.'stages.inc');

function project_continuity_check($projectid,$orig_state,$no_more_pages)
// $orig_state was the project's state at some time in the past,
// when some action was presented to the user as being valid.
// The user has now submitted a request to perform that action.
// If the project is no longer in that state,
// then the action is no longer valid.
//
// If $no_more_pages is true, then the action was/is to
// "proof the next available page", and the error message (if any)
// should be slightly more explanatory.
{
	$err = project_continuity_test($projectid, $orig_state, $no_more_pages);
	if ($err)
	{
		echo "<p>";
		echo $err;
		echo "</p>\n";

		global $code_url;
		$round = get_Round_for_project_state($orig_state);
		echo "<p>";
		echo "<a href='$code_url/tools/proofers/round.php?round_id={$round->id}' target=\"_top\">{$round->name}</a>";
		echo "</p>\n";

		die();
	}
}

function project_continuity_test($projectid, $orig_state, $no_more_pages)
{
	$sql = "SELECT state FROM projects WHERE projectid='$projectid' LIMIT 1";
	$result = mysql_query($sql);
	if ($result === FALSE) die(mysql_error());

	if (mysql_num_rows($result) == 0)
	{
		return sprintf(
			_("There is no project with projectid='%s'."),
		       	$projectid );
	}

	$curr_state = mysql_result($result, 0);
	if ($curr_state != $orig_state)
	{
		$orig_state_text = project_states_text($orig_state);
		$curr_state_text = project_states_text($curr_state);
		$err = sprintf(
			_("This project is no longer '%s', it is now '%s', so your request is no longer valid."),
			$orig_state_text,
			$curr_state_text
		);

		if ($no_more_pages)
		{
			$err .= "\n" . _("The project has no more pages available for proofreading in that round.");
		}
		return $err;
	}
}

?>
