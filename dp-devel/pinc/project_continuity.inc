<?PHP
include_once($relPath.'f_project_states.inc');

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
	$sql = "SELECT state FROM projects WHERE projectid='$projectid' LIMIT 1";
	$result = mysql_query($sql);
	$curr_state = mysql_result($result, 0);
	if ($curr_state != $orig_state)
	{
		$orig_state_text = project_states_text($orig_state);
		$curr_state_text = project_states_text($curr_state);
		echo "<p>";
		echo _("This project is no longer")." '$orig_state_text', ";
		echo _("it is now")." '$curr_state_text', ";
		echo _("so your request is no longer valid.");
		echo "</p>\n";

		if ($no_more_pages)
		{
			echo "<p>";
			echo _("The project has no more pages available for proofreading in that round.");
			echo "</p>\n";
		}

		echo "<p>";
		echo "<a href='list_avail.php'>"._("Back to list of projects available for proofreading.")."</a>";
		echo "</p>\n";

		die();
	}
}
?>
