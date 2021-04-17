<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'misc.inc'); // html_safe()
include_once('../quiz_defaults.inc'); // $default_*

require_login();

output_header(_('Quiz Wizard'));

function evalmessages()
{
    if (isset($_SESSION['quiz_data']['messages'][$_POST['name']]) || $_POST['name'] == '') {
        return false;
    } else {
        $_SESSION['quiz_data']['messages'][$_POST['name']]['message_text'] = $_POST['message_text'];
        $_SESSION['quiz_data']['messages'][$_POST['name']]['challengetext'] = $_POST['challengetext'];
        $_SESSION['quiz_data']['messages'][$_POST['name']]['feedbacktext'] = $_POST['feedbacktext'];
        if ($_POST['hinttext'] != "") {
            $_SESSION['quiz_data']['messages'][$_POST['name']]['hints'][0]['linktext'] = $_POST['linktext'];
            $_SESSION['quiz_data']['messages'][$_POST['name']]['hints'][0]['hint_text'] = $_POST['hinttext'];
        }
        if ($_POST['hinttext2'] != "") {
            $_SESSION['quiz_data']['messages'][$_POST['name']]['hints'][1]['linktext'] = $_POST['linktext2'];
            $_SESSION['quiz_data']['messages'][$_POST['name']]['hints'][1]['hint_text'] = $_POST['hinttext2'];
        }
        if ($_POST['P_guideline'] != "") {
            $_SESSION['quiz_data']['messages'][$_POST['name']]['P_guideline'] = $_POST['P_guideline'];
        } elseif ($_POST['F_guideline'] != "") {
            $_SESSION['quiz_data']['messages'][$_POST['name']]['F_guideline'] = $_POST['F_guideline'];
        }
        return true;
    }
}


function evalstart()
{
    $_SESSION['quiz_data']['browser_title'] = $_POST['browser_title'];
    $_SESSION['quiz_data']['welcome'] = $_POST['welcome'];
    $_SESSION['quiz_data']['initial_text'] = $_POST['initial_text'];
    unset($_SESSION['quiz_data']['solutions']);
    $_SESSION['quiz_data']['solutions'][] = $_POST['solution1'];
    if ($_POST['solution2'] != "") {
        $_SESSION['quiz_data']['solutions'][] = $_POST['solution2'];
    }
    if ($_POST['solution3'] != "") {
        $_SESSION['quiz_data']['solutions'][] = $_POST['solution3'];
    }
    if ($_POST['solution4'] != "") {
        $_SESSION['quiz_data']['solutions'][] = $_POST['solution4'];
    }
    unset($_SESSION['quiz_data']['criteria']);
    if ($_POST['criterion1'] != "") {
        $_SESSION['quiz_data']['criteria'][] = $_POST['criterion1'];
    }
    if ($_POST['criterion2'] != "") {
        $_SESSION['quiz_data']['criteria'][] = $_POST['criterion2'];
    }
    if ($_POST['criterion3'] != "") {
        $_SESSION['quiz_data']['criteria'][] = $_POST['criterion3'];
    }
    $_SESSION['quiz_data']['solved_message'] = $_POST['solved_message'];
    $_SESSION['quiz_data']['links_out'] = $_POST['links_out'];
}

function filltext($x)
{
    global $fill;
    if ($fill) {
        return html_safe($_POST[$x]);
    }
}


echo "<h2>" . _("Error Messages") . "</h2>";

if ($_SESSION['quiz_data']['lastpage'] == 'general') {
    evalstart();
    echo "<p>" . _("Now you need to fill out this form for each error message you want to define. In the next step you can define <b>when</b> these messages will be given.") . "</p>";
} elseif ($_SESSION['quiz_data']['lastpage'] == 'messages') {
    if (!evalmessages()) {
        $fill = true;
        echo "<p>" . _("The error name is blank or already taken:") . " '" . $_POST['name'] . "' ";
        echo _("Please choose a different one.") . "</p>\n";
    } else {
        $fill = false;
    }
}

echo "<p>" . sprintf(_("There are also some <a href='%s' target='_blank'>built-in default messages</a> available.  If you would like to use any of those, you don't need to write your own version for it now; you can select the built-in message at a later step."), "./default_messages.php") . "</p>\n";

echo "<p>" . sprintf(_("If you have entered all error messages click <a href='%s'>here</a> to proceed with the next step."), "./checks.php") . "</p>\n";

echo "<p>" . sprintf(_("You can also <a href='%s'>view the results</a> of the data you've entered."), "./output.php") . "</p>\n";

echo "<form method='post' action='./messages.php'>\n";

echo "<p>" . _("Error name (this will not be displayed, it is only used for reference later):");
echo " <input type='text' name='name' size='20'></p>\n<hr>\n";

echo "<p>" . _("Message text:");
echo " <input type='text' name='message_text' size='100' value='" . filltext('message_text') . "'><br>\n";
echo _("HTML allowed. A typical value would be: &lt;h2&gt;Scanno&lt;/h2&gt; You've missed one typical 'scanno' in the text.") . "</p>\n<hr>\n";

echo "<p>" . _("Text telling the user he should try to correct the error:");
echo " <input type='text' name='challengetext' size='100' value='" . filltext('challengetext') . "'><br>\n";
echo _("HTML allowed. This field is optional. If you leave it empty the following default will appear:");
echo " $default_challenge</p>\n<hr>\n";

echo "<p>" . _("Text telling the user where they can report feedback about this quiz:");
echo " <input type='text' name='feedbacktext' size='100' value='" . filltext('feedbacktext') . "'><br>\n";
echo _("HTML allowed. This field is optional. If you leave it empty the following default will appear:");
echo " " . sprintf($default_feedbacktext, $default_feedbackurl) . "</p>\n<hr>\n";

echo "<p>" . _("For very tricky errors you can prepare additional hints, which will not be shown before the user requests this by clicking a link. This feature was introduced because too many people couldn't find scannos like tbe and arid and answering all the forum messages got somewhat burdensome. :-)") . "</p>\n";

echo "<p>" . _("Introducing text for 1st hint:");
echo " <input type='text' name='linktext' size='100' value='" . filltext('linktext') . "'><br>\n";
echo _("HTML allowed. This field is optional. If you leave it empty the following default will appear:");
echo " $default_hintlink</p>\n";

echo "<p>" . _("Text of 1st hint:");
echo " <input type='text' name='hinttext' size='100' value='" . filltext('hinttext') . "'><br>\n";
echo _("HTML allowed. This field is optional. If you leave it empty there will be no hint for this type of error.") . "</p>\n<hr>\n";

echo "<p>" . _("You can even add another hint in case the user is still helpless.") . "</p>\n";

echo "<p>" . _("Introducing text for 2nd hint:");
echo " <input type='text' name='linktext2' size='100' value='" . filltext('linktext2') . "'><br>\n";
echo _("HTML allowed. This field is optional. If you leave it empty the following default will appear:");
echo " $default_hintlink</p>\n";

echo "<p>" . _("Text of 2nd hint:");
echo " <input type='text' name='hinttext2' size='100' value='" . filltext('hinttext2') . "'><br>\n";
echo _("HTML allowed. This field is optional. If you leave it empty there will be no 2nd hint for this type of error.") . "</p>\n";

echo "<p>" . _("Theoretically you can add more hints, but again you have to manually edit the final file for this.") . "</p>\n<hr>\n";

echo "<p>" . sprintf(_("If you want you can also provide a link to a relevant section of the guidelines.  If you choose one of these, a sentence such as this will appear: \"See the <a href='%s' target='_blank'>Page Headers/Page Footers</a> section of the Proofreading Guidelines for details.\" (with a different section name and \"Proofreading\" or \"Formatting\" as appropriate)."), "../../../faq/proofreading_guidelines.php#page_hf") . "</p>\n";

echo "<p>" . _("Proofreading Guidelines section:") . "<br>\n";
echo "<select size='1' name='P_guideline'>\n<option></option>\n";

foreach (RandomRule::get_rules('proofreading_guidelines.php') as $rule) {
    echo "<option value='$rule->anchor'>$rule->subject</option>\n";
}

echo "</select><br>\n";
echo _("or") . "<br>\n";

echo _("Formatting Guidelines section:") . "<br>\n";
echo "<select size='1' name='F_guideline'>\n<option></option>";

foreach (RandomRule::get_rules('formatting_guidelines.php') as $rule) {
    echo "<option value='$rule->anchor'>$rule->subject</option>\n";
}

echo "</select>\n</p>\n";

echo "<p><input type='submit' value='" . _("send") . "'></p>\n</form>";

$_SESSION['quiz_data']['lastpage'] = 'messages';
