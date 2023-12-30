<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'project_trans.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'abort.inc');
include_once('PPage.inc');

require_login();

if (isset($ppage)) {
    // This file was include()'d (rather than invoked as a top-level script)
    // and $ppage was set before the include().
} else {
    // This file was invoked as a top-level script.
    try {
        $ppage = get_requested_PPage($_POST);
    } catch (ProjectException | ProjectPageException $exception) {
        abort($exception->getMessage());
    }
}

$projectid = $ppage->projectid();
$imagefile = $ppage->imagefile();

if (!isset($_POST['submitted']) || $_POST['submitted'] != 'true') {
    $header = _("Report Bad Page");
    output_header($header, NO_STATSBAR);

    echo "<h1>$header</h1>";

    echo "<h2>"._("Common Fixes for Bad Pages. Try these first!")."</h2>";
    echo "<ul>";
    echo "<li>"._("First, we need to look at what a bad page really is.  Remember this is proofreading so you may see line breaks after every word.  A column may seem to have text missing but all you may need to do is look further down in the text, sometimes the columns may not wrap properly.  There may actually be a portion of the text missing but not all of it.  In these circumstances as well as similiar ones you would want to proofread the page like normal.  Move the text where it needs to be, type in any missing text, etc...  These would <b>not</b> be bad pages.")."</li>\n";
    // xgettext:no-php-format
    echo "<li>"._("Sometimes, the image may not show up due to technical problems with your browser.  Depending upon your browser there are many ways to try to reload that image.  For example, in Internet Explorer you can right click on the image & left click Show Image or Refresh.  This 90% of the time causes the image to then display.  Again, this would <b>not</b> be a bad page.")."</li>\n";
    echo "<li>"._("Occasionally, you may come across a page that has so many mistakes in the optical character recognition (OCR) that you may think it is a bad page that needs to be re-OCRed.  However, this is what you are there for.  You may want to copy it into your local word editing program (eg: Microsoft Word, StarOffice, vi, etc.) and make the changes there & copy them back into the editor.")."</li>\n";
    echo "<li>".sprintf(_("Lastly, checking out our common solutions thread may also help you with making sure the report is as correct as possible.  Here's a link to it <a %s>here</a>."), "href='" . get_url_to_view_topic(1659) . "' target='_new'") ."</li>\n";
    echo "<li>"._("If you've made sure that nothing is going wrong with your computer and you still think it is a bad page please let us know by filling out the information below.  However, if you are at the least bit hesitant that it may not actually be a bad page please do not mark it so & just hit Cancel on the form above.  Marking pages bad when they really aren't takes time away from the project managers so we want to make sure they don't spend their entire time correcting & adding pages back to the project that aren't bad.") . "</li>\n";
    echo "</ul>";

    echo "<h2>" . _("Submit a Bad Page Report") . "</h2>";
    echo "<form action='report_bad_page.php' method='post'>\n";
    $ppage->echo_hidden_fields();
    echo "<input type='hidden' name='submitted' value='true'>\n";

    echo "<p><b>" . _("Reason") . ":</b> ";
    echo "<select name='reason' required>";
    foreach ($PAGE_BADNESS_REASONS as $i => $reason) {
        if ($i == 0) {
            echo "<option value=''></option>";
        } else {
            echo "<option value='$i'>$reason</option>";
        }
    }
    echo "</select>";
    echo "</p>";

    echo "<p><b>" . _("What to do") . ":</b>";
    echo "<div style='padding-left: 2em'>";
    echo "<input name='redirect_action' value='proof' type='radio'> " . _("Continue Proofreading") . "<br>";
    echo "<input name='redirect_action' value='quit' checked type='radio'> " . _("Stop Proofreading");
    echo "</div>";
    echo "</p>";

    echo "<p>";
    echo "<input type='submit' value='".attr_safe(_("Submit Report"))."'> ";
    echo "<input type='button' value='".attr_safe(_("Cancel"))."' onclick='javascript:history.go(-1)'>";
    echo "</p>";

    echo "<p><b>" . _("Note") . ":</b> "._("If this report causes a project to be marked bad you will be redirected to the Activity Hub.") . "</p>";
    echo "</form>";
} else {
    $reason = $_POST['reason'] ?? 0;

    //See if they filled in a reason.  If not tell them to go back
    if ($reason == 0) {
        include_once($relPath.'theme.inc');
        output_header(_("Incomplete Form!"), NO_STATSBAR);
        echo "<p class='error'>"._("You have not completely filled out this form!  Please hit the <a href='javascript:history.back()'>back</a> button on your browser & fill out all fields.")."</p>";
        exit();
    }

    //Update the page the user was working on to reflect a bad page.
    //This may cause the whole project to be marked bad.
    try {
        $project_is_bad = $ppage->markAsBad($pguser, $reason);
    } catch (ProjectPageException $exception) {
        abort($exception->getMessage());
    }

    // Redirect the user to either continue proofreading if project is still open
    // or present a link back to the activity hub
    if (($_POST['redirect_action'] == "proof") && (!$project_is_bad)) {
        $frame1 = $ppage->url_for_do_another_page();
        $title = _("Bad Page Report");
        $body = _("Continuing to Proofread");
        metarefresh(0, $frame1, $title, $body);
    } else {
        $frame1 = "../../activity_hub.php";
        $title = _("Stop Proofreading");

        $body = sprintf(
            _("Return to the <a %s>Activity Hub</a>."),
            "href='$frame1' target='_top'"
        );
        slim_header($title);
        echo $body;
        exit;
    }
}
