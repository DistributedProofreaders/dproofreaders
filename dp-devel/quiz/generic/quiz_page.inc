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

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// vim: sw=4 ts=4 expandtab
