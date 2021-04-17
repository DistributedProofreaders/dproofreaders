<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

require_login();

output_header(_('Quiz Wizard'));

if ($_SESSION['quiz_data']['lastpage'] == 'start') {
    unset($_SESSION['quiz_data']['pages']);
}

echo "<h2>" . _("New Quiz") . "</h2>";

echo "<p>" . _("Please fill in the following fields having to do with the whole quiz, not the individual quiz pages.") . "</p>\n";
echo "<form method='post' action='./quiz_pages.php'>\n<p>";
echo _("Quiz ID (should be brief, e.g. p_basic for basic proofreading quiz):");
echo " <input type='text' name='quiz_id' size='6'></p>\n";

echo "<p>" . _("Quiz name:");
echo " <input type='text' name='quiz_name' size='40'><br>";
echo _("For example, Moderate Proofreading Quiz, Part 2") . "</p>\n";

echo "<p>" . _("Shortened quiz name:");
echo " <input type='text' name='short_quiz_name' size='30'><br>";
echo _("A shorter version of the quiz name, for use in areas where little space is available.  If the entire quiz name is short you can use the same name here.") . "</p>\n";

echo "<p>" . _("Description (optional):");
echo " <input type='text' name='description' size='50'><br>";
echo _("HTML allowed. On the quiz home page, the description will appear under the quiz title and above the list of pages in the quiz.") . "</p>\n";

echo "<p>" . _("Forum thread (optional):");
echo " <input type='text' name='thread' size='40'><br>";
echo _("URL of the forum thread for questions about this quiz. If none is provided, the default thread will be used.") . "</p>\n";


echo "<p><input type='submit' value='" . _("send") . "'></p>\n</form>\n";

$_SESSION['quiz_data']['lastpage'] = 'newquiz';
