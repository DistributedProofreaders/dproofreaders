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

include_once($relPath.'misc.inc'); // html_safe()
include_once($relPath.'faq.inc');
include_once($relPath.'Quiz.inc');
include_once('./quiz_defaults.inc');

// $quiz_page_id must be defined and valid.
assert(isset($quiz_page_id));
$quiz = get_Quiz_containing_page($quiz_page_id);
assert(!is_null($quiz));

// Load the particulars of the requested quiz page.
// (Set a bunch of global variables.)
include "./data/qd_$quiz_page_id.inc";

// -------------------------------------------------------------------
// All strings whose primary purpose is to be output to the user
// are assumed to be in the form of HTML content, and so can
// contain markup and character references.
// They should NOT be passed through html_safe().
//     $browser_title
//     $initial_text
//     $intro_title
//     $initial_instructions
//     $constant_message
//     $messages[*]['message_title']
//     $messages[*]['message_body']
//     $messages[*]['wiki_ref']
//     $messages[*]['challengetext']
//     $messages[*]['feedbacktext']
//     $messages[*]['hints'][*]['linktext']
//     $messages[*]['hints'][*]['hint_title']
//     $messages[*]['hints'][*]['hint_body']
//     $parting_message
//
// The following strings are assumed to *not* be in the form of HTML content.
// You should pass them through html_safe()
// before using them as the content of an HTML element.
//     $solutions[*]
// -------------------------------------------------------------------

if ($quiz->thread != "")
    $quiz_feedbackurl = $quiz->thread;
else
    $quiz_feedbackurl = $default_feedbackurl;

$quiz_feedbacktext = sprintf($default_feedbacktext, $quiz_feedbackurl);

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// called by main.php:

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
    global $initial_text;
    return $initial_text;
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

// called by returnfeed.php:

function qp_text_contains_anticipated_error($text, $test)
{
    switch ($test['type'])
    {
        case "forbiddentext":
            // It's an error if the text contains any of the strings in $searchtext.
            // (A correct text will not contain any of the strings in $searchtext.)
            $found = FALSE;
            if(!is_array($test["searchtext"]))
            {
                $test["searchtext"] = array($test["searchtext"]);
            }
            foreach ($test["searchtext"] as $expected)
            {
                if (in_string($expected,$text,$test["case_sensitive"]))
                {
                    $found = TRUE;
                    break;
                }
            }
            if ($found == TRUE)
            {
                return $test["error"];
            }
            break;

        case "expectedtext":
            // It's an error if the text contains none of the strings in $searchtext.
            // (A correct text will contain at least one of the strings in $searchtext.)
            $found = FALSE;
            foreach ($test["searchtext"] as $expected)
            {
                if (in_string($expected,$text,$test["case_sensitive"]))
                    $found = TRUE;
            }
            if (!$found)
            {
                return $test["error"];
            }
            break;

        case "multioccurrence":
            // It's an error if the text contains more than one occurrence of $searchtext.
            // (A correct text will contain zero or one occurrence of $searchtext.)
            if (number_of_occurrences($text, $test["searchtext"], $test["case_sensitive"]) > 1)
            {
                return $test["error"];
            }
            break;

        // -----------------------------------------------------------

        case "markupmissing":
            // It's an error if the text does not contain both $opentext and $closetext.
            // (A correct text will contain $opentext or $closetext [or both, really].)
            $opentext_is_present  = in_string($test["opentext"],  $text, $test["case_sensitive"]);
            $closetext_is_present = in_string($test["closetext"], $text, $test["case_sensitive"]);
            if (!$opentext_is_present && !$closetext_is_present)
            {
                return $test["error"];
            }
            break;

        case "markupcorrupt":
            // It's an error if the text contains one but not both of $opentext and $closetext,
            // or if the first occurrence of $closetext is before the first occurrence of $opentext.
            // (A correct text will contain both $opentext and $closetext, in that order.)
            $opentext_posn  = str_posn($text, $test["opentext"],  $test["case_sensitive"]);
            $closetext_posn = str_posn($text, $test["closetext"], $test["case_sensitive"]);
            $opentext_is_present  = ($opentext_posn  !== false);
            $closetext_is_present = ($closetext_posn !== false);
            if (
                ($opentext_is_present && !$closetext_is_present)
                ||
                (!$opentext_is_present && $closetext_is_present)
                ||
                ($closetext_posn < $opentext_posn)
            )
            {
                return $test["error"];
            }
            break;

        case "wrongtextorder":
            // It's an error if the text contains both $firsttext and $secondtext,
            // and the first occurrence of $secondtext is before the first occurrence of $firsttext.
            // (A correct text will, if it contains both $firsttext and $secondtext,
            // contain them in that order.)
            $p1 = str_posn($text,$test["firsttext"],$test["case_sensitive"]);
            $p2 = str_posn($text,$test["secondtext"],$test["case_sensitive"]);
            if ($p1 !== false && $p2 !== false && ($p1 > $p2))
            {
                return $test["error"];
            }
            break;

        // -----------------------------------------------------------

        case "expectedlinebreaks":
            // It's an error if the portion of the text between $starttext and $stoptext
            // does not contain exactly $number newlines.
            // (A correct text will contain exactly $number newlines in that range.)
            $len = strlen($test["starttext"]);
            if ($test["case_sensitive"])
            {
                $part = strstr($text,$test["starttext"]);
                $part= substr($part, $len, strpos($part,$test["stoptext"]) - $len);
            }
            else
            {
                $part = stristr($text,$test["starttext"]);
                $part= substr($part, $len, stripos($part,$test["stoptext"]) - $len);
            }
            $num = number_of_occurrences($part, "\n", TRUE);
            if ($num < $test["number"])
            {
                return $test["errorlow"];
            }
            if ($num > $test["number"])
            {
                return $test["errorhigh"];
            }
            break;

        case "longline":
            // It's an error if the text contains a line longer than $lengthlimit.
            // (A correct text's lines will all be of length $lengthlimit or less.)
            $arr = explode("\n", $text);
            foreach($arr as $line)
            {
                if (strlen($line) > $test["lengthlimit"])
                {
                    return $test["error"];
                }
            }
            break;

        default:
            die("test-type '{$test['type']}' not recognized");

    }

    return "";
}

function in_string($needle, $haystack, $sensitive = 0) 
{
    return (str_posn($haystack, $needle, $sensitive) !== false);
}

function str_posn($haystack,$needle,$cs)
// Find the numeric (0-based) position of
// the first occurrence of $needle in $haystack.
// The search is case-sensitive or not, depending on
// whether the value of $cs is true-ish or not (respectively).
//
// Returns FALSE if $needle is not found.
// (Be careful to distinguish FALSE from 0.)
{
    if ($cs)
        return strpos($haystack,$needle);
    else
        return stripos($haystack,$needle);
}

function number_of_occurrences($haystack, $needle, $cs)
{
    if (!$cs)
    {
        $needle = strtolower($needle);
        $haystack = strtolower($haystack);
    }

    return substr_count($haystack, $needle);
}

// =============================================================================

function qp_echo_error_html($message_id)
{
    global $messages, $constant_message; // from the qd file
    global $default_challenge; // from quiz_defaults.inc
    global $quiz; // from returnfeed.php

    //If the quiz has a message to show all the time, put that in first
    if (@$constant_message != "")
    {
        echo $constant_message;
        echo "\n<hr>\n";
    }

    $message = $messages[$message_id];

    // Show the error 'title' and main body.
    echo "<h2>" . $message["message_title"] . "</h2>\n";
    echo "<p>" . $message["message_body"] . "</p>\n";

    // Optionally show a link to a guideline.
    if (isset($message["guideline"]))
    {
        $anchor = $message["guideline"];

        if ($quiz->activity_type == "proof")
        {
            $guidelines_doc = "proofreading_guidelines.php";
            $see_named_section = _("See the %s section of the Proofreading Guidelines for details.");
            $see_unnamed_section = _("See <a %s>this</a> section of the Proofreading Guidelines for details.");
        }
        elseif ($quiz->activity_type == "format")
        {
            $guidelines_doc = "formatting_guidelines.php";
            $see_named_section = _("See the %s section of the Formatting Guidelines for details.");
            $see_unnamed_section = _("See <a %s>this</a> section of the Formatting Guidelines for details.");
        }

        $guidelines_url = get_faq_url($guidelines_doc) . "#$anchor";
        $guidelines_attrs = "href='$guidelines_url' target='_blank'";
        $rule = RandomRule::load_from_anchor($guidelines_doc, $anchor);
        if($rule)
        {
            $guidelines_link = "<a $guidelines_attrs>$rule->subject</a>";

            echo "<p>";
            echo sprintf($see_named_section, $guidelines_link);
            echo "</p>\n";
        }
        else
        {
            echo "<p>";
            echo sprintf($see_unnamed_section, $guidelines_attrs);
            echo "</p>\n";
        }
    }

    // Optionally show a wiki reference.
    if (isset($message['wiki_ref']))
    {
        echo "<p>" . $message['wiki_ref'] . "</p>\n";
    }
    
    // Optionally show a link to a hint.
    qp_echo_link_to_hint_if_it_exists($message_id, 0);

    // Challenge the quiz-taker to fix the error.
    echo "<p>";
    if (isset($message["challengetext"]))
        echo $message["challengetext"];
        // XXX: Currently unused. Might be discontinued.
    else
        echo $default_challenge;
    echo "</p>\n";

    echo_quiz_feedback_and_links(@$message["feedbacktext"]);
}

function echo_quiz_feedback_and_links($message=NULL)
{
    global $code_dir;
    global $quiz_feedbacktext;
    global $quiz, $quiz_page_id;

    echo "<h3>" . _("Need more help?") . "</h2>";

    // Add a disclaimer re the error-finding algorithm,
    // and invite feedback somewhere.
    echo "<p>";
    echo $message ?? $quiz_feedbacktext;
    echo "</p>\n";

    echo "<p>";
    echo _("You might also want to re-read the page tutorial, if one is available, or take a break and try again later.");
    echo "</p>";

    echo "<ul>";
    // if there is a tutorial link, show it
    $tutfile = "$code_dir/quiz/tuts/tut_$quiz_page_id.php";
    if (file_exists($tutfile))
    {
        echo qp_exit_link(
            "../tuts/tut_$quiz_page_id.php",
            _("Back to quiz page tutorial")
        );
    }
    // Give a link back to quiz home (P or F as appropriate)
    echo qp_exit_link(
        "../start.php?show_only={$quiz->activity_type}",
        _("Back to quizzes home")
    );
    echo "</ul>";
}

// =============================================================================

function qp_choose_solution($text)
{
    global $solutions, $criteria;

    assert(isset($solutions));
    assert(is_array($solutions));
    assert(count($solutions) > 0);

    if (count($solutions) == 1)
    {
        // There's only one solution, so we don't even have to look at $text.
        assert(!isset($criteria));
        return $solutions[0];
    }
    else
    {
        // There's more than one solution.

        // Solution-selection criteria must be defined.
        assert(isset($criteria));
        assert(is_array($criteria));
        assert(count($criteria) == count($solutions) - 1);

        // Check through the criteria to see if any are found in
        // the user's text. If so, use the corresponding solution.
        foreach ($criteria as $key => $criterion)
        {
            if (in_string($criterion, $text, TRUE))
            {
                return $solutions[$key];
            }
        }

        // None of the criteria was found in the user's text,
        // so use the default solution, i.e. the last one.
        return $solutions[count($solutions) - 1];
    }
}

// =============================================================================

function qp_compare_texts($user_text, $soln_text)
// If the two texts are the same, return FALSE.
// If they differ, output HTML that describes how they differ, and return TRUE.
{
    if ($user_text == $soln_text) return FALSE;

    echo '<h2>' . _('Difference with expected text') . "</h2>\n";
    echo '<p>';
    echo _('There is still a difference between your text and the expected one.');
    echo "\n";
    // Note that this leaves a 'p' element open,
    // which qp_describe_difference() must close.

    qp_describe_difference($user_text, $soln_text);

    echo_quiz_feedback_and_links();

    return TRUE;
}

function qp_describe_difference($user_text, $soln_text)
{
    assert($user_text != $soln_text);

    $user_lines = explode("\n", $user_text);
    $soln_lines = explode("\n", $soln_text);

    $max_n_lines = max(count($user_lines), count($soln_lines));

    for ($i = 0; $i < $max_n_lines; $i++)
    {
        $user_line = @$user_lines[$i];
        $soln_line = @$soln_lines[$i];
        if (isset($user_line) && isset($soln_line))
        {
            // We haven't run out of either text yet.
            if ($user_line != $soln_line)
            {
                // We've found a difference.
                echo sprintf(
                    _('The first point where the two texts differ is on line %d:'),
                    $i + 1
                );
                echo "</p>\n";

                $pieces = array(
                    'user' => array( _('You submitted:'), $user_line ),
                    'soln' => array( _('We expected:'),   $soln_line )
                );

                $max_prefix_len = max(
                    strlen($pieces['user'][0]),
                    strlen($pieces['soln'][0])
                );

                echo "<pre>\n";
                foreach ( $pieces as $line_info )
                {
                    list($prefix, $line) = $line_info;
                    echo str_pad($prefix, $max_prefix_len, ' ', STR_PAD_LEFT);
                    echo " ";
                    if ($line == '')
                        echo "<i>(" . _('a blank line') . ")</i>";
                    else
                        echo html_safe($line);
                    echo "\n";
                }
                echo "</pre>\n";
                return;
            }
        }
        elseif (isset($user_line) && !isset($soln_line))
        {
            // We've run out of lines in the solution-text,
            // but not in the user-text.
            echo _("You need to delete one or more lines at the end of your text.");
            echo "</p>";
            // (We could say exactly how many lines,
            // but that would make it too easy.)
            return;
        }
        elseif (!isset($user_line) && isset($soln_line))
        {
            // We've run out of lines in the user-text,
            // but not in the solution-text.
            echo _("The text you submitted is correct, as far as it goes, but it is missing one or more lines at the end.");
            echo "</p>";
            return;
        }
        else
        {
            // We've run out of lines in both texts.
            // This is logically impossible.
            assert(FALSE);
        }
    }

    // We've gone through all lines of both texts
    // without finding a difference, which can't happen.
    assert(FALSE);
}

// =============================================================================

function qp_echo_solved_html()
{
    global $code_dir;
    global $quiz_page_id, $quiz;
    global $parting_message;

    echo "<h2>" . _("You've solved this quiz.") . "</h2>\n";
    echo "<p>" . _("Congratulations, no errors found!") . "</p>\n";

    if (isset($parting_message))
    {
        echo $parting_message;
        echo "\n";
    }

    echo "<h3>" . _("Go to...") . "</h3>";

    echo "<ul>";

    // Figure out what the next quiz page is, if any
    $map_index_to_quiz_page_id = array_keys($quiz->pages);
    $map_quiz_page_id_to_index = array_flip($map_index_to_quiz_page_id);
    $current_index = $map_quiz_page_id_to_index[$quiz_page_id];
    $next_index = $current_index + 1;

    // If there's a next page, give a link to it, plus its tutorial if one exists
    if (count($map_index_to_quiz_page_id) > $next_index)
    {
        $next_quiz_page_id = $map_index_to_quiz_page_id[$next_index];
        $tutfile = "$code_dir/quiz/tuts/tut_$next_quiz_page_id.php";
        if (file_exists($tutfile))
        {
            echo qp_exit_link(
                "../tuts/tut_$next_quiz_page_id.php",
                _("Next step of tutorial")
            );
        }
        echo qp_exit_link(
            "main.php?quiz_page_id=$next_quiz_page_id",
            _("Next step of quiz")
        );
    }
    // Give a link back to quiz home (P or F as appropriate)
    echo qp_exit_link(
        "../start.php?show_only={$quiz->activity_type}",
        _("Back to quizzes home")
    );

    echo "</ul>\n";
}

function qp_exit_link($url, $text)
{
    return "<li><a href='$url' target='_top'>$text</a></li>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// called from hints.php:

function qp_echo_hint_html($message_id, $hint_number)
{
    global $messages;

    if (!isset($messages[$message_id]))
    {
        die("supplied message-id ($message_id) is not valid");
    }

    if (!isset($messages[$message_id]["hints"]))
    {
        die("The specified message ($message_id) does not have any hints.");
    }

    $max_hint_number = count($messages[$message_id]["hints"])-1;
    if ($hint_number > $max_hint_number)
    {
        die("supplied hint-number ($hint_number) is greater than the maximum $max_hint_number");
    }

    $hint = $messages[$message_id]["hints"][$hint_number];

    // Display current hint
    echo "<h2>" . $hint["hint_title"] . "</h2>\n";
    echo "<p>" . $hint["hint_body"] . "</p>";

    // If there are any further hints for this message,
    // display a link to the next hint.
    qp_echo_link_to_hint_if_it_exists($message_id, $hint_number+1);
}

function qp_echo_link_to_hint_if_it_exists($message_id, $hint_number)
{
    global $quiz_page_id;
    global $messages, $default_hintlink;

    $hint = @$messages[$message_id]["hints"][$hint_number];

    if (isset($hint))
    {
        echo "<p>";

        if (isset($hint["linktext"]))
        {
            echo $hint["linktext"];
            // XXX: Currently unused. Might be discontinued.
        }
        else
        {
            echo $default_hintlink;
        }
        echo " ";

        echo sprintf(
            _("Get more hints <a href='%s'>here</a>."),
            "./hints.php?quiz_page_id=$quiz_page_id&error=$message_id&number=$hint_number"
        );

        echo "</p>\n";
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// vim: sw=4 ts=4 expandtab
