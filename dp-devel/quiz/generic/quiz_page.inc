<?php
// This file aims to be an API for functionality relating to quiz-pages.
// It will define an abstraction layer that hides the details of
//     quiz/generic/quiz_defaults.inc and
//     quiz/generic/data/qd_*.inc
// from
//     quiz/generic/*.php
//
// The caller should:
// (1) Set the global variable $quiz_page_id to a valid value.
// (2) Include this file.
// (3) Call functions that it declares.

include_once($relPath.'base.inc');
include_once($relPath.'Quiz.inc');
include_once('./quiz_defaults.inc');

// $quiz_page_id must be defined and valid.
assert(isset($quiz_page_id));
$quiz = get_Quiz_containing_page($quiz_page_id);
assert(!is_null($quiz));

// Load the particulars of the requested quiz page.
// (Set a bunch of global variables.)
include "./data/qd_$quiz_page_id.inc";

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// called by main.php:

function qp_full_browser_title()
{
    global $site_abbreviation; // from site_vars.php
    global $browser_title; // from the qd file

    if (isset($browser_title))
        return "$site_abbreviation: $browser_title";
    else
        return $site_abbreviation;
}

function qp_round_id_for_pi_toolbox()
{
    global $quiz;
    if ($quiz->activity_type == 'proof')
    {
        return 'P1';
    }
    else if ($quiz->activity_type == 'format')
    {
        return 'F1';
    }
    else
    {
        assert(FALSE);
        return 'P1';
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// called by orig.php:

function qp_page_image_path()
{
    global $quiz_page_id;
    return "./images/qi_$quiz_page_id.png";
    // relative path works if the top-level script is quiz/generic/*.php
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// called from proof.php:

function qp_initial_page_text()
{
    global $ocr_text;
    return $ocr_text;
}

function qp_sample_solution()
{
    global $solutions;
    return $solutions[0];
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// called from right.php:

function qp_echo_introduction_html()
{
    global $intro_title, $initial_instructions, $constant_message;

    echo "<h2>" . $intro_title . "</h2>\n";

    echo "<p>" . $initial_instructions . "</p>";

    if (@$constant_message != "")
    {
        echo $constant_message;
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// vim: sw=4 ts=4 expandtab
