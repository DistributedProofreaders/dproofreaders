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

// A Note on Character Encodings
//
// All solution-texts are encoded in UTF-8.
// Of course, many of them are ASCII-only, so for those, you can't tell.
// But see, for instance, qd_p_mod1_3.inc:
//     The image contains an ae ligature,
//     and the corresponding point in the solution-text
//     has the two bytes xC3 and xA6,
//     which is the UTF-8 encoding of
//     U+00C6 LATIN SMALL LETTER AE.
// 
// Note that there are also a few qd files that specify different solutions
// depending on the value of global variable $utf8_site.
// But in such cases, both solutions are still encoded in UTF-8; the
// difference is how the user is expected to represent non-Latin-1 characters.
// E.g., in qd_p_mod2_3.inc:
//     The image contains an e with acute accent and an oe ligature.
//     The e-acute (a Latin-1 character) is represented
//         in *both* solution-texts
//             as C3 A9 (the UTF-8 encoding of U+00E9).
//     The oe ligature (a non-Latin-1 character) is represented:
//         in the (!$utf8_site) solution,
//             by the 4 bytes "[oe]".
//         in the ($utf8_site) solution,
//             by the 2 bytes C5 93 (the UTF-8 encoding of U+0153)
// 
// 
// HOWEVER, the page-text that the user submitted (i.e., $text) is encoded
// according to $charset.
// 
// (At least, we appear to assume it is, though I'm doubtful that this is
// guaranteed.  Certainly we sent the HTML form with
//     <META http-equiv="Content-Type" content="text/html; charset=$charset">
// but is the browser obliged to use the same charset when it sends a
// submission request based on that form?)
// 
// So, if $charset isn't UTF-8, we have a mismatch between the encodings
// used by the solution-text and the submitted-text. Before we compare
// them, we must convert one of them to the encoding of the other.
// 
// Do we convert the the solution-text into $charset, or the submitted-text
// into UTF-8?
// 
// I think what decides this question is the fact that $tests
//     (e.g., $tests[*]["searchtext"])
// are also encoded in UTF-8.  If we were to convert the solution-text into
// $charset, we would also have to convert the tests into $charset. So it's
// probably simpler if we convert the submitted-text into UTF-8.
//
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

    foreach ($tests as $test)
    {
        $message_id = qp_text_contains_anticipated_error($text, $test);
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
