<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once('../quiz_defaults.inc'); // $messages

require_login();

output_header(_('Quiz Wizard'));

function evalchecks()
{
    $test['type'] = $_POST['type'];
    if ($test['type'] == '')
        return;
    if ($_POST['type'] == 'forbiddentext')
    {
        if ($_POST['forbiddentext_message_source'] == 'user')
            $test['error'] = $_POST['forbiddentext_error_user'];
        else
            $test['error'] = $_POST['forbiddentext_error_default'];
        if ($_POST['forbiddentext_case_sensitive'] == 'yes')
            $test['case_sensitive'] = TRUE;
        else
            $test['case_sensitive'] = FALSE;
        if ($_POST['forbiddentext_searchtext1'] != "")
            $test['searchtext'][] = $_POST['forbiddentext_searchtext1'];  
        if ($_POST['forbiddentext_searchtext2'] != "")
            $test['searchtext'][] = $_POST['forbiddentext_searchtext2'];  
        if ($_POST['forbiddentext_searchtext3'] != "")
            $test['searchtext'][] = $_POST['forbiddentext_searchtext3'];  
        if ($_POST['forbiddentext_searchtext4'] != "")
            $test['searchtext'][] = $_POST['forbiddentext_searchtext4'];  
        if ($_POST['forbiddentext_searchtext5'] != "")
            $test['searchtext'][] = $_POST['forbiddentext_searchtext5'];  
        if ($_POST['forbiddentext_searchtext6'] != "")
            $test['searchtext'][] = $_POST['forbiddentext_searchtext6'];  
        if ($_POST['forbiddentext_searchtext7'] != "")
            $test['searchtext'][] = $_POST['forbiddentext_searchtext7'];  
        if ($_POST['forbiddentext_searchtext8'] != "")
            $test['searchtext'][] = $_POST['forbiddentext_searchtext8'];  
        if ($_POST['forbiddentext_searchtext9'] != "")
            $test['searchtext'][] = $_POST['forbiddentext_searchtext9'];  
        if ($_POST['forbiddentext_searchtext10'] != "")
            $test['searchtext'][] = $_POST['forbiddentext_searchtext10'];  
    }
    elseif ($_POST['type'] == 'expectedtext')
    {
        if ($_POST['expectedtext_message_source'] == 'user')
            $test['error'] = $_POST['expectedtext_error_user'];
        else
            $test['error'] = $_POST['expectedtext_error_default'];
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
        if ($_POST['wrongtextorder_message_source'] == 'user')
            $test['error'] = $_POST['wrongtextorder_error_user'];
        else
            $test['error'] = $_POST['wrongtextorder_error_default'];
        if ($_POST['wrongtextorder_case_sensitive'] == 'yes')
            $test['case_sensitive'] = TRUE;
        else
            $test['case_sensitive'] = FALSE;
        $test['firsttext'] = $_POST['wrongtextorder_firsttext'];
        $test['secondtext'] = $_POST['wrongtextorder_secondtext'];
    }
    elseif ($_POST['type'] == 'multioccurrence')
    {
        if ($_POST['multioccurrence_message_source'] == 'user')
            $test['error'] = $_POST['multioccurrence_error_user'];
        else
            $test['error'] = $_POST['multioccurrence_error_default'];
        $test['error'] = $_POST['multioccurrence_error'];
        if ($_POST['multioccurrence_case_sensitive'] == 'yes')
            $test['case_sensitive'] = TRUE;
        else
            $test['case_sensitive'] = FALSE;
        $test['searchtext'] = $_POST['multioccurrence_searchtext'];
    }
    elseif ($_POST['type'] == 'markupmissing')
    {
        if ($_POST['markupmissing_message_source'] == 'user')
            $test['error'] = $_POST['markupmissing_error_user'];
        else
            $test['error'] = $_POST['markupmissing_error_default'];
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
        if ($_POST['markupcorrupt_message_source'] == 'user')
            $test['error'] = $_POST['markupcorrupt_error_user'];
        else
            $test['error'] = $_POST['markupcorrupt_error_default'];
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
        if ($_POST['expectedlinebreaks_errorhigh_message_source'] == 'user')
            $test['errorhigh'] = $_POST['expectedlinebreaks_errorhigh_user'];
        else
            $test['errorhigh'] = $_POST['expectedlinebreaks_errorhigh_default'];
        if ($_POST['expectedlinebreaks_errorlow_message_source'] == 'user')
            $test['errorlow'] = $_POST['expectedlinebreaks_errorlow_user'];
        else
            $test['errorlow'] = $_POST['expectedlinebreaks_errorlow_default'];
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
        if ($_POST['longline_message_source'] == 'user')
            $test['error'] = $_POST['longline_error_user'];
        else
            $test['error'] = $_POST['longline_error_default'];
        $test['lengthlimit'] = $_POST['longline_lengthlimit'];
    }
    $_SESSION['quiz_data']['tests'][] = $test;
}


$errlist = '';
foreach($_SESSION['quiz_data']['messages'] as $key => $dummy)
{
    $errlist .= '<option>' . $key . '</option>';
}

$defaultlist = '';
foreach ($messages as $key => $message)
{
    $defaultlist .= '<option>' . $key . '</option>';
}

echo "<h2>" . _("Error Tests") . "</h2>\n";

if ($_SESSION['quiz_data']['lastpage'] != 'checks') // we are coming from elsewhere
{
    echo "<p>" . _("Now you can define tests which shall be applied to the text and link them to error messages you've previously defined. You have to fill out this form for each test.") . "</p>";
}
else // we are coming from this page (checks.php)
{
    evalchecks();
}
?>

<script>

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

<?php
echo "<p>" . sprintf( _("If you have entered all error tests click <a href='%s'>here</a> to view the output."), "./output.php") . "</p>\n<p>";
echo sprintf( _("You can also go back to add further <a href='%s'>error messages.</a>"), "./messages.php") . "</p>";

echo "<form name='checkform' method='post' action='./checks.php' onSubmit='return chkFormular();'>\n";

echo "<p>" . _("In all textfields below you can encode linebreaks as \\n if you need them.") . "</p>\n";

echo "<hr>\n<p><input type='radio' name='type' value='forbiddentext'><b>" . _("Check for forbidden text") . "</b></p>\n";

echo "<p>" . _("This will look if at least one of the strings you give here does occur in the text.") . "</p>\n";

echo "<p>\n";
echo _("Text") . ": <input type='text' name='forbiddentext_searchtext1' size='50'><br>\n";
echo _("Text") . ": <input type='text' name='forbiddentext_searchtext2' size='50'><br>\n";
echo _("Text") . ": <input type='text' name='forbiddentext_searchtext3' size='50'><br>\n";
echo _("Text") . ": <input type='text' name='forbiddentext_searchtext4' size='50'><br>\n";
echo _("Text") . ": <input type='text' name='forbiddentext_searchtext5' size='50'><br>\n";
echo _("Text") . ": <input type='text' name='forbiddentext_searchtext6' size='50'><br>\n";
echo _("Text") . ": <input type='text' name='forbiddentext_searchtext7' size='50'><br>\n";
echo _("Text") . ": <input type='text' name='forbiddentext_searchtext8' size='50'><br>\n";
echo _("Text") . ": <input type='text' name='forbiddentext_searchtext9' size='50'><br>\n";
echo _("Text") . ": <input type='text' name='forbiddentext_searchtext10' size='50'><br>\n";
echo "</p>\n";

echo "<p>" . _("Should you need more than 10 texts being searched simultaneously you'll have to edit the final file.") . "</p>\n";

echo "<p>" . _("Search case sensitive?") . " <input type='radio' name='forbiddentext_case_sensitive' checked value='yes'>" . _("Yes");
echo "\n<input type='radio' name='forbiddentext_case_sensitive' value='no'>" . _("No") . "<br>\n";
echo _("Error message to be given if any of those texts is found:") . "<br>\n";
echo "<input type='radio' name='forbiddentext_message_source' checked value='user'>" . _("Your message:");
echo " <select size='1' name='forbiddentext_error_user'>\n$errlist</select>&nbsp; &nbsp; &nbsp; ";
echo "<input type='radio' name='forbiddentext_message_source' value='default'><a href='./default_messages.php' target='_blank'>" . _("Built-in message:");
echo "</a> <select size='1' name='forbiddentext_error_default'>\n$defaultlist</select>\n</p>\n<hr>\n";

echo "<p><input type='radio' name='type' value='expectedtext'><b>" . _("Check for expected text") . "</b></p>\n";
echo _("This will look if at least one of the strings you give here does occur in the text.") . "</p>\n";

echo "<p>\n";
echo _("Text") . ": <input type='text' name='expectedtext_searchtext1' size='50'><br>\n";
echo _("Text") . ": <input type='text' name='expectedtext_searchtext2' size='50'><br>\n";
echo _("Text") . ": <input type='text' name='expectedtext_searchtext3' size='50'><br>\n";
echo _("Text") . ": <input type='text' name='expectedtext_searchtext4' size='50'><br>\n";
echo _("Text") . ": <input type='text' name='expectedtext_searchtext5' size='50'><br>\n";
echo _("Text") . ": <input type='text' name='expectedtext_searchtext6' size='50'><br>\n";
echo _("Text") . ": <input type='text' name='expectedtext_searchtext7' size='50'><br>\n";
echo _("Text") . ": <input type='text' name='expectedtext_searchtext8' size='50'><br>\n";
echo _("Text") . ": <input type='text' name='expectedtext_searchtext9' size='50'><br>\n";
echo _("Text") . ": <input type='text' name='expectedtext_searchtext10' size='50'><br>\n";
echo "</p>\n";

echo "<p>" . _("Should you need more than 10 texts being searched simultaneously you'll have to edit the final file.") . "</p>";

echo "<p>" . _("Search case sensitive?") . " <input type='radio' name='expectedtext_case_sensitive' checked value='yes'>" . _("Yes");
echo "\n<input type='radio' name='expectedtext_case_sensitive' value='no'>" . _("No") . "<br>\n";
echo _("Error message to be given if none of those texts is found:") . "<br>\n";
echo "<input type='radio' name='expectedtext_message_source' checked value='user'>" . _("Your message:");
echo " <select size='1' name='expectedtext_error_user'>\n$errlist</select>&nbsp; &nbsp; &nbsp; ";
echo "<input type='radio' name='expectedtext_message_source' value='default'><a href='./default_messages.php' target='_blank'>" . _("Built-in message:");
echo "</a> <select size='1' name='expectedtext_error_default'>\n$defaultlist</select>\n</p>\n<hr>\n";

echo "<p><input type='radio' name='type' value='wrongtextorder'><b>" . _("Check for text in wrong order") . "</b></p>\n";
echo "<p>" . _("First Text (has to be before second text):") . " <input type='text' name='wrongtextorder_firsttext' size='50'></p>\n";
echo "<p>" . _("Second Text (has to be before first text):") . " <input type='text' name='wrongtextorder_secondtext' size='50'><br>\n";
echo _("Search case sensitive?") . " <input type='radio' name='wrongtextorder_case_sensitive' checked value='yes'>" . _("Yes");
echo "\n<input type='radio' name='wrongtextorder_case_sensitive' value='no'>" . _("No") . "<br>\n";
echo _("Error message to be given if texts are found in wrong order:") . "<br>\n";
echo "<input type='radio' name='wrongtextorder_message_source' checked value='user'>" . _("Your message:");
echo " <select size='1' name='wrongtextorder_error_user'>\n$errlist</select>&nbsp; &nbsp; &nbsp; ";
echo "<input type='radio' name='wrongtextorder_message_source' value='default'><a href='./default_messages.php' target='_blank'>" . _("Built-in message:");
echo "</a> <select size='1' name='wrongtextorder_error_default'>\n$defaultlist</select>\n</p>\n<hr>\n";

echo "<p><input type='radio' name='type' value='multioccurrence'><b>" . _("Check for text incorrectly appearing twice or more times") . "</b></p>\n";
echo "<p>" . _("Text which is expected only once:") . " <input type='text' name='multioccurrence_searchtext' size='50'><br>\n";
echo _("Search case sensitive?") . " <input type='radio' name='multioccurrence_case_sensitive' checked value='yes'>" . _("Yes");
echo "\n<input type='radio' name='multioccurrence_case_sensitive' value='no'>" . _("No") . "<br>\n";
echo _("Error message to be given if text is found more than once:") . "<br>\n";
echo "<input type='radio' name='multioccurrence_message_source' checked value='user'>" . _("Your message:");
echo " <select size='1' name='multioccurrence_error_user'>\n$errlist</select>&nbsp; &nbsp; &nbsp; ";
echo "<input type='radio' name='multioccurrence_message_source' value='default'><a href='./default_messages.php' target='_blank'>" . _("Built-in message:");
echo "</a> <select size='1' name='multioccurrence_error_default'>\n$defaultlist</select>\n</p>\n<hr>\n";

echo "<p><input type='radio' name='type' value='markupmissing'><b>" . _("Check for markup with opening and closing tag which is completely missing") . "</b></p>\n";
echo "<p>" . _("Opening tag:") . " <input type='text' name='markupmissing_opentext' size='50'></n>\n";
echo "<p>" . _("Closing tag:") . " <input type='text' name='markupmissing_closetext' size='50'><br>\n";
echo _("Search case sensitive?") . " <input type='radio' name='markupmissing_case_sensitive' checked value='yes'>" . _("Yes");
echo "\n<input type='radio' name='markupmissing_case_sensitive' value='no'>" . _("No") . "<br> \n";
echo _("Error message to be given if no opening and no closing tag is found:") . "<br>\n";
echo "<input type='radio' name='markupmissing_message_source' checked value='user'>" . _("Your message:");
echo " <select size='1' name='markupmissing_error_user'>\n$errlist</select>&nbsp; &nbsp; &nbsp; ";
echo "<input type='radio' name='markupmissing_message_source' value='default'><a href='./default_messages.php' target='_blank'>" . _("Built-in message:");
echo "</a> <select size='1' name='markupmissing_error_default'>\n$defaultlist</select>\n</p>\n<hr>\n";

echo "<p><input type='radio' name='type' value='markupcorrupt'><b>" . _("Check for corrupt markup with opening and closing tag") . "</b></p>\n";
echo "<p>" . _("For now this only checks for <b>one</b> orphaned opening or closing tag and for the correct order of the tags. So it basically only works OK, if there is just one pair of opening/closing tags expected in the text.") . "</p>\n";
echo "<p>" . _("Opening tag:") . " <input type='text' name='markupcorrupt_opentext' size='50'></p>\n";
echo "<p>" . _("Closing tag:") . " <input type='text' name='markupcorrupt_closetext' size='50'><br>\n";
echo _("Search case sensitive?") . " <input type='radio' name='markupcorrupt_case_sensitive' checked value='yes'>" . _("Yes");
echo "\n<input type='radio' name='markupcorrupt_case_sensitive' value='no'>" . _("No") . "<br>\n";
echo _("Error message to be given if markup is corrupt:") . "<br>\n";
echo "<input type='radio' name='markupcorrupt_message_source' checked value='user'>" . _("Your message:");
echo " <select size='1' name='markupcorrupt_error_user'>\n$errlist</select>&nbsp; &nbsp; &nbsp; ";
echo "<input type='radio' name='markupcorrupt_message_source' value='default'><a href='./default_messages.php' target='_blank'>" . _("Built-in message:");
echo "</a> <select size='1' name='markupcorrupt_error_default'>\n$defaultlist</select>\n</p>\n<hr>\n";

echo "<p><input type='radio' name='type' value='expectedlinebreaks'><b>" . _("Check for expected number of linebreaks between two chunks of text.") . "</b></p>\n";
echo "<p>" . _("First text:") . " <input type='text' name='expectedlinebreaks_starttext' size='50'></p>\n";
echo "<p>" . _("Second text:") . " <input type='text' name='expectedlinebreaks_stoptext' size='50'><br>\n";
echo "Search case sensitive? <input type='radio' name='expectedlinebreaks_case_sensitive' checked value='yes'>" . _("Yes");
echo "\n<input type='radio' name='expectedlinebreaks_case_sensitive' value='no'>" . _("No") . "</p>\n";
echo "<p>" . _("Number of expected linebreaks:") . " <input type='text' name='expectedlinebreaks_number' size='10'><br>\n";
echo _("Error message to be given if number of linebreaks is lower than expected:") . "<br>\n";
echo "<input type='radio' name='expectedlinebreaks_errorlow_message_source' checked value='user'>" . _("Your message:");
echo " <select size='1' name='expectedlinebreaks_errorlow_user'>\n$errlist</select>&nbsp; &nbsp; &nbsp; ";
echo "<input type='radio' name='expectedlinebreaks_errorlow_message_source' value='default'><a href='./default_messages.php' target='_blank'>" . _("Built-in message:");
echo "</a> <select size='1' name='expectedlinebreaks_errorlow_default'>\n$defaultlist</select>\n</p>\n";

echo _("Error message to be given if number of linebreaks is higher than expected:") . "<br>\n";
echo "<input type='radio' name='expectedlinebreaks_errorhigh_message_source' checked value='user'>" . _("Your message:");
echo " <select size='1' name='expectedlinebreaks_errorhigh_user'>\n$errlist</select>&nbsp; &nbsp; &nbsp; ";
echo "<input type='radio' name='expectedlinebreaks_errorhigh_message_source' value='default'><a href='./default_messages.php' target='_blank'>" . _("Built-in message:");
echo "</a> <select size='1' name='expectedlinebreaks_errorhigh_default'>\n$defaultlist</select>\n</p>\n<hr>\n";

echo "<p><input type='radio' name='type' value='longline'><b>" . _("Check for long lines.") . "</b></p>\n";
echo "<p>" . _("Max. allowed line length:") . " <input type='text' name='longline_lengthlimit' size='10'><br>\n";
echo _("Error message to be given if a line is longer than allowed:") . "<br>\n";
echo "<input type='radio' name='longline_message_source' checked value='user'>" . _("Your message:");
echo " <select size='1' name='longline_error_user'>\n$errlist</select>&nbsp; &nbsp; &nbsp; ";
echo "<input type='radio' name='longline_message_source' value='default'><a href='./default_messages.php' target='_blank'>" . _("Built-in message:");
echo "</a> <select size='1' name='longline_error_default'>\n$defaultlist</select>\n</p>\n<hr>\n";

echo "<p><input type='submit' value='" . _("send") . "'></p>\n";

echo "</form>\n";

$_SESSION['quiz_data']['lastpage'] = 'checks';

