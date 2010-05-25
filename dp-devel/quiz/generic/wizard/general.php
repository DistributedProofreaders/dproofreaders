<?php
$relPath='../../../pinc/';
include_once($relPath.'site_vars.php');
include_once($relPath.'connect.inc');
include_once($relPath.'theme.inc');

theme(_('Quiz Wizard'),'header');


if ($_SESSION['quiz_data']['lastpage'] == 'start')
{
  unset($_SESSION['quiz_data']['messages']);
  unset($_SESSION['quiz_data']['tests']);
}

?>

<p><?php echo _("Please fill in the following fields. Don't be disturbed if they appear somewhat small. There is no limit for the length of the text you can insert. (At least none that would be relevant.)</p>"); ?>

<hr>
<form method="post" action="./messages.php">

<p><?php echo _("Title of quiz (will be displayed in browser title bar):"); ?> <input type="text" name="browser_title" size="50"></p>
<hr>
<p><?php echo _("Welcome message (will be displayed initially on right hand side):"); ?> <input type="text" name="welcome" size="50"><br>
<?php echo _("HTML allowed. A typical value would be: &lt;h2&gt;Quiz, part 1&lt;/h2&gt; Try to correct the text ..."); ?></p>
<hr>
<p><?php echo _("Initial text the user will have to correct:"); ?><br>
<textarea name="ocr_text" rows="12" cols="80" wrap="off">
</textarea></p> 
<hr>
<p><?php echo _("Corrected text the user is expected to leave:"); ?><br>
<textarea name="solution1" rows="12" cols="80" wrap="off">
</textarea></p> 

<p><?php echo _("Alternative corrected text the user is expected to leave (optional):"); ?><br>
<textarea name="solution2" rows="12" cols="80" wrap="off">
</textarea></p> 

<p><?php echo _("Yet another alternative corrected text the user is expected to leave (optional):"); ?><br>
<textarea name="solution3" rows="12" cols="80" wrap="off">
</textarea></p> 

<p><?php echo _("And the last alternative corrected text the user is expected to leave (optional):"); ?><br>
<textarea name="solution4" rows="12" cols="80" wrap="off">
</textarea><br>
<?php echo _("Should you <b>really</b> need more than 4 alternatives you can add more manually in the final file which will be the output of this wizard."); ?>

</p> 
<hr>
<p><?php echo _("In case of unexpected differences, shall the solution be shown? (Only applies if you have multiple solutions.) When in doubt leave it at 'yes'."); ?><br>

<input type="radio" name="showsolution" value="yes" checked><?php echo _("Yes"); ?><br>
<input type="radio" name="showsolution" value="no"><?php echo _("No"); ?></p>
<hr>
<p><?php echo _("Solved message (will be displayed when no errors are left):"); ?> <input type="text" name="solved_message" size="50"><br>
<?php echo _("HTML allowed. A typical value would be: &lt;h2&gt;Quiz successfully solved&lt;/h2&gt; Congratulations, no errors found!"); ?></p>
<hr>
<p><?php echo _("Links shown after solved message (optional):"); ?> <input type="text" name="links_out" size="50"><br>
<?php echo _("HTML allowed. A typical value would be: &lt;a href='../generic/main.php?type=step2' target='_top'&gt;Next step of quiz&lt;/a&gt;"); ?></p>
<hr>

<p><input type="submit" value="send"></p>

</form>



<?php
$_SESSION['quiz_data']['lastpage'] = 'general';

theme("", "footer");
?>
