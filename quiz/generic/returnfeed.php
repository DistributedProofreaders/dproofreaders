<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc'); // array_get()
include_once($relPath.'quizzes.inc'); // get_quiz_page_id_param
include_once('../small_theme.inc'); // output_small_header

$quiz_page_id = get_quiz_page_id_param($_REQUEST, 'quiz_page_id');
$text = array_get($_POST, 'text_data', '');
$text = stripslashes($text);

include "./quiz_page.inc"; // qp_*

$quiz = get_Quiz_containing_page($quiz_page_id);

output_small_header($quiz);

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
if (!$utf8_site)
{
    $text = iconv($charset, "UTF-8//IGNORE", $text);
}

$text = remove_insignificant_whitespace($text);

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

echo "\n</div>\n";

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function remove_insignificant_whitespace($x)
{
    // Remove blank lines at the bottom of the text
    // (and whitespace at the end of the last non-blank line).
    $x = rtrim($x);

    // Remove whitespace at the end of each line.
    // (Note that this also converts line-ends from CRLF to just LF.)
    $arr = explode("\n",$x);
    foreach($arr as $line)
        $out[] = rtrim($line);

    return implode("\n",$out);

    // This function is similar to _normalize_page_text() in pinc/DPage.inc.
    // Should we just call that one instead?
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

function handle_unanticipated_error()
{
    global $text;

    $solution = qp_choose_solution($text);

    return qp_compare_texts($text, $solution);
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
