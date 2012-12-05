<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
theme(_('Quiz Wizard'),'header');

function evalchecks()
{
  $test['type'] = $_POST['type'];
  if ($test['type'] == '')
    return;
  if ($_POST['type'] == 'forbiddentext')
  {
    $test['error'] = $_POST['forbiddentext_error'];
    if ($_POST['forbiddentext_case_sensitive'] == 'yes')
      $test['case_sensitive'] = TRUE;
    else
      $test['case_sensitive'] = FALSE;
    $test['searchtext'] = $_POST['forbiddentext_searchtext'];
  }
  elseif ($_POST['type'] == 'expectedtext')
  {
    $test['error'] = $_POST['expectedtext_error'];
    if ($_POST['expectedtext_case_sensitive'] == 'yes')
      $test['case_sensitive'] = TRUE;
    else
      $test['case_sensitive'] = FALSE;
    if ($_POST['expectedtext_searchtext1'] != "")
      $test['searchtext'][] = $_POST['expectedtext_searchtext1'];  
    if ($_POST['expectedtext_searchtext2'] != "")
      $test['searchtext'][] = $_POST['expectedtext_searchtext2'];  
    if ($_POST['expectedtext_searchtext3'] != "")
      $test['searchtext'][] = $_POST['expectedtext_searchtext3'];  
    if ($_POST['expectedtext_searchtext4'] != "")
      $test['searchtext'][] = $_POST['expectedtext_searchtext4'];  
    if ($_POST['expectedtext_searchtext5'] != "")
      $test['searchtext'][] = $_POST['expectedtext_searchtext5'];  
    if ($_POST['expectedtext_searchtext6'] != "")
      $test['searchtext'][] = $_POST['expectedtext_searchtext6'];  
    if ($_POST['expectedtext_searchtext7'] != "")
      $test['searchtext'][] = $_POST['expectedtext_searchtext7'];  
    if ($_POST['expectedtext_searchtext8'] != "")
      $test['searchtext'][] = $_POST['expectedtext_searchtext8'];  
    if ($_POST['expectedtext_searchtext9'] != "")
      $test['searchtext'][] = $_POST['expectedtext_searchtext9'];  
    if ($_POST['expectedtext_searchtext10'] != "")
      $test['searchtext'][] = $_POST['expectedtext_searchtext10'];  
  }
  elseif ($_POST['type'] == 'wrongtextorder')
  {
    $test['error'] = $_POST['wrongtextorder_error'];
    if ($_POST['wrongtextorder_case_sensitive'] == 'yes')
      $test['case_sensitive'] = TRUE;
    else
      $test['case_sensitive'] = FALSE;
    $test['firsttext'] = $_POST['wrongtextorder_firsttext'];
    $test['secondtext'] = $_POST['wrongtextorder_secondtext'];
  }
  elseif ($_POST['type'] == 'multioccurrence')
  {
    $test['error'] = $_POST['multioccurrence_error'];
    if ($_POST['multioccurrence_case_sensitive'] == 'yes')
      $test['case_sensitive'] = TRUE;
    else
      $test['case_sensitive'] = FALSE;
    $test['searchtext'] = $_POST['multioccurrence_searchtext'];
  }
  elseif ($_POST['type'] == 'markupmissing')
  {
    $test['error'] = $_POST['markupmissing_error'];
    if ($_POST['markupmissing_case_sensitive'] == 'yes')
      $test['case_sensitive'] = TRUE;
    else
      $test['case_sensitive'] = FALSE;
    $test['opentext'] = $_POST['markupmissing_opentext'];
    $test['closetext'] = $_POST['markupmissing_closetext'];
  }
  elseif ($_POST['type'] == 'markupcorrupt')
  {
    $test['error'] = $_POST['markupcorrupt_error'];
    if ($_POST['markupcorrupt_case_sensitive'] == 'yes')
      $test['case_sensitive'] = TRUE;
    else
      $test['case_sensitive'] = FALSE;
    $test['opentext'] = $_POST['markupcorrupt_opentext'];
    $test['closetext'] = $_POST['markupcorrupt_closetext'];
  }
  elseif ($_POST['type'] == 'expectedlinebreaks')
  {
    $test['errorhigh'] = $_POST['expectedlinebreaks_errorhigh'];
    $test['errorlow'] = $_POST['expectedlinebreaks_errorlow'];
    if ($_POST['expectedlinebreaks_case_sensitive'] == 'yes')
      $test['case_sensitive'] = TRUE;
    else
      $test['case_sensitive'] = FALSE;
    $test['starttext'] = $_POST['expectedlinebreaks_starttext'];
    $test['stoptext'] = $_POST['expectedlinebreaks_stoptext'];
    $test['number'] = $_POST['expectedlinebreaks_number'];
  }
  elseif ($_POST['type'] == 'longline')
  {
    $test['error'] = $_POST['longline_error'];
    $test['lengthlimit'] = $_POST['longline_lengthlimit'];
  }
  $_SESSION['quiz_data']['tests'][] = $test;
}


$errlist = '';
foreach($_SESSION['quiz_data']['messages'] as $key => $dummy)
{
 $errlist .= '<option>' . $key . '</option>';
}

if ($_SESSION['quiz_data']['lastpage'] != 'checks') // we are coming from elsewhere
{
?>
<h3><?php echo _("Error tests"); ?></h3>
<p><?php echo _("Now you can define tests which shall be applied to the text and link them to error messages you've previously defined. You have to fill out this for for each test."); ?></p>



<?php
}
else // we are coming from this page (checks.php)
{
evalchecks();

}
?>

<script type="text/javascript">

function nonechecked(x) 
{
  for(var i = 0; i < x.length; i++) 
  {
    if(x[i].checked) 
    { 
      return false; 
    }
  }
return true;
}

function chkFormular () {
  if (nonechecked(document.checkform.type)) 
  {
    alert(<?php echo _("Please explicitly choose one of the test types."); ?>;
    return false;
  }
}
</script>

<p><?php echo sprintf( _("If you have entered all error tests click <a href='%s'>here</a> to view the output."), "./output.php"); ?></p>
<p><?php echo sprintf( _("You can also go back to add further <a href='%s'>error messages.</a>"), "./messages.php"); ?>

<form name="checkform" method="post" action="./checks.php" onSubmit="return chkFormular();">

<p><?php echo _("In all textfields below you can encode linebreaks as \n if you need them."); ?></p>

<hr>
<p><input type="radio" name="type" value="forbiddentext"><b><?php echo _("Check for forbidden text"); ?></b></p>
<p><?php echo _("Text:"); ?> <input type="text" name="forbiddentext_searchtext" size="50"><br>
<?php echo _("Search case sensitive?"); ?> <input type="radio" name="forbiddentext_case_sensitive" checked value="yes">Yes 
<input type="radio" name="forbiddentext_case_sensitive" value="no">No<br> 
<?php echo _("Error message to be given if text is found:"); ?> <select size="1" name="forbiddentext_error">
<?php echo $errlist; ?>
</select>
</p>
<hr>

<p><input type="radio" name="type" value="expectedtext"><b><?php echo _("Check for expected text"); ?></b></p>
<p><?php echo _("This will look if at least one of the strings you give here does occur in the text."); ?></p>
<p>
Text: <input type="text" name="expectedtext_searchtext1" size="50"><br>
Text: <input type="text" name="expectedtext_searchtext2" size="50"><br>
Text: <input type="text" name="expectedtext_searchtext3" size="50"><br>
Text: <input type="text" name="expectedtext_searchtext4" size="50"><br>
Text: <input type="text" name="expectedtext_searchtext5" size="50"><br>
Text: <input type="text" name="expectedtext_searchtext6" size="50"><br>
Text: <input type="text" name="expectedtext_searchtext7" size="50"><br>
Text: <input type="text" name="expectedtext_searchtext8" size="50"><br>
Text: <input type="text" name="expectedtext_searchtext9" size="50"><br>
Text: <input type="text" name="expectedtext_searchtext10" size="50"><br>
</p>
<p><?php echo _("Should you need more than 10 texts being searched simultaneously you'll have to edit the final file."); ?></p>
<p>
<?php echo _("Search case sensitive?"); ?> <input type="radio" name="expectedtext_case_sensitive" checked value="yes"><?php echo _("Yes"); ?> 
<input type="radio" name="expectedtext_case_sensitive" value="no"><?php echo _("No"); ?><br> 
<?php echo _("Error message to be given if none of those texts is found:"); ?> <select size="1" name="expectedtext_error">
<?php echo $errlist; ?>
</select>
</p>
<hr>

<p><input type="radio" name="type" value="wrongtextorder"><b><?php echo _("Check for text in wrong order"); ?></b></p>
<p><?php echo _("first Text (has to be before second text):"); ?> <input type="text" name="wrongtextorder_firsttext" size="50"><br>
<p><?php echo _("second Text (has to be before first text):"); ?> <input type="text" name="wrongtextorder_secondtext" size="50"><br>
<?php echo _("Search case sensitive?"); ?> <input type="radio" name="wrongtextorder_case_sensitive" checked value="yes"><?php echo _("Yes"); ?> 
<input type="radio" name="wrongtextorder_case_sensitive" value="no"><?php echo _("No"); ?><br> 
<?php echo _("Error message to be given if texts are found in wrong order:"); ?> <select size="1" name="wrongtextorder_error">
<?php echo $errlist; ?>
</select>
</p>
<hr>

<p><input type="radio" name="type" value="multioccurrence"><b><?php echo _("Check for text incorrectly appearing twice or more times"); ?></b></p>
<p><?php echo _("Text which is expected only once:"); ?> <input type="text" name="multioccurrence_searchtext" size="50"><br>
<?php echo _("Search case sensitive?"); ?> <input type="radio" name="multioccurrence_case_sensitive" checked value="yes"><?php echo _("Yes"); ?>
<input type="radio" name="multioccurrence_case_sensitive" value="no"><?php echo _("No"); ?><br> 
<?php echo _("Error message to be given if text is found more than once:"); ?> <select size="1" name="multioccurrence_error">
<?php echo $errlist; ?>
</select>
</p>
<hr>

<p><input type="radio" name="type" value="markupmissing"><b><?php echo _("Check for markup with opening and closing tag which is completely missing"); ?></b></p>
<p><?php echo _("opening tag:"); ?> <input type="text" name="markupmissing_opentext" size="50"><br>
<p><?php echo _("closing tag:"); ?> <input type="text" name="markupmissing_closetext" size="50"><br>
<?php echo _("Search case sensitive?"); ?> <input type="radio" name="markupmissing_case_sensitive" checked value="yes"><?php echo _("Yes"); ?> 
<input type="radio" name="markupmissing_case_sensitive" value="no"><?php echo _("No"); ?><br> 
<?php echo _("Error message to be given if no opening and no closing tag is found:"); ?> <select size="1" name="markupmissing_error">
<?php echo $errlist; ?>
</select>
</p>
<hr>

<p><input type="radio" name="type" value="markupcorrupt"><b><?php echo _("Check for corrupt markup with opening and closing tag"); ?></b></p>
<p><?php echo _("For now this only checks for <b>one</b> orphaned opening or closing tag and for the correct oder of the tags. So it basically only works OK, if there is just one pair of opening/closing tags expected in the text."); ?> </p>
<p><?php echo _("opening tag:"); ?> <input type="text" name="markupcorrupt_opentext" size="50"><br>
<p><?php echo _("closing tag:"); ?> <input type="text" name="markupcorrupt_closetext" size="50"><br>
<?php echo _("Search case sensitive?"); ?> <input type="radio" name="markupcorrupt_case_sensitive" checked value="yes"><?php echo _("Yes"); ?> 
<input type="radio" name="markupcorrupt_case_sensitive" value="no"><?php echo _("No"); ?><br> 
<?php echo _("Error message to be given if markup is corrupt:"); ?> <select size="1" name="markupcorrupt_error">
<?php echo $errlist; ?>
</select>
</p>
<hr>

<p><input type="radio" name="type" value="expectedlinebreaks"><b><?php echo _("Check for expected number of linebreaks between two chunks of text."); ?></b></p>
<p><?php echo _("first text:"); ?> <input type="text" name="expectedlinebreaks_starttext" size="50"><br>
<p><?php echo _("second text:"); ?> <input type="text" name="expectedlinebreaks_stoptext" size="50"><br>
Search case sensitive? <input type="radio" name="expectedlinebreaks_case_sensitive" checked value="yes"><?php echo _("Yes"); ?>
<input type="radio" name="expectedlinebreaks_case_sensitive" value="no"><?php echo _("No"); ?><br> 
<p><?php echo _("number of expected linebreaks:"); ?> <input type="text" name="expectedlinebreaks_number" size="10"><br>
<?php echo _("Error message to be given if number of linebreaks is lower than expected:"); ?> <select size="1" name="expectedlinebreaks_errorlow">
<?php echo $errlist; ?>
</select><br>
<?php echo _("Error message to be given if number of linebreaks is higher than expected:"); ?> <select size="1" name="expectedlinebreaks_errorhigh">
<?php echo $errlist; ?>
</select>
</p>
<hr>

<p><input type="radio" name="type" value="longline"><b><?php echo _("Check for long lines."); ?></b></p>
<p><?php echo _("max. allowed line length:"); ?> <input type="text" name="longline_lengthlimit" size="10"><br>
<?php echo _("Error message to be given if a line is loger than allowed:"); ?> <select size="1" name="longline_error">
<?php echo $errlist; ?>
</select><br>
</p>
<hr>

<p><input type="submit" value="send"></p>

</form>




<?php
$_SESSION['quiz_data']['lastpage'] = 'checks';

theme("", "footer");
?>
