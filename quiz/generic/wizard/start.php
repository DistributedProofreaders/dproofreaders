<?php
$relPath = '../../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

require_login();

output_header(_('Quiz Wizard'));

$_SESSION['quiz_data']['lastpage'] = 'start';

echo "<h2>" . _("Quiz Wizard") . "</h2>";

echo "<p>" . _("This small application will assist you in setting up a new quiz or quiz page. You have to be logged in, or it won't work.") . "</p>";

echo "<p>" . _("The user interface is probably neither very comfortable nor extremely tolerant. But using it can fortunately do absolutely no harm to anything else, so you can play around with it without any danger. ") . "</p>";

echo "<p>" . _("Before starting here there are a few steps you already should have taken:") . "</p>";

echo "<ol><li>" . _("If you're making a whole new quiz (not just adding a page to an existing one), choose a name for the quiz.");
echo "</li>\n<li>";
echo _("Decide what you want to include in each quiz page. Resist the temptation to include too much in one page. The more you include, the more complicated testing for errors gets. When in doubt better split it up in two or more separate pages.");
echo "</li>\n<li>";
echo _("Look for or write the text for each quiz page.");
echo "</li>\n<li>";
echo _("Prepare the image(s). A width of around 350-450 pixels usually works well; it probably shouldn't be wider than 450.");
echo "</li>\n<li>";
echo _("Prepare the initial text(s) the user will have to correct.");
echo "</li>\n<li>";
echo _("Prepare the solution text(s). If a quiz page has more than one solution, choose what word/phrase will determine what solution is used. (For example, if 'X' appears in the user's text, use solution 1; otherwise, use solution 2.)");
echo "</li>\n<li>";
echo _("Prepare a list of (assumed) typical errors and what hints you want to give when the user makes them.");
echo "</li></ol>";

echo "<p>" . _("Done all that? OK, let's start!") . "</p>";

echo "<p><a href='./new_quiz.php'>" . sprintf(_("Create a completely new quiz</a> or <a href='%s'>create a new page for an existing quiz</a>."), "./general.php?start=true") . "</p>";
