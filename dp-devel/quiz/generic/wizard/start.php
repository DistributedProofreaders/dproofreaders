<?
$relPath='../../../pinc/';
include_once($relPath.'site_vars.php');
include_once($relPath.'connect.inc');
include_once($relPath.'theme.inc');

theme(_('Quiz Wizard'),'header');

$_SESSION['quiz_data']['lastpage'] = 'start';

?>
<h2>Quiz wizard</h2>

<p>This small application will assist you in setting up a new quiz. You have to be logged in, or it won't work.</p>

<p>The user interface is probably neither very comfortable nor extremely tolerant.
But using it can fortunately do absolutely no harm to anything else, so you can play around with it
without any danger. </p>

<p>Before starting here there are a few steps you already should have taken:</p>
<p><b>1.</b> Decide what you want to include in the quiz. Resist the temptation to
include too much in one quiz. The more you include, the more complicated testing for errors gets.
When in doubt better split it up in two or more separate quizzes.</p>

<p><b>2.</b> Look for or write the text for the quiz.</p>

<p><b>3.</b> Prepare the image. It shouldn't be larger than xxx x xxx pixels if possible.</p>

<p><b>4.</b> Prepare the initial text the user will have to correct.</p>

<p><b>5.</b> Prepare the solution text. If you have more than one solution, choose what word/phrase
will determine what solution is used. (E.g., if "X" appears in the user's text, use solution 1; otherwise,
use solution 2.)</p>

<p><b>6.</b> Prepare a list of (assumed) typical errors and what hints you want to give when the user
makes them.<p>

<p>Done all that? OK, let's start!</p>

<p><a href="./general.php?start=true">Continue</a>


<?
theme("", "footer");
?>