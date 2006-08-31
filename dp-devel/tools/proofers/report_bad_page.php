<?
$relPath="./../../pinc/";
include_once($relPath.'site_vars.php');
include_once($relPath.'dp_main.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'project_trans.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'gettext_setup.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'projectinfo.inc');
include_once('PPage.inc');

$no_stats=1;

if (isset($ppage))
{
	// This file was include()'d (rather than invoked as a top-level script)
	// and $ppage was set before the include().
}
else
{
	// This file was invoked as a top-level script.
	$ppage = get_requested_PPage($_POST);
}

$projectid  = $ppage->projectid();
$imagefile  = $ppage->imagefile();

if (!isset($_POST['submitted']) || $_POST['submitted'] != 'true')
{
	$header = _("Bad Page Report");
	theme($header, "header");

	echo "<br><br>\n<center>";
	echo "<table width='80%' align='center' bgcolor='#ffffff' border='1' bordercolor='#111111' cellspacing='0' cellpadding='0' style='border-collapse: collapse'><tr><td bgcolor='#ffffff' colspan='2' align='left'>";
	echo "<font color='#000000'><center><b>"._("Common Fixes for Bad Pages. Try these first!")."</b></center>";
	echo "<ul>";
	echo "<li>"._("First, we need to look at what a bad page really is.  Remember this is proofreading so you may see line breaks after every word.  A column may seem to have text missing but all you may need to do is look further down in the text, sometimes the columns may not wrap properly.  There may actually be a portion of the text missing but not all of it.  In these circumstances as well as similiar ones you would want to proofread the page like normal.  Move the text where it needs to be, type in any missing text, etc...  These would <b>not</b> be bad pages.")."<br><br>\n";
	echo "<li>"._("Sometimes, the image may not show up due to technical problems with your browser.  Depending upon your browser there are many ways to try to reload that image.  For example, in Internet Explorer you can right click on the image & left click Show Image or Refresh.  This 90% of the time causes the image to then display.  Again, this would <b>not</b> be a bad page.")."<br><br>\n";
	echo "<li>"._("Occasionally, you may come across a page that has so many mistakes in the optical character recognition (OCR) that you may think it is a bad page that needs to be re-OCRed.  However, this is what you are there for.  You may want to copy it into your local word editing program (eg: Microsoft Word, StarOffice, vi, etc..) and make the changes there & copy them back into the editor.")."<br><br>\n";
	echo "<li>"._("Lastly, checking out our common solutions thread may also help you with making sure the report is as correct as possible.  Here's a link to it <a href='$forums_url/viewtopic.php?t=1659' target='_new'>here</a>.")."<br><br>\n";
	echo "<li>"._("If you've made sure that nothing is going wrong with your computer and you still think it is a bad page please let us know by filling out the information below.  However, if you are at the least bit hestitant that it may not actually be a bad page please do not mark it so & just hit Cancel on the form above.  Marking pages bad when they really aren't takes time away from the project managers so we want to make sure they don't spend their entire time correcting & adding pages back to the project that aren't bad.");
	echo "</ul></td></tr></table></div></center></font>";
	echo "<br><br>\n<center>";
	echo "<form action='report_bad_page.php' method='post'>\n";
	$ppage->echo_hidden_fields();
	echo "<input type='hidden' name='submitted' value='true'>\n";
	echo "<table bgcolor='#ffffff' border='1' bordercolor='#111111' cellspacing='0' cellpadding='0' style='border-collapse: collapse'>\n";
	echo "<tr><td bgcolor='$theme[color_headerbar_bg]' colspan='2' align='center'>";
	echo "<B><font color='#ffffff'>"._("Submit a Bad Page Report")."</font></B>";
	echo "\n";
	echo "<tr><td bgcolor='$theme[color_logobar_bg]' align='left'>";
	echo "<strong>"._("Reason").":</strong>";
	echo "<td bgcolor='#ffffff' align='center'>";
	echo "<select name='reason'>";
	for ($i=0;$i<count($PAGE_BADNESS_REASONS);$i++)
	{
		echo "<option value='$i'>$PAGE_BADNESS_REASONS[$i]</option>";
	}
	echo "</select>";
	echo "\n";
	echo "<tr><td bgcolor='$theme[color_logobar_bg]' align='left'>";
	echo "<strong>"._("What to Do").":</strong>";
	echo "<td bgcolor='#ffffff' align='center'>";
	echo "<input name='redirect_action' value='proof' type='radio'>"._("Continue Proofreading")."<input name='redirect_action' value='quit' checked type='radio'>"._("Stop Proofreading");
	echo "\n";
	echo "<tr><td bgcolor='$theme[color_headerbar_bg]' colspan='2' align='center'>";
	echo "<input type='submit' value='"._("Submit Report")."'>";
	echo "<input type='button' value='"._("Cancel")."' onclick='javascript:history.go(-1)'>";
	echo "\n";
	echo "<tr><td bgcolor='#ffffff' colspan='2' align='center'>";
	echo "<B>"._("Note").":</B> "._("If this report causes a project to be marked<br> bad you will be redirected to the Activity Hub.");
	echo "</td></tr>\n";
	echo "</table></form></center></div>\n";
	theme("", "footer");
}
else
{
	$reason   = $_POST['reason'];

	//See if they filled in a reason.  If not tell them to go back
	if ($reason == 0) {
		include_once($relPath.'theme.inc');
		theme(_("Incomplete Form!"), "header");
		echo "<br><center>"._("You have not completely filled out this form!  Please hit the <a href='javascript:history.back()'>back</a> button on your browser & fill out all fields.")."</center>";
		theme("","footer");
		exit();
	}

	//Update the page the user was working on to reflect a bad page.
	//This may cause the whole project to be marked bad.
	$ppage->markAsBad( $pguser, $reason );

	// Redirect the user to either continue proofreading if project is still open
  // or present a link back to the activity hub
	if (($_POST['redirect_action'] == "proof") && (!$project_is_bad)) {
		$frame1 = $ppage->url_for_do_another_page();
		$title = _("Bad Page Report");
		$body = _("Continuing to Proofread");
	  metarefresh(0,$frame1, $title, $body);
  } else {
		$frame1 = "../../activity_hub.php";
		$title = _("Stop Proofreading");
		$body = _("Exiting proofreading interface");
		$body = sprintf(_("Return to the %s Activity Hub%s."),
                                       "<a href='$frame1' target='_top'>","</a>");
    echo "<html><head><title>$title</title></head><body>$body</body></html>";
    exit;
	}

}
?>
