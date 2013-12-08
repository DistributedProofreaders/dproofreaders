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

// called from hints.php:

function qp_echo_hint_html($message_id, $hint_number)
{
    global $quiz_page_id;
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
    if ($hint_number < $max_hint_number)
    {
        echo "<p>";

        if (isset($hint["linktext"]))
        {
            echo $hint["linktext"];
        }
        else
        {
            echo _("Desperate? Can't find it?");
        }
        echo " ";

        echo sprintf(
            _("Get more hints <a href='%s'>here</a>."),
            "./hints.php?type=$quiz_page_id&error=$message_id&number=" . ($hint_number + 1)
        );

        echo "</p>";
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
    global $default_challenge, $default_hintlink; // from quiz_defaults.inc
    global $quiz_page_id, $quiz, $quiz_feedbacktext; // from returnfeed.php

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
            $guidelines_doc = "document.php";
            $see_named_section = _("See the %s section of the Formatting Guidelines for details.");
            $see_unnamed_section = _("See <a %s>this</a> section of the Formatting Guidelines for details.");
        }
        $guidelines_url = "../../faq/$guidelines_doc#$anchor";
        // XXX localization bug:
        // $guidelines_url should point to the locale-appropriate version of the guidelines.
        $guidelines_attrs = "href='$guidelines_url' target='_blank'";

        $result = mysql_query("
            SELECT subject
            FROM rules
            WHERE document = '$guidelines_doc' AND anchor = '$anchor'
        ");
        if (mysql_num_rows($result) > 0)
        {
            $rule = mysql_fetch_assoc($result);
            $subject = $rule['subject'];
            $guidelines_link = "<a $guidelines_attrs>$subject</a>";

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
    if (count(@$message["hints"]) > 0)
    {
        echo "<p>";
        if (isset($message["hints"][0]["linktext"]))
            echo $message["hints"][0]["linktext"];
        else
            echo $default_hintlink;
        $link_contents = "./hints.php?type=$quiz_page_id&error=$message_id&number=0";
        echo " ";
        echo sprintf(_("Get more hints <a href='%s'>here</a>."), $link_contents);
        echo "</p>\n";
    }

    // Challenge the quiz-taker to fix the error.
    echo "<p>";
    if (isset($message["challengetext"]))
        echo $message["challengetext"];
    else
        echo $default_challenge;
    echo "</p>\n";

    // Add a disclaimer re the error-finding algorithm,
    // and invite feedback somewhere.
    echo "<p>";
    if (isset($message["feedbacktext"]))
    {
        echo $message["feedbacktext"];
    }
    else
    {
        echo $quiz_feedbacktext;
    }
    echo "</p>\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// vim: sw=4 ts=4 expandtab
