<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'quizzes.inc'); // get_quiz_page_id_param get_quiz_id_param get_Quiz_with_id
include_once($relPath.'misc.inc'); // stripos
include_once('quiz_defaults.inc'); // $default_* $messages
include_once('../small_theme.inc'); // output_small_header

$quiz_page_id = get_quiz_page_id_param($_REQUEST, 'type');
$text = stripslashes($_POST['text_data']);

include "./quiz_page.inc"; // qp_*

$quiz = get_Quiz_containing_page($quiz_page_id);

output_small_header($quiz);

$quiz_feedbackurl = $quiz->thread;

if ($quiz_feedbackurl != "")
{
    $quiz_feedbacktext = sprintf ($default_feedbacktext, $quiz_feedbackurl);
}
else
{
    $quiz_feedbacktext = sprintf ($default_feedbacktext, $default_feedbackurl);
}

$text = multilinertrim($text);

// If the site isn't using utf-8 encoding, convert the user input to utf-8
if("UTF-8" != strtoupper($charset))
{
    $text = iconv($charset, "UTF-8//IGNORE", $text);
}

// A margin
echo "\n<div style='margin: .5em;'>";

// This boolean expression is evaluated solely for its side-effects.
// Each handle_* function returns TRUE iff it detects & handles its particular thing.
// The first to return TRUE short-circuits the evaluation.
(
    handle_anticipated_error()
    ||
    handle_unanticipated_error()
    ||
    handle_solved()
);

echo "\n</div>\n</body>\n</html>";

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function multilinertrim($x)
{
    $arr = explode("\n",$x);
    foreach($arr as $line)
        $out[] = rtrim($line);

    return implode("\n",$out);
}

function handle_anticipated_error()
{
    global $tests, $text;

    foreach ($tests as $key => $value)
    {
        $message_id = qp_text_contains_anticipated_error($text, $value);
        if ($message_id != "")
        {
            qp_echo_error_html($message_id);
            return TRUE;
        }
    }

    return FALSE;
}

function diff($s1, $s2)
{
    $arr1 = explode("\n",$s1);
    $arr2 = explode("\n",$s2);
    if (count($arr2) > count($arr1))
    {
        $arrdummy = $arr1;
        $arr1 = $arr2;
        $arr2 = $arrdummy;
    }

    foreach($arr1 as $key => $line1)
    {
        if (isset($arr2[$key]))
        {
            if ($line1 != $arr2[$key])
                return $line1 . "\n" . $arr2[$key];
        }
        else
        {
            if ($line1 != "")
                return $line1;
        }
    }
}

function handle_unanticipated_error()
{
    global $text;
    global $quiz_feedbacktext;
    global $charset;
  
    $solution = qp_choose_solution($text);

    $d = diff($text,$solution);
    if ($d == "")
    {
        return FALSE;
    }
    echo '<h2>' . _('Difference with expected text') . '</h2>';
    echo '<p>' . _('There is still a difference between your text and the expected one.') . ' ';
    if (preg_match('/^\n/',$d) || preg_match('/\n$/',$d))
    {
        echo _('There are probably too few or too many blank lines before this text:') . '<br>';
    }
    else
    {
        echo _('Finding the reason for this is beyond the current scope of the analysing software.') . '</p>';
        echo '<p>' . _('This is the first differing line:') . '<br>';
    }
    echo "<pre>\n";
    // If the site isn't using utf-8 encoding, convert the diff string
    if("UTF-8" != strtoupper($charset))
    {
        $d = iconv("UTF-8","$charset//IGNORE",$d);
    }
    echo htmlspecialchars($d);
    echo "\n</pre></p>\n<p>$quiz_feedbacktext</p>";
    return TRUE;
}

function handle_solved()
{
    global $pguser;
    global $quiz_page_id;

    if (isset($pguser)) record_quiz_attempt($pguser, $quiz_page_id, 'pass');
    qp_echo_solved_html();

    return TRUE;
}

// vim: sw=4 ts=4 expandtab
