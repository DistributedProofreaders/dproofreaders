<?
$relPath='../../../../pinc/';
include_once($relPath.'v_site.inc');
include_once($relPath.'connect.inc');
include_once($relPath.'theme.inc');
include ('../quiz_defaults.inc');
theme(_('Quiz Wizard'),'header');

function evalmessages()
{
  if (isset($_SESSION['quiz_data']['messages'][$_POST['name']]))
  {
    return false;
  }
  else
  {
    $_SESSION['quiz_data']['messages'][$_POST['name']]['message_text'] = $_POST['message_text'];
    $_SESSION['quiz_data']['messages'][$_POST['name']]['challengetext'] = $_POST['challengetext'];
    $_SESSION['quiz_data']['messages'][$_POST['name']]['feedbacktext'] = $_POST['feedbacktext'];
    if ($_POST['hinttext'] != "")
    {
      $_SESSION['quiz_data']['messages'][$_POST['name']]['hints'][0]['linktext'] = $_POST['linktext'];
      $_SESSION['quiz_data']['messages'][$_POST['name']]['hints'][0]['hint_text'] = $_POST['hinttext'];
    };
    if ($_POST['hinttext2'] != "")
    {
      $_SESSION['quiz_data']['messages'][$_POST['name']]['hints'][1]['linktext'] = $_POST['linktext2'];
      $_SESSION['quiz_data']['messages'][$_POST['name']]['hints'][1]['hint_text'] = $_POST['hinttext2'];
    };  
    return true;
  };
}


function evalstart()
{
  $_SESSION['quiz_data']['browser_title'] = $_POST['browser_title'];
  $_SESSION['quiz_data']['welcome'] = $_POST['welcome'];
  $_SESSION['quiz_data']['ocr_text'] = $_POST['ocr_text'];
  unset($_SESSION['quiz_data']['solutions']);
  $_SESSION['quiz_data']['solutions'][] = $_POST['solution1'];
  if ($_POST['solution2'] != "")
    $_SESSION['quiz_data']['solutions'][] = $_POST['solution2'];
  if ($_POST['solution3'] != "")
    $_SESSION['quiz_data']['solutions'][] = $_POST['solution3'];
  if ($_POST['solution4'] != "")
    $_SESSION['quiz_data']['solutions'][] = $_POST['solution4'];
  if ($_POST['showsolution'] == "yes")
    $_SESSION['quiz_data']['showsolution'] = TRUE;
  else
    $_SESSION['quiz_data']['showsolution'] = FALSE;
  $_SESSION['quiz_data']['solved_message'] = $_POST['solved_message'];
  $_SESSION['quiz_data']['links_out'] = $_POST['links_out'];
}

function filltext($x)
{
  global $fill;
  if ($fill)
    echo $_POST[$x];
};

if ($_SESSION['quiz_data']['lastpage'] == 'general') 
{
  evalstart();
?>
<h3>Error messages</h3>
<p>Now you need to fill out this form for each error message you want to define.
In the next step you can define <b>when</b> these messages will be given.</p>



<?
}
elseif ($_SESSION['quiz_data']['lastpage'] == 'messages')
{
  if (!evalmessages())
  {
    $fill = TRUE;
?>  

<p>The error name '<? echo $_POST['name'] ?>' was already taken. 
Please choose a different one.</p>

<?
  }
  else
  {
    $fill = FALSE;
  }
};
?>

<p>If you have entered all error messages click <a href="./checks.php">here</a> to proceed with the next steps.</p>
<p>You can also <a href="./output.php">view the results</a> of the data you've entered</p>

<form method="post" action="./messages.php">

<p>Error name (this will not be displayed, it is only used for reference later): <input type="text" name="name" size="20"></p>
<hr>
<p>Message text: <input type="text" name="message_text" size="100" value="<? filltext('message_text') ?>"><br>
HTML allowed. A typical value would be: &lt;h2&gt;Scanno&lt;/h2&gt; You've missed one typical 'scanno' in the text.</p>
<hr>
<p>Text telling the user he should try to correct the error: <input type="text" name="challengetext" size="100" value="<? filltext('challengetext') ?>"><br>
HTML allowed. This field is optional. If you leave it empty the following default will appear: <? echo $default_challenge; ?></p>
<hr>
<p>Text telling the user where they can report feedback about this quiz: <input type="text" name="feedbacktext" size="100" value="<? filltext('feedbacktext') ?>"><br>
HTML allowed. This field is optional. If you leave it empty the following default will appear: <? echo $default_feedbacktext; ?></p>
<hr>
<p>For very tricky errors you can prepare additional hints, which will not be shown before the user 
requests this by clicking a link. This feature was introduced because too many people couldn't 
find scannos like tbe and arid and answering all the forum messages got somewhat burdensome. :-)</p>

<p>Introducing text for 1st hint: <input type="text" name="linktext" size="100" value="<? filltext('linktext') ?>"><br>
HTML allowed. This field is optional. If you leave it empty the following default will appear: <? echo $default_hintlink; ?></p>

<p>Text of 1st hint: <input type="text" name="hinttext" size="100" value="<? filltext('hinttext') ?>"><br>
HTML allowed. This field is optional. If you leave it empty there will be no hint for this type of error.</p>
<hr>

<p>You can even add another hint in case the user is still helpless.</p>

<p>Introducing text for 2nd hint: <input type="text" name="linktext2" size="100" value="<? filltext('linktext2') ?>"><br>
HTML allowed. This field is optional. If you leave it empty the following default will appear: <? echo $default_hintlink; ?></p>

<p>Text of 2nd hint: <input type="text" name="hinttext2" size="100" value="<? filltext('hinttext2') ?>"><br>
HTML allowed. This field is optional. If you leave it empty there will be no 2nd hint for this type of error.</p>

<p>Theoretically you can add more hints, but again you have to manually edit the final file for this.</p>

<p><input type="submit" value="send"></p>

</form>





<?
$_SESSION['quiz_data']['lastpage'] = 'messages';

theme("", "footer");
?>