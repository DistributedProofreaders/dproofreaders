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

function qp_text_contains_anticipated_error($text, $value)
{
    switch ($value['type'])
    {
        case "forbiddentext":
            // It's an error if the text contains any of the strings in $searchtext.
            // (A correct text will not contain any of the strings in $searchtext.)
            $found = FALSE;
            if(!is_array($value["searchtext"]))
            {
                $value["searchtext"] = array($value["searchtext"]);
            }
            foreach ($value["searchtext"] as $expected)
            {
                if (in_string($expected,$text,$value["case_sensitive"]))
                {
                    $found = TRUE;
                    break;
                }
            }
            if ($found == TRUE)
            {
                return $value["error"];
            }
            break;

        case "markupmissing":
            // It's an error if the text does not contain both $opentext and $closetext.
            // (A correct text will contain $opentext or $closetext [or both, really].)
            if (!in_string($value["opentext"],$text,$value["case_sensitive"]) && !in_string($value["closetext"],$text,$value["case_sensitive"]))
            {
                return $value["error"];
            }
            break;

        case "markupcorrupt":
            // It's an error if the text contains one but not both of $opentext and $closetext,
            // or if the first occurrence of $closetext is before the first occurrence of $opentext.
            // (A correct text will contain both $opentext and $closetext, in that order.)
            if ((in_string($value["opentext"],$text,$value["case_sensitive"]) && !in_string($value["closetext"],$text,$value["case_sensitive"]))
                    || (!in_string($value["opentext"],$text,$value["case_sensitive"]) && in_string($value["closetext"],$text,$value["case_sensitive"]))
                    || ((!$value["case_sensitive"]) && (stripos($text, $value["closetext"]) < stripos($text, $value["opentext"])))
                    || (($value["case_sensitive"]) && (strpos($text, $value["closetext"]) < strpos($text, $value["opentext"])))    )
            {
                return $value["error"];
            }
            break;

        case "expectedtext":
            // It's an error if the text contains none of the strings in $searchtext.
            // (A correct text will contain at least one of the strings in $searchtext.)
            $found = FALSE;
            foreach ($value["searchtext"] as $expected)
            {
                if (in_string($expected,$text,$value["case_sensitive"]))
                    $found = TRUE;
            }
            if (!$found)
            {
                return $value["error"];
            }
            break;

        case "expectedlinebreaks":
            // It's an error if the portion of the text between $starttext and $stoptext
            // does not contain exactly $number newlines.
            // (A correct text will contain exactly $number newlines in that range.)
            $len = strlen($value["starttext"]);
            if ($value["case_sensitive"])
            {
                $part = strstr($text,$value["starttext"]);
                $part= substr($part, $len, strpos($part,$value["stoptext"]) - $len);
            }
            else
            {
                $part = stristr($text,$value["starttext"]);
                $part= substr($part, $len, stripos($part,$value["stoptext"]) - $len);
            }
            $num = number_of_occurrences($part, "\n", TRUE);
            if ($num < $value["number"])
            {
                return $value["errorlow"];
            }
            if ($num > $value["number"])
            {
                return $value["errorhigh"];
            }
            break;

        case "multioccurrence":
            // It's an error if the text contains more than one occurrence of $searchtext.
            // (A correct text will contain zero or one occurrence of $searchtext.)
            if (number_of_occurrences($text, $value["searchtext"], $value["case_sensitive"]) > 1)
            {
                return $value["error"];
            }
            break;

        case "wrongtextorder":
            // It's an error if the text contains both $firsttext and $secondtext,
            // and the first occurrence of $secondtext is before the first occurrence of $firsttext.
            // (A correct text will, if it contains both $firsttext and $secondtext, contain them in that order.)
            $p1 = str_posn($text,$value["firsttext"],$value["case_sensitive"]);
            $p2 = str_posn($text,$value["secondtext"],$value["case_sensitive"]);
            if ($p1 && $p2 && ($p1 > $p2))
            {
                return $value["error"];
            }
            break;

        case "longline":
            // It's an error if the text contains a line longer than $lengthlimit.
            // (A correct text's lines will all be of length $lengthlimit or less.)
            $arr = explode("\n", $text);
            foreach($arr as $line)
            {
                if (strlen($line) > $value["lengthlimit"])
                {
                    return $value["error"];
                }
            }
            break;

        default:
            die("test-type '{$value['type']}' not recognized");

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

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// vim: sw=4 ts=4 expandtab
