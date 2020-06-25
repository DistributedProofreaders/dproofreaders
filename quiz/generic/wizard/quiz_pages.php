<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'misc.inc'); // html_safe()

require_login();

output_header(_('Quiz Wizard'));

function evalpages()
{
    if (isset($_SESSION['quiz_data']['pages'][$_POST['page_id']]))
    {
        return false;
    }
    else
    {
        $_SESSION['quiz_data']['pages'][$_POST['page_id']] = $_POST['page_name'];
        return true;
    }
}

function evalstart()
{
    $_SESSION['quiz_data']['quiz_id'] = $_POST['quiz_id'];
    $_SESSION['quiz_data']['quiz_name'] = $_POST['quiz_name'];
    $_SESSION['quiz_data']['short_quiz_name'] = $_POST['short_quiz_name'];
    $_SESSION['quiz_data']['description'] = $_POST['description'];
    $_SESSION['quiz_data']['thread'] = $_POST['thread'];
}

function filltext($x)
{
    global $fill;
    if ($fill)
        return html_safe($_POST[$x]);
}

echo "<h2>" . _("Add Quiz Pages") . "</h2>";

if ($_SESSION['quiz_data']['lastpage'] == 'newquiz') 
{
    evalstart();
    echo "<p>" . _("Fill out the details for the first page in your quiz.") . "</p>\n";
}
else
{
    if (!evalpages())
    {
        $fill = TRUE;
        echo "<p>" . _("This page ID is already taken:") . " '" . html_safe($_POST['page_id']) . "' ";
        echo _("Please choose a different one.") . "</p>\n";
    }
    else
    {
        $fill = FALSE;
    }
    echo "<p>" . sprintf(_("If you have entered all quiz pages, <a href='%s'>click here</a> to view the output."), "./output_quiz.php") . "</p>\n";
}

echo "<form method='post' action='./quiz_pages.php'>\n<p>";
echo _("Page ID:");
echo " <input type='text' name='page_id' size='10' value='" . filltext('page_id') . "'><br>";
echo _("This should be brief and not contain any spaces or special characters. For example, pqbasic5 or latex3.") . "</p>\n";

echo "<p>" . _("Page name/description:");
echo " <input type='text' name='page_name' size='40' value='" . filltext('page_name') . "'><br>";
echo _("HTML allowed. For example, &lt;b&gt;Page 5&lt;/b&gt;: Poetry, font size changes.") . "</p>\n";

echo "<p><input type='submit' value='" . _("send") . "'></p>\n</form>";

$_SESSION['quiz_data']['lastpage'] = 'quizpages';

// vim: sw=4 ts=4 expandtab
