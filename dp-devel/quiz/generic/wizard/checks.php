<?
$relPath='../../../../pinc/';
include_once($relPath.'v_site.inc');
include_once($relPath.'connect.inc');
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
  };
  $_SESSION['quiz_data']['tests'][] = $test;
}


$errlist = '';
foreach($_SESSION['quiz_data']['messages'] as $key => $dummy)
{
 $errlist .= '<option>' . $key . '</option>';
};

if ($_SESSION['quiz_data']['lastpage'] != 'checks') // we are coming from elsewhere
{
?>
<h3>Error tests</h3>
<p>Now you can define tests which shall be applied to the text and link them 
to error messages you've previously defined. You have to fill out this for for each test.</p>



<?
}
else // we are coming from this page (checks.php)
{
evalchecks();

};
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
};

function chkFormular () {
  if (nonechecked(document.checkform.type)) 
  {
    alert("Please explicitly choose one of the test types.");
    return false;
  };
};
</script>

<p>If you have entered all error tests click <a href="./output.php">here</a> to view the output.</p>
<p>You can also go back to add further <a href="./messages.php">error messages</a>.

<form name="checkform" method="post" action="./checks.php" onSubmit="return chkFormular();">

<p>In all textfields below you can encode linebreaks as \n if you need them.</p>

<hr>
<p><input type="radio" name="type" value="forbiddentext"><b>Check for forbidden text</b></p>
<p>Text: <input type="text" name="forbiddentext_searchtext" size="50"><br>
Search case sensitive? <input type="radio" name="forbiddentext_case_sensitive" checked value="yes">Yes 
<input type="radio" name="forbiddentext_case_sensitive" value="no">No<br> 
Error message to be given if text is found: <select size="1" name="forbiddentext_error">
<? echo $errlist; ?>
</select>
</p>
<hr>

<p><input type="radio" name="type" value="expectedtext"><b>Check for expected text</b></p>
<p>This will look if at least one of the strings you give here does occur in the text.</p>
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
<p>Should you need more than 10 texts being searched simultaneously you'll have to
edit the final file.</p>
<p>
Search case sensitive? <input type="radio" name="expectedtext_case_sensitive" checked value="yes">Yes 
<input type="radio" name="expectedtext_case_sensitive" value="no">No<br> 
Error message to be given if none of those texts is found: <select size="1" name="expectedtext_error">
<? echo $errlist; ?>
</select>
</p>
<hr>

<p><input type="radio" name="type" value="wrongtextorder"><b>Check for text in wrong order</b></p>
<p>first Text (has to be before second text): <input type="text" name="wrongtextorder_firsttext" size="50"><br>
<p>second Text (has to be before first text): <input type="text" name="wrongtextorder_secondtext" size="50"><br>
Search case sensitive? <input type="radio" name="wrongtextorder_case_sensitive" checked value="yes">Yes 
<input type="radio" name="wrongtextorder_case_sensitive" value="no">No<br> 
Error message to be given if texts are found in wrong order: <select size="1" name="wrongtextorder_error">
<? echo $errlist; ?>
</select>
</p>
<hr>

<p><input type="radio" name="type" value="multioccurrence"><b>Check for text incorrectly appearing twice or more times</b></p>
<p>Text which is expected only once: <input type="text" name="multioccurrence_searchtext" size="50"><br>
Search case sensitive? <input type="radio" name="multioccurrence_case_sensitive" checked value="yes">Yes 
<input type="radio" name="multioccurrence_case_sensitive" value="no">No<br> 
Error message to be given if text is found more than once: <select size="1" name="multioccurrence_error">
<? echo $errlist; ?>
</select>
</p>
<hr>

<p><input type="radio" name="type" value="markupmissing"><b>Check for markup with opening and closing tag which is completely missing</b></p>
<p>opening tag: <input type="text" name="markupmissing_opentext" size="50"><br>
<p>closing tag: <input type="text" name="markupmissing_closetext" size="50"><br>
Search case sensitive? <input type="radio" name="markupmissing_case_sensitive" checked value="yes">Yes 
<input type="radio" name="markupmissing_case_sensitive" value="no">No<br> 
Error message to be given if no opening and no closing tag is found: <select size="1" name="markupmissing_error">
<? echo $errlist; ?>
</select>
</p>
<hr>

<p><input type="radio" name="type" value="markupcorrupt"><b>Check for corrupt markup with opening and closing tag</b></p>
<p>For now this only checks for <b>one</b> orphaned opening or closing tag and for the correct oder of the tags.
So it basically only works OK, if there is just one pair of opening/closing tags expected in the text.</p>
<p>opening tag: <input type="text" name="markupcorrupt_opentext" size="50"><br>
<p>closing tag: <input type="text" name="markupcorrupt_closetext" size="50"><br>
Search case sensitive? <input type="radio" name="markupcorrupt_case_sensitive" checked value="yes">Yes 
<input type="radio" name="markupcorrupt_case_sensitive" value="no">No<br> 
Error message to be given if markup is corrupt: <select size="1" name="markupcorrupt_error">
<? echo $errlist; ?>
</select>
</p>
<hr>

<p><input type="radio" name="type" value="expectedlinebreaks"><b>Check for expected number of linebreaks between two chunks of text.</b></p>
<p>first text: <input type="text" name="expectedlinebreaks_starttext" size="50"><br>
<p>second text: <input type="text" name="expectedlinebreaks_stoptext" size="50"><br>
Search case sensitive? <input type="radio" name="expectedlinebreaks_case_sensitive" checked value="yes">Yes 
<input type="radio" name="expectedlinebreaks_case_sensitive" value="no">No<br> 
<p>number of expected linebreaks: <input type="text" name="expectedlinebreaks_number" size="10"><br>
Error message to be given if number of linebreaks is lower than expected: <select size="1" name="expectedlinebreaks_errorlow">
<? echo $errlist; ?>
</select><br>
Error message to be given if number of linebreaks is higher than expected: <select size="1" name="expectedlinebreaks_errorhigh">
<? echo $errlist; ?>
</select>
</p>
<hr>

<p><input type="radio" name="type" value="longline"><b>Check for long lines.</b></p>
<p>max. allowed line length: <input type="text" name="longline_lengthlimit" size="10"><br>
Error message to be given if a line is loger than allowed: <select size="1" name="longline_error">
<? echo $errlist; ?>
</select><br>
</p>
<hr>

<p><input type="submit" value="send"></p>

</form>




<?
$_SESSION['quiz_data']['lastpage'] = 'checks';

theme("", "footer");
?>