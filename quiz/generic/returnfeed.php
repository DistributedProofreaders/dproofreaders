<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc'); // array_get()
include_once($relPath.'quizzes.inc'); // get_quiz_page_id_param
include_once('../small_theme.inc'); // output_small_header

require_login();

$quiz_page_id = get_quiz_page_id_param($_REQUEST, 'quiz_page_id');
$text = array_get($_POST, 'text_data', '');

include "./quiz_page.inc"; // qp_*

$quiz = get_Quiz_containing_page($quiz_page_id);

output_small_header($quiz);

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

