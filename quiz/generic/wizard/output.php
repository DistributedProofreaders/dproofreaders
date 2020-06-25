<?php
$relPath='../../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'misc.inc'); // html_safe()
include_once('../quiz_defaults.inc'); // ?

require_login();

output_header(_('Quiz Wizard'), NO_STATSBAR);

function ssqs($x)
{
    return str_replace('\\\'','\'',$x);
}

function enl($x)
{
    $out = str_replace("\r\n",'\n',$x);
    return str_replace("\n",'\n',$out);
}

function sdbsn($x)
{
    return str_replace('\\\\n','\\n',$x);
}


function make_output()
{
    $out  = '<?php' . "\n\n";
    $out .= 'function quizsolved()';
    $out .= "\n{\n";
    $out .= "  //a developer will fill this in to allow quiz passes\n";
    $out .= "}\n\n";
    $out .= '$browser_title           = "' . ssqs($_SESSION['quiz_data']['browser_title']) . "\";\n";
    $out .= '$welcome                 = "' . ssqs($_SESSION['quiz_data']['welcome']) . "\";\n";
    $out .= '$initial_text            = "' . enl(ssqs($_SESSION['quiz_data']['initial_text'])) . "\";\n";
    $out .= '$solutions               = array("';
    $out .= enl(ssqs($_SESSION['quiz_data']['solutions'][0])) . '"';
    if(isset($_SESSION['quiz_data']['solutions'][1]))
        $out .= ', "' . enl(ssqs($_SESSION['quiz_data']['solutions'][1])) . '"';
    if(isset($_SESSION['quiz_data']['solutions'][2]))
        $out .= ', "' . enl(ssqs($_SESSION['quiz_data']['solutions'][2])) . '"';
    if(isset($_SESSION['quiz_data']['solutions'][3]))
        $out .= ', "' . enl(ssqs($_SESSION['quiz_data']['solutions'][3])) . '"';
    $out .= ");\n";
    $out .= '$criteria                = array("';
    if(isset($_SESSION['quiz_data']['criteria'][0]))
        $out .= enl(ssqs($_SESSION['quiz_data']['criteria'][0])) . '"';
    if(isset($_SESSION['quiz_data']['criteria'][1]))
        $out .= ', "' . enl(ssqs($_SESSION['quiz_data']['criteria'][1])) . '"';
    if(isset($_SESSION['quiz_data']['criteria'][2]))
        $out .= ', "' . enl(ssqs($_SESSION['quiz_data']['criteria'][2])) . '"';
    $out .= ");\n";
    $out .= '$solved_message          = "' . ssqs($_SESSION['quiz_data']['solved_message']) . "\";\n";
    if($_SESSION['quiz_data']['links_out'] != "")
        $out .= '$links_out               = "' . ssqs($_SESSION['quiz_data']['links_out']) . "\";\n";
    $out .= "\n\n" . '// error messages' . "\n\n";  
    foreach($_SESSION['quiz_data']['messages'] as $name => $err)
    {
        $out .= '$messages["';
        $out .= ssqs($name);
        $out .= '"] = array("message_text" => "';
        $out .= ssqs($err['message_text']);
        $out .= '"';
        if ($err['challengetext'] != "")
        {
            $out .= ', "challengetext" => "';
            $out .= ssqs($err['challengetext']);
            $out .= '"';
        }
        if ($err['feedbacktext'] != "")
        {
            $out .= ', "feedbacktext" => "';
            $out .= ssqs($err['feedbacktext']);
            $out .= '"';
        }
        $out .= ', "hints" => array(';
        if (isset($err['hints'][0]))
        {
            $out .= 'array("hint_text" => "';
            $out .= ssqs($err['hints'][0]['hint_text']);
            $out .= '"';
            if ($err['hints'][0]['linktext'] != "")
            {
                $out .= ', "linktext" => "';
                $out .= ssqs($err['hints'][0]['linktext']);
                $out .= '"';
            }
            $out .= ')';
            if (isset($err['hints'][1]))
            {
                $out .= ', array("hint_text" => "';
                $out .= ssqs($err['hints'][1]['hint_text']);
                $out .= '"';
                if ($err['hints'][1]['linktext'] != "")
                {
                    $out .= ', "linktext" => "';
                    $out .= ssqs($err['hints'][1]['linktext']);
                    $out .= '"';
                }
                $out .= ')';
            }
        }
        $out .= ")";    
        if ($err['P_guideline'] != "")
        {
            $out .= ', "guideline" => "';
            $out .= ssqs($err['P_guideline']);
            $out .= '"';
        }
        if ($err['F_guideline'] != "")
        {
            $out .= ', "guideline" => "';
            $out .= ssqs($err['F_guideline']);
            $out .= '"';
        }
        $out .= ");\n";    
    }
    $out .= "\n\n";
    $out .= "// error checks\n\n";
    foreach($_SESSION['quiz_data']['tests'] as $key => $test)
    {
        $out .= '$tests[] = array("type" => "' . $test['type'] .'"';
        if ($test['type'] == 'multioccurrence')
        {
            $out .= ', "searchtext" => "' . ssqs(sdbsn($test['searchtext'])) .'", "case_sensitive" => ';
            if ($test['case_sensitive'])
                $out .= 'TRUE';
            else
                $out .= 'FALSE';
            $out .= ', "error" => "';
            $out .= $test['error'];
            $out .= '"';
        }
        elseif ($test['type'] == 'forbiddentext')
        {
            $out .= ', "searchtext" =>  array(';
            foreach(ssqs(sdbsn($test['searchtext'])) as $numsearch => $search)
            {
                if ($numsearch != 0)
                    $out .= ', ';
                $out .= '"' . $search . '"';
            }
            $out .= '), "case_sensitive" => ';
            if ($test['case_sensitive'])
                $out .= 'TRUE';
            else
                $out .= 'FALSE';
            $out .= ', "error" => "';
            $out .= $test['error'];
            $out .= '"';    
        }
        elseif ($test['type'] == 'expectedtext')
        {
            $out .= ', "searchtext" =>  array(';
            foreach(ssqs(sdbsn($test['searchtext'])) as $numsearch => $search)
            {
                if ($numsearch != 0)
                    $out .= ', ';
                $out .= '"' . $search . '"';
            }
            $out .= '), "case_sensitive" => ';
            if ($test['case_sensitive'])
                $out .= 'TRUE';
            else
                $out .= 'FALSE';
            $out .= ', "error" => "';
            $out .= $test['error'];
            $out .= '"';    
        }
        elseif ($test['type'] == 'wrongtextorder')
        {
            $out .= ', "firsttext" => "' . ssqs(sdbsn($test['firsttext'])) .'", "secondtext" => "' . ssqs(sdbsn($test['secondtext'])) .'", "case_sensitive" => ';
            if ($test['case_sensitive'])
                $out .= 'TRUE';
            else
                $out .= 'FALSE';
            $out .= ', "error" => "';
            $out .= $test['error'];
            $out .= '"';
        }
        elseif (($test['type'] == 'markupmissing') || ($test['type'] == 'markupcorrupt'))
        {
            $out .= ', "opentext" => "' . ssqs(sdbsn($test['opentext'])) .'", "closetext" => "' . ssqs(sdbsn($test['closetext'])) .'", "case_sensitive" => ';
            if ($test['case_sensitive'])
                $out .= 'TRUE';
            else
                $out .= 'FALSE';
            $out .= ', "error" => "';
            $out .= $test['error'];
            $out .= '"';
        }
        elseif ($test['type'] == 'expectedlinebreaks')
        {
            $out .= ', "starttext" => "' . ssqs(sdbsn($test['starttext'])) . '", "stoptext" => "' . ssqs(sdbsn($test['stoptext'])) . '", "number" => ' . ssqs(sdbsn($test['number'])) .', "case_sensitive" => ';
            if ($test['case_sensitive'])
                $out .= 'TRUE';
            else
                $out .= 'FALSE';
            $out .= ', "errorlow" => "';
            $out .= $test['errorlow'];
            $out .= '"';
            $out .= ', "errorhigh" => "';
            $out .= $test['errorhigh'];
            $out .= '"';
        }
        elseif ($test['type'] == 'longline')
        {
            $out .= ', "lengthlimit" => ' . ssqs(sdbsn($test['lengthlimit']));
            $out .= ', "error" => "';
            $out .= $test['error'];
            $out .= '"';
        }
        $out .= ');' . "\n";    
    }

    $out .= "?>";
    return html_safe($out);
}

echo "<h2>" . _("Done!") . "</h2>\n";
echo "<p>" . _("Below you will find some prepared php code. Choose a unique name for your quiz page (&lt;pagename&gt;), if you did not already do so.  Copy the code into an editor and save it under the name qd_&lt;pagename&gt;.inc. Save the image you've prepared under the name qi_&lt;pagename&gt;.png. For testing the quiz it is highly recommended to contact a developer with a sandbox on the DP test server (www.pgdp.org). The inc file has to be placed in /quiz/generic/data and the image in /quiz/generic/images.  If you are creating a complete new quiz, the code you generated earlier will need to be inserted into /pinc/quizzes.inc and the quiz will have to be added to one of the quiz levels/categories.  If you are adding a new page to an existing quiz, the page will need to be added to the listing in that same file.") . "</p>";
echo "<p>" . _("When that has happened your quiz should appear in the listing of available quizzes in that developer's sandbox.  If you want to create a tutorial, copy the contents of the appropriate guidelines (or other instructional page) and save it under /quiz/tuts/tut_&lt;pagename&gt;.php.") . "</p>";

echo "<p>" . _("You can also enter more <a href='./messages.php'>error messages</a> or <a href='./checks.php'>error tests</a> if you missed any.") . "</p>";

echo "<textarea rows='15' cols='120' wrap='off'>";
echo make_output();
echo "</textarea>\n";

echo "<p><a href='./start.php'>" . _("Clear all data and restart quiz wizard.") . "</a></p>";

$_SESSION['quiz_data']['lastpage'] = 'output';

// vim: sw=4 ts=4 expandtab
