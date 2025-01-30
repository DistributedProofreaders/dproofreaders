<?php
$relPath = "./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'project_trans.inc');
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

    echo "<p>" . _("If you are unable to proofread the page you were presented, you can mark it bad with this form to let the Project Manager know it requires attention. Before doing so, let's review what constitutes a bad page and some possible fixes you can try first.") . "</p>";

    echo "<h2>" . _("Commonly Misidentified Bad Pages") . "</h2>";
    echo "<p>" . sprintf(_("The following scenarios are commonly reported as bad pages, but they are not. If either of the below are true, hit '%s' below and continue proofreading the page."), _("Cancel")) . "</p>";
    echo "<ul>";
    echo "<li>" . _("<b>Blank image and text</b> - Books often have blank pages in them. If the image loads and is blank and there is no page text, this is a blank page, not a bad page. Please proofread it as a blank page per the guidelines.") . "</li>";
    echo "<li>" . _("<b>Garbled text</b> - Sometimes the optical character recognition (OCR) does a very poor job on an image and the text is more garbled than useful. Please treat these pages as type-ins and make the text match the image. These are frustrating, but not bad, pages.") . "</li>";
    echo "</ul>";

    echo "<h2>" . _("Issues and Possible Fixes") . "</h2>";
    echo "<ul>";
    echo "<li>" . sprintf(_("<b>%s</b> - If the page text loaded but no image is visible, it might be a missing image. Sometimes, the image may not show up due to technical problems with your browser. Saving the page as 'In Progress' and opening it back up can often resolve this issue. If this doesn't fix the problem, please report it as a bad page."), _("Missing Image")) . "</li>\n";
    echo "<li>" . sprintf(_("<b>%s</b> - If the image loads and has text on it, but no page text is visible it's a bad page."), _("Missing Text")) . "</li>\n";
    echo "<li>" . sprintf(_("<b>%s</b> - If the image loads and has text on it and in no way matches the text that was loaded, it's a bad page."), _("Image/Text Mismatch")) . "</li>\n";
    echo "<li>" . sprintf(_("<b>%s</b> - If the image loads but it looks corrupted in some way, as if the image was not saved correctly, it's a bad page."), _("Corrupted Image")) . "</li>\n";
    echo "</ul>";

    echo "<p>" . sprintf(_("Rarely there are other issues that could be considered a bad page, but please review the first section for misidentified pages before reporting them as <b>%s</b>."), _("Other")) . "</li>\n";

    echo "<h2>" . _("Submit a Bad Page Report") . "</h2>";
    echo "<li>" . sprintf(_("If you still think it is a bad page, please let us know by filling out the information below. If not, hit %s."), _("Cancel")) . "</li>\n";

    echo "<form action='report_bad_page.php' method='post'>\n";
    $ppage->echo_hidden_fields();
    echo "<input type='hidden' name='submitted' value='true'>\n";

    echo "<p><b>" . _("Reason") . ":</b> ";
    echo "<select name='reason' required>";
    echo "<option value=''></option>";
    foreach ($PAGE_BADNESS_REASONS as $i => $reason) {
        echo "<option value='$i'>" . $reason["string"] . "</option>";
    }
    echo "</select>";
    echo "</p>";

    echo "<p><b>" . _("What to do") . ":</b></p>";
    echo "<div style='padding-left: 2em'>";
    echo "<input name='redirect_action' value='proof' type='radio'> " . _("Continue Proofreading") . "<br>";
    echo "<input name='redirect_action' value='quit' checked type='radio'> " . _("Stop Proofreading");
    echo "</div>";

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
    } catch (ProjectException | PageNotOwnedException $exception) {
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
