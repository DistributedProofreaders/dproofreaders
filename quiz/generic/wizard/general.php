<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

require_login();

output_header(_('Quiz Wizard'));


if ($_SESSION['quiz_data']['lastpage'] == 'output_quiz' || $_SESSION['quiz_data']['lastpage'] == 'start') {
    unset($_SESSION['quiz_data']['messages']);
    unset($_SESSION['quiz_data']['tests']);
}

echo "<h2>" . _("New Quiz Page") . "</h2>";

echo "<p>" . _("Please fill in the following fields. Don't be disturbed if they appear somewhat small. There is no limit for the length of the text you can insert. (At least none that would be relevant.)") . "</p>\n";
echo "<hr>\n<form method='post' action='./messages.php'>\n<p>";
echo _("Title of quiz page (will be displayed in browser title bar):");
echo " <input type='text' name='browser_title' size='50'></p>\n";

echo "<hr>\n<p>";
echo _("Welcome message (will be displayed initially on right hand side):");
echo " <input type='text' name='welcome' size='50'><br>";
echo _("HTML allowed. A typical value would be: &lt;h2&gt;Quiz, page 1&lt;/h2&gt; Try to correct the text ...") . "</p>\n";

echo "<hr>\n<p>";
echo _("Initial text the user will have to correct:");
echo "<br>\n<textarea name='initial_text' rows='12' cols='80' wrap='off'>\n</textarea></p>\n";

echo "<hr>\n<p>";
echo _("Corrected text the user is expected to leave:");
echo "<br>\n<textarea name='solution1' rows='12' cols='80' wrap='off'>\n</textarea><br>\n";
echo _("Use this solution if the following is present:");
echo " <input type='text' name='criterion1' size='20'><br>\n";
echo _("(Fill in <b>only</b> if you have a second solution below. Use \\n if needed for a line break.)") . "</p>\n";

echo "<p>";
echo _("Alternative corrected text the user is expected to leave (optional):");
echo "<br>\n<textarea name='solution2' rows='12' cols='80' wrap='off'>\n</textarea><br>\n";
echo _("Use this solution if the following is present:");
echo " <input type='text' name='criterion2' size='20'><br>\n";
echo _("(Fill in <b>only</b> if you have a third solution below. Use \\n if needed for a line break.)") . "</p>\n";

echo "<p>";
echo _("Yet another alternative corrected text the user is expected to leave (optional):");
echo "<br>\n<textarea name='solution3' rows='12' cols='80' wrap='off'>\n</textarea><br>\n";
echo _("Use this solution if the following is present:");
echo " <input type='text' name='criterion3' size='20'><br>\n";
echo _("(Fill in <b>only</b> if you have a fourth solution below. Use \\n if needed for a line break.)") . "</p>\n";

echo "<p>";
echo _("And the last alternative corrected text the user is expected to leave (optional):");
echo "<br>\n<textarea name='solution4' rows='12' cols='80' wrap='off'>\n</textarea><br>\n";
echo _("Should you <b>really</b> need more than 4 alternatives you can add more manually in the final file which will be the output of this wizard.");
echo "</p>\n<hr>\n<hr>";

echo "<p>";
echo _("Solved message (will be displayed when no errors are left):");
echo " <input type='text' name='solved_message' size='50'><br>\n";
echo _("HTML allowed. A typical value would be: &lt;h2&gt;Quiz successfully solved&lt;/h2&gt; Congratulations, no errors found!") . "</p>\n";

echo "<hr>\n<p>";
echo _("Links shown after solved message (optional):");
echo " <input type='text' name='links_out' size='50'><br>\n";
echo _("HTML allowed. A typical value would be: &lt;a href='../generic/main.php?quiz_page_id=step2' target='_top'&gt;Next step of quiz&lt;/a&gt;") . "<br>\n";
echo _("If you leave this blank there will automatically be links to the next page of the quiz (if one exists) as well as the corresponding tutorial (if one exists) and a link back to the quiz home page.");
echo "</p>\n<hr>\n";

echo "<p><input type='submit' value='" . _("send") . "'></p>\n</form>\n";

$_SESSION['quiz_data']['lastpage'] = 'general';
