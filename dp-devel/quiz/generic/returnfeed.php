<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'quizzes.inc'); // get_quiz_page_id_param get_quiz_id_param ${$quiz_id}
include_once($relPath.'misc.inc'); // stripos
include_once('quiz_defaults.inc'); // $default_* $messages

include_once('../small_theme.inc');

$utf8_site=!strcasecmp($charset,"UTF-8");

$page_id = get_quiz_page_id_param($_REQUEST, 'type');

include "./data/qd_${page_id}.inc"; // many things

$quiz_id = get_quiz_id_param($_REQUEST, 'quiz_id');
$quiz_feedbackurl = ${$quiz_id}->thread;

if ($quiz_feedbackurl != "")
{
    $quiz_feedbacktext = sprintf ($default_feedbacktext, $quiz_feedbackurl);
}
else
{
    $quiz_feedbacktext = sprintf ($default_feedbacktext, $default_feedbackurl);
}

function in_string($needle, $haystack, $sensitive = 0) 
{
    if ($sensitive) 
        return (false !== strpos($haystack, $needle))    ? true : false;
    else
        return (false !== stristr($haystack, $needle)) ? true : false;
} 

function strposgen($haystack,$needle,$cs)
{
    if ($cs)
        return strpos($haystack,$needle);
    else
        return stripos($haystack,$needle);
}

function multilinertrim($x)
{
    $arr = explode("\n",$x);
    foreach($arr as $line)
        $out[] = rtrim($line);

    return implode("\n",$out);
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

function finddiff()
{
    global $text;
    global $solutions;
    global $criteria;
    global $quiz_feedbacktext;
    global $charset;
  
    // If there's more than one solution, and solution criteria are defined
    // for this quiz, check through the criteria to see if any are found in
    // the user's text. If so, use the corresponding solution.
    if (isset($criteria) and count($solutions) > 1)
    {
        foreach ($criteria as $key => $criterion)
        {
            if (in_string ($criterion,$text,TRUE) )
            {
                $solution = $solutions[$key];
                break;
            }
        }
    }

    // If there's no solution selected yet, use the the default,
    // ie the last one in the array.
    if (!isset($solution))
    {
        $solution = $solutions[count($solutions) - 1];
    }
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

function error_check()
{
    global $tests, $text, $charset;
    $text = multilinertrim(stripslashes($_POST['text_data']));
    // If the site isn't using utf-8 encoding, convert the user input to utf-8
    if("UTF-8" != strtoupper($charset))
    {
        $text = iconv($charset, "UTF-8//IGNORE", $text);
    }
    foreach ($tests as $key => $value)
    {
        if ($value["type"]=="forbiddentext") 
        {
            /* Return an error if *any* of the searchtext items are found */
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
            if ($found == TRUE) {
                return $value["error"];
            }
        }
        if ($value["type"]=="markupmissing") 
        {
            if (!in_string($value["opentext"],$text,$value["case_sensitive"]) && !in_string($value["closetext"],$text,$value["case_sensitive"]))
            {
                return $value["error"];
            }
        }
        if ($value["type"]=="markupcorrupt") 
        {
            if ((in_string($value["opentext"],$text,$value["case_sensitive"]) && !in_string($value["closetext"],$text,$value["case_sensitive"]))
                    || (!in_string($value["opentext"],$text,$value["case_sensitive"]) && in_string($value["closetext"],$text,$value["case_sensitive"]))
                    || ((!$value["case_sensitive"]) && (stripos($text, $value["closetext"]) < stripos($text, $value["opentext"])))
                    || (($value["case_sensitive"]) && (strpos($text, $value["closetext"]) < strpos($text, $value["opentext"])))    )
            {
                return $value["error"];
            }
        }
        if ($value["type"]=="expectedtext") 
        {
            $found = FALSE;
            foreach ($value["searchtext"] as $expected)
            {
                if (in_string($expected,$text,$value["case_sensitive"]))
                    $found = TRUE;
            }
            if (!$found)
                return $value["error"];
        }
        if ($value["type"]=="expectedlinebreaks") 
        {
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
                return $value["errorlow"];
            if ($num > $value["number"])
                return $value["errorhigh"];
        }
        if ($value["type"]=="multioccurrence") 
        {
            if (number_of_occurrences($text, $value["searchtext"], $value["case_sensitive"]) > 1)
                return $value["error"];
        }
        if ($value["type"]=="wrongtextorder") 
        {
            $p1 = strposgen($text,$value["firsttext"],$value["case_sensitive"]);
            $p2 = strposgen($text,$value["secondtext"],$value["case_sensitive"]);
            if ($p1 && $p2 && ($p1 > $p2))
                return $value["error"];
        }
        if ($value["type"]=="longline") 
        {
            $arr = explode("\n", $text);
            foreach($arr as $line)
            {
                if (strlen($line) > $value["lengthlimit"])
                    return $value["error"];
            }
        }
    }

    return "";
}

// A margin
echo "\n<div style='margin: .5em;'>";

$error_found = error_check();
if ($error_found == "")
{
    $d = finddiff();
    if (!$d)
    {
        quizsolved();
        echo $solved_message;
        echo "\n<p>";
        if (isset($links_out))
        {
            echo $links_out;
        }
        else
        {
            // Figure out what the next quiz page is, if any
            $quiz_pages = array_values(${$quiz_id}->pages);
            $quiz_keys = array_flip($quiz_pages);
            $current_index = $quiz_keys[$page_id];
            $next_index = $current_index + 1;

            // If there's a next page, give a link to it, plus its tutorial if one exists
            if (count($quiz_pages) > $next_index)
            {
                $next_page = $quiz_pages[$next_index];
                global $code_dir;
                $tutfile = "$code_dir/quiz/tuts/tut_".$next_page.".php";
                if (file_exists($tutfile))
                {
                    echo "<a href='../tuts/tut_$next_page.php' target='_top'>" . _("Next step of tutorial") . "</a><br>";
                }
                echo "<a href='main.php?type=$next_page&quiz_id=$quiz_id' target='_top'>" . _("Next step of quiz") . "</a><br>";
            }
            // Give a link back to quiz home (P or F as appropriate)
            get_activity_type_for_quiz($quiz_id);
            echo "<a href='../start.php?show_only=$activity_type_for_quiz' target='_top'>" . _("Back to quizzes home") . "</a>";
        }
        echo "</p>";
    }
}
else
{
    //If the quiz has a message to show all the time, put that in first
    if (@$constant_message != "")
    {
        echo $constant_message;
        echo "\n<hr>\n";
    }
    //Give the error message and any associated hints
    echo $messages[$error_found]["message_text"];
    echo "\n<p>";
    if (count($messages[$error_found]["hints"]) > 0)
    {
        if (isset($messages[$error_found]["hints"][0]["linktext"]))
            echo $messages[$error_found]["hints"][0]["linktext"];
        else
            echo $default_hintlink;
        $link_contents = "./hints.php?type=$page_id&quiz_id=$quiz_id&error=$error_found&number=0";
        echo " " . sprintf(_("Get more hints <a href='%s'>here</a>."), $link_contents) . "<p>";
    }
    if (isset($messages[$error_found]["guideline"]))
    {
        get_activity_type_for_quiz($quiz_id);
        if ($activity_type_for_quiz == "proof")
        {
            $guidelines_url = "proofreading_guidelines.php";
            $guidelines_title = _("Proofreading Guidelines");
        }
        elseif ($activity_type_for_quiz == "format")
        {
            $guidelines_url = "document.php";
            $guidelines_title = _("Formatting Guidelines");
        }
        $anchor = $messages[$error_found]["guideline"];
        $query = "SELECT subject FROM rules WHERE document = '$guidelines_url' AND anchor = '$anchor'";
        $result = mysql_query($query);
        $rule = mysql_fetch_assoc($result);
        echo "<p>" . sprintf(_("See the <a href='../../faq/%1\$s#%2\$s' target='_blank'>%3\$s</a> section of the %4\$s for details."), $guidelines_url, $anchor, $rule['subject'], $guidelines_title) . "</p>";
    }

    if (isset($messages[$error_found]["challengetext"]))
        echo $messages[$error_found]["challengetext"];
    else
        echo $default_challenge;

    echo "</p>\n<p>";
    if (isset($messages[$error_found]["feedbacktext"]))
    {
        echo $messages[$error_found]["feedbacktext"];
    }
    else
    {
        echo $quiz_feedbacktext;
    }
    echo "</p>";
}

echo "\n</div>\n</body>\n</html>";
