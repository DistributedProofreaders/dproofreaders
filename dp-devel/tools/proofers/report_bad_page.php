<?
$relPath="./../../pinc/";
include_once($relPath.'v_site.inc');
include_once($relPath.'maybe_mail.inc');
include_once($relPath.'dp_main.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'project_trans.inc');
include_once($relPath.'page_states.inc');
include_once($relPath.'gettext_setup.inc');
include_once($relPath.'theme.inc');

$no_stats=1;
$projectid  = $_POST['projectname'];
$proofstate = $_POST['proofstate'];
$fileid     = $_POST['fileid'];
$imagefile  = $_POST['imagefile'];
// When this file included from processtext.php,
// $_POST['badState'] is not defined,
// $badState is set "manually".
$reason_list = array('',_("Image Missing"),_("Missing Text"),_("Image/Text Mismatch"),_("Corrupted Image"),_("Other"));

if (!isset($_POST['submitted']) || $_POST['submitted'] != 'true')
{
	$header = _("Bad Page Report");
	theme($header, "header");

	echo "<br><br><center>";
	echo "<table width='80%' align='center' bgcolor='#ffffff' border='1' bordercolor='#111111' cellspacing='0' cellpadding='0' style='border-collapse: collapse'><tr><td bgcolor='#336633' colspan='2' align='left'>";
	echo "<font color='#ffffff'><center><b>"._("Common Fixes for Bad Pages. Try these first!")."</b></center>";
	echo "<ul>";
	echo "<li>"._("First, we need to look at what a bad page really is.  Remember this is proofreading so you may see line breaks after every word.  A column may seem to have text missing but all you may need to do is look further down in the text, sometimes the columns may not wrap properly.  There may actually be a portion of the text missing but not all of it.  In these circumstances as well as similiar ones you would want to proofread the page like normal.  Move the text where it needs to be, type in any missing text, etc...  These would <b>not</b> be bad pages.")."<br><br>";
	echo "<li>"._("Sometimes, the image may not show up due to technical problems with your browser.  Depending upon your browser there are many ways to try to reload that image.  For example, in Internet Explorer you can right click on the image & left click Show Image or Refresh.  This 90% of the time causes the image to then display.  Again, this would <b>not</b> be a bad page.")."<br><br>";
	echo "<li>"._("Occasionally, you may come across a page that has so many mistakes in the optical character recognition (OCR) that you may think it is a bad page that needs to be re-OCRed.  However, this is what you are there for.  You may want to copy it into your local word editing program (eg: Microsoft Word, StarOffice, vi, etc..) and make the changes there & copy them back into the editor.")."<br><br>";
	echo "<li>"._("Lastly, checking out our common solutions thread may also help you with making sure the report is as correct as possible.  Here's a link to it <a href='$forums_url/viewtopic.php?t=1659' target='_new'>here</a>.")."<br><br>";
	echo "<li>"._("If you've made sure that nothing is going wrong with your computer and you still think it is a bad page please let us know by filling out the information below.  However, if you are at the least bit hestitant that it may not actually be a bad page please do not mark it so & just hit Cancel on the form above.  Marking pages bad when they really aren't takes time away from the project managers so we want to make sure they don't spend their entire time correcting & adding pages back to the project that aren't bad.");
	echo "</ul></td></tr></table></div></center></font>";
	echo "<br><br><center>";
	echo "<form action='badpage.php' method='post'>";
	echo "<input type='hidden' name='fileid' value='$fileid'>";
	echo "<input type='hidden' name='projectname' value='$projectid'>";
	echo "<input type='hidden' name='badState' value='$badState'>";
	echo "<input type='hidden' name='proofstate' value='$proofstate'>";
	echo "<input type='hidden' name='submitted' value='true'>";
	echo "<table bgcolor='#ffffff' border='1' bordercolor='#111111' cellspacing='0' cellpadding='0' style='border-collapse: collapse'>";
	echo "<tr><td bgcolor='#336633' colspan='2' align='center'>";
	echo "<B><font color='#ffffff'>"._("Submit a Bad Page Report")."</font></B>";
	echo "<tr><td bgcolor='#e0e8dd' align='left'>";
	echo "<strong>"._("Reason").":</strong>";
	echo "<td bgcolor='#ffffff' align='center'>";
	echo "<select name='reason'>";
	for ($i=0;$i<count($reason_list);$i++)
	{
		echo "<option value='$i'>$reason_list[$i]</option>";
	}
	echo "</select>";
	echo "<tr><td bgcolor='#e0e8dd' align='left'>";
	echo "<strong>"._("What to Do").":</strong>";
	echo "<td bgcolor='#ffffff' align='center'>";
	echo "<input name='redirect_action' value='proof' type='radio'>"._("Continue Proofreading")."<input name='redirect_action' value='quit' checked type='radio'>"._("Stop Proofreading");
	echo "<tr><td bgcolor='#336633' colspan='2' align='center'>";
	echo "<input type='submit' value='"._("Submit Report")."'>";
	echo "<input type='button' value='"._("Cancel")."' onclick='javascript:history.go(-1)'>";
	echo "<tr><td bgcolor='#ffffff' colspan='2' align='center'>";
	echo "<B>"._("Note").":</B> "._("If this report causes a project to be marked<br> bad you will be redirected to your personal page.");
	echo "</td></tr></table></form></center></div>";
	theme("", "footer");
}
else
{
	$reason   = $_POST['reason'];
	$badState = $_POST['badState'];

	//See if they filled in a reason.  If not tell them to go back
	if ($reason == 0) {
		include_once($relPath.'theme.inc');
		theme(_("Incomplete Form!"), "header");
		echo "<br><center>"._("You have not completely filled out this form!  Please hit the <a href='javascript:history.back()'>back</a> button on your browser & fill out all fields.")."</center>";
		theme("","footer");
		exit();
	}

	//Update the page the user was working on to reflect a bad page.
	if ($badState == "bad_first") {
		$text_copier = 'round1_text=master_text';
	} else {
		$text_copier = 'round2_text=round1_text';
	}
	if ($writeBIGtable) {
		$result = mysql_query("UPDATE project_pages SET state='$badState', b_user='$pguser', b_code=$reason, $text_copier WHERE projectid = '$projectid' AND fileid='$fileid'");
	}

	$result = mysql_query("UPDATE $projectid SET state='$badState', b_user='$pguser', b_code=$reason, $text_copier WHERE fileid='$fileid'");

	//Find out how many pages have been marked bad
	$totalBad = mysql_num_rows(mysql_query("SELECT * FROM $projectid WHERE state='$badState'"));

	$project_is_bad = FALSE;
	//If $totalBad >= 10 check to see if there are more than 3 unique reports. If there are mark the whole project as bad
	if ($totalBad >= 10) {
		$result = mysql_query("SELECT COUNT(DISTINCT(b_user)) FROM $projectid WHERE state='$badState'");
		$uniqueBadPages = mysql_result($result,0);
		if ($uniqueBadPages >= 3) {
			if($badState==BAD_FIRST) {
				$new_state = PROJ_PROOF_FIRST_BAD_PROJECT;
			} else {
				$new_state = PROJ_PROOF_SECOND_BAD_PROJECT;
			}
			$error_msg = project_transition( $projectid, $new_state );
			if ($error_msg)
			{
				echo "$error_msg<br>\n";
				return;
			}
			$project_is_bad = TRUE;
		}
	}


	//If the project has been shut down advise PM otherwise advise PM that the page has been marked bad
	if ($project_is_bad) {
		$message =
"This project has been shut down.
This is due to 10 or more problem reports, from at least
3 unique users, noting errors or problems with this project.
Please visit the Project Manager page to view a list
of your bad projects and make any necessary changes.
You will then be able to put the project back up on the site.";
	} else {
		$message =
"$imagefile has been a page marked as bad in this project due to $reason_list[$reason].
Please visit $code_url/tools/project_manager/badpage.php?projectid=$projectid&fileid=$fileid to view
the reason it was marked as bad by the user.
You will then be able to make any needed
changes and put the page back up for proofreading.
If 10 pages are marked bad by at least 3 unique users,
the project will be automatically shut down.";
	}

	//Send the email to the PM
	maybe_mail_project_manager($projectid, $message, "DP Bad Page");

	//Redirect the user to either continue proofreading if project is still open or back to their personal page
	if (($_POST['redirect_action'] == "proof") && (!$project_is_bad)) {
		$frame1 = "proof_frame.php?project={$projectid}&amp;proofstate={$proofstate}";
		$title = _("Bad Page Report");
		$body = _("Continuing to Proofread");
	} else {
		$frame1 = "projects.php?project={$projectid}&amp;proofstate={$proofstate}";
		$title = _("Stop Proofreading");
		$body = _("Exiting proofreading interface");
	}
	metarefresh(0,$frame1, $title, $body);
}
?>
